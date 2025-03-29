<template>
    <div class="panel container-fluid">
        <div class="row">
            <div class="col-12">
                <template v-if="id_consult === null">
                    <div class="mb-4 mt-5">
                        <h2 class="heading-title">Listado de Consultas</h2>
                    </div>
                    <div class="d-flex mt-3 mb-3" style="color: #000; border: 1px solid #b9bdc3; border-radius: 10px">
                        <div class="form-group col-md-3">
                            <label style="color: black">Desde</label>
                            <b-form-input v-model="queryParams.desde" type="date" class="small-input form-control2" />
                        </div>
                        <div class="form-group col-md-3">
                            <label style="color: black">Hasta</label>
                            <b-form-input v-model="queryParams.hasta" type="date" class="small-input form-control2" />
                        </div>
                        <div class="form-group col-md-3">
                            <label style="color: black">Documento</label>
                            <input class="form-control2" v-model="filter" placeholder="Documento" />
                        </div>
                        <div class="form-group col-md-3 mt-4">
                            <b-button variant="success" @click="getHistoryConsults">
                                <i class="fa fa-filter"></i> Filtrar
                            </b-button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <b-table
                            head-variant="dark"
                            style="border: 1px solid #b9bdc3; border-radius: 10px"
                            responsive
                            bordered
                            striped
                            hover
                            :fields="fields"
                            :items="HistoryConsult.data"
                            :busy="isBusy"
                        >
                            <template #table-busy>
                                <div class="text-center my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Cargando...</strong>
                                </div>
                            </template>
                            <template #cell(actions)="data">
                                <b-button variant="primary" class="mb-2" @click="getData(data.item)">
                                    Observar
                                </b-button>
                                <b-button variant="secondary" href="/solicitud">Proceso HEGO</b-button>
                            </template>
                        </b-table>
                    </div>
                    <b-pagination
                        v-if="HistoryConsult.total > 0"
                        v-model="currentPage"
                        :per-page="perPage"
                        :total-rows="totalRows"
                        @change="getHistoryConsults"
                    />
                </template>
                <template v-else>
                    <button class="btn btn-primary mb-4" @click="back">Volver</button>
                    <detail-history-component-draft :id="id_consult" :user="user" :pagaduriaType="pagaduriaType" />
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['user'],
    data() {
        return {
            HistoryConsult: { data: [], per_page: 15, total: 0 },
            detailHistory: {},
            filter: '',
            id_consult: null,
            pagaduriaType: '',
            fields: [
                { key: 'created_at', label: 'Fecha y Hora', sortable: true },
                { key: 'pagaduria', label: 'Pagaduria', sortable: true },
                { key: 'ced', label: 'Cedula', sortable: true },
                { key: 'estado', label: 'Estado', sortable: true },
                { key: 'causal', label: 'Causal', sortable: true },
                { key: 'observacion', label: 'Observación', sortable: true },
                { key: 'nombre', label: 'Nombre Completo', sortable: true },
                { key: 'score', label: 'Score', sortable: true },
                {
                    key: 'cuotacredito',
                    label: 'Cuota',
                    sortable: true,
                    formatter: value =>
                        new Intl.NumberFormat('es-CO', {
                            style: 'currency',
                            currency: 'COP',
                            minimumFractionDigits: 0
                        }).format(value)
                },
                {
                    key: 'monto',
                    label: 'Monto',
                    sortable: true,
                    formatter: value =>
                        new Intl.NumberFormat('es-CO', {
                            style: 'currency',
                            currency: 'COP',
                            minimumFractionDigits: 0
                        }).format(value)
                },
                { key: 'plazo', label: 'Plazo', sortable: true },
                { key: 'actions', label: 'Acciones' }
            ],
            isBusy: false,
            perPage: 15,
            totalRows: 0,
            currentPage: 1,
            queryParams: { desde: null, hasta: null }
        };
    },
    mounted() {
        this.getHistoryConsults();
    },
    methods: {
        getHistoryConsults() {
            this.isBusy = true;
            axios
                .get(`/getHistoryConsults?page=${this.currentPage}`)
                .then(response => {
                    const dataWrapper = response.data.data || {};
                    this.HistoryConsult.data = dataWrapper.data || [];
                    this.perPage = dataWrapper.per_page || 15;
                    this.totalRows = dataWrapper.total || 0;
                })
                .catch(() => (this.HistoryConsult = { data: [], per_page: 15, total: 0 }))
                .finally(() => (this.isBusy = false));
        },
        getData(data) {
            this.pagaduriaType = data.pagaduria;
            this.detailHistory = data;
            this.id_consult = data.id;
        },
        back() {
            this.id_consult = null;
        }
    }
};
</script>
