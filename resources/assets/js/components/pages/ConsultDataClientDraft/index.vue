<template>
    <div class="container-fluid" style="background-color: #f9fafc">
        <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="true" color="#0CEDB0" />

        <b-toast id="toast-incapacidad-month" title="Alerta del Sistema" solid auto-hide-delay="10000" variant="info">
            Cliente con incapacidad mayor a 2 meses.
        </b-toast>

        <div v-if="type_consult === 'individual'">
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-end" v-if="datamesFopep">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3" />
                        <div>
                            <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">
                                {{ datamesFopep.nomp }}
                            </h2>
                        </div>
                    </div>
                    <!-- BOTON DOWNLOAD ORIGINAL -->
                    <!-- <button type="button" v-on:click="print" class="btn btn-black-pearl ml-auto px-3">
                        <span class="mr-1">Descargar PDF</span>
                        <DownloadIcon fill="#fff" />
                    </button> -->
                </div>
            </div>

            <div id="consulta-container" class="row">
                <FormConsult :user="user" @emitInfo="emitInfo" @downloadPdf="print" />

                <!--============================
                DATAMES FOPEP -
                ==============================-->
                <div class="info-container col-12">
                    <DatamesComponent
                        v-if="pagaduriaType == 'FOPEP' && datamesFopep"
                        :user="user"
                        :datamesFopep="datamesFopep"
                    />

                    <!--============================
                    FIDUPREVISORA datamesFidu
                ==============================-->
                    <DatamesFidu
                        v-if="pagaduriaType == 'FIDUPREVISORA' && datamesFidu"
                        :user="user"
                        :datamesFidu="datamesFidu"
                    />

                    <!--============================
                    DATAMESSEDUC SED VALLE
                ==============================-->
                    <!-- <DatamesSedValle
                    v-if="pagaduriaType == 'SEDVALLE' && datamessedvalle"
                    :user="user"
                    :datamessedvalle="datamessedvalle"

                /> -->

                    <!--================================
                SEMCALI datamessemcali
                ===================================-->
                    <!-- <DatamesSemCali
                    v-if="pagaduriaType == 'SEMCALI' && datamessemcali"
                    :user="user"
                    :datamessemcali="datamessemcali"
                /> -->

                    <!--================================
                DATAMES SECRETARIAS
                ===================================-->
                    <!--<DatamesData2
                    v-if="
                        pagaduriaType == 'SEMSAHAGUN'" />-->

                    <DatamesData
                        class="col-12"
                        v-if="
                            pagaduriaType == 'SEDCHOCO' ||
                            pagaduriaType == 'SEDVALLE' ||
                            pagaduriaType == 'SEDCAUCA' ||
                            pagaduriaType == 'SEMSAHAGUN' ||
                            pagaduriaType == 'SEMSANTAMARTA' ||
                            pagaduriaType == 'SEMCALI' ||
                            pagaduriaType == 'SEMQUIBDO' ||
                            pagaduriaType == 'SEDMAGDALENA' ||
                            pagaduriaType == 'SEMPOPAYAN' ||
                            pagaduriaType == 'SEMMONTERIA' ||
                            pagaduriaType == 'SEDCORDOBA' ||
                            pagaduriaType == 'SEDCALDAS' ||
                            pagaduriaType == 'SEDBOYACA' ||
                            pagaduriaType == 'SEDBOLIVAR' ||
                            pagaduriaType == 'SEMBARRANQUILLA' ||
                            pagaduriaType == 'SEDATLANTICO' ||
                            pagaduriaType == 'SEDHUILA' ||
                            pagaduriaType == 'SEDRISARALDA' ||
                            pagaduriaType == 'SEDMETA' ||
                            pagaduriaType == 'SEDCUNDINAMARCA' ||
                            pagaduriaType == 'SEMMOSQUERA' ||
                            pagaduriaType == 'SEMMAGANGUE' ||
                            pagaduriaType == 'SEMBUGA' ||
                            pagaduriaType == 'SEMNEIVA' ||
                            pagaduriaType == 'SEMFUNZA' ||
                            pagaduriaType == 'SEMZIPAQUIRA' ||
                            pagaduriaType == 'SEMYUMBO' ||
                            pagaduriaType == 'SEMYOPAL' ||
                            pagaduriaType == 'SEMIPIALES' ||
                            pagaduriaType == 'SEMPIEDECUESTA' ||
                            pagaduriaType == 'SEMVALLEDUPAR' ||
                            pagaduriaType == 'SEMURIBIA' ||
                            pagaduriaType == 'SEMTURBO' ||
                            pagaduriaType == 'SEMTUNJA' ||
                            pagaduriaType == 'SEMBUCARAMANGA' ||
                            pagaduriaType == 'SEMMANIZALES' ||
                            pagaduriaType == 'SEMMAICAO' ||
                            pagaduriaType == 'SEMMALAMBO' ||
                            pagaduriaType == 'SEMPASTO' ||
                            pagaduriaType == 'SEMBUENAVENTURA' ||
                            pagaduriaType == 'SEMPALMIRA' ||
                            pagaduriaType == 'SEMJAMUNDI' ||
                            pagaduriaType == 'SEMCARTAGO' ||
                            pagaduriaType == 'SEMDUITAMA' ||
                            pagaduriaType == 'SEMGIRON' ||
                            pagaduriaType == 'SEMGIRARDOT' ||
                            pagaduriaType == 'SEMCHIA' ||
                            pagaduriaType == 'SEMBELLO' ||
                            pagaduriaType == 'SEMCIENAGA' ||
                            pagaduriaType == 'SEMCUCUTA' ||
                            pagaduriaType == 'SEMMEDELLIN' ||
                            pagaduriaType == 'SEMDOSQUEBRADAS' ||
                            pagaduriaType == 'SEMCARTAGENA' ||
                            pagaduriaType == 'SEMFUSAGAZUGA' ||
                            pagaduriaType == 'SEMENVIGADO' ||
                            pagaduriaType == 'SEMFACATATIVA' ||
                            pagaduriaType == 'SEMARMENIA' ||
                            pagaduriaType == 'SEMVILLAVICENCIO' ||
                            pagaduriaType == 'SEMFLORENCIA' ||
                            pagaduriaType == 'SEMFLORIDABLANCA' ||
                            pagaduriaType == 'SEDNORTEDESANTANDER' ||
                            pagaduriaType == 'SEDSANTANDER' ||
                            pagaduriaType == 'SEMGUAINIA' ||
                            pagaduriaType == 'SEMIBAGUE' ||
                            pagaduriaType == 'SEMLORICA' ||
                            pagaduriaType == 'SEDCASANARE' ||
                            pagaduriaType == 'SEMPEREIRA' ||
                            pagaduriaType == 'SEMITAGUI' ||
                            pagaduriaType == 'SEMAPARTADO' ||
                            pagaduriaType == 'SEMBARRANCABERMEJA' ||
                            pagaduriaType == 'SEMPITALITO' ||
                            pagaduriaType == 'SEMRIOHACHA' ||
                            pagaduriaType == 'SEMRIONEGRO' ||
                            pagaduriaType == 'SEMTULUA' ||
                            pagaduriaType == 'SEMTUMACO' ||
                            pagaduriaType == 'SEMSABANETA' ||
                            pagaduriaType == 'SEMSAN' ||
                            pagaduriaType == 'SEMSOACHA' ||
                            pagaduriaType == 'SEMSOGAMOSO' ||
                            pagaduriaType == 'SEMSOLEDAD' ||
                            pagaduriaType == 'SEMESTRELLA' ||
                            pagaduriaType == 'SEDCAQUETA' ||
                            pagaduriaType == 'SEDANTIOQUIA' ||
                            pagaduriaType == 'SEDARAUCA' ||
                            pagaduriaType == 'SEDPUTUMAYO' ||
                            pagaduriaType == 'SEDQUINDIO' ||
                            pagaduriaType == 'SEDSINCELEJO' ||
                            pagaduriaType == 'SEDSUCRE' ||
                            pagaduriaType == 'SEDCESAR' ||
                            pagaduriaType == 'SEDAMAZONAS' ||
                            pagaduriaType == 'SEDTOLIMA' ||
                            pagaduriaType == 'SEDVAUPES' ||
                            pagaduriaType == 'SEDVICHADA' ||
                            pagaduriaType == 'SEDGUAJIRA' ||
                            pagaduriaType == 'SEDGUAVIARE' ||
                            pagaduriaType == 'SEDNARINO'
                        "
                    />
                </div>

                <!-- <DatamesSedChoco v-if="pagaduriaType == 'SEDCHOCO'" /> -->
                <!-- <DatamesSedMagdalena v-if="pagaduriaType == 'SEDMAGDALENA'" /> -->
                <!-- <DatamesSemSahagun v-if="pagaduriaType == 'SEMSAHAGUN'" /> -->

                <!--============================
                COMPONENTE HISTORIAL LABORAL
                ==============================-->
                <template v-if="fechavinc">
                    <div class="info-container col-12">
                        <EmploymentHistory2
                            class="col-12"
                            :fechavinc="fechavinc"
                            :datamesFidu="datamesFidu"
                            :user="user"
                        />
                    </div>
                    <div class="info-container col-12">
                        <EmploymentHistory
                            class="col-12"
                            :fechavinc="fechavinc"
                            :datamesFidu="datamesFidu"
                            :datamessemcali="datamessemcali"
                            :user="user"
                        />
                    </div>
                    <Detallecliente class="detallecliente-top-margin" :totales="totalesData" />
                </template>

