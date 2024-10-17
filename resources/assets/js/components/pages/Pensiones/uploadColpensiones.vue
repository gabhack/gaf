<template>
    <div class="panel d-flex vh-100 flex-column">
        <b-card class="flex-grow-1 w-100">
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

            <div class="p-0 mb-3 col-md-12 mt-4 mb-3">
                <div class="row">
                    <div class="col-sm mb-2 mt-5">
                        <h3 class="heading-title">Carga de Archivo Colpensiones</h3>
                    </div>
                    <div class="col-sm mb-2 mt-5" v-if="logs.length > 0">
                        <CustomButton text="Agregar Documento" @click="showModalToAdd"/>
                    </div>
                </div>
                
                <div v-if="logs.length === 0">
                    <div class="text-center" style="margin-top: 100px;">
                        <Lupa style="margin-bottom: 50px;"></Lupa>
                        <h2>Aun no tienes archivos cargados, Puedes...</h2>
                        <CustomButton text="Agregar Documento" @click="showModalToAdd"/>
                    </div>
                </div>
                <div class="panel-body">
                    <div v-if="uploading">
                        <div class="alert alert-info" role="alert">Cargando archivo al servidor...</div>
                    </div>

                    <div v-if="uploadingComplete && !processing">
                        <div class="alert alert-info" role="alert">CARGANDO REGISTROS EN LA BASE DE DATOS...</div>
                    </div>

                    <div v-if="processing">
                        <div class="overlay">
                            <p>Processing the file, please wait... This may take up to 15 minutes.</p>
                        </div>
                    </div>

                    <div v-if="completed">
                        <p>Se registraron los clientes en la base de datos!</p>
                        <b-button variant="success" @click="reset" :disabled="processing"
                            >Subir otro archivo colpensiones</b-button
                        >
                    </div>
                </div>
            </div>

            <div class="p-0 mb-3 col-md-12" v-if="logs.length">
                <h3 class="heading-title">Archivos en Proceso</h3>
        
                <div class="panel-body">
                    <b-table head-variant="dark" style="border: 1px solid #b9bdc3; border-radius: 10px" striped hover :items="sortedLogs" :fields="fields" :row-class="rowClass"></b-table>
                </div>
            </div>
        </b-card>

        <div class="modal" tabindex="-1" role="dialog" style="display: none" ref="editModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submit" v-if="!processing && !completed && !uploadingComplete">
                            <b-form-group label="Seleccione el archivo para cargar">
                                <b-form-file @change="handleFileUpload" accept=".csv,.txt" required></b-form-file>
                            </b-form-group>
                            <CustomButton type="submit" text="Cargar archivo" :disabled="uploading || processing"/>
                            <CustomButton v-if="uploading" text="Cancelar" @click="cancelUpload" class="white"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hideModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Lupa from '../../icons/Lupa.vue';
import CustomButton from '../../customComponents/CustomButton.vue';

