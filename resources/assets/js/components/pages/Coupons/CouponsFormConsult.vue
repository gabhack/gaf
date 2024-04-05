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
                            <b-form-input
                                v-model="pagaduria"
                                list="lista-pagadurias"
                                placeholder="Pagaduría"
                            ></b-form-input>
                            <div class="estilo-datalist">
                                <b-form-datalist
                                    class="listado"
                                    id="lista-pagadurias"
                                    :options="pagaduriasList"
                                ></b-form-datalist>
                            </div>
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
                            <b-form-input
                                type="text"
                                v-model="mliquid"
                                placeholder="Mensaje de liquidación"
                            ></b-form-input>
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
                <b>RESUMEN</b>
            </div>
            <div class="row d-flex align-items-center justify-content-center py-4">
                <div class="col-4"><label class="label-titulo">Estado</label></div>
                <div class="col-4"><label class="label-titulo">Total Clientes</label></div>
                <div class="col-4"><label class="label-titulo">Total Cuotas</label></div>

                <div class="col-4 pb-2"><label class="label-resumen">Al día</label></div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsAldia }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen pb-2">{{ totalCuotasAldia }}</label>
                </div>
            </div>
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
                </div>
            </div>
        </div>

        <!-- Tabla para mostrar los resultados de EN MORA -->

        <div class="panel mb-3 col-md-12" v-if="descuentos && descuentos.length > 0 && selectedEstado === 'En mora'">
            <div class="panel-heading">
                <b>RESUMEN</b>
            </div>
            <div class="row d-flex align-items-center justify-content-center py-4">
                <div class="col-4"><label class="label-titulo">Estado</label></div>
                <div class="col-4"><label class="label-titulo">Total Clientes</label></div>
                <div class="col-4"><label class="label-titulo">Total Cuotas</label></div>

                <div class="col-4 pb-2"><label class="label-resumen">En mora</label></div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsMora }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ totalCuotasMora }}</label>
                </div>
            </div>
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Cartera en Mora)</b>
            </div>
            <div class="panel-body">
                <b-form-input
                    v-model="filtroDescuento"
                    placeholder="Buscar por documento..."
                    class="mb-3"
                ></b-form-input>
                <div class="table-responsive">
                    <b-table striped id="mora-table" hover :fields="descuentosFields" :items="descuentosFiltrados">
                        <template v-slot:cell(actions)="{ item }">
                            <b-button v-b-modal.modal-1 variant="primary" @click="handleButtonClick(item.doc, item.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25" height="25">
                                    <g id="_01_align_center" data-name="01 align center">
                                        <path
                                            d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z"
                                        />
                                        <path
                                            d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"
                                        />
                                    </g>
                                </svg>
                            </b-button>
                        </template>
                    </b-table>
                    <b-modal id="modal-1" centered title="Causales" @hidden="clearCausales">
                        <template v-if="isLoadingModal">
                            <!-- Contenido mostrado durante la carga -->
                            <div class="text-center">
                                <loading
                                    :active.sync="isLoadingModal"
                                    :can-cancel="false"
                                    :is-full-page="false"
                                    color="#0CEDB0"
                                />
                                <p>Cargando...</p>
                            </div>
                        </template>
                        <template v-else>
                            <!-- Contenido del modal mostrado después de cargar -->
                            <div v-if="causalesFinal.some(c => c.motivo === 'Embargo')">
                                <h5>Embargado Por:</h5>
                                <ul>
                                    <li
                                        v-for="causal in causalesFinal.filter(c => c.motivo === 'Embargo')"
                                        :key="causal.id"
                                    >
                                        {{ causal.entidad }} - {{ causal.docentidad }} - ${{ causal.valor }}
                                    </li>
                                </ul>
                            </div>
                            <div v-if="causalesFinal.some(c => c.motivo === 'Mora')">
                                <h5>En Mora Con:</h5>
                                <ul>
                                    <li
                                        v-for="causal in causalesFinal.filter(c => c.motivo === 'Mora')"
                                        :key="causal.id"
                                    >
                                        {{ causal.entidad }} - por el valor de: ${{ causal.valor }}
                                    </li>
                                </ul>
                            </div>
                            <div v-if="situacionLaboral !== 'normal' && descuentos.length > 0">
                                <h5>La situacion del trabajador no es normal</h5>
                            </div>
                            <div v-if="situacionLaboral === 'normal' && descuentos.length === 1">
                                <h5>El trabajador está sobre-endeudado</h5>
                            </div>
                            <div v-if="causalesFinal.length === 0">
                                <h5>No hay causales registradas en los datos actuales.</h5>
                            </div>
                        </template>
                        <template #modal-footer="{ hide }">
                            <b-button size="md" variant="outline-secondary" @click="hide('forget')">Cerrar</b-button>
                        </template>
                    </b-modal>

                    <b-pagination
                        v-model="currentPageMora"
                        :per-page="perPageMora"
                        :total-rows="rowsMora"
                        aria-controls="mora-table"
                    ></b-pagination>
                </div>
            </div>
        </div>

        <!-- Tabla para mostrar los resultados de EMBARGOS -->

        <div class="panel mb-3 col-md-12" v-if="embargos && embargos.length > 0 && selectedEstado === 'Embargado'">
            <div class="panel-heading">
                <b>RESUMEN</b>
            </div>
            <div class="row d-flex align-items-center justify-content-center py-4">
                <div class="col-4"><label class="label-titulo">Estado</label></div>
                <div class="col-4"><label class="label-titulo">Total Clientes</label></div>
                <div class="col-4"><label class="label-titulo">Total Cuotas</label></div>

                <div class="col-4 pb-2"><label class="label-resumen">Embargado</label></div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsEmbargo }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ totalCuotasEmbargo }}</label>
                </div>
            </div>
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
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsAldia }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen pb-2">{{ totalCuotasAldia }}</label>
                </div>

                <div class="col-4 pb-2"><label class="label-resumen">En mora</label></div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsMora }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ totalCuotasMora }}</label>
                </div>

                <div class="col-4 pb-2"><label class="label-resumen">Embargado</label></div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsEmbargo }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ totalCuotasEmbargo }}</label>
                </div>

                <div class="col-4"><label class="label-resumen">Total</label></div>
                <div class="col-4">
                    <label class="label-resumen">{{ totalClientes }}</label>
                </div>
                <div class="col-4">
                    <label class="label-resumen">{{ totalCuotas }}</label>
                </div>
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
                                <b-table striped hover :fields="descuentosFields" :items="descuentosFiltrados">
                                </b-table>

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