<!-- Tabla de Carteras de la Solicitud (sin botón, ni modal) -->
<div class="info-container col-12 mb-3" v-if="carterasCargadas && carteras.length > 0">
  <h5 style="margin-bottom: 15px">Carteras de la Solicitud</h5>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Tipo de Cartera</th>
          <th>Entidad</th>
          <th>Valor Cuota</th>
          <th>Saldo</th>
          <th>Opera en desprendible</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(cart, idx) in carteras" :key="idx">
          <td>{{ cart.tipo_cartera }}</td>
          <td>{{ cart.nombre_entidad }}</td>
          <td>{{ cart.valor_cuota }}</td>
          <td>{{ cart.saldo }}</td>
          <td>{{ cart.opera_x_desprendible ? 'Sí' : 'No' }}</td>

        </tr>
      </tbody>
    </table>
  </div>
</div>



                <template v-if="showOthers">
                    <DescapliEmpty
    v-if="showDescapli"
    :disabledProspect="disabledProspect"
  />
                    <hr class="divider" />
                    <!--===================================
                            OBLIGACIONES VIGENTES EN MORA
                    ========================================-->
                    <DescnoapEmpty v-if="pagaduriaType == 'FIDUPREVISORA'" />
                    <Descnoap v-if="pagaduriaType == 'FOPEP'" :descnoap="descnoap" />

                    <EmbargosEmpty v-if="pagaduriaType == 'SED'" :embargosempty="embargosempty" />
                    <Embargos v-else :selectedPeriod="selectedPeriod" />
                    <hr class="divider" />
                    <!--===================================
                            LIQUIDACIONES
                    ========================================-->
                    <!-- <Descuentossemsahagun
                        v-if="pagaduriaType == 'SEMSAHAGUN'"
                        :descuentossemsahagun="descuentossemsahagun"
                    /> -->
                    <DescuentosEmpty v-if="pagaduriaType == 'SED'" :descuentosempty="descuentosempty" />
                    <Descuentos :selectedPeriod="selectedPeriod" v-else />
                    <hr class="divider" />
                    <div class="col-12 text-right">
                        <CustomButton text="Visar" style="width: 164px" @click="visadoFunction" />
                        <!-- <b-button class="mb-3" variant="black-pearl" @click="visadoFunction">Visar</b-button> -->
                    </div>
                </template>

                <!-- <Others
                    v-if="showOthers && pagadurias"
                    :pagaduriaType="pagaduriaType"
                    :pagadurias="pagadurias"
                    :fechavinc="fechavinc"
                    :descapli="descapli"
                    :descnoap="descnoap"
                    :embargossedvalle="embargossedvalle"
                    :user="user"
                /> -->
            </div>
        </div>

       

    </div>

    