export default {
    components: {
        Loading,
        Lupa,
        CustomButton
    },
    data() {
        return {
            file: null,
            uploading: false,
            processing: false,
            completed: false,
            progress: 0,
            isLoading: false,
            progressKey: null,
            cancelTokenSource: null,
            logs: [],
            uploadingComplete: false,
            fields: [
                { key: 'file_path', label: 'File Path' },
                { key: 'timestamp', label: 'Timestamp' },
                { key: 'total_registros', label: 'Total Registros' },
                { key: 'registros_procesados', label: 'Registros Procesados' },
                { key: 'total_por_registrar', label: 'Total Por Registrar' },
                { key: 'http_status', label: 'HTTP Status' }
            ]
        };
    },
    computed: {
        sortedLogs() {
            return this.logs.slice().sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        }
    },
    methods: {
        showModalToAdd() {
            this.$refs.editModal.style.display = 'block';
            this.editMode = false;
        },
        hideModal() {
            this.$refs.editModal.style.display = 'none';
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file && (file.type === 'text/csv' || file.name.endsWith('.csv') || file.name.endsWith('.txt'))) {
                this.file = file;
            } else {
                alert('Please select a valid CSV or TXT file.');
                this.file = null;
            }
        },
        async submit() {
            if (!this.file) {
                alert('Please select a file to upload');
                return;
            }

            const formData = new FormData();
            formData.append('file', this.file);

            this.uploading = true;
            this.progress = 0;
            this.cancelTokenSource = axios.CancelToken.source();

            try {
                const response = await axios.post('/colpensiones/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    cancelToken: this.cancelTokenSource.token
                });

                console.log('Upload response:', response.data);

                if (response.data.success) {
                    console.log(response.data.message);
                    this.uploading = false;
                    this.uploadingComplete = true;
                    this.processing = true;
                    this.progressKey = response.data.progressKey;
                    this.isLoading = true;
                    this.checkProgress();
                } else {
                    console.error(response.data.message);
                    this.uploading = false;
                }

                // Actualizar la lista de archivos después de cargar el archivo
                this.fetchLogs();
            } catch (error) {
                if (axios.isCancel(error)) {
                    console.log('Upload canceled');
                } else {
                    console.error('Error uploading file:', error);
                }
                this.uploading = false;
            }
        },
        async checkProgress() {
            try {
                const response = await axios.get(`/colpensiones/progress/${this.progressKey}`);

                if (response.data.completed) {
                    this.processing = false;
                    this.completed = true;
                    this.isLoading = false;
                    this.uploadingComplete = false; // Remove the loading message
                    this.fetchLogs(); // Fetch logs when the processing is completed
                } else {
                    setTimeout(this.checkProgress, 5000); // Check progress every 5 seconds
                }
            } catch (error) {
                console.error('Error checking progress:', error);
                setTimeout(this.checkProgress, 5000);
            }
        },
        cancelUpload() {
            if (this.cancelTokenSource) {
                this.cancelTokenSource.cancel('Upload canceled by user');
            }
            this.uploading = false;
            this.file = null;
            this.progress = 0;
        },
        reset() {
            this.file = null;
            this.uploading = false;
            this.uploadingComplete = false;
            this.processing = false;
            this.completed = false;
            this.progress = 0;
            this.isLoading = false;
            this.progressKey = null;
        },
        async fetchLogs() {
            try {
                const response = await axios.get('/file-upload-logs');
                this.logs = response.data;
            } catch (error) {
                console.error('Error fetching logs:', error);
            }
        },
        rowClass(item) {
            return item.progress_key === this.progressKey ? 'bg-light-green' : '';
        }
    },
    mounted() {
        this.fetchLogs();
        setInterval(this.fetchLogs, 10000); // Fetch logs every 10 seconds
    }
};
</script>

<style>
.form-group legend {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 900;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    color: white;
}

.label-resumen {
    font-weight: 600;
    text-align: center;
    background-color: #e1e1e1;
    color: #021b1e;
    border-radius: 38px;
    border-color: transparent;
    min-height: 30px;
    margin: 0 !important;
    display: flex;
    align-items: center;
    padding-top: 6px;
    padding-bottom: 6px;
    padding-left: 12px;
    padding-right: 12px;
}

.label-titulo {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 900;
    margin: 0 !important;
    display: flex;
    align-items: center;
}

.button-tablas {
    text-align: left;
    background: #000;
    color: #fff;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 48px;
}

.button-tablas:hover {
    background: #000 !important;
    color: #0cedb0 !important;
}

.button-tablas:active {
    background: #000 !important;
    color: #0cedb0 !important;
}

.button-tablas:not(:disabled):not(.disabled):active,
.button-tablas:not(:disabled):not(.disabled).active,
.show > .button-tablas.dropdown-toggle {
    background: #000 !important;
    color: #0cedb0 !important;
}

.bg-light-green {
    background-color: #d4edda !important;
}
</style>
