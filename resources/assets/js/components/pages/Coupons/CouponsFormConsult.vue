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
                            <b-form-input v-model="concept" placeholder="Concepto"></b-form-input>
                        </b-form-group>
                    </div>

                    <div class="col-md-3" v-if="selectedEstado === 'En mora' || selectedEstado === 'Todas'">
                        <b-form-group label="CODIGO">
                            <b-form-input type="text" v-model="mliquid" placeholder="Mensaje de liquidación"></b-form-input>
                        </b-form-group>
                    </div>

                    <div v-if="selectedEstado === 'Embargado' || selectedEstado === 'Todas'" class="col-md-3">
                        <b-form-group label="ENTIDAD DEMANDANTE">
                            <b-form-input
                                v-model="entidadDemandante"
                                placeholder="Entidad demandante"
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
                    <b-table striped id="aldia-table" hover :fields="cupones" :items="paginatedCoupons"></b-table>
                    <b-pagination
                        v-model="currentPageAldia"
                        :per-page="perPageAldia"
                        :total-rows="rowsAldia"
                        aria-controls="aldia-table"
                    ></b-pagination>
                    <div class="row mb-5">
                        <div class="col-12 col-md-3" />
                        <div class="col-12 col-md-3" />
                        <div class="col-12 col-md-6">
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col-6">
                                    <label class="label-consulta mb-0" for="pad">
                                        <input class="form-control" value="Número total de clientes:" disabled > </input>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <p class="panel-value mb-0 text-center">
                                        {{ rowsAldia }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <label class="label-consulta mb-0" for="pad">
                                        <input class="form-control" value="Total cuotas (página):" disabled />
                                    </label>
                                </div>
                                <div class="col-4">
                                    <p class="panel-value mb-0 text-center">
                                        {{ totalCuotasAldia }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <b-table
                        striped
                        id="mora-table"
                        hover
                        :fields="descuentosFields"
                        :items="paginatedDescuentos"
                    ></b-table>
                    <b-pagination
                        v-model="currentPageMora"
                        :per-page="perPageMora"
                        :total-rows="rowsMora"
                        aria-controls="mora-table"
                    ></b-pagination>
                    <div class="row mb-5">
                        <div class="col-12 col-md-3" />
                        <div class="col-12 col-md-3" />
                        <div class="col-12 col-md-6">
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col-6">
                                    <label class="label-consulta mb-0" for="pad">
                                        <input class="form-control" value="Número total de clientes:" disabled />
                                    </label>
                                </div>
                                <div class="col-4">
                                    <p class="panel-value mb-0 text-center">
                                        {{ rowsMora }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <label class="label-consulta mb-0" for="pad">
                                        <input class="form-control" value="Total cuotas (página):" disabled />
                                    </label>
                                </div>
                                <div class="col-4">
                                    <p class="panel-value mb-0 text-center">
                                        {{ totalCuotasMora }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <b-table
                        striped
                        id="embargo-table"
                        hover
                        :fields="embargosFields"
                        :items="paginatedEmbargos"
                    ></b-table>
                    <b-pagination
                        v-model="currentPageEmbargo"
                        :per-page="perPageEmbargo"
                        :total-rows="rowsEmbargo"
                        aria-controls="embargo-table"
                    ></b-pagination>
                    <div class="row mb-5">
                        <div class="col-12 col-md-3" />
                        <div class="col-12 col-md-3" />
                        <div class="col-12 col-md-6">
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col-6">
                                    <label class="label-consulta mb-0" for="pad">
                                        <input class="form-control" value="Número total de clientes:" disabled />
                                    </label>
                                </div>
                                <div class="col-4">
                                    <p class="panel-value mb-0 text-center">
                                        {{ rowsEmbargo }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <label class="label-consulta mb-0" for="pad">
                                        <input class="form-control" value="Total cuotas (página):" disabled />
                                    </label>
                                </div>
                                <div class="col-4">
                                    <p class="panel-value mb-0 text-center">
                                        {{ totalCuotasEmbargo }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            id="todasDiv"
            class="panel mb-3 col-md-12"
            v-if="coupons && coupons.length > 0 && selectedEstado === 'Todas'"
        >
            <div class="panel-heading">
                <b>RESUMEN</b>
            </div>
            <div class="row d-flex align-items-center justify-content-center py-4">
                    <div class="col-4"><label class="label-titulo">Estado</label></div>
                    <div class="col-4"><label class="label-titulo">Total Clientes</label></div>
                    <div class="col-4"><label class="label-titulo">Total Cuotas</label></div>

                    <div class="col-4 pb-2"><label class="label-resumen">Al día</label></div>
                    <div class="col-4 pb-2"><label class="label-resumen">{{ rowsAldia }}</label></div>
                    <div class="col-4 pb-2"><label class="label-resumen pb-2">{{ totalCuotasAldia }}</label></div>

                    <div class="col-4 pb-2"><label class="label-resumen">En mora</label></div>
                    <div class="col-4 pb-2"><label class="label-resumen">{{ rowsMora }}</label></div>
                    <div class="col-4 pb-2"><label class="label-resumen">{{ totalCuotasMora }}</label></div>

                    <div class="col-4 pb-2"><label class="label-resumen">Embargado</label></div>
                    <div class="col-4 pb-2"><label class="label-resumen">{{ rowsEmbargo }}</label></div>
                    <div class="col-4 pb-2"><label class="label-resumen">{{ totalCuotasEmbargo }}</label></div>

                    <div class="col-4"><label class="label-resumen">Total</label></div>
                    <div class="col-4"><label class="label-resumen">{{ totalClientes }}</label></div>
                    <div class="col-4"><label class="label-resumen">{{ totalCuotas }}</label></div>
                </div>
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Todas)</b>
            </div>
            <div class="panel-body">
                <b-accordion>
                    <b-card no-body class="mb-2">
                        <b-card-header header-tag="header" class="p-1" role="tab">
                            <b-button class="button-tablas d-flex" block v-b-toggle.accordion-1>
                                <div class="row" style="width: 100%">
                                    <div class="col-11">Cartera al Día</div>
                                    <div class="col-1 pr-0 d-flex justify-content-end align-items-center">
                                        <svg
                                            width="20px"
                                            height="20px"
                                            viewBox="0 0 24 24"
                                            fill="white"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M19 9L14 14.1599C13.7429 14.4323 13.4329 14.6493 13.089 14.7976C12.7451 14.9459 12.3745 15.0225 12 15.0225C11.6255 15.0225 11.2549 14.9459 10.9109 14.7976C10.567 14.6493 10.2571 14.4323 10 14.1599L5 9"
                                                stroke="white"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </b-button>
                        </b-card-header>

                        <b-collapse id="accordion-1" accordion="my-accordion2" role="tabpanel">
                            <b-card-body>
                                <b-table striped hover :fields="cupones" :items="paginatedCoupons"></b-table>
                                <b-pagination
                                    v-model="currentPageAldia"
                                    :per-page="perPageAldia"
                                    :total-rows="rowsAldia"
                                    aria-controls="aldia-table"
                                ></b-pagination>
                            </b-card-body>
                        </b-collapse>
                    </b-card>
                    <!-- Cartera en Mora -->
                    <b-card no-body class="mb-2">
                        <b-card-header header-tag="header" class="p-1" role="tab">
                            <b-button class="button-tablas d-flex" block v-b-toggle.accordion-2>
                                <div class="row" style="width: 100%">
                                    <div class="col-11">Cartera en Mora</div>
                                    <div class="col-1 pr-0 d-flex justify-content-end align-items-center">
                                        <svg
                                            width="20px"
                                            height="20px"
                                            viewBox="0 0 24 24"
                                            fill="white"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M19 9L14 14.1599C13.7429 14.4323 13.4329 14.6493 13.089 14.7976C12.7451 14.9459 12.3745 15.0225 12 15.0225C11.6255 15.0225 11.2549 14.9459 10.9109 14.7976C10.567 14.6493 10.2571 14.4323 10 14.1599L5 9"
                                                stroke="white"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </b-button>
                        </b-card-header>

                        <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                            <b-card-body>
                                <b-table
                                    striped
                                    hover
                                    :fields="descuentosFields"
                                    :items="paginatedDescuentos"
                                ></b-table>
                                <b-pagination
                                    v-model="currentPageMora"
                                    :per-page="perPageMora"
                                    :total-rows="rowsMora"
                                    aria-controls="mora-table"
                                ></b-pagination>
                            </b-card-body>
                        </b-collapse>
                    </b-card>
                    <!-- Cartera Embargada -->
                    <b-card no-body class="mb-2">
                        <b-card-header header-tag="header" class="p-1" role="tab">
                            <b-button class="button-tablas d-flex" block v-b-toggle.accordion-3>
                                <div class="row" style="width: 100%">
                                    <div class="col-11">Cartera Embargada</div>
                                    <div class="col-1 pr-0 d-flex justify-content-end align-items-center">
                                        <svg
                                            width="20px"
                                            height="20px"
                                            viewBox="0 0 24 24"
                                            fill="white"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M19 9L14 14.1599C13.7429 14.4323 13.4329 14.6493 13.089 14.7976C12.7451 14.9459 12.3745 15.0225 12 15.0225C11.6255 15.0225 11.2549 14.9459 10.9109 14.7976C10.567 14.6493 10.2571 14.4323 10 14.1599L5 9"
                                                stroke="white"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </b-button>
                        </b-card-header>

                        <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                            <b-card-body>
                                <b-table striped hover :fields="embargosFields" :items="paginatedEmbargos"></b-table>
                                <b-pagination
                                    v-model="currentPageEmbargo"
                                    :per-page="perPageEmbargo"
                                    :total-rows="rowsEmbargo"
                                    aria-controls="embargo-table"
                                ></b-pagination>
                            </b-card-body>
                        </b-collapse>
                    </b-card>
                </b-accordion>
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

.label-resumen{
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
.label-titulo{
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
            descuentosFields: [
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
                },{
                    key: 'nomina',
                    label: 'Nómina',
                    sortable: false
                },
                {
                    key: 'valor',
                    label: 'Valor',
                    sortable: false
                }                
            ],
            embargosFields: [
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
            isEntidadDemandanteValid: true,
            currentPageAldia: 1,
            perPageAldia: 20,
            rowsAldia: 0,
            currentPageMora: 1,
            perPageMora: 20,
            rowsMora: 0,
            currentPageEmbargo: 1,
            perPageEmbargo: 20,
            rowsEmbargo: 0,
            totalCuotasAldia: 0,
            totalCuotasMora: 0,
            totalCuotasEmbargo: 0,
            totalClientes: 0,
            totalCuotas: 0
        };
    },
    watch: {
        selectedEstado(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.coupons = [];
                this.searchPerformed = false;
            }
        },
        currentPage(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.getCoupons();
            }
        }
    },
    async mounted() {
        await this.getPagaduriasNames();
    },
    computed: {
    paginatedCoupons() {
        const start = (this.currentPageAldia - 1) * this.perPageAldia;
        const end = start + this.perPageAldia;
        return this.coupons.slice(start, end).map(item => ({
            ...item,
            egresos: this.formatCurrency(item.egresos)
        }));
    },
    paginatedDescuentos() {
        const start = (this.currentPageMora - 1) * this.perPageMora;
        const end = start + this.perPageMora;
        return this.descuentos.slice(start, end).map(item => ({
            ...item,
            valor: this.formatCurrency(item.valor)
        }));
    },
    paginatedEmbargos() {
        const start = (this.currentPageEmbargo - 1) * this.perPageEmbargo;
        const end = start + this.perPageEmbargo;
        return this.embargos.slice(start, end).map(item => ({
            ...item,
            temb: this.formatCurrency(item.temb)
        }));
    }
},

    methods: {
        formatCurrency(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return value; 
    return number.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
},

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
                payload.page = this.currentPage;
                payload.perPage = this.perPage;
                if (this.selectedEstado === 'Todas') {
                    payload.concept = this.concept;
                    payload.mliquid = this.mliquid;
                    payload.entidadDemandante = this.entidadDemandante;

                    let couponsResponse = await axios.post(couponsUrl, payload);
                    let descuentosResponse = await axios.post(descuentosUrl, payload);
                    let embargosResponse = await axios.post(embargosUrl, payload);
                    this.coupons = couponsResponse.data;
                    this.rowsAldia = this.coupons.length;

                    this.descuentos = descuentosResponse.data;
                    this.rowsMora = this.descuentos.length;

                    this.embargos = embargosResponse.data;
                    this.rowsEmbargo = this.embargos.length;

                    const parseToNumber = value => {
                        const parsed = parseFloat(value);
                        return isNaN(parsed) ? 0 : parsed;
                    };

                    this.totalCuotasAldia = this.coupons.reduce(
                        (total, item) => total + parseToNumber(item.egresos),
                        0
                    );
                    this.formatCurrency(this.totalCuotasAldia);
                    this.totalCuotasMora = this.descuentos.reduce(
                        (total, item) => total + parseToNumber(item.valor),
                        0
                    );
                    this.formatCurrency(this.totalCuotasMora);

                    this.totalCuotasEmbargo = this.embargos.reduce(
                        (total, item) => total + parseToNumber(item.temb),
                        0
                    );
                    this.formatCurrency(this.totalCuotasEmbargo);

this.totalCuotas = this.formatCurrency(this.totalCuotasAldia + this.totalCuotasMora + this.totalCuotasEmbargo);

                } else {
                    if (this.selectedEstado === 'Al día') {
                        const parseToNumber = value => {
                            const parsed = parseFloat(value);
                            return isNaN(parsed) ? 0 : parsed;
                        };
                        payload.concept = this.concept;
                        payload.code = this.code;
                        const response = await axios.post(couponsUrl, payload);
                        this.coupons = response.data;
                        this.rowsAldia = this.coupons.length;
                        this.totalCuotasAldia = this.coupons.reduce(
                        (total, item) => total + parseToNumber(item.egresos),
                        0
                    );                        this.totalCuotas = this.totalCuotasAldia;
                    } else if (this.selectedEstado === 'En mora') {
                        const parseToNumber = value => {
                            const parsed = parseFloat(value);
                            return isNaN(parsed) ? 0 : parsed;
                        };
                        payload.mliquid = this.mliquid;
                        couponsUrl = descuentosUrl;
                        const response = await axios.post(descuentosUrl, payload);
                        this.descuentos = response.data;
                        this.rowsMora = this.descuentos.length;
                        this.totalCuotasMora = this.descuentos.reduce(
                        (total, item) => total + parseToNumber(item.valor),
                        0
                    );                        this.totalCuotas = this.totalCuotasMora;

                    } else if (this.selectedEstado === 'Embargado') {
                        const parseToNumber = value => {
                            const parsed = parseFloat(value);
                            return isNaN(parsed) ? 0 : parsed;
                        };
                        payload.entidadDemandante = this.entidadDemandante;
                        couponsUrl = embargosUrl;
                        const response = await axios.post(embargosUrl, payload);
                        this.embargos = response.data;
                        this.rowsEmbargo = this.embargos.length;
                        this.totalCuotasEmbargo = this.embargos.reduce(
                        (total, item) => total + parseToNumber(item.temb),
                        0
                    );                        this.totalCuotas = this.totalCuotasEmbargo;

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