</template>

<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css" />
<script>
/* ────────────────────────── IMPORTS ────────────────────────── */
import printJS from 'print-js';
import FormConsult from './FormConsult';
import EmploymentHistory from './EmploymentHistory';
import EmploymentHistory2 from './EmploymentHistory2';
import DatamesComponent from './Datames.vue';
import CustomButton from '../../customComponents/CustomButton.vue';
import DatamesData from './DatamesData';
import DatamesData2 from './DatamesData2';
import DatamesFidu from './DatamesFidu';
import DatamesSedChoco from './DatamesSedChoco.vue';
import DatamesSedMagdalena from './DatamesSedMagdalena';
import DatamesSedValle from './DatamesSedValle.vue';
import DatamesSemCali from './DatamesSemCali.vue';
import DatamesSemSahagun from './DatamesSemSahagun';

import Descapli from './Descapli';
import DescapliEmpty from './DescapliEmpty.vue';
import Descnoap from './Descnoap';
import DescnoapEmpty from './DescnoapEmpty';
import Others from './Others.vue';
import Embargos from './Embargos.vue';
import EmbargosEmpty from './EmbargosEmpty';
import Descuentos from './Descuentos.vue';
import DescuentosEmpty from './DescuentosEmpty';
import Detallecliente from './Detallecliente';
import Descuentossemsahagun from './Descuentossemsahagun';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

