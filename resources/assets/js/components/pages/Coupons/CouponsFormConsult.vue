<template>
    <div>
        <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="#0CEDB0" />

        <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
                <b>Prospección de Mercado "Diamond"</b>
            </div>
            <div class="panel-body">
                <div class="row d-flex justify-content-start align-items-end">
                    <div class="col-md-3">
                        <b-form-group label="PAGADURÍA">
                            <b-form-select
                                v-model="pagaduria"
                                :options="pagaduriasList"
                                placeholder="Seleccione una pagaduría"
                            ></b-form-select>
                        </b-form-group>
                    </div>
                    <div class="col-md-3">
                        <b-form-group label="ESTADO">
                            <b-form-select v-model="selectedEstado" :options="estadosOptions"> </b-form-select>
                        </b-form-group>
                    </div>

                    <!-- Condicionales para mostrar/ocultar campos según el estado -->

                    <div class="col-md-3" v-if="selectedEstado === 'Al día' || selectedEstado === 'Todas'">
                        <b-form-group label="ENTIDAD (Banco-Financiera-Cooperativa-CFC):">
                            <b-form-input v-model="concept" placeholder="Ingrese el concepto"></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-3" v-if="selectedEstado === 'Al día' || selectedEstado === 'Todas'">
                        <b-form-group label="CÓDIGO">
                            <b-form-input v-model="code" placeholder="Ingrese el código"></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-3" v-if="selectedEstado === 'En mora' || selectedEstado === 'Todas'">
                        <b-form-group label="MENSAJE LIQUIDACIÓN">
                            <b-form-input
                                type="text"
                                v-model="mliquid"
                                placeholder="Ingrese el mensaje de liquidación"
                            ></b-form-input>
                        </b-form-group>
                    </div>

                    <div v-if="selectedEstado === 'Embargado' || selectedEstado === 'Todas'" class="col-md-3">
                        <b-form-group label="ENTIDAD DEMANDANTE">
                            <b-form-input
                                v-model="entidadDemandante"
                                placeholder="Ingrese la entidad demandante"
                                required
                            ></b-form-input>
                        </b-form-group>
                    </div>

                    <!--fin de condicionales-->
                    <div class="col-md-3">
                        <b-form-group label="MES Y AÑO">
                            <div class="d-flex">
                                <b-form-input
                                    type="number"
                                    v-model="month"
                                    placeholder="Mes"
                                    class="mr-2"
                                ></b-form-input>
                                <b-form-input type="number" v-model="year" placeholder="Año"></b-form-input>
                            </div>
                        </b-form-group>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <b-button variant="primary" @click="getCoupons">PROSPECTAR</b-button>
                    </div>
                </div>
                <!-- Mensajes de error -->
                <div
                    v-if="!isPagaduriaValid || !isMonthValid || !isYearValid || !isEntidadDemandanteValid"
                    class="text-danger"
                >
                    <div v-if="!isPagaduriaValid">La pagaduría es obligatoria.</div>
                    <div v-if="!isMonthValid">El mes es obligatorio.</div>
                    <div v-if="!isYearValid">El año es obligatorio.</div>
                    <div v-if="!isEntidadDemandanteValid">La entidad demandante es obligatoria.</div>
                </div>
            </div>
        </div>
        <!-- Tabla para mostrar los resultados de cupones AL DIA -->

        <div class="panel mb-3 col-md-12" v-if="coupons && coupons.length > 0 && selectedEstado === 'Al día'">
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Cartera al Día)</b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <b-table striped hover :fields="cupones" :items="coupons"></b-table>
                </div>
            </div>
        </div>
        <!-- Tabla para mostrar los resultados de EN MORA -->

        <div class="panel mb-3 col-md-12" v-if="descuentos && descuentos.length > 0 && selectedEstado === 'En mora'">
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Cartera en Mora)</b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <b-table striped hover :fields="descuentos" :items="descuentos"></b-table>
                </div>
            </div>
        </div>

        <!-- Tabla para mostrar los resultados de EMBARGOS -->
        <div class="panel mb-3 col-md-12" v-if="embargos && embargos.length > 0 && selectedEstado === 'Embargado'">
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Cartera Embargada)</b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <b-table striped hover :fields="embargos" :items="embargos"></b-table>
                </div>
            </div>
        </div>
        <div class="panel mb-3 col-md-12" v-if="coupons && coupons.length > 0 && selectedEstado === 'Todas'">
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Todas)</b>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="panel-heading">
                        <b>Cartera al Día</b>
                    </div>
                    <b-table striped hover :fields="cupones" :items="coupons"></b-table>
                    <div class="panel-heading mt-5">
                        <b>Cartera en Mora</b>
                    </div>
                    <b-table class="mt-3" striped hover :fields="descuentos" :items="descuentos"></b-table>
                    <div class="panel-heading mt-5">
                        <b>Cartera Embargada</b>
                    </div>
                    <b-table class="mt-3" striped hover :fields="embargos" :items="embargos"></b-table>
                </div>
            </div>
        </div>

        <div v-if="searchPerformed && coupons.length === 0">
            <p>No se encontraron datos para los criterios de búsqueda proporcionados.</p>
        </div>
    </div>
