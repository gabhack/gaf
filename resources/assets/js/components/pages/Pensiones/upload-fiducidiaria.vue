<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Carga de Archivo y Proceso de Datos Fiducidiaria</b>
            </div>
            <div class="panel-body">
                <form @submit.prevent="submit" v-if="!processing && !completed">
                    <b-form-group label="Seleccione el archivo para cargar">
                        <b-form-file @change="handleFileUpload" required></b-form-file>
                    </b-form-group>
                    <b-button type="submit" variant="primary">Upload File</b-button>
                </form>

                <div v-if="uploading">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" :style="{ width: progress + '%' }">
                            {{ progress }}%
                        </div>
                    </div>
                </div>

                <div v-if="processing">
                    <div class="overlay">
                        <p>Processing the file, please wait... This may take up to 15 minutes.</p>
                    </div>
                </div>

                <div v-if="completed">
                    <p>File processing completed!</p>
                    <b-button variant="success" @click="reset">Upload Another File</b-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Loading
    },
    data() {
        return {
            file: null,
            uploading: false,
            processing: false,
            completed: false,
            progress: 0,
            isLoading: false,
            progressKey: null
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
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

            try {
                const response = await axios.post('/fiducidiaria/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: progressEvent => {
                        this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                });

                console.log('Upload response:', response.data);

                if (response.data.success) {
                    console.log(response.data.message);
                    this.uploading = false;
                    this.processing = true;
                    this.progressKey = response.data.progressKey;
                    this.isLoading = true;
                    this.checkProgress();
                } else {
                    console.error(response.data.message);
                    this.uploading = false;
                }
            } catch (error) {
                console.error('Error uploading file:', error);
                this.uploading = false;
            }
        },
        async checkProgress() {
            try {
                const response = await axios.get(`/fiducidiaria/progress/${this.progressKey}`);

                if (response.data.completed) {
                    this.processing = false;
                    this.completed = true;
                    this.isLoading = false;
                } else {
                    setTimeout(this.checkProgress, 5000); // Check progress every 5 seconds
                }
            } catch (error) {
                console.error('Error checking progress:', error);
                setTimeout(this.checkProgress, 5000);
            }
        },
        reset() {
            this.file = null;
            this.uploading = false;
            this.processing = false;
            this.completed = false;
            this.progress = 0;
            this.isLoading = false;
            this.progressKey = null;
        }
    }
};
</script>

<style>
.form-group legend {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 900;
}

.progress {
    width: 100%;
    background-color: #f3f3f3;
}

.progress-bar {
    width: 0%;
    height: 20px;
    background-color: #4caf50;
    text-align: center;
    line-height: 20px;
    color: white;
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
    border-color: none !important;
}

.button-tablas:active {
    background: #000 !important;
    color: #0cedb0 !important;
    border-color: none !important;
}

.button-tablas:not(:disabled):not(.disabled):active,
.button-tablas:not(:disabled):not(.disabled).active,
.show > .button-tablas.dropdown-toggle {
    background: #000 !important;
    color: #0cedb0 !important;
    border-color: none !important;
}
</style>
