<template>
    <div class="container-fluid">
        <div v-if="type_consult === 'individual'">
            <div id="consulta-container" class="row">
                <div class="panel mb-3 col-md-12">
                    <div class="panel-heading" style=" box-shadow: 10px 5px 5px #09ac80">
                        <h4 class="mb-0" style="font-weight: bold;">Certificados de Nacimiento - Defunción</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b-button id="show-btn" v-on:click="showModal">Carga Masiva</b-button>
                                <b-modal ref="modal" hide-footer title="Carga Individual">
                                    <b class="panel-label">Nombres:</b>
                                    <input
                                        required
                                        class="form-control text-center"
                                        type="text"
                                        v-model="dataclient.name"
                                    />
                                    <b class="panel-label">Cédula:</b>
                                    <input
                                        required
                                        class="form-control text-center"
                                        type="text"
                                        v-model="dataclient.name"
                                    />
                                    <div class="row">
                                        <div class="col-6 d-flex align-items-center">
                                            <b-button class="mt-3" variant="outline-danger" block @click="hideModal">Cerrar</b-button>
                                        </div>        
                                        <div class="col-6 d-flex align-items-center">
                                            <b-button class="mt-3" variant="outline-warning" block @click="hideModal">Cargar</b-button>
                                        </div>
                                    </div>
                                </b-modal>
                                <b-button id="show-btn" v-on:click="showModal2">Carga indivual</b-button>
                                <b-modal ref="modal2" hide-footer title="Carga Individual">
                                    <b class="panel-label">Nombres:</b>
                                    <input
                                        required
                                        class="form-control text-center"
                                        type="text"
                                        v-model="dataclient.name"
                                    />
                                    <b class="panel-label">Cédula:</b>
                                    <input
                                        required
                                        class="form-control text-center"
                                        type="text"
                                        v-model="dataclient.name"
                                    />
                                    <b class="panel-label">Subir certificado:</b>
                                    <b-form-file
                                        v-model="dataclient.file"
                                        :state="Boolean(dataclient.file)"
                                        placeholder="Oprime o Suelta el archivo aquí"
                                        class="carga-archivo"
                                    ></b-form-file>
                                    <div class="row">
                                        <div class="col-6 d-flex align-items-center">
                                            <b-button class="mt-3" variant="outline-danger" block @click="hideModal2">Cerrar</b-button>
                                        </div>        
                                        <div class="col-6 d-flex align-items-center">
                                            <b-button class="mt-3" variant="outline-warning" block @click="hideModal2">Cargar</b-button>
                                        </div>
                                    </div>
                                </b-modal>
                            </div>
                            <!-- <b-button
                                type="button"
                                variant="black-pearl"
                                class="px-4"
                                @click="getAllPagadurias"
                            >
                                CONSULTAR PAGADURIAS
                            </b-button> -->
                            <div class="col-6">
                                
                            </div>
                            <div id="table1" style="display: none;">
                                HOLA
                            </div>

                            <div id="table2" style="display: none;">
                                ADIÓS
                            </div>
                        </div>
                    </div>
                <div class="panel-heading" style="box-shadow: 10px 5px 5px  #09ac80;">
                    <h5 class="mb-0 text-center" style="font-weight: bold;">RESULTADO</h5>
                </div>
                <!-- <DescapliEmpty /> -->
                <!-- <div><carteraembargo /></div>
                <div><carteraaldia /></div> -->
                <div><carteramora /></div> 
                
                </div>
            </div>
        </div>
    </div>          
</template>
<style>
.table-text {
    font-size: 12px;
}
.carga-archivo:hover{
    cursor: pointer;
}
.tables-space {
    margin-top: 15px !important;
}
</style>
<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css" />
<script>
import printJS from 'print-js';
import carteraaldia from './carteraaldia'
import carteramora from './carteramora'
import carteraembargo from './carteraembargo'
import DescapliEmpty from '../ConsultDataClientDraft/DescapliEmpty.vue';

export default {
    components: {
        carteraaldia,
        carteramora,
        carteraembargo,
        DescapliEmpty
    },
    props: ['user'],
    data() {
        return {
            opciones: ['sem choco', 'sed boyaca', 'sem ibague', 'sed tolima'],
            dataclient: {
                name: '',
                tasa: null,
                file: 0
            },
            plan: 'basico',
            selected: '',
            anio: '',
            mes: '',
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
        },
    },
    methods: {
        showModal() {
        this.$refs['modal'].show()
      },
      hideModal() {
        this.$refs['modal'].hide()
      },
      showModal2() {
        this.$refs['modal2'].show()
      },
      hideModal2() {
        this.$refs['modal2'].hide()
      },
        updateSecondInput() {
      this.isRequired = this.selectedOption === "option2";
    },
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