datalist {
    position: absolute;
    max-height: 20em;
    border: 0 none;
    overflow-x: hidden;
    overflow-y: auto;
}

datalist option {
    font-size: 0.8em;
    padding: 0.3em 1em;
    background-color: #ccc;
    cursor: pointer;
}

datalist option:hover,
datalist option:focus {
    color: #fff;
    background-color: #036;
    outline: 0 none;
}

td,
th {
    text-align: center;
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
                },
                {
                    key: 'nomina',
                    label: 'Nómina',
                    sortable: false
                },
                {
                    key: 'valor',
                    label: 'Valor',
                    sortable: false
                },
                {
                    key: 'actions',
                    label: 'Acciones'
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
            totalCuotas: 0,
            causales: [],
            causalesFinal: [],
            filtroDescuento: '',
            situacionLaboral: '',
            isLoadingModal: false
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
            this.rowsAldia = this.coupons.length;
            this.updateTotals();
            return this.coupons.slice(start, end).map(item => ({
                ...item,
                egresos: this.formatCurrency(item.egresos)
            }));
        },
        paginatedEmbargos() {
            const start = (this.currentPageEmbargo - 1) * this.perPageEmbargo;
            const end = start + this.perPageEmbargo;
            this.rowsEmbargo = this.embargos.length;
            this.updateTotals();

            return this.embargos.slice(start, end).map(item => ({
                ...item,
                temb: this.formatCurrency(item.temb)
            }));
        },
        descuentosFiltrados() {
            let resultadosFiltrados = this.descuentos;

            if (this.mliquid) {
                resultadosFiltrados = resultadosFiltrados.filter(descuento =>
                    descuento.mliquid.toLowerCase().includes(this.mliquid.toLowerCase())
                );
            }

            if (this.filtroDescuento) {
                resultadosFiltrados = resultadosFiltrados.filter(descuento =>
                    descuento.doc.toLowerCase().includes(this.filtroDescuento.toLowerCase())
                );
            }

            let totalCuotasMora = this.sumarTotalesSinFormato(resultadosFiltrados, 'valor');
            this.totalCuotasMora = this.formatCurrency(totalCuotasMora);

            this.rowsMora = resultadosFiltrados.length;

            if (!this.filtroDescuento && !this.mliquid) {
                const start = (this.currentPageMora - 1) * this.perPageMora;
                const end = start + this.perPageMora;
                resultadosFiltrados = resultadosFiltrados.slice(start, end);
            }

            return resultadosFiltrados;
        },
        totalRows() {
            return this.filtroDescuento ? this.descuentosFiltrados.length : this.descuentos.length;
        }
    },

    methods: {
        clearCausales() {
            this.causalesFinal = [];
        },
        formatCurrency(value) {
            const number = parseFloat(value);
            if (isNaN(number)) return value;

            try {
                return number.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
            } catch (e) {
                return value;
            }
        },
        async getPagaduriasNames() {
            try {
                const response = await axios.get('/pagadurias/namesAmi');
                this.pagaduriasList = response.data;
            } catch (error) {
                console.error('Error al obtener las pagadurías:', error);
            }
        },

        async getCoupons() {
            this.resetData();

            if (!this.validateInputs()) {
                return;
            }

            this.isLoading = true;

            const payload = {
                pagaduria: this.pagaduria,
                month: this.month,
                year: this.year,
                concept: this.concept,
                entidadDemandante: this.entidadDemandante
            };

            // Separar las llamadas y manejar cada una independientemente
            this.fetchData('/coupons/by-pagaduria', payload, this.handleCouponsResponse);
            this.fetchData('/descuentos/by-pagaduria', payload, this.handleDescuentosResponse);
            this.fetchData('/embargos/by-pagaduria', payload, this.handleEmbargosResponse);
        },

        // Método genérico para realizar la solicitud y procesar la respuesta
        async fetchData(url, payload, responseHandler) {
            try {
                const response = await axios.post(url, payload);
                responseHandler.call(this, response.data);
            } catch (error) {
                console.error(`Error al obtener datos de: ${url}`, error);
            } finally {
                this.isLoading = false;
            }
        },

        // Manejadores específicos para cada tipo de respuesta
        handleCouponsResponse(data) {
            this.coupons = data;
            // Lógica específica para procesar cupones, como calcular totales
        },

        handleDescuentosResponse(data) {
            this.descuentos = data;
            // Procesar y llenar causales de descuentos
            this.fillCausalesFromDescuentos();
        },

        handleEmbargosResponse(data) {
            this.embargos = data;
            // Procesar y llenar causales de embargos
            this.fillCausalesFromEmbargos();
        },

        // Ejemplo de cómo podrías llenar causales específicamente para descuentos y embargos
        fillCausalesFromDescuentos() {
            this.descuentos.forEach(descuento => {
                const causal = {
                    id: descuento.id,
                    motivo: 'Mora',
                    entidad: descuento.mliquid,
                    docentidad: '',
                    doc: descuento.doc,
                    valor: descuento.valor
                };
                this.causales.push(causal);
            });
        },

        fillCausalesFromEmbargos() {
            this.embargos.forEach(embargo => {
                const causal = {
                    id: embargo.id,
                    motivo: 'Embargo',
                    entidad: embargo.entidaddeman,
                    docentidad: embargo.docdeman,
                    doc: embargo.doc,
                    valor: embargo.temb
                };
                this.causales.push(causal);
            });
        },
        resetData() {
            this.coupons = [];
            this.descuentos = [];
            this.embargos = [];
        },
        validateInputs() {
            this.isPagaduriaValid = !!this.pagaduria;
            this.isMonthValid = !!this.month && this.month.length === 2;
            this.isYearValid = !!this.year && this.year.length === 4;
            this.isEntidadDemandanteValid = this.selectedEstado !== 'Embargado' || !!this.entidadDemandante;

            return this.isPagaduriaValid && this.isMonthValid && this.isYearValid && this.isEntidadDemandanteValid;
        },

        updateTotals() {
            let totalCuotasAldia = this.sumarTotalesSinFormato(this.coupons, 'egresos');
            let totalCuotasEmbargo = this.sumarTotalesSinFormato(this.embargos, 'temb');

            this.totalCuotasAldia = this.formatCurrency(totalCuotasAldia);
            this.totalCuotasEmbargo = this.formatCurrency(totalCuotasEmbargo);

            if (this.selectedEstado === 'Todas') {
                this.totalCuotas = this.formatCurrency(totalCuotasAldia + totalCuotasMora + totalCuotasEmbargo);
            } else if (this.selectedEstado === 'Al día') {
                this.totalCuotas = this.formatCurrency(totalCuotasAldia);
            } else if (this.selectedEstado === 'En mora') {
            } else if (this.selectedEstado === 'Embargado') {
                this.totalCuotas = this.formatCurrency(totalCuotasEmbargo);
            }

            this.totalClientes = this.coupons.length + this.descuentos.length + this.embargos.length;
        },

        sumarTotalesSinFormato(items, key) {
            const parseToNumber = value => {
                const parsed = parseFloat(value);
                return isNaN(parsed) ? 0 : parsed;
            };
            return items.reduce((total, item) => total + parseToNumber(item[key]), 0);
        },
        getCausalesByDoc(doc, idRow) {
            // Filtrado más complejo para obtener los causales relacionados
            const causalesFiltrados = this.causales.filter(
                causal =>
                    (causal.motivo === 'Embargo' && causal.doc === doc) ||
                    (causal.motivo === 'Mora' && causal.doc === doc && causal.id != idRow)
            );
            console.log(causalesFiltrados);
            return causalesFiltrados;
        },
        async handleButtonClick(doc, idRow) {
            this.isLoadingModal = true;

            const causalesRelacionados = this.getCausalesByDoc(doc, idRow);
            causalesRelacionados.forEach(causalEmbargo => {
                const causalesDeEmbargo = {
                    entidad: causalEmbargo.entidad,
                    docentidad: causalEmbargo.docentidad || '',
                    valor: causalEmbargo.valor,
                    motivo: causalEmbargo.motivo
                };
                this.causalesFinal.push(causalesDeEmbargo);
            });

            try {
                const response = await axios.get(`/situacion-laboral/${doc}`);
                if (response.data && response.data.situacion_laboral) {
                    this.situacionLaboral = response.data.situacion_laboral.trim().toLowerCase();
                } else {
                    this.situacionLaboral = 'información no disponible';
                }
            } catch (error) {
                console.error('Error al obtener la situación laboral:', error);
                this.situacionLaboral = 'error al obtener la información';
            } finally {
                this.isLoadingModal = false;
            }
        }
    }
};
</script>