import { mapActions, mapState, mapGetters } from 'vuex';

/* ───────────────────── COMPONENT ───────────────────── */
export default {
    props: ['user'],

    components: {
        FormConsult,
        EmploymentHistory,
        EmploymentHistory2,
        DatamesComponent,
        DatamesData,
        DatamesData2,
        DatamesFidu,
        DatamesSedChoco,
        DatamesSedMagdalena,
        DatamesSedValle,
        DatamesSemCali,
        DatamesSemSahagun,
        Descapli,
        DescapliEmpty,
        Descnoap,
        DescnoapEmpty,
        Others,
        Embargos,
        Detallecliente,
        Descuentos,
        Descuentossemsahagun,
        DescuentosEmpty,
        EmbargosEmpty,
        CustomButton,
        Loading
    },

    /* ───────────────────── DATA ───────────────────── */
    data() {
        return {
            type_consult: 'individual',
            /*  datames / desc / emb arrays ­*/
            fechavinc: null,
            datamesFopep: null,
            datamessedvalle: null,
            datamesFidu: null,
            datamessemcali: null,
            descapli: [],
            descnoap: [],
            embargossedvalle: [],
            embargossedchoco: [],
            embargossedatlantico: [],
            embargossedcauca: [],
            embargossemquibdo: [],
            embargossempopayan: [],
            embargossemcali: [],
            embargosempty: [],
            descuentossedvalle: [],
            descuentosempty: [],
            descuentossedchoco: [],
            descuentossedcauca: [],
            descuentossemcali: [],
            descuentossemquibdo: [],
            descuentossemsahagun: [],
            descuentossempopayan: [],
            selectedPeriod: '',
            monto: 0,
            pagaduriaKey: '',
            cargo: null,
            showOthers: false,
            pagadurias: null,
            isLoading: false,
            disabledProspect: false,
            visado: null,
            visadoValido: 'NO FACTIBLE',

            /* Totales como números crudos (sin redondeo) */
            totalesData: {
                libreInversion: 0,
                libreInversionSuma: 0,
                compraCartera: 0,
                cuotaMaxima: 0
            },

            showCarterasModal: false,
            carteras: [],
            carterasCargadas: false
        };
    },

    /* ───────────────────── WATCHERS ───────────────────── */
    watch: {
        couponsIngresos: {
            handler() {
                this.calcularTotales();
            },
            deep: true,
            immediate: true
        },

        ingresosExtras() {
            let totalIncapacidad = 0;

            this.ingresosExtras.some(item => {
                if (item.concept.toLowerCase().includes('definitiva')) {
                    this.alertDefinitiva({
                        message: 'Cliente en proceso de retiro',
                        variant: 'danger'
                    });
                    return true;
                }
            });

            this.ingresosExtras.forEach(item => {
                if (item.concept.toLowerCase().includes('incapacidad')) {
                    totalIncapacidad += Number(item.ingresos);
                }
            });

            if (Number(totalIncapacidad) > Number(this.valorIngreso)) {
                this.alertIncapacidad({
                    message: 'Cliente no apto por incapacidad',
                    variant: 'danger'
                });
            }
        }
    },

    /* ─────────────────── LIFECYCLE ─────────────────── */
    mounted() {
        if (this.couponsIngresos) this.calcularTotales();
    },

    /* ─────────────────── COMPUTED ─────────────────── */
    computed: {
  ...mapState('pagaduriasModule', [
    'coupons',
    'couponsType',
    'pagaduriaType',
    'pagaduriaLabel',
    'pagaduriasTypes'      // ← añadido
  ]),
  ...mapGetters('pagaduriasModule', [
    'couponsPerPeriod',
    'valorIngreso',
    'ingresosIncapacidadPerPeriod',
    'incapacidadValida',
    'couponsIngresos',
    'ingresosExtras'
  ]),
  ...mapState('embargosModule', ['embargosType']),
  ...mapState('descuentosModule', ['descuentosType']),
  ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresosPlus']),
  ...mapGetters('descuentosModule', ['descuentosPerPeriod']),

  showDescapli () {
    const normalize = s => (s || '').replace(/\s+/g, '').toUpperCase()
    const current = normalize(this.pagaduriaType)
    return this.pagaduriasTypes.some(p => normalize(p.value) === current)
  },
        /* --------  TOTALES SIN REDONDEAR NI TRUNCAR  -------- */
        totales() {
            const valrSM = 1423500;

            /* → lógica original de negocio ← */
            let totalWithoutHealthPension = 0;
            this.couponsIngresos.items.forEach(i => {
                if (!['APFPM', 'APEPEN', 'APESDN'].includes(i.code))
                    totalWithoutHealthPension += Number(i.vaplicado);
            });

            let valorIngreso = 0;
            if (this.pagaduriaType === 'FOPEP')
                valorIngreso = Number(this.datamesFopep.vpension.replace(/\D/g, '').slice(0, -2));
            else if (this.pagaduriaType === 'FIDUPREVISORA')
                valorIngreso = Number(this.datamesFidu.vpension.replace(/\D/g, '').slice(0, -2));
            else
                valorIngreso =
                    this.couponsPerPeriod.items.find(i => i.code === 'INGCUP')?.ingresos || 0;

            if (this.cargo === 'Rector Institucion Educativa Completa') valorIngreso *= 1.3;
            if (this.cargo === 'Coordinador') valorIngreso *= 1.2;
            if (this.cargo === 'Director De Nucleo') valorIngreso *= 1.35;

            let disccount = 0.08;
            if (['FOPEP', 'FIDUPREVISORA', 'CASUR'].includes(this.pagaduriaType)) {
                if (valorIngreso === valrSM) disccount = 0.04;
                else if (valorIngreso >= valrSM * 2) disccount = 0.12;
            }

            const valorIngresoTemp = valorIngreso - valorIngreso * disccount;
            const previousDiscount = valorIngresoTemp / 2;

            const items =
                ['FOPEP', 'FIDUPREVISORA'].includes(this.pagaduriaType)
                    ? this.descapli
                    : this.couponsPerPeriod.items.filter(
                          i => i.code !== 'INGCUP' && Number(i.egresos) > 0
                      );

            const totalEgresos = items.reduce(
                (t, i) => t + Number(i.egresos || i.vaplicado),
                0
            );

            let libreInversion =
                valorIngresoTemp < valrSM * 2
                    ? valorIngresoTemp - valrSM - totalWithoutHealthPension
                    : valorIngresoTemp / 2 - totalWithoutHealthPension;

            let compraCartera =
                previousDiscount < valrSM
                    ? valorIngresoTemp - valrSM
                    : valorIngresoTemp / 2;

            let cuotaMaxima =
                previousDiscount < valrSM
                    ? valorIngresoTemp - valrSM
                    : valorIngresoTemp / 2;

            /*  devolvemos los valores crudos tal como salen (positivos o negativos)  */
            return {
                libreInversion,
                libreInversionSuma: libreInversion,
                compraCartera,
                cuotaMaxima
            };
        }
    },
    created () {
  if (!this.pagaduriasTypes.length) {
    this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
  }
},
    methods: {
        ...mapActions('pagaduriasModule', ['fetchCoupons']),
        ...mapActions('embargosModule', ['fetchEmbargos']),
        ...mapActions('descuentosModule', ['fetchDescuentos']),

        /* ---------------- EMIT INFO ---------------- */
        emitInfo(payload) {
            this.isLoading = true;
            this.pagadurias = payload.pagadurias;
            this.pagaduriaKey = payload.pagaduriaKey;
            this.cargo = payload.cargo;
            this.datamesFopep =
                this.datamessedvalle =
                this.datamesFidu =
                this.datamessemcali =
                    null;
            this.visado = payload.visado;
            this.monto = payload.monto;

            if (payload.carteras) {
                this.carteras = payload.carteras;
                this.carterasCargadas = true;
            }

            this.getCoupons({
                doc: payload.doc,
                pagaduria: this.couponsType,
                pagaduriaLabel: payload.pagaduria
            });

            this.getDescapli(payload);
            this.getDescnoap(payload);

            this.getEmbargos({
                doc: payload.doc,
                pagaduria: this.embargosType,
                pagaduriaLabel: payload.pagaduria
            });
            this.getDescuentos({
                doc: payload.doc,
                pagaduria: this.descuentosType,
                pagaduriaLabel: payload.pagaduria
            });

            if (payload.pagaduria === 'FOPEP') this.getDatames(payload);
            else if (payload.pagaduria === 'SEDVALLE') this.getDatamesSedValle(payload);
            else if (payload.pagaduria === 'FIDUPREVISORA') this.getDatamesFidu(payload);
            else if (payload.pagaduria === 'SEMCALI') this.getDatamesSemCali(payload);

            this.getFechaVinc(payload).then(() => {
                this.showOthers = true;
                this.isLoading = false;
            });
        },

        /* ---------------- AXIOS HELPER METHODS ---------------- */
        async getDatames(payload) {
            this.datamesFopep = (await axios.get(`/datamesfopep/${payload.doc}`)).data;
        },
        async getDatamesSedValle(payload) {
            this.datamessedvalle = (
                await axios.post('/datamessedvalle/consultaUnitaria', { doc: payload.doc })
            ).data.data;
        },
        async getDatamesFidu(payload) {
            this.datamesFidu = (
                await axios.post('/datamesfidu/consultaUnitaria', { doc: payload.doc })
            ).data.data;
        },
        async getDatamesSemCali(payload) {
            this.datamessemcali = (
                await axios.post('/consultaDatamessemcali', { doc: payload.doc })
            ).data.data;
        },
        async getFechaVinc(payload) {
            this.fechavinc = (await axios.get(`/fechavinc/${payload.doc}`)).data;
        },
        async getDescapli(payload) {
            this.descapli = (await axios.get(`/descapli/${payload.doc}`)).data;
        },
        async getDescnoap(payload) {
            this.descnoap = (await axios.get(`/descnoap/${payload.doc}`)).data;
        },
        async getCoupons(payload) {
            const res = await axios.post('/get-coupons', payload);
            this.fetchCoupons(res.data.items || res.data);
            setTimeout(() => {
                if (!this.incapacidadValida) this.$bvToast.show('toast-incapacidad-month');
            }, 1000);
        },
        async getEmbargos(payload) {
            this.fetchEmbargos((await axios.post('/get-embargos', payload)).data);
        },
        async getDescuentos(payload) {
            this.fetchDescuentos((await axios.post('/get-descuentos', payload)).data);
        },

        /* ---------------- TOASTS ---------------- */
        alertIncapacidad(data) {
            this.$bvToast.toast(data.message, {
                title: data.title || 'Alerta del sistema',
                autoHideDelay: 10000,
                variant: data.variant,
                solid: true
            });
        },
        alertDefinitiva(data) {
            this.disabledProspect = true;
            this.$bvToast.toast(data.message, {
                title: data.title || 'Alerta del sistema',
                autoHideDelay: 10000,
                variant: data.variant,
                solid: true
            });
        },

        print() {
            window.print();
        },

        /* ---------------- VISADO FUNCTION ---------------- */
        visadoFunction() {
            let causal = '';
            let obligacionMarcadas = false;
            let embargosSinMora = false;

            const cuotaMaximaDef =
                Number(this.conteoEgresosPlus) + Number(this.totalesData.libreInversion);

            const definitivaAlerta = this.ingresosExtras.some(i =>
                i.concept.toLowerCase().includes('definitiva')
            );

            const cuotaMenor = Number(this.cuotadeseada) < cuotaMaximaDef;
            const cuotaMayor = Number(this.cuotadeseada) > cuotaMaximaDef;

            if (this.descuentosPerPeriod.total > 0) {
                obligacionMarcadas = this.descuentosPerPeriod.items.some(i => i.check === true);
            } else {
                embargosSinMora = true;
            }

            if (cuotaMenor && !obligacionMarcadas) {
                this.visadoValido = 'NO FACTIBLE';
                causal = 'Presenta obligaciones en mora';
            } else if (cuotaMenor && obligacionMarcadas) {
                this.visadoValido = 'FACTIBLE';
                causal = 'Sin causal';
            }

            if (cuotaMayor && embargosSinMora) {
                this.visadoValido = 'NO FACTIBLE';
                causal += (causal ? ', ' : '') + 'Negado por cupo';
            } else if (cuotaMenor && embargosSinMora) {
                this.visadoValido = 'FACTIBLE';
                causal = 'Sin causal';
            } else if (cuotaMayor && !obligacionMarcadas && !embargosSinMora) {
                this.visadoValido = 'NO FACTIBLE';
                causal =
                    '1. Presenta obligaciones en mora, 2. Negado por cupo';
            } else if (cuotaMayor && obligacionMarcadas) {
                this.visadoValido = 'NO FACTIBLE';
                causal = 'Negado por cupo';
            }

            if (definitivaAlerta) {
                this.visadoValido = 'NO FACTIBLE';
                causal = 'Cliente en proceso de retiro';
            }

            const data = {
                estado: this.visadoValido,
                cuotacredito: this.cuotadeseada,
                monto: this.monto,
                causal
            };

            axios
                .post(`/visados/${this.visado.id}`, data)
                .then(() => (window.location.href = '/historyClient'))
                .catch(err => console.error('visadoFunction error:', err));
        },

        /* -------------- CALCULAR TOTALES (BACKEND) -------------- */
        async calcularTotales() {
            try {
                if (!this.couponsIngresos?.items?.length) return;

                const { finperiodo, doc } = this.couponsIngresos.items[0];
                const [year, month] = finperiodo.split('-');
                this.isLoading = true;

                const res = await fetch(
                    `/demografico/calcular-cupo/${doc}/${parseInt(month)}/${parseInt(year)}`
                );
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const r = await res.json();

                /*  guardamos los números crudos  */
                this.totalesData = {
                    libreInversion: r.cupo_libre,
                    libreInversionSuma: r.libreInversionSuma,
                    compraCartera: r.compra_cartera,
                    cuotaMaxima: r.cuotaMaxima
                };
            } catch (e) {
                console.error('calcularTotales:', e);
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>


<style>
.info-container {
    display: flex;
    justify-content: space-between;
    gap: 20px; /* Espaciado entre los bloques */
    margin-top: 20px;
}

.info-block {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    background-color: #fff;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 30%; /* Ajustar el ancho para un diseño responsivo */
}
.table-text {
    font-size: 12px;
}

.tables-space {
    margin-top: 15px !important;
}

.divider {
    width: 100%;
    height: 2px;
    background-color: #70777f;
    border: none;
    margin: 20px 12px;
}

.detallecliente-top-margin {
    margin-top: 20px;
}
</style>
