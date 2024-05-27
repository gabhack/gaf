<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Datos Demográficos</b>
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

        <div v-if="results.length" class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Resultados</b>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Celular</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Correo Electrónico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="result in results" :key="result.doc">
                            <td>{{ result.doc }}</td>
                            <td>{{ result.cel }}</td>
                            <td>{{ result.direccion_residencial }}</td>
                            <td>{{ result.telefono }}</td>
                            <td>{{ result.correo_electronico }}</td>
                        </tr>
                    </tbody>
                </table>
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
            error: null
        };
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
        }
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
</style>