</template>
<style>
.form-group legend {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 900;
}

td,
th {
    text-align: center;
}
</style>
<script>
import axios from 'axios';
import { sassFalse } from 'sass';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    name: 'CouponsFormConsult',
    components: {
        Loading
    },
    data() {
        return {
            cupones: [
                {
                    key: 'doc',
                    label: 'Documento',
                    sortable: false
                },
                {
                    key: 'names',
                    label: 'Nombres Completos',
                    sortable: false
                },
                {
                    key: 'code',
                    label: 'Homónimo',
                    sortable: false
                },
                {
                    key: 'concept',
                    label: 'Concepto',
                    sortable: false
                },
                {
                    key: 'egresos',
                    label: 'Valor Cuota',
                    sortable: false
                }
            ],
            descuentos: [
                {
                    key: 'doc',
                    label: 'Documento',
                    sortable: false
                },
                {
                    key: 'nomp',
                    label: 'Nombre Completo',
                    sortable: false
                },
                {
                    key: 'mliquid',
                    label: 'Mensaje Liquidación',
                    sortable: false
                },
                {
                    key: 'valor',
                    label: 'Valor',
                    sortable: false
                },
                {
                    key: 'nomina',
                    label: 'Nómina',
                    sortable: false
                }
            ],
            embargos: [
                {
                    key: 'doc',
                    label: 'Documento',
                    sortable: false
                },
                {
                    key: 'nomp',
                    label: 'Nombre Proceso',
                    sortable: false
                },
                {
                    key: 'docdeman',
                    label: 'Documento de la Demanda',
                    sortable: false
                },
                {
                    key: 'entidaddeman',
                    label: 'Entidad Demandante',
                    sortable: false
                },
                {
                    key: 'temb',
                    label: 'Total Embargos',
                    sortable: false
                }
            ],
            pagaduria: '',
            pagaduriasList: [],
            concept: '',
            code: '',
            month: '',
            year: '',
            mliquid: '',
            coupons: [],
            embargos: [],
            descuentos: [],
            isPagaduriaValid: true,
            isMonthValid: true,
            isYearValid: true,
            isLoading: false,
            searchPerformed: false,
            selectedEstado: 'Al día',
            estadosOptions: ['Al día', 'En mora', 'Embargado', 'Todas'],
            entidadDemandante: '',
            isEntidadDemandanteValid: true
        };
    },
    watch: {
        selectedEstado(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.coupons = [];
                this.searchPerformed = false;
            }
        }
    },
    async mounted() {
        await this.getPagaduriasNames();
    },

    methods: {
        async getPagaduriasNames() {
            try {
                const response = await axios.get('/pagadurias/names');
                this.pagaduriasList = response.data;
            } catch (error) {
                console.error('Error al obtener las pagadurías:', error);
            }
        },
        async getCoupons() {
            this.isPagaduriaValid = !!this.pagaduria;
            this.isMonthValid = !!this.month && this.month.length === 2;
            this.isYearValid = !!this.year && this.year.length === 4;

            if (
                !this.isPagaduriaValid ||
                !this.isMonthValid ||
                !this.isYearValid ||
                (this.selectedEstado === 'Embargado' && !this.isEntidadDemandanteValid)
            ) {
                return;
            }

            this.isLoading = true;

            try {
                let payload = {
                    pagaduria: this.pagaduria,
                    month: this.month,
                    year: this.year
                };

                let couponsUrl = '/coupons/by-pagaduria';
                let descuentosUrl = '/descuentos/by-pagaduria';
                let embargosUrl = '/embargos/by-pagaduria';

                if (this.selectedEstado === 'Todas') {
                    payload.concept = this.concept;
                    payload.code = this.code;
                    payload.mliquid = this.mliquid;
                    payload.entidadDemandante = this.entidadDemandante;
                    let couponsResponse = await axios.post(couponsUrl, payload);
                    let descuentosResponse = await axios.post(descuentosUrl, payload);
                    let embargosResponse = await axios.post(embargosUrl, payload);
                    this.coupons = couponsResponse.data;
                    this.descuentos = descuentosResponse.data;
                    this.embargos = embargosResponse.data;
                } else {
                    if (this.selectedEstado === 'Al día') {
                        payload.concept = this.concept;
                        payload.code = this.code;
                        const response = await axios.post(couponsUrl, payload);
                        this.coupons = response.data;
                    } else if (this.selectedEstado === 'En mora') {
                        payload.mliquid = this.mliquid;
                        couponsUrl = descuentosUrl;
                        const response = await axios.post(descuentosUrl, payload);
                        this.descuentos = response.data;
                    } else if (this.selectedEstado === 'Embargado') {
                        payload.entidadDemandante = this.entidadDemandante;
                        couponsUrl = embargosUrl;
                        const response = await axios.post(embargosUrl, payload);
                        this.embargos = response.data;
                    }
                    this.searchPerformed = true;
                }
            } catch (error) {
                console.error('Error al obtener los datos:', error);
                console.log(error);
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>
