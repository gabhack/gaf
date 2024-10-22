<template>
    <div style="padding: 30px">
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <b-row>
            <b-col cols="12" md="9">
                <h3 class="heading-title">Datos demográficos</h3>
                <p>Lörem ipsum despejode anas. Heteros ståpaddling. Dekameling agnostityp</p>
            </b-col>
            <b-col cols="12" md="3" class="d-flex justify-content-start justify-content-md-end align-items-center">
                <CustomButton @click="toggleRecentConsultations">{{
                    showRecentConsultations ? 'Ocultar Consultas Recientes' : 'Ver Consultas Recientes'
                }}</CustomButton>
            </b-col>
        </b-row>
        <div
            style="min-height: 500px"
            class="panel mb-3 col-md-12 d-flex justify-content-center align-items-center"
            v-if="!results.length"
        >
            <div class="d-flex flex-column align-items-center justify-content-center">
                <Lupa class="mb-3" />
                <p>
                    Aún no tienes archivos <br />
                    cargados, puedes...
                </p>
                <CustomButton text="Cargar archivo" @click="$bvModal.show('bv-modal-example')" />
            </div>
            <b-modal id="bv-modal-example" hide-footer style="min-width: 1000px">
                <template #modal-title><span class="heading-title">Agregar datos demográficos</span></template>
                <div class="" style="background-color: #f9fafc; border-left: 4px solid #249fe3; border-radius: 4px">
                    <b-row style="padding: 16px">
                        <b-col cols="1" class="d-flex justify-content-center align-items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM9 4C9 4.55228 8.55228 5 8 5C7.44772 5 7 4.55228 7 4C7 3.44772 7.44772 3 8 3C8.55228 3 9 3.44772 9 4ZM7 7C6.44772 7 6 7.44772 6 8C6 8.55229 6.44772 9 7 9V12C7 12.5523 7.44772 13 8 13H9C9.55228 13 10 12.5523 10 12C10 11.4477 9.55228 11 9 11V8C9 7.44772 8.55228 7 8 7H7Z"
                                    fill="#20A0E9"
                                />
                            </svg>
                        </b-col>
                        <b-col cols="11" class="d-flex justify-content-center align-items-center">
                            <p class="modal-text">
                                Por favor, asegúrese de que el archivo Excel tiene una columna con el encabezado
                                <strong>'cedulas'</strong> y que contiene los números de cédula.
                            </p>
                        </b-col>
                    </b-row>
                </div>
                <b-row class="py-3">
                    <div class="col-md-12">
                        <b-form-group label="Mes (MM)">
                            <b-form-input
                                v-model="mes"
                                type="number"
                                placeholder="01"
                                class="input_style_b form-control2"
                            ></b-form-input>
                        </b-form-group>
                    </div>
                    <div class="col-md-12">
                        <b-form-group label="Año (YYYY)">
                            <b-form-input
                                v-model="año"
                                type="number"
                                placeholder="2024"
                                class="input_style_b form-control2"
                            ></b-form-input>
                            
                        </b-form-group>
                    </div>
                    <b-col
                        cols="12"
                        style="
                            min-height: 150px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            cursor: pointer;
                        "
                        @click="triggerFileInput"
                        @dragover.prevent="handleDragOver"
                        @dragleave.prevent="handleDragLeave"
                        @drop.prevent="handleDrop"
                    >
                        <div
                            style="display: flex; flex-direction: column; align-items: center; justify-content: center"
                        >
                            <UploadFile class="mb-2" />
                            <p class="text-center" style="margin-bottom: 0.5rem">
                                Arrastre o suelte el archivo <br />
                                o
                            </p>
                            <CustomButton text="Seleccionar archivo" :color="'white'" />
                        </div>
                        <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none" />
                    </b-col>
                </b-row>
                <div class="d-flex justify-content-center align-item-center mb-5" style="width: 100%" v-if="file">
                    <div
                        style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            width: 300px;
                            border-bottom: 1px solid #babcbe;
                            padding: 8px;
                        "
                    >
                        <span style="font-size: 12px; font-weight: 400; line-height: 15.62px; color: black">{{
                            file.name
                        }}</span>
                        <button style="padding: 0; margin: 0; border: none; background: none;" @click="deleteFile"><Trash /></button>
                    </div>
                </div>
                <CustomButton @click="uploadFile($bvModal)" text="Subir archivo" v-if="file" />
                <CustomButton @click="$bvModal.hide('bv-modal-example')" :color="'white'" text="Cerrar" />
            </b-modal>
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
                    <button @click="exportToPDF" class="btn btn-danger mr-2">Exportar a PDF</button>
                    <button @click="exportToExcel" class="btn btn-success">Exportar a Excel</button>
                </div>
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
                                <td>{{ result.doc }}</td>
                                <td>{{ result.nombre_usuario }}</td>
                                <td>{{ result.cel }}</td>
                                <td>{{ result.tel }}</td>
                                <td>{{ result.correo_electronico }}</td>
                                <td>{{ result.ciudad }}</td>
                                <td>{{ result.direccion_residencial }}</td>
                                <td>{{ result.centro_costo }}</td>
                                <td>{{ result.tipo_contrato }}</td>
                                <td>{{ result.edad }}</td>
                                <td>{{ result.fecha_nacimiento }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <button @click="fetchPaginatedResults(page - 1)" :disabled="page === 1" class="btn btn-primary">
                        Anterior
                    </button>
                    <button
                        @click="fetchPaginatedResults(page + 1)"
                        :disabled="page * perPage >= total"
                        class="btn btn-primary"
                    >
                        Siguiente
                    </button>
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
import Lupa from '../../icons/Lupa.vue';
import UploadFile from '../../icons/UploadFile.vue';
import Trash from '../../icons/Trash.vue';
export default {
    name: 'DemographicData',
    components: {
        CustomButton,
        Lupa,
        UploadFile,
        Trash
    },
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
            total: 0,
            mes: 0,
            año: 0
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
        deleteFile() {
            this.file = null;
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        handleDragOver(event) {
            this.isDragging = true; 
        },
        handleDragLeave(event) {
            this.isDragging = false; 
        },
        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file) {
                this.file = file;
                this.handleFileUpload({ target: { files: [file] } }); 
                console.log('Archivo cargado desde drag & drop:', file);
            }
            this.isDragging = false;
        },
        async uploadFile(modal) {
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
                modal.hide('bv-modal-example');
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
                let response = await axios.get('/demografico/fetch-paginated-results', {
                    params: {
                        page: page,
                        perPage: this.perPage,
                        mes: this.mes,
                        año: this.año
                    }
                });

                console.log('Datos recibidos:', response.data.data); // Depuración

                // Mapear los resultados para agregar propiedades para mostrar detalles
                this.results = response.data.data.filter(item => item && typeof item === 'object').map(item => ({
                    ...item,
                    showCupones: false,
                    showEmbargos: false,
                    showDescuentos: false
                }));
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
            const columns = [
                'Documento',
                'Nombre',
                'Celular',
                'Teléfono Fijo',
                'Email',
                'Ciudad',
                'Dirección',
                'Centro de Costo',
                'Tipo de Contrato',
                'Edad',
                'Fecha de Nacimiento'
            ];
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
            const columns = [
                'Documento',
                'Nombre',
                'Celular',
                'Teléfono Fijo',
                'Email',
                'Ciudad',
                'Dirección',
                'Centro de Costo',
                'Tipo de Contrato',
                'Edad',
                'Fecha de Nacimiento'
            ];
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
    }
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

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
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
::v-deep {
    & .modal-header {
        border-bottom: none;
    }
    & .modal-dialog {
        min-width: 600px;
    }
}
.modal-text {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
    margin: 0;
}
.drag-over {
    border: 2px dashed #007bff;
    background-color: #f0f8ff;
}
</style>