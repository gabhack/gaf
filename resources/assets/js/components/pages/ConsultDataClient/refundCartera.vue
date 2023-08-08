<template>
    <div class="container-fluid">
        <div v-if="type_consult === 'individual'">
            <div id="consulta-container" class="row">
                <div class="panel mb-3 col-md-12">
                    <div class="panel-heading">
                        <b>REALIZAR CONSULTA</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">NOMBRES Y APELLIDOS:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="text"
                                    v-model="dataclient.name"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">CEDULA:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="number"
                                    v-model="dataclient.doc"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">NUMERO DE PAGARE:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="number"
                                    v-model="dataclient.doc"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">ENTIDAD O CARTERA:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="number"
                                    v-model="dataclient.doc"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">FECHA INICIO CREDITO:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="number"
                                    v-model="dataclient.doc"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">FECHA FINAL CREDITO:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="number"
                                    v-model="dataclient.doc"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">TASA:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    step="0.01%"
                                    type="number"
                                    v-model="dataclient.tasa"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">DOCUMENTOS LB</b>
                                <b-form-file
                                    v-model="dataclient.file"
                                    :state="Boolean(dataclient.file)"
                                    drop-placeholder="Suelta el archivo aquí..."
                                ></b-form-file>
                            </div>
                            <div class="col-6 mt-4">
                                <button type="button" class="btn btn-primary" v-on:click="getData">CONSULTAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.table-text {
    font-size: 12px;
}

.tables-space {
    margin-top: 15px !important;
}
</style>
<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css" />
<script>
import printJS from 'print-js';

export default {
    props: ['user'],
    data() {
        return {
            dataclient: {
                name: '',
                tasa: null,
                file: 0
            },
            plan: 'basico',
            enableFirstStep: false,
            enableSecondStep: false,
            enableThirdStep: false,
            enableFourStep: false,
            dataPlusFopep: false,
            dataPlusFidu: false,
            dataPlusFode: false,
            file1: false,
            consultaDescapli: [],
            actualDate: new Date().toLocaleString(),
            pagare: [],
            pagareSelected: [],
            nomterSelect: [],
            resultPagare: [],
            filter: '',
            type_consult: 'individual',

            datames: [],
            fechaVinc: [],
            descapli: [],
            descnoap: [],
            datamesfidu: [],
            datamessedvalle: [],
            id_consulta: null
        };
    },
    computed: {
        filteredRows() {
            if (!this.resultPagare.entidad) return false;

            return this.resultPagare.entidad.filter(row => {
                const pagare = row.toString().toLowerCase();
                const searchTerm = this.filter.toLowerCase();

                return pagare.includes(searchTerm);
            });
        }
    },
    methods: {
        getData() {
            this.getDatames();
            this.getFechaVinc();
            this.getDescapli();
            this.getDescnoap();
            this.getDatamesfidu();
            this.getDatamesSedValle();
        },
        getDatames() {
            axios.get(`datames/${this.dataclient.doc}`).then(response => {
                this.datames = response.data;
            });
        },
        getFechaVinc() {
            axios.get(`fechavinc/${this.dataclient.doc}`).then(response => {
                this.fechaVinc = response.data;
            });
        },
        getDescapli() {
            axios.get(`descapli/${this.dataclient.doc}`).then(response => {
                this.descapli = response.data;
            });
        },
        getDescnoap() {
            axios.get(`descnoap/${this.dataclient.doc}`).then(response => {
                console.log(response.data);
                this.descnoap = response.data;
            });
        },
        getDatamesfidu() {
            axios.post('/datamesfidu/consultaUnitaria', { doc: this.dataclient.doc }).then(response => {
                this.datamesfidu = response.data.data;
            });
        },
        getDatamesSedValle() {
            axios.post('/datamessedvalle/consultaUnitaria', { doc: this.dataclient.doc }).then(response => {
                console.log(response.data);
                this.datamessedvalle = response.data.data;
            });
        },
        enableSteps(enable) {
            if (enable === true) {
                this.plan === 'premium';
                this.enableFirstStep = true;
                this.enableSecondStep = true;
                this.enableThirdStep = true;
                this.enableFourStep = true;
                this.sendPagare();
            } else {
            }
        },
        getDataClient() {
            axios
                .post('consultaDescnoap', { data: this.dataclient })
                .then(response => {
                    if (response.data.message === 'El cliente seleccionado tiene inconsistencias.') {
                        this.consultaDescapli = response.data.data;
                    } else {
                        axios
                            .post('consultaUnitaria', { data: this.dataclient })
                            .then(response => {
                                if (response.data.message === 'El cliente seleccionado tiene inconsistencias.') {
                                    toastr.success(response.data.message);
                                    this.consultaDescapli = response.data.data;
                                } else {
                                    this.consultaDescapli = response.data.data;
                                }
                            })
                            .catch(error => {
                                toastr.success(response.data.message);
                            });
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },

        vAplicado(value, data, pagareSelect, nomterSelected) {
            if (value === true) {
                this.pagareSelected.push(data);
            }

            this.dataclient.pagareSelected = this.pagareSelected;

            if (value === true) {
                this.pagare.push(data);
                this.nomterSelect.push(nomterSelected);
                this.dataclient.v_aplicado = this.pagare;
                this.dataclient.nomterSelect = this.nomterSelect;
            } else {
                let pagare = this.pagare.filter(function (item) {
                    return item !== nomterSelected;
                });
                this.dataclient.v_aplicado = pagare;

                let pagareSelected = this.pagareSelected.filter(function (item) {
                    return item.pagare !== pagareSelect;
                });
                this.dataclient.pagareSelected = pagareSelected;

                let nomterSelect = this.nomterSelect.filter(function (item) {
                    return item !== nomterSelected;
                });
                this.dataclient.nomterSelect =
                    nomterSelect.length === 0 ? nomterSelected : this.nomterSelect.push(nomterSelected);
            }
            console.log(this.dataclient);
        },

        sendPagare() {
            axios
                .post('resultadoAprobacion', { data: this.dataclient })
                .then(response => {
                    toastr.success(response.data.message);
                    this.id_consulta = response.data.data.id_consulta;
                    this.resultPagare = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        print() {
            window.print();
        }
    }
};
</script>
