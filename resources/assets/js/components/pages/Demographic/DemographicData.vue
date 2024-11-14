<template>
    <div style="padding: 30px">
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <b-row>
            <b-col cols="12" md="9" style="margin-left: 28px;">
                <h3 class="heading-title">Análisis de Cartera</h3>
                <p>Acceda a información estratégica que facilita la toma de decisiones en la compra de cartera, 
            permitiendo identificar y priorizar a los pensionados y empleados activos del sector público con potencial de recuperación.</p>
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
                                <strong>'cédulas'</strong> y que contiene los números de cédula.
                            </p>
                        </b-col>
                    </b-row>
                </div>
                <b-row class="py-3">
                    <div class="col-md-12">
                        <b-form-group label="Mes (MM):">
                            <b-form-input
                                v-model="mes"
                                placeholder="01"
                                maxlength="2"
                                class="input_style_b form-control2"
                            ></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-12">
                        <b-form-group label="Año (YYYY):">
                            <b-form-input
                                v-model="año"
                                placeholder="2024"
                                maxlength="4"
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

        <!-- Panel de Resultados -->
        <div v-if="results.length" class="panel mb-3 col-md-12">  
                <b-row style="margin-left: 900px">
                    <b-col cols="12" md="3" class="d-flex justify-content-start justify-content-md-end align-items-center">
                        <CustomButton text="Cargar archivo" @click="$bvModal.show('bv-modal-example')" style="white-space: nowrap; margin-right: 8px;" />
                        <CustomButton @click="exportToPDF" class="btn btn-danger mr-2" text="Exportar a PDF"  style="white-space: nowrap;"/>
                        <CustomButton @click="exportToExcel" class="btn btn-success" text="Exportar a Excel" style="white-space: nowrap;"/>
                    </b-col>
                </b-row>          
                <hd style="margin-left: 16px" class="heading-title">Resultado:</hd>
                <div class="panel-body">
                <b-form-group>
                    <b-form-input
                        v-model="searchQuery"
                        placeholder="Buscar por documento"
                        class="input_style_b form-control2"
                    ></b-form-input>
                </b-form-group>
                <div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre del Cliente</th>
                                <th>Fecha Nacimiento</th>
                                <th>Edad</th>
                                <th>Tipo de Contrato</th>
                                <th>Cargo</th>
                                <th>Situación Laboral</th>
                                <th>Pagaduría</th>
                                <th>Cupo Libre</th>
                                <th>Detalle de Embargo</th>
                                <th>Detalle de Egresos</th>
                                <th>Detalle de Mora</th>
                                <th>Colpensiones</th>
                                <th>Fiduprevisora</th>
                                <th>Fopep</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Fila Principal -->
                            <tr v-for="(result, index) in filteredResults" :key="result.doc + '-' + index">
                                <td>{{ result.doc }}</td>
                                <td>{{ capitalize(result.nombre_usuario) || 'No disponible' }}</td>
                                <td>{{ result.fecha_nacimiento || 'No disponible' }}</td>
                                <td>{{ result.edad || 'No disponible' }}</td>
                                <td>{{ capitalize(result.tipo_contrato) || 'No disponible' }}</td>
                                <td>{{ capitalize(result.cargo) || 'No disponible' }}</td>
                                <td>{{ capitalize(result.situacion_laboral) || 'No disponible' }}</td>
                                <td>{{ capitalize(result.pagaduria) || 'No disponible' }}</td>
                                <td>{{ formatCurrency(result.cupo_libre) }}</td>
                                <td>
                                    <CustomButton
                                        v-if="result.embargos && result.embargos.length"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'embargos')"
                                        data-toggle="modal" data-target="#modalEmbargos"
                                        :text="isRowExpanded(result, 'embargos') ? 'Ocultar Embargos' : 'Ver Embargos'"    
                                    />
                                    <span v-else>No hay embargos</span>
                                </td>
                                <td>
                                    <CustomButton
                                        v-if="result.cupones && result.cupones.length"
                                        @click="toggleDetails(result, 'cupones')"
                                        data-toggle="modal" data-target="#modalCupones"
                                        :text="isRowExpanded(result, 'cupones') ? 'Ocultar Cupones' : 'Ver Cupones'"
                                    />
                                    <span v-else>No hay cupones</span>        
                                </td>
                                
                                <td>
                                    <CustomButton
                                        v-if="result.descuentos && result.descuentos.length"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'descuentos')"
                                        data-toggle="modal" data-target="#modalDescuentos"
                                        :text="isRowExpanded(result, 'descuentos') ? 'Ocultar Descuentos' : 'Ver Descuentos'"
                                    />
                                    <span v-else>No hay descuentos</span>
                                </td>
                                <td>{{ result.colpensiones ? 'Sí' : 'No' }}</td>
                                <td>{{ result.fiducidiaria ? 'Sí' : 'No' }}</td>
                                <td>{{ result.fopep ? 'Sí' : 'No' }}</td>

                            </tr>
                            <!-- Fila para mostrar mensaje si no hay resultados -->
                            <tr v-if="filteredResults.length === 0">
                                <td colspan="15">No hay resultados</td>
                            </tr>
                        </tbody>
                    </table>
                   <!-- Modal de Cupones -->
                   <div class="modal fade" id="modalCupones" tabindex="-1" role="dialog" aria-labelledby="modalCuponesLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCuponesLabel">Cupones</h5>
                                    <button @click="closeExpandedRows" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" v-for="(result, index) in filteredResults" :key="'cupones-' + result.doc + '-' + index" v-if="isRowExpanded(result, 'cupones')">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Egresos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="cupon in result.cupones" :key="cupon.id">
                                                <td>{{ cupon.concept || 'No disponible' }}</td>
                                                <td>{{ formatCurrency(cupon.egresos) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal de Embargos -->
                    <div class="modal fade" id="modalEmbargos" tabindex="-1" role="dialog" aria-labelledby="modalEmbargosLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEmbargosLabel">Detalle de Embargos</h5>
                                    <button @click="closeExpandedRows" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" v-for="(result, index) in filteredResults" :key="'embargos-' + result.doc + '-' + index" v-if="isRowExpanded(result, 'embargos')">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Documento Demandante</th>
                                                <th>Entidad Demandante</th>
                                                <th>Fecha Inicio</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="embargo in result.embargos" :key="embargo.id">
                                                <td>{{ embargo.docdeman || 'No disponible' }}</td>
                                                <td>{{ embargo.entidaddeman || 'No disponible' }}</td>
                                                <td>{{ embargo.fembini || 'No disponible' }}</td>
                                                <td>{{ formatCurrency(embargo.valor || embargo.netoemb) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal de descuentos -->
                    <div class="modal fade" id="modalDescuentos" tabindex="-1" role="dialog" aria-labelledby="modalDescuentosLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDescuentosLabel">Obligaciones vigentes en mora</h5>
                                    <button @click="closeExpandedRows" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" v-for="(result, index) in filteredResults" :key="'descuentos-' + result.doc + '-' + index" v-if="isRowExpanded(result, 'descuentos')">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Mliquid</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="descuento in result.descuentos" :key="descuento.id">
                                                <td>{{ descuento.mliquid || 'No disponible' }}</td>
                                                <td>{{ formatCurrency(descuento.valor) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <div class="modal-footer">
                                    <CustomButton data-dismiss="modal" @click="closeExpandedRows" text="Cerrar"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Paginación -->
                <div class="pagination">
                    <button v-if="page > 1" @click="fetchPaginatedResults(page - 1)" class="btn btn-primary" style="background-color: #2c8c73";>Anterior</button>
                    <button @click="fetchPaginatedResults(page + 1)" :disabled="page * perPage >= total" class="btn btn-primary" style="background-color: #2c8c73;">Siguiente</button>
                </div><br>
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
                                <strong>'cédulas'</strong> y que contiene los números de cédula.
                            </p>
                        </b-col>
                    </b-row>
                </div>
                <b-row class="py-3">
                    <div class="col-md-12">
                        <b-form-group label="Mes (MM):">
                            <b-form-input
                                v-model="mes"
                                placeholder="01"
                                maxlength="2"
                                class="input_style_b form-control2"
                            ></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-12">
                        <b-form-group label="Año (YYYY):">
                            <b-form-input
                                v-model="año"
                                placeholder="2024"
                                maxlength="4"
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
        </div>

        <!-- Mensaje de error -->
        <div v-if="error" class="alert alert-danger mt-3">
            {{ error }}
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import * as XLSX from 'xlsx';
import CustomButton from '../../customComponents/CustomButton.vue';
import Lupa from '../../icons/Lupa.vue';
import UploadFile from '../../icons/UploadFile.vue';
import Trash from '../../icons/Trash.vue';
import jsPDF from 'jspdf'; // Si utilizas exportToPDF
import 'jspdf-autotable'; // Plugin para tablas en jsPDF

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
            perPage: 500,
            total: 0,
            mes: '',
            año: '',
            expandedRows: [] // Lista para rastrear filas expandidas
        };
    },
    computed: {
        filteredResults() {
            if (!this.results || !Array.isArray(this.results)) {
                return [];
            }
            return this.results.filter(result => {
                if (!result || !result.doc) {
                    return false;
                }
                if (this.searchQuery.length < 3) {
                    return true;
                }
                return result.doc.toString().includes(this.searchQuery);
            });
        }
    },
    methods: {
        capitalize(text) {
        if (!text) return '';
        return text.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
    },
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
            if (!this.isValidMonthYear()) {
                alert('Por favor, ingrese un mes válido (MM) y un año válido (YYYY).');
                return;
            }

            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('mes', this.mes);
            formData.append('año', this.año);

            try {
                this.isLoading = true;
                let response = await axios.post('/demografico/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                modal.hide('bv-modal-example');
                this.error = null;
                await this.fetchPaginatedResults(1); // Obtener la primera página de resultados
            } catch (error) {
                this.error = error.response ? error.response.data.error : 'Error subiendo el archivo';
            } finally {
                this.isLoading = false;
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
                this.error = null; // Limpiar errores si la solicitud fue exitosa
            } catch (error) {
                this.error = error.response ? error.response.data.error : 'Error al buscar los resultados paginados';
            } finally {
                this.isLoading = false;
            }
        },
        isValidMonthYear() {
            const mesRegex = /^(0[1-9]|1[0-2])$/;
            const añoRegex = /^[0-9]{4}$/;
            return mesRegex.test(this.mes) && añoRegex.test(this.año);
        },
        loadConsultationData(data) {
            // Asegúrate de que los datos cargados tengan las propiedades necesarias
            this.results = data.filter(item => item && typeof item === 'object').map(item => ({
                ...item,
                showCupones: false,
                showEmbargos: false,
                showDescuentos: false
            }));
        },
        toggleRecentConsultations() {
            if (!this.showRecentConsultations) {
                this.fetchRecentConsultations();
            }
            this.showRecentConsultations = !this.showRecentConsultations;
        },
        toggleDetails(result, type) {
            const key = `${result.doc}-${type}`;
            const index = this.expandedRows.indexOf(key);
            if (index > -1) {
                // Si ya está expandido, lo eliminamos
                this.expandedRows.splice(index, 1);
            } else {
                // Si no está expandido, lo añadimos
                this.expandedRows.push(key);
            }
        },
        isRowExpanded(result, type) {
            const key = `${result.doc}-${type}`;
            return this.expandedRows.includes(key);
        },
        closeExpandedRows(){
            this.expandedRows = [];
        }, 
        exportToPDF() {
            const doc = new jsPDF();
            const columns = ['Cédula', 'Nombre del Cliente', 'Fecha Nacimiento', 'Edad', 'Tipo de Contrato', 'Cargo', 'Situación Laboral', 'Pagaduría', 'Cupo Libre', 'Colpensiones', 'Fiduprevisora', 'Fopep'];
            const rows = this.filteredResults.map(item => [
                item.doc,
                item.nombre_usuario || 'No disponible',
                item.fecha_nacimiento || 'No disponible',
                item.edad || 'No disponible',
                this.capitalizeFirstLetter(item.tipo_contrato) || 'No disponible',
                item.cargo || 'No disponible',
                item.situacion_laboral || 'No disponible',
                item.pagaduria || 'No disponible',
                this.formatCurrency(item.cupo_libre),
                item.colpensiones ? 'Sí' : 'No',
                item.fiducidiaria ? 'Sí' : 'No',
                item.fopep ? 'Sí' : 'No'

            ]);
            doc.autoTable({
                head: [columns],
                body: rows,
                styles: { fontSize: 8 },
                headStyles: { fillColor: [32, 160, 233] }
            });
            doc.save('resultados.pdf');
        },
        exportToExcel() {
            const columns = [
                'Cédula', 'Nombre del Cliente', 'Fecha Nacimiento', 'Edad', 'Tipo de Contrato', 'Cargo',
                'Situación Laboral', 'Pagaduría', 'Cupo Libre', 'Detalle de Embargos', 'Detalle de Cupones',
                'Detalle de Descuentos', 'Colpensiones', 'Fiduprevisora', 'Fopep'
            ];
            
            const rows = this.results.map(item => [
                item.doc || 'No disponible',
                item.nombre_usuario || 'No disponible',
                item.fecha_nacimiento || 'No disponible',
                item.edad || 'No disponible',
                item.tipo_contrato || 'No disponible',
                item.cargo || 'No disponible',
                item.situacion_laboral || 'No disponible',
                item.pagaduria || 'No disponible',
                item.cupo_libre || 'No disponible',
                this.formatEmbargos(item.embargos),
                this.formatCupones(item.cupones),
                this.formatDescuentos(item.descuentos),
                item.colpensiones ? 'Sí' : 'No',
                item.fiducidiaria ? 'Sí' : 'No',
                item.fopep ? 'Sí' : 'No'
            ]);

            const worksheet = XLSX.utils.aoa_to_sheet([columns, ...rows]);

            // Aplicar wrap text a las columnas de detalles
            const wrapTextColumns = ['J', 'K', 'L']; // Columnas para Embargos, Cupones y Descuentos
            wrapTextColumns.forEach(col => {
                for (let row = 2; row <= rows.length + 1; row++) { // +1 por el encabezado
                    const cellRef = col + row;
                    if (worksheet[cellRef]) {
                        if (!worksheet[cellRef].s) worksheet[cellRef].s = {};
                        worksheet[cellRef].s.alignment = { wrapText: true };
                    }
                }
            });

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Datos Demográficos');
            XLSX.writeFile(workbook, 'datos_demograficos.xlsx');
        },

        // Helper para formatear embargos
        formatEmbargos(embargos) {
            if (!embargos || !embargos.length) return 'No hay embargos';
            return embargos
                .map(e => `Documento: ${e.docdeman || 'N/A'}, Entidad: ${e.entidaddeman || 'N/A'}, Valor: ${e.valor || e.netoemb}`)
                .join('\r\n');
        },

        // Helper para formatear cupones
        formatCupones(cupones) {
            if (!cupones || !cupones.length) return 'No hay cupones';
            return cupones
                .map(c => `Concepto: ${c.concept || 'N/A'}, Egresos: ${c.egresos}`)
                .join('\r\n');
        },

        // Helper para formatear descuentos
        formatDescuentos(descuentos) {
            if (!descuentos || !descuentos.length) return 'No hay descuentos';
            return descuentos
                .map(d => `Mliquid: ${d.mliquid || 'N/A'}, Valor: ${d.valor}`)
                .join('\r\n');
        },
        async fetchRecentConsultations() {
            try {
                let response = await axios.get('/demografico/recent-consultations');
                this.recentConsultations = response.data;
            } catch (error) {
                console.error('Error al obtener consultas recientes:', error);
            }
        },
        formatCurrency(value) {
            if (value == null || isNaN(value)) {
                return 'No disponible';
            }
            return new Intl.NumberFormat('es-CO').format(value);
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
th {
    white-space: nowrap; /* Evita el salto de línea */
}
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
</style>
