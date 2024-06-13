<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Busqueda de Cedulas</b>
            </div>
            <div class="panel-body">
                <form @submit.prevent="submitFile" v-if="!uploading && !processing">
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
                        <p>Processing the file, please wait...</p>
                    </div>
                </div>

                <div v-if="results.length > 0">
                    <div class="mb-3">
                        <b-form-input
                            v-model="searchQuery"
                            placeholder="Buscar por cédula (mínimo 3 caracteres)"
                            @input="filterResults"
                        ></b-form-input>
                    </div>
                    <div class="table-responsive">
                        <b-table striped hover :fields="fields" :items="filteredResults">
                            <template v-slot:cell(found)="row">
                                <span v-if="row.item.found">Found</span>
                                <span v-else>No se encontró</span>
                            </template>
                        </b-table>
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

export default {
    components: {
        Loading
    },
    data() {
        return {
            file: null,
            uploading: false,
            processing: false,
            progress: 0,
            isLoading: false,
            cedulas: [],
            results: [],
            searchQuery: '',
            fields: [
                { key: 'cedula', label: 'Cedula' },
                { key: 'primer_apellido', label: 'Primer Apellido' },
                { key: 'segundo_apellido', label: 'Segundo Apellido' },
                { key: 'primer_nombre', label: 'Primer Nombre' },
                { key: 'segundo_nombre', label: 'Segundo Nombre' },
                { key: 'direccion', label: 'Direccion' },
                { key: 'telefono', label: 'Telefono' },
                { key: 'correo_electronico', label: 'Correo Electronico' },
                { key: 'nacimiento', label: 'Nacimiento' },
                { key: 'sexo', label: 'Sexo' },
                { key: 'departamento', label: 'Departamento' },
                { key: 'municipio', label: 'Municipio' },
                { key: 'vpensiones', label: 'VPensiones' },
                { key: 'vsalud', label: 'VSalud' },
                { key: 'vembargo', label: 'VEmbargo' },
                { key: 'vdescuentos', label: 'VDescuentos' },
                { key: 'capacidad', label: 'Capacidad' },
                { key: 'found', label: 'Encontrado' }
            ]
        };
    },
    computed: {
        filteredResults() {
            if (this.searchQuery.length < 3) {
                return this.results;
            }
            return this.results.filter(item => item.cedula.toString().includes(this.searchQuery));
        }
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async submitFile() {
            if (!this.file) {
                alert('Please select a file to upload');
                return;
            }

            const formData = new FormData();
            formData.append('file', this.file);

            this.uploading = true;
            this.progress = 0;

            try {
                const response = await axios.post('/joinpensiones/upload', formData, {
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
                    this.cedulas = response.data.cedulas;
                    this.searchCedulas();
                } else {
                    console.error(response.data.message);
                    this.uploading = false;
                }
            } catch (error) {
                console.error('Error uploading file:', error);
                this.uploading = false;
            }
        },
        async searchCedulas() {
            try {
                const response = await axios.post('/joinpensiones/search', { cedulas: this.cedulas });
                this.results = response.data.results;
                this.processing = false;
            } catch (error) {
                console.error('Error searching cedulas:', error);
                this.processing = false;
            }
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
</style>
