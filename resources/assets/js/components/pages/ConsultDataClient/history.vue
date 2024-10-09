<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <template v-if="id_consult === null">
                    <div class="mb-4 mt-5">
                        <h2 class="title"><strong>Listado de Consultas</strong></h2>
                    </div>
                    <div class="d-flex mt-3 mb-3" justified style="color:#ffff; border: 1px solid #b9bdc3; border-radius: 10px">
                        <div class="form-group col-md-3">
                            <label for="" style="color: black;">Desde</label>
                            <b-form-input
                                id="input-anio"
                                placeholder="Desde"
                                v-model.number="queryParams.empresaOUsuario"
                                type="date"
                                class="small-input"
                                style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"
                            />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="" style="color: black;">Hasta</label>
                            <b-form-input
                                id="input-mes"
                                placeholder="Hasta"
                                v-model.number="queryParams.empresaOUsuario"
                                type="date"
                                class="small-input"
                                style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px"
                            />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="" style="color: black;">Documento</label>
                            <input style="border: 1px solid #b9bdc3; background-color:white; border-radius: 10px" class="form-control" placeholder="Documento" v-model="filter" />
                        </div>
                        <b-button type="submit" class="align-self-end mb-3 ml-5" variant="success" id="filtrarButton" style="border: 1px solid #b9bdc3; border-radius: 10px">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                            Filtrar
                        </b-button>
                        
                    </div>
                    <div class="table-responsive">
                        <b-table head-variant="dark" style="border: 1px solid #b9bdc3; border-radius: 10px" responsive bordered striped hover :fields="fields" :items="HistoryConsult.data" :busy="isBusy">
                            <template #table-busy>
                                <div class="text-center text-black-pearl my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Cargando...</strong>
                                </div>
                            </template>
                            <template #cell(actions)="data">
                                <button
                                    type="button"
                                    class="btn btn-primary mb-2"
                                    data-toggle="modal"
                                    data-target="#exampleModal"
                                    @click="getData(data)"
                                >
                                    Observar
                                </button>
                                <b-button variant="black-pearl" href="/solicitud"> Proceso HEGO </b-button>
                            </template>
                        </b-table>
                    </div>
                    <b-pagination
                        v-if="HistoryConsult"
                        v-model="currentPage"
                        :per-page="perPage"
                        :total-rows="totalRows"
                    >
                    </b-pagination>
                    <!-- Modal -->
                    <div
                        class="modal fade"
                        id="exampleModal"
                        tabindex="-1"
                        role="dialog"
                        aria-labelledby="exampleModalLabel"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detalle de Historial</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input disabled class="form-control" :value="detailHistory.nombre" />
                                    </div>

                                    <div class="form-group">
                                        <label>Cedula</label>
                                        <input disabled class="form-control" :value="detailHistory.ced" />
                                    </div>

                                    <div class="form-group">
                                        <label>Aprobado</label>
                                        <input disabled class="form-control" :value="detailHistory.aprobado" />
                                    </div>

                                    <div class="form-group">
                                        <label>Cuota Compra</label>
                                        <input disabled class="form-control" :value="detailHistory.ccompra" />
                                    </div>

                                    <div class="form-group">
                                        <label>Cantidad Libre Inversión</label>
                                        <input disabled class="form-control" :value="detailHistory.clibinv" />
                                    </div>

                                    <div class="form-group">
                                        <label>Cantidad Maxima Incorporación</label>
                                        <input disabled class="form-control" :value="detailHistory.cmaxincorp" />
                                    </div>

                                    <div class="form-group">
                                        <label>Consecutivo</label>
                                        <input
                                            disabled
                                            class="form-control"
                                            :value="detailHistory.conc ? detailHistory.conc : detailHistory.id"
                                        />
                                    </div>

                                    <div class="form-group">
                                        <label>Cuota Credito</label>
                                        <input disabled class="form-control" :value="detailHistory.cuotacredito" />
                                    </div>

                                    <div class="form-group">
                                        <label>Entidad</label>
                                        <input disabled class="form-control" :value="detailHistory.entidad" />
                                    </div>

                                    <div class="form-group">
                                        <label>Estado</label>
                                        <input disabled class="form-control" :value="detailHistory.estado" />
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha Consulta AMI</label>
                                        <input disabled class="form-control" :value="detailHistory.fconsultaami" />
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha Respuesta</label>
                                        <input disabled class="form-control" :value="detailHistory.frespuesta" />
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha Vinculación</label>
                                        <input disabled class="form-control" :value="detailHistory.fvinculacion" />
                                    </div>

                                    <div class="form-group">
                                        <label>Pagare</label>
                                        <input disabled class="form-control" :value="detailHistory.pagare" />
                                    </div>

                                    <div class="form-group">
                                        <label>Plazo</label>
                                        <input disabled class="form-control" :value="detailHistory.plazo" />
                                    </div>

                                    <div class="form-group">
                                        <label>Porcentaje Incorporación</label>
                                        <input disabled class="form-control" :value="detailHistory.porcincorp" />
                                    </div>

                                    <div class="form-group">
                                        <label>Total Credito</label>
                                        <input disabled class="form-control" :value="detailHistory.tcredito" />
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo Consulta</label>
                                        <input disabled class="form-control" :value="detailHistory.tipo_consulta" />
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo Vinculación</label>
                                        <input disabled class="form-control" :value="detailHistory.tvinculacion" />
                                    </div>

                                    <div class="form-group">
                                        <label>Valor Credito</label>
                                        <input disabled class="form-control" :value="detailHistory.vcredito" />
                                    </div>

                                    <div class="form-group">
                                        <label>vdesembolso</label>
                                        <input disabled class="form-control" :value="detailHistory.vdesembolso" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <button class="btn btn-primary mb-4" v-on:click="back">Volver</button>
                    <detail-history-component-draft :id="id_consult" :user="user" :pagaduriaType="pagaduriaType" />
                </template>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
    props: ['user'],
    data() {
        return {
            HistoryConsult: null,
            detailHistory: {},
            filter: '',
            id_consult: null,
            pagaduriaType: '',
            fields: [
                {
                    key: 'created_at',
                    label: 'Fecha y Hora',
                    sortable: true
                },
                {
                    key: 'tipo_consulta',
                    label: 'Tipo de Consulta',
                    sortable: true
                },
                {
                    key: 'pagaduria',
                    label: 'Pagaduria',
                    sortable: true
                },
                {
                    key: 'ced',
                    label: 'Cedula',
                    sortable: true
                },
                {
                    key: 'nombre',
                    label: 'Nombre Completo',
                    sortable: true
                },
            ],
            pagaduriasOptions: [
                { text: 'Pagadurias', value: null },
                { text: 'FOPEP', value: 'FOPEP', key: 'datames' },
                { text: 'FIDUPREVISORA', value: 'FIDUPREVISORA', key: 'datamesfidu' },
                { text: 'SEM CALI', value: 'SEMCALI', key: 'datamessemcali' },
                { text: 'SED VALLE', value: 'SEDVALLE', key: 'datamesedvalle' },
                { text: 'SED CAUCA', value: 'SEDCAUCA', key: 'datamesSedCauca' },
                { text: 'SED CHOCO', value: 'SEDCHOCO', key: 'datamesSedChoco' },
                { text: 'SEM POPAYAN', value: 'SEMPOPAYAN', key: 'datamesSemPopayan' },
                { text: 'SEM QUIBDO', value: 'SEMQUIBDO', key: 'datamesSemQuibdo' },
                { text: 'SED MAGDALENA', value: 'SEDMAGDALENA', key: 'datamesSedMagdalena' },
                { text: 'SED BOLIVAR', value: 'SEDBOLIVAR', key: 'datamesSedBolivar' },
                { text: 'SEM BARRANQUILLA', value: 'SEMBARRANQUILLA', key: 'datamesSemBarranquilla' },
                { text: 'SED ATLANTICO', value: 'SEDATLANTICO', key: 'datamessedatlantico' },
                { text: 'SED NARIÑO', value: 'SEDNARINO', key: 'datamesSedNarino' }
            ],
            tipoConsultaOptions: [{ text: 'Consulta', value: null }, 'Silver', 'Gold', 'Diamond'],

            isBusy: false,

            // pagination
            perPage: 15,
            totalRows: 0,
            currentPage: 1,

            queryParams: {
                pagadurias: null,
                tipoConsulta: null,
                empresaOUsuario: null,
                resultado: null
            }
        };
    },
    mounted() {
        this.getHistoryConsults();
    },
    watch: {
        currentPage() {
            this.getHistoryConsults();
        }
    },
    computed: {
        filteredRows() {
            if (!this.HistoryConsult) return false;

            return this.HistoryConsult.filter(row => {
                const name = row.nombre ? row.nombre.toString().toLowerCase() : '';
                const searchTerm = this.filter.toLowerCase();
                let data = name.includes(searchTerm);
                if (data === true) {
                    return name.includes(searchTerm);
                } else {
                    const ced = row.ced.toString().toLowerCase();
                    const searchTerm = this.filter.toLowerCase();

                    let data1 = ced.includes(searchTerm);
                    if (data1 === true) {
                        return ced.includes(searchTerm);
                    } else {
                        const pag = row.pagaduria.toString().toLowerCase();
                        const searchTerm = this.filter.toLowerCase();
                        let data2 = pag.includes(searchTerm);
                        if (data2 === true) {
                            return pag.includes(searchTerm);
                        } else {
                            const date = row.created_at.toString().toLowerCase();
                            const searchTerm = this.filter.toLowerCase();
                            let data3 = date.includes(searchTerm);
                            if (data3 === true) {
                                return date.includes(searchTerm);
                            } else {
                                const id = row.id.toString().toLowerCase();
                                const searchTerm = this.filter.toLowerCase();
                                let data4 = id.includes(searchTerm);
                                if (data4 === true) {
                                    return id.includes(searchTerm);
                                } else {
                                    const type_consulta = row.tipo_consulta.toString().toLowerCase();
                                    const searchTerm = this.filter.toLowerCase();
                                    let data5 = type_consulta.includes(searchTerm);
                                    return type_consulta.includes(searchTerm);
                                }
                            }
                        }
                    }
                }
            });
        }
    },
    methods: {
        getHistoryConsults() {
            this.isBusy = true;
            const url = `getHistoryConsults?page=${this.currentPage}`;

            axios
                .get(url)
                .then(response => {
                    this.HistoryConsult = response.data.data;
                    this.perPage = response.data.data.per_page;
                    this.totalRows = response.data.data.total;
                })
                .finally(() => {
                    this.isBusy = false;
                });
        },
        getData(data) {
            this.pagaduriaType = data.pagaduria;
            this.detailHistory = data;
            this.id_consult = data.id;
        },
        back() {
            this.id_consult = null;
        },
        exportToPDF() {
            const doc = new jsPDF('landscape');
            const columns = this.fields.filter(field => field.key !== 'actions').map(field => field.label);
            const rows = this.HistoryConsult.data.map(item => this.fields.filter(field => field.key !== 'actions').map(field => item[field.key]));
            doc.autoTable({
                head: [columns],
                body: rows,
                theme: 'striped',
                styles: {
                    fontSize: 8
                },
                headStyles: {
                    fillColor: [22, 160, 133]
                }
            });
            doc.save('historial_consultas.pdf');
        }
    }
};
</script>
