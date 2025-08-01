<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="panel mb-3 col-md-12">    
            <div class="row">
                <div class="col-sm mb-2 mt-5">
                    <h3 class="heading-title">Busqueda de Cedulas</h3>
                </div>
                <div class="col-sm mb-2 mt-5" v-if="results.length > 0">
                    <CustomButton text="Agregar Documento" @click="showModalToAdd"/>
                </div>
            </div>
            <div v-if="results.length === 0">
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
                        <b-table striped hover :fields="fields" :items="filteredResults" :row-class="rowClass">
                            <template v-slot:cell(cedula)="row">
                                <span :class="{ 'text-danger': !row.item.found }">{{ row.item.cedula }}</span>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
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
                        <form @submit.prevent="submitFile" v-if="!uploading && !processing">
                            <b-form-group label="Seleccione el archivo para cargar">
                                <b-form-file @change="handleFileUpload" required></b-form-file>
                            </b-form-group>
                            <CustomButton type="submit" text="Upload File"/>
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
        showModalToAdd() {
            this.$refs.editModal.style.display = 'block';
            this.editMode = false;
        },
        hideModal() {
            this.$refs.editModal.style.display = 'none';
        },
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
                this.results = response.data.results.map(result => ({
                    ...result,
                    found: Object.keys(result).some(key => result[key] !== null && result[key] !== '')
                }));
                this.processing = false;
            } catch (error) {
                console.error('Error searching cedulas:', error);
                this.processing = false;
            }
        },
        rowClass(item) {
            return item.found ? '' : 'table-danger';
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

.text-danger {
    color: red !important;
}

.table-danger {
    background-color: #f8d7da !important;
}
</style>
