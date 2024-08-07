<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Datos Demográficos</b>
                <b-button @click="toggleRecentConsultations" variant="info" class="float-right">
                    {{ showRecentConsultations ? 'Ocultar Consultas Recientes' : 'Ver Consultas Recientes' }}
                </b-button>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <p>
                        Por favor, asegúrese de que el archivo Excel tiene una columna con el encabezado
                        <strong>'cedulas'</strong> y que contiene los números de cédula.
                    </p>
                </div>
                <div class="form-group">
                    <input type="file" @change="handleFileUpload" class="form-control mb-3" />
                    <b-button @click="uploadFile" variant="primary">Subir</b-button>
                </div>
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
            <div class="panel-heading">
                <b>Resultados</b>
                <div class="float-right">
                    <b-button @click="exportToPDF" variant="danger" class="mr-2">Exportar a PDF</b-button>
                    <b-button @click="exportToExcel" variant="success">Exportar a Excel</b-button>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" v-model="searchQuery" placeholder="Buscar por documento" class="form-control mb-3" />
                </div>
                <div style="overflow-x: auto;">
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
                                <th>Municipio</th>
                                <th>Centro de Costo</th>
                                <th>Tipo de Contrato</th>
                                <th>Fecha de Nacimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="result in filteredResults" :key="result.doc">
                                <td>{{ result.doc }}</td>
                                <td>{{ result.nombre_usuario }}</td>
                                <td>{{ result.cel }}</td>
                                <td>{{ result.tel }}</td>
                                <td>{{ result.correo_electronico }}</td>
                                <td>{{ result.ciudad }}</td>
                                <td>{{ result.direccion_residencial }}</td>
                                <td>{{ result.municipio || 'N/A' }}</td>
                                <td>{{ result.centro_costo || 'N/A' }}</td>
                                <td>{{ result.tipo_contrato || 'N/A' }}</td>
                                <td>{{ result.fecha_nacimiento || 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
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
import { BButton } from 'bootstrap-vue';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';

export default {
    name: 'DemographicData',
    components: {
        BButton,
        Loading
    },
    data() {
        return {
            file: null,
            isLoading: false,
            results: [],
            searchQuery: '',
            error: null,
            recentConsultations: [],
            showRecentConsultations: false
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
            } catch (error) {
                if (error.response && error.response.data) {
                    this.error = error.response.data.error;
                } else {
                    this.error = 'Error subiendo el archivo';
                }
            } finally {
                this.isLoading = false;
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
        loadConsultationData(data) {
            this.results = data;
        },
        toggleRecentConsultations() {
            this.showRecentConsultations = !this.showRecentConsultations;
        },
        exportToPDF() {
            const doc = new jsPDF();
            const columns = ['Documento', 'Nombre', 'Celular', 'Teléfono Fijo', 'Email', 'Ciudad', 'Dirección', 'Municipio', 'Centro de Costo', 'Tipo de Contrato', 'Fecha de Nacimiento'];
            const rows = this.results.map(item => [
                item.doc,
                item.nombre_usuario,
                item.cel,
                item.tel,
                item.correo_electronico,
                item.ciudad,
                item.direccion_residencial,
                item.municipio || 'N/A',
                item.centro_costo || 'N/A',
                item.tipo_contrato || 'N/A',
                item.fecha_nacimiento || 'N/A'
            ]);
            doc.autoTable(columns, rows);
            doc.save('resultados.pdf');
        },
        exportToExcel() {
            const columns = ['Documento', 'Nombre', 'Celular', 'Teléfono Fijo', 'Email', 'Ciudad', 'Dirección', 'Municipio', 'Centro de Costo', 'Tipo de Contrato', 'Fecha de Nacimiento'];
            const rows = this.results.map(item => [
                item.doc,
                item.nombre_usuario,
                item.cel,
                item.tel,
                item.correo_electronico,
                item.ciudad,
                item.direccion_residencial,
                item.municipio || 'N/A',
                item.centro_costo || 'N/A',
                item.tipo_contrato || 'N/A',
                item.fecha_nacimiento || 'N/A'
            ]);
            
            // Convertir las filas a una hoja de trabajo de Excel
            const worksheet = XLSX.utils.aoa_to_sheet([columns, ...rows]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Resultados');
            XLSX.writeFile(workbook, 'resultados.xlsx');
        }
    },
    mounted() {
        this.fetchRecentConsultations();
    }
};
</script>

<style scoped>
.panel-heading {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
}

.panel-body {
    padding: 15px;
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
