<template>
    <div class="container-fluid">
        <div v-if="type_consult === 'individual'">
            <div id="consulta-container" class="row">
                <div class="panel mb-3 col-md-12">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm mb-2 mt-5">
                                <h2 class="title"><strong>Registro de Documentos</strong></h2>
                            </div>
                            <div class="col-sm mb-2 mt-5" v-if="items.length > 0">
                                <button class="btn btn-sm btn-success" style="border: 1px solid #b9bdc3; border-radius: 10px" @click="showModalToAdd">
                                    Agregar Documento
                                </button>
                                <button class="btn btn-sm btn-outline-success" style="border: 1px solid #b9bdc3; border-radius: 10px" @click="showBulkUploadModal">
                                    Crear Registro Masivo
                                </button>
                            </div>
                        </div>
                        <table class="table table-striped mt-3" style="border: 1px solid #b9bdc3; border-radius: 10px" v-if="items.length > 0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha y Hora</th>
                                    <th>Compañía</th>
                                    <th>Usuario</th>
                                    <th>Cédula</th>
                                    <th>Nombre Completo</th>
                                    <th>Tipo de Documento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in items" :key="item.id">
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.timestamp }}</td>
                                    <td>{{ item.company }}</td>
                                    <td>{{ item.user }}</td>
                                    <td>{{ item.documentId }}</td>
                                    <td>{{ item.fullName }}</td>
                                    <td>{{ item.documentType }}</td>
                                    <td>
                                        {{ item.status }}
                                    
                                        <button class="btn btn-success" @click="showUploadModal(item)">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        <button class="btn btn-danger" @click="deletePdf(item)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <a href="#" v-if="item.pdfUrl" @click="downloadPdf(item)" class="btn btn-info">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            <div class="text-center" style="margin-top: 100px;">
                                <Lupa style="margin-bottom: 50px;"></Lupa>
                                <h2>Aun no tienes archivos cargados, Puedes...</h2>
                                <button class="btn btn-success" style="border: 1px solid #b9bdc3; border-radius: 10px" @click="showModalToAdd">
                                    Agregar Documento
                                </button>
                                <button class="btn btn-outline-success" style="border: 1px solid #b9bdc3; border-radius: 10px" @click="showBulkUploadModal">
                                    Crear Registro Masivo
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for adding/editing a document -->
        <div class="modal" tabindex="-1" role="dialog" style="display: none" ref="editModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ currentItem.id ? 'Editar Documento' : 'Agregar Documento' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm">
                            <div class="form-group">
                                <label for="company">Compañía</label>
                                <input
                                    type="text"
                                    id="company"
                                    v-model="currentItem.company"
                                    class="form-control2"
                                    style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <label for="user">Usuario</label>
                                <input type="text" id="user" v-model="currentItem.user" class="form-control2" style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px" required />
                            </div>
                            <div class="form-group">
                                <label for="documentId">Cédula</label>
                                <input
                                    type="text"
                                    id="documentId"
                                    v-model="currentItem.documentId"
                                    class="form-control2"
                                    style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <label for="fullName">Nombre Completo</label>
                                <input
                                    type="text"
                                    id="fullName"
                                    v-model="currentItem.fullName"
                                    class="form-control2"
                                    style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <label for="documentType">Tipo de Documento</label>
                                <select
                                    id="documentType"
                                    v-model="currentItem.documentType"
                                    class="form-control2"
                                    style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"
                                    required
                                >
                                    <option disabled value="">Seleccione uno</option>
                                    <option value="certificado de defunción">Certificado de Defunción</option>
                                    <option value="nacimiento">Nacimiento</option>
                                    <option value="historia clínica">Historia Clínica</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ currentItem.id ? 'Actualizar' : 'Agregar' }}
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="hideModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for uploading PDF -->
        <div class="modal" tabindex="-1" role="dialog" style="display: none" ref="uploadModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cargar PDF para Documento</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="hideUploadModal"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" @change="handleFileUpload" class="form-control2" style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="uploadPdf">Subir</button>
                        <button type="button" class="btn btn-secondary" @click="hideUploadModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for bulk uploading documents -->
        <div class="modal" tabindex="-1" role="dialog" style="display: none" ref="bulkUploadModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear un registro masivo</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click="hideBulkUploadModal"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card" style="background: 5px solid #3498db; border-radius: 10px; border-left: 5px solid #3498db; margin-bottom: 30px;">
                            <div class="card-body" style="background: #f9fafc;">
                                Por favor asegúrese de que el archivo Excel tenga los siguientes encabezados (en cualquier
                                orden) y que los datos sean válidos: <strong>Compañia, Usuario, Cedula, NombreCompleto, Tipo (1, 2, o 3)</strong>
                            </div>
                        </div>
                        <input type="file" @change="handleBulkFileUpload" class="form-control2" style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px" />
                        <div v-if="bulkUploadError" class="alert alert-danger mt-3">
                            <p>Error al cargar el archivo:</p>
                            <ul>
                                <li v-for="message in bulkUploadErrorMessages" :key="message">{{ message }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="uploadBulkFile">Subir</button>
                        <button type="button" class="btn btn-secondary" @click="hideBulkUploadModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Lupa from '../../icons/Lupa.vue';

export default {
    data() {
        return {
            items: [],
            type_consult: 'individual',
            currentItem: {},
            editMode: false,
            bulkFile: null,
            bulkUploadError: false,
            bulkUploadErrorMessages: []
        };
    },
    components: {
        Lupa,
    },
    created() {
        this.fetchDocuments();
    },
    methods: {
        fetchDocuments() {
            axios
                .get('/documents')
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => {
                    console.error('There was an error fetching the documents: ', error);
                });
        },
        downloadPdf(item) {
            const url = `/documents/${item.id}/download-pdf`;
            axios
                .get(url, { responseType: 'blob' })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', `Document-${item.id}.pdf`);
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch(error => {
                    console.error('Error downloading the PDF:', error.response.data);
                    console.log('Detailed path info:', error.response.data.path);
                });
        },
        showModalToAdd() {
            this.currentItem = { status: 'Pendiente' };
            this.$refs.editModal.style.display = 'block';
            this.editMode = false;
        },
        showModal(item) {
            this.currentItem = Object.assign({}, item);
            this.$refs.editModal.style.display = 'block';
            this.editMode = true;
        },
        hideModal() {
            this.$refs.editModal.style.display = 'none';
        },
        showUploadModal(item) {
            this.currentItem = Object.assign({}, item);
            this.$refs.uploadModal.style.display = 'block';
        },
        hideUploadModal() {
            this.$refs.uploadModal.style.display = 'none';
        },
        handleFileUpload(event) {
            this.currentItem.file = event.target.files[0];
        },
        submitForm() {
            let method = this.editMode ? 'put' : 'post';
            let url = `documents${this.editMode ? '/' + this.currentItem.id : ''}`;
            axios[method](url, this.currentItem)
                .then(response => {
                    this.fetchDocuments();
                    this.hideModal();
                })
                .catch(error => {
                    console.error('There was an error submitting the form: ', error);
                });
        },
        uploadPdf() {
            if (!this.currentItem.file) {
                alert('Please select a file.');
                return;
            }
            const formData = new FormData();
            formData.append('pdf', this.currentItem.file);
            axios
                .post(`documents/${this.currentItem.id}/upload-pdf`, formData)
                .then(response => {
                    this.currentItem.status = 'Procesado';
                    this.fetchDocuments();
                    this.hideUploadModal();
                })
                .catch(error => {
                    console.error('There was an error uploading the file: ', error);
                });
        },
        deletePdf(item) {
            axios
                .post(`/documents/${item.id}/delete-pdf`)
                .then(response => {
                    item.status = 'PDF Eliminado';
                    this.fetchDocuments();
                    console.log('PDF deleted successfully');
                })
                .catch(error => {
                    console.error('There was an error deleting the PDF: ', error);
                });
        },
        showBulkUploadModal() {
            this.$refs.bulkUploadModal.style.display = 'block';
        },
        hideBulkUploadModal() {
            this.$refs.bulkUploadModal.style.display = 'none';
        },
        handleBulkFileUpload(event) {
            this.bulkFile = event.target.files[0];
        },
        uploadBulkFile() {
            if (!this.bulkFile) {
                alert('Por favor seleccione un archivo.');
                return;
            }
            const formData = new FormData();
            formData.append('file', this.bulkFile);
            axios
                .post('/documents/upload-bulk', formData)
                .then(response => {
                    this.fetchDocuments();
                    this.hideBulkUploadModal();
                    this.bulkUploadError = false;
                    this.bulkUploadErrorMessages = [];
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.bulkUploadError = true;
                        this.bulkUploadErrorMessages = error.response.data.messages;
                    } else {
                        console.error('Hubo un error al cargar el archivo: ', error);
                    }
                });
        }
    }
};
</script>

<style scoped>
.panel-heading {
    box-shadow: 10px 5px 5px #09ac80;
    font-weight: bold;
}
.btn-primary,
.btn-warning,
.btn-danger,
.btn-success {
    margin-right: 8px;
}
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content {
    border-color:white;
}
.modal-dialog {
    background: white;
    padding: 20px;
    border-radius: 5px;
    max-width: 662px; /* Adjust the width as needed */
}
.modal-body {
    max-height: 70vh; /* Adjust the height as needed */
    overflow-y: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: #3e575b; /* Color de fondo de las cabeceras */
    color: white; /* Color del texto para contraste */
    padding: 10px;
    text-align: left;
}
td {
    padding: 10px;
    border: 1px solid #ddd; /* Borde de las celdas */
}
tr:nth-child(even) {
    background-color: #f9f9f9; /* Color de fondo para filas pares */
}
</style>
