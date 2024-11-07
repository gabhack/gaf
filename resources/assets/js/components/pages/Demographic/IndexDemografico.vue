<template>
    <div>
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <div class="panel mb-3 col-md-12" style="margin-left: 20px;">
            <div class="panel-heading" style="background-color: white;">
                <b style="color: black;">Datos Demográficos</b>
            </div>
            <div class="panel-body">
                <div style="background-color: white">
                    <hd style="color: black">
                        Por favor, asegúrese de que el archivo Excel tiene una columna con el encabezado
                        <strong>'cédulas'</strong> y que contiene los números de cédula.
                    </hd><br><br>
                </div > 
                <div class="form-group d-flex align-items-center">
                    <div class="custom-file-upload">
                        <input type="file" @change="handleFileUpload" id="file-upload" class="file-input"/>
                        <label for="file-upload">Elegir archivo</label>
                    </div>
                </div>
                        <CustomButton 
                        v-if="file" 
                        @click="uploadFile" class="btn btn-primary" 
                        style="white-space: nowrap; background-color: #2c8c73;">Subir Archivo
                        </CustomButton>
                </div>
        </div>

        <!-- Card para mostrar las consultas recientes -->
        <div v-if="showRecentConsultations && recentConsultations.length" class="card recent-consultations">
            <div class="card-header">
                <b>Consultas Recientes</b>
            </div>
            <ul class="list-group list-group-flush">
                <li v-for="consultation in recentConsultations" :key="consultation.id" class="list-group-item">
                    <a href="#" @click.prevent="loadConsultationData(consultation.consulta_data)">
                        {{ consultation.created_at }}
                    </a>
                </li>
            </ul>
        </div>

        <div v-if="results.length" class="panel mb-3 col-md-12">
            <div class="panel-heading" style="background-color: white">
                <div class="float-right">
                    <button @click="exportToPDF" class="btn btn-danger mr-2" style="background-color: #2c8c73">Exportar a PDF</button>
                    <button @click="exportToExcel" class="btn btn-success" style="background-color: #2c8c73">Exportar a Excel</button>
                    <CustomButton @click="toggleRecentConsultations" class="btn btn-info float-right" style="white-space: nowrap; background-color: #2c8c73; margin-left: 20px;">
                    {{ showRecentConsultations ? 'Ocultar Vista' : 'Ver Recientes'}}
                </CustomButton>
                </div><br>
                <b style="color: black; margin-left: 20px;">Resultado:</b>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Buscar por documento"
                        class="form-control mb-3"
                    />
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre</th>
                                <th>Celular</th>
                                <th>Teléfono Fijo</th>
                                <th>Email</th>
                                <th>Ciudad</th>
                                <th>Dirección</th>
                                <th>Centro de Costo</th>
                                <th>Tipo de Contrato</th>
                                <th>Edad</th>
                                <th>Fecha de Nacimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="result in filteredResults" :key="result.doc">
                                <td>{{ capitalize(result.doc) }}</td>
                                <td>{{ capitalize(result.nombre_usuario) }}</td>
                                <td>{{ result.cel }}</td>
                                <td>{{ result.tel }}</td>
                                <td>{{ result.correo_electronico }}</td>
                                <td>{{ capitalize(result.ciudad) }}</td>
                                <td>{{ capitalize(result.direccion_residencial) }}</td>
                                <td>{{ capitalize(result.centro_costo) }}</td>
                                <td>{{ capitalize(result.tipo_contrato) }}</td>
                                <td>{{ result.edad }}</td>
                                <td>{{ result.fecha_nacimiento }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <button v-if="page > 1" @click="fetchPaginatedResults(page - 1)" class="btn btn-primary" style="background-color: #2c8c73">Anterior</button>
                    <button @click="fetchPaginatedResults(page + 1)" :disabled="page * perPage >= total" class="btn btn-primary" style="background-color: #2c8c73">Siguiente</button>
                </div>
            </div>
        </div>

        <div v-if="error" class="alert alert-danger mt-3">
            {{ error }}
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import CustomButton from '../../customComponents/CustomButton.vue';

export default {
    name: 'DemographicIndex',
    data() {
        return {
            file: null,
            isLoading: false,
            results: [],
            searchQuery: '',
            error: null,
            recentConsultations: [],
            showRecentConsultations: false,
            page: 1,
            perPage: 30,
            total: 0
        };
    },
    computed: {
        filteredResults() {
            if (this.searchQuery.length < 3) {
                return this.results;
            }
            return this.results.filter(result => {
                return result.doc.toString().includes(this.searchQuery);
            });
        }
    },
    methods: {
        capitalize(text) {
        if (!text) return '';
        return text.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
    },
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async uploadFile() {
            if (!this.file) {
                alert('Seleccione un archivo primero');
                return;
            }
            let formData = new FormData();
            formData.append('file', this.file);

            try {
                this.isLoading = true;
                let response = await axios.post('/demografico/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                console.log('Resultados:', response.data); // Log para ver los resultados
                this.results = response.data;
                this.error = null;
                await this.fetchPaginatedResults(1); // Fetch the first page of results
            } catch (error) {
                if (error.response && error.response.data) {
                    this.error = error.response.data.error;
                } else {
                    this.error = 'Error subiendo el archivo';
                }
                this.isLoading = false; // Stop loading if there is an error
            }
        },
        async fetchRecentConsultations() {
            try {
                let response = await axios.get('/demografico/recent-consultations');
                this.recentConsultations = response.data;
            } catch (error) {
                console.error('Error fetching recent consultations:', error);
            }
        },
        async fetchPaginatedResults(page) {
            this.isLoading = true;
            try {
                let response = await axios.get(`/demografico/fetch-paginated-results-demografico?page=${page}&perPage=${this.perPage}`);
                this.results = response.data.data;
                this.total = response.data.total;
                this.page = response.data.page;
                this.perPage = response.data.perPage;
            } catch (error) {
                console.error('Error fetching paginated results:', error);
                this.error = 'Error al buscar los resultados paginados';
            } finally {
                this.isLoading = false; // Stop loading after results are fetched
            }
        },
        loadConsultationData(data) {
            this.results = data;
        },
        toggleRecentConsultations() {
            if (!this.showRecentConsultations) {
                this.fetchRecentConsultations();
            }
            this.showRecentConsultations = !this.showRecentConsultations;
        },
        exportToPDF() {
            const doc = new jsPDF();
            const columns = ['Documento', 'Nombre', 'Celular', 'Teléfono Fijo', 'Email', 'Ciudad', 'Dirección', 'Centro de Costo', 'Tipo de Contrato', 'Edad', 'Fecha de Nacimiento'];
            const rows = this.results.map(item => [
                item.doc,
                item.nombre_usuario,
                item.cel,
                item.tel,
                item.correo_electronico,
                item.ciudad,
                item.direccion_residencial,
                item.centro_costo,
                item.tipo_contrato,
                item.edad,
                item.fecha_nacimiento
            ]);
            doc.autoTable(columns, rows);
            doc.save('resultados.pdf');
        },
        exportToExcel() {
            const columns = ['Documento', 'Nombre', 'Celular', 'Teléfono Fijo', 'Email', 'Ciudad', 'Dirección', 'Centro de Costo', 'Tipo de Contrato', 'Edad', 'Fecha de Nacimiento'];
            const rows = this.results.map(item => [
                item.doc,
                item.nombre_usuario,
                item.cel,
                item.tel,
                item.correo_electronico,
                item.ciudad,
                item.direccion_residencial,
                item.centro_costo,
                item.tipo_contrato,
                item.edad,
                item.fecha_nacimiento
            ]);

            // Convertir las filas a una hoja de trabajo de Excel
            const worksheet = XLSX.utils.aoa_to_sheet([columns, ...rows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Resultados');
            XLSX.writeFile(workbook, 'resultados.xlsx');
        }
    },
};
</script>

<style scoped>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.spinner {
    border: 16px solid #f3f3f3;
    border-top: 16px solid #3498db;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

.custom-file-upload .file-input{
    display:none;
}

.custom-file-upload label{
    display:inline-block;
    padding: 10px 18px;
    color:white;
    background-color: #2c8c73;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 14px;
}

.custom-file-upload label:hover{
    background-color: #2c8c73;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.panel-heading {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
}

.panel-body {
    padding: 15px;
}

.table-responsive {
    overflow-x: auto;
}

.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.form-group {
    margin-bottom: 15px;
}

.float-right {
    float: right;
}

.recent-consultations {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
}
</style>