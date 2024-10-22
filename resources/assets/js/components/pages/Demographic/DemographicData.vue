<template>
    <div>
        <!-- Overlay de carga -->
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner"></div>
        </div>

        <!-- Panel de carga de datos demográficos -->
        <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Datos Demográficos</b>
                <button @click="toggleRecentConsultations" class="btn btn-info float-right">
                    {{ showRecentConsultations ? 'Ocultar Consultas Recientes' : 'Ver Consultas Recientes' }}
                </button>
            </div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <p>
                        Por favor, asegúrese de que el archivo Excel tiene una columna con el encabezado
                        <strong>'cedulas'</strong> y que contiene los números de cédula.
                    </p>
                </div>
                <div class="form-group">
                    <label for="mes">Mes (MM):</label>
                    <input
                        type="text"
                        id="mes"
                        v-model="mes"
                        maxlength="2"
                        placeholder="Mes en dos dígitos"
                        class="form-control mb-3"
                    />
                </div>
                <div class="form-group">
                    <label for="año">Año (YYYY):</label>
                    <input
                        type="text"
                        id="año"
                        v-model="año"
                        maxlength="4"
                        placeholder="Año en cuatro dígitos"
                        class="form-control mb-3"
                    />
                </div>
                <div class="form-group">
                    <input type="file" @change="handleFileUpload" class="form-control mb-3" />
                    <button @click="uploadFile" class="btn btn-primary">Subir</button>
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

        <!-- Panel de Resultados -->
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
                                <th>Cédula</th>
                                <th>Nombre del Cliente</th>
                                <th>Fecha Nacimiento</th>
                                <th>Edad</th>
                                <th>Tipo de Contrato</th>
                                <th>Pagaduría</th>
                                <th>Cupo Libre</th>
                                <th>Embargo</th>
                                <th>Detalle Embargo</th>
                                <th>Cupones</th>
                                <th>Descuentos</th>
                                <th>Colpensiones</th>
                                <th>Fiducidiaria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Fila Principal -->
                            <tr v-for="(result, index) in filteredResults" :key="result.doc + '-' + index">
                                <td>{{ result.doc }}</td>
                                <td>{{ result.nombre_usuario || 'No disponible' }}</td>
                                <td>{{ result.fecha_nacimiento || 'No disponible' }}</td>
                                <td>{{ result.edad || 'No disponible' }}</td>
                                <td>{{ result.tipo_contrato || 'No disponible' }}</td>
                                <td>{{ result.pagaduria || 'No disponible' }}</td>
                                <td>{{ formatCurrency(result.cupo_libre) }}</td>
                                <td>{{ result.embargos && result.embargos.length > 0 ? 'Sí' : 'No' }}</td>
                                <td>
                                    <button
                                        v-if="result.embargos && result.embargos.length"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'embargos')"
                                    >
                                        {{ isRowExpanded(result, 'embargos') ? 'Ocultar Detalle' : 'Ver Detalle' }}
                                    </button>
                                    <span v-else>No hay embargos</span>
                                </td>
                                <td>
                                    <button
                                        v-if="result.cupones && result.cupones.length"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'cupones')"
                                    >
                                        {{ isRowExpanded(result, 'cupones') ? 'Ocultar Cupones' : 'Ver Cupones' }}
                                    </button>
                                    <span v-else>No hay cupones</span>
                                </td>
                                <td>
                                    <button
                                        v-if="result.descuentos && result.descuentos.length"
                                        class="btn btn-link"
                                        @click="toggleDetails(result, 'descuentos')"
                                    >
                                        {{ isRowExpanded(result, 'descuentos') ? 'Ocultar Descuentos' : 'Ver Descuentos' }}
                                    </button>
                                    <span v-else>No hay descuentos</span>
                                </td>
                                <td>{{ result.colpensiones ? 'Sí' : 'No' }}</td>
                                <td>{{ result.fiducidiaria ? 'Sí' : 'No' }}</td>
                            </tr>
                            <!-- Filas de Detalles: Embargos -->
                            <!-- Fila para mostrar mensaje si no hay resultados -->
                            <tr v-if="filteredResults.length === 0">
                                <td colspan="13">No hay resultados</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4" v-for="(result, index) in filteredResults" :key="'embargos-' + result.doc + '-' + index" v-if="isRowExpanded(result, 'embargos')">
                        <h5>Detalle de Embargos</h5>
                        <table class="table table-lg">
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
                    <!-- Filas de Detalles: Cupones -->
                    <div class="mt-4" v-for="(result, index) in filteredResults" :key="'cupones-' + result.doc + '-' + index" v-if="isRowExpanded(result, 'cupones')">
                        <h5>Cupones</h5>
                        <table class="table table-lg">
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
                    <!-- Filas de Detalles: Descuentos -->
                    <div class="mt-4" v-for="(result, index) in filteredResults" :key="'descuentos-' + result.doc + '-' + index" v-if="isRowExpanded(result, 'descuentos')">
                        <h5>Obligaciones vigentes en mora</h5>
                        <table class="table table-lg">
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
                    </div>
                </div>
                <!-- Paginación -->
                <div class="pagination">
                    <button @click="fetchPaginatedResults(page - 1)" :disabled="page === 1" class="btn btn-primary">Anterior</button>
                    <button @click="fetchPaginatedResults(page + 1)" :disabled="page * perPage >= total" class="btn btn-primary">Siguiente</button>
                </div>
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

export default {
    name: 'DemographicData',
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
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async uploadFile() {
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
        exportToPDF() {
            // Implementación de exportación a PDF si es necesario
        },
        exportToExcel() {
            // Implementación de exportación a Excel si es necesario
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
            return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(value);
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

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination .btn {
    margin: 0 5px;
}
</style>
