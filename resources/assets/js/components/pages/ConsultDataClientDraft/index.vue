<template>
  <div class="container-fluid">
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
              <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{ datamesFopep.nomp }}</h2>
            </div>
          </div>
          <button type="button" v-on:click="print" class="btn btn-black-pearl ml-auto px-3">
            <span>Descargar PDF</span>
            <download-icon></download-icon>
          </button>
        </div>
      </div>

      <div id="consulta-container" class="row">
        <FormConsult @emitInfo="emitInfo" />

        <!--============================
          DATAMES FOPEP -
        ==============================-->
        <DatamesComponent v-if="pagaduriaType == 'FOPEP' && datamesFopep" :user="user" :datamesFopep="datamesFopep" />

        <!--============================
            FIDUPREVISORA datamesFidu
        ==============================-->
        <DatamesFiduComponent
          v-if="pagaduriaType == 'FIDUPREVISORA' && datamesFidu"
          :user="user"
          :datamesFidu="datamesFidu"
        />

        <!--============================
          DATAMESSEDUC SED VALLE
      ==============================-->
        <DatamesSeducComponent
          v-if="pagaduriaType == 'SEDVALLE' && datamesseceduc"
          :user="user"
          :datamesseceduc="datamesseceduc"
        />

        <!--================================
         SEMCALI datamessemcali
        ===================================-->
        <DatamesSemCali
          v-if="pagaduriaType == 'SEMCALI' && datamessemcali"
          :user="user"
          :datamessemcali="datamessemcali"
        />

        <!--================================
         DATAMES SECRETARIAS
        ===================================-->

        <DatamesData
          v-if="
            pagaduriaType == 'SEDCAUCA' ||
            pagaduriaType == 'SEDCHOCO' ||
            pagaduriaType == 'SEDQUIBDO' ||
            pagaduriaType == 'SEDPOPAYAN' ||
            pagaduriaType == 'SEDBOLIVAR' ||
            pagaduriaType == 'SEMBARRANQUILLA' ||
            pagaduriaType == 'SEDATLANTICO' ||
            pagaduriaType == 'SEDNARINO'
          "
        />

        <DatamesSedMagdalena v-if="pagaduriaType == 'SEDMAGDALENA'" />
        <DatamesSemSahagun v-if="pagaduriaType == 'SEMSAHAGUN'" />

        <!--============================
          COMPONENTE HISTORIAL LABORAL
        ==============================-->
        <template v-if="fechavinc">
          <EmploymentHistory
            :fechavinc="fechavinc"
            :datamesseceduc="datamesseceduc"
            :datamesFidu="datamesFidu"
            :datamessemcali="datamessemcali"
            :user="user"
          />
          <Detallecliente :descuentossedcauca="descuentossedcauca" :totales="totales" />
        </template>

        <template v-if="showOthers">
          <DescapliEmpty
            v-if="
              pagaduriaType == 'FIDUPREVISORA' ||
              pagaduriaType == 'SEDVALLE' ||
              pagaduriaType == 'SEDCAUCA' ||
              pagaduriaType == 'SEDCHOCO' ||
              pagaduriaType == 'SEDQUIBDO' ||
              pagaduriaType == 'SEMCALI' ||
              pagaduriaType == 'SEDMAGDALENA' ||
              pagaduriaType == 'SEMSAHAGUN' ||
              pagaduriaType == 'SEMBARRANQUILLA' ||
              pagaduriaType == 'SEDATLANTICO' ||
              pagaduriaType == 'SEDBOLIVAR' ||
              pagaduriaType == 'SEDPOPAYAN' ||
              pagaduriaType == 'FOPEP'
            "
            :disabledProspect="disabledProspect"
          />

          <!--===================================
                OBLIGACIONES VIGENTES EN MORA
          ========================================-->
          <DescnoapEmpty v-if="pagaduriaType == 'FIDUPREVISORA'" />
          <EmbargosSemCali v-else-if="pagaduriaType == 'SEMCALI'" :embargossemcali="embargossemcali" />
          <Descnoap v-if="pagaduriaType == 'FOPEP'" :descnoap="descnoap" />
          <EmbargosSeceduc v-if="pagaduriaType == 'SEDVALLE'" :embargossedvalle="embargossedvalle" />
          <EmbargosSedchoco v-if="pagaduriaType == 'SEDCHOCO'" :embargossedchoco="embargossedchoco" />
          <EmbargosSedcauca v-if="pagaduriaType == 'SEDCAUCA'" :embargossedcauca="embargossedcauca" />
          <EmbargosSedquibdo v-if="pagaduriaType == 'SEDQUIBDO'" :embargossedquibdo="embargossedquibdo" />
          <EmbargosSedpopayan v-if="pagaduriaType == 'SEDPOPAYAN'" :embargossedpopayan="embargossedpopayan" />
          <EmbargosEmpty
            v-if="
              pagaduriaType == 'SEDMAGDALENA' ||
              pagaduriaType == 'SEMSAHAGUN' ||
              pagaduriaType == 'SEMBARRANQUILLA' ||
              pagaduriaType == 'SEDATLANTICO' ||
              pagaduriaType == 'SEDBOLIVAR' ||
              pagaduriaType == 'SEDNARINO'
            "
            :embargosempty="embargosempty"
          />

          <!--===================================
                LIQUIDACIONES
          ========================================-->
          <Descuentossecedu v-if="pagaduriaType == 'SEDVALLE'" :descuentossedvalle="descuentossedvalle" />
          <Descuentossedchoco v-if="pagaduriaType == 'SEDCHOCO'" :descuentossedchoco="descuentossedchoco" />
          <Descuentossedcauca v-if="pagaduriaType == 'SEDCAUCA'" :descuentossedcauca="descuentossedcauca" />
          <DescuentosSemCali v-if="pagaduriaType == 'SEMCALI'" :descuentossemcali="descuentossemcali" />
          <Descuentossedquibdo v-if="pagaduriaType == 'SEDQUIBDO'" :descuentossedquibdo="descuentossedquibdo" />
          <Descuentossemsahagun v-if="pagaduriaType == 'SEMSAHAGUN'" :descuentossemsahagun="descuentossemsahagun" />
          <Descuentossedpopayan v-if="pagaduriaType == 'SEDPOPAYAN'" :descuentossedpopayan="descuentossedpopayan" />
          <DescuentosEmpty
            v-if="
              pagaduriaType == 'SEDMAGDALENA' ||
              pagaduriaType == 'SEMBARRANQUILLA' ||
              pagaduriaType == 'SEDATLANTICO' ||
              pagaduriaType == 'SEDBOLIVAR' ||
              pagaduriaType == 'SEDNARINO'
            "
            :descuentosempty="descuentosempty"
          />

          <div class="col-12">
            <b-button class="mb-3" variant="black-pearl" @click="visadoFunction">Visar</b-button>
          </div>
        </template>

        <Others
          v-if="showOthers && pagadurias"
          :pagaduriaType="pagaduriaType"
          :pagadurias="pagadurias"
          :fechavinc="fechavinc"
          :descapli="descapli"
          :descnoap="descnoap"
          :embargossedvalle="embargossedvalle"
          :user="user"
        />
      </div>
    </div>
  </div>
</template>

<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css" />

<script>
import printJS from 'print-js';
import FormConsult from './FormConsult';
import EmploymentHistory from './EmploymentHistory';
import DatamesComponent from './Datames.vue';

import DatamesData from './DatamesData';
import DatamesSedMagdalena from './DatamesSedMagdalena';
import DatamesSemSahagun from './DatamesSemSahagun';

import DatamesSeducComponent from './DatamesSeduc';
import DatamesFiduComponent from './DatamesFidu';
import DatamesSemCali from './DatamesSemCali.vue';
import Descapli from './Descapli';
import DescapliEmpty from './DescapliEmpty';
import Descnoap from './Descnoap';
import DescnoapEmpty from './DescnoapEmpty';
import Others from './Others.vue';
import EmbargosSeceduc from './EmbargosSeceduc.vue';
import EmbargosSedchoco from './EmbargosSedchoco';
import EmbargosSedquibdo from './EmbargosSedquibdo';
import EmbargosSedcauca from './EmbargosSedcauca';
import EmbargosSedpopayan from './EmbargosSedpopayan';
import EmbargosSemCali from './EmbargosSemCali.vue';
import EmbargosEmpty from './EmbargosEmpty';
import Descuentossecedu from './Descuentossecedu.vue';
import DescuentosEmpty from './DescuentosEmpty';
import Descuentossedchoco from './Descuentossedchoco';
import Descuentossedcauca from './Descuentossedcauca';
import Detallecliente from './Detallecliente';
import DescuentosSemCali from './DescuentosSemCali.vue';
import Descuentossedquibdo from './Descuentossedquibdo';
import Descuentossemsahagun from './Descuentossemsahagun';
import Descuentossedpopayan from './Descuentossedpopayan';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

import { mapActions, mapState, mapGetters } from 'vuex';

export default {
  props: ['user'],
  created() {},
  components: {
    FormConsult,
    EmploymentHistory,
    DatamesComponent,
    DatamesData,
    DatamesSedMagdalena,
    DatamesSemSahagun,
    DatamesSeducComponent,
    DatamesFiduComponent,
    DatamesSemCali,
    Descapli,
    DescapliEmpty,
    Descnoap,
    DescnoapEmpty,
    Others,
    EmbargosSeceduc,
    EmbargosSedchoco,
    EmbargosSedcauca,
    EmbargosSedpopayan,
    EmbargosSedquibdo,
    EmbargosSemCali,
    Descuentossecedu,
    Descuentossedchoco,
    Descuentossedcauca,
    Detallecliente,
    DescuentosSemCali,
    Descuentossedquibdo,
    Descuentossemsahagun,
    Descuentossedpopayan,
    DescuentosEmpty,
    EmbargosEmpty,
    Loading
  },

  data() {
    return {
      type_consult: 'individual',
      fechavinc: null,
      datamesFopep: null,
      datamesseceduc: null,
      datamesFidu: null,
      datamessemcali: null,
      descapli: [],
      descnoap: [],
      embargossedvalle: [],
      embargossedchoco: [],
      embargossedcauca: [],
      embargossedquibdo: [],
      embargossedpopayan: [],
      embargossemcali: [],
      embargosempty: [],
      descuentossedvalle: [],
      descuentosempty: [],
      descuentossedchoco: [],
      descuentossedcauca: [],
      descuentossemcali: [],
      descuentossedquibdo: [],
      descuentossemsahagun: [],
      descuentossedpopayan: [],

      monto: 0,

      pagaduriaKey: '',
      cargo: null,
      showOthers: false,
      pagadurias: null,
      isLoading: false,
      disabledProspect: false,
      visado: null,
      visadoValido: 'NO FACTIBLE'
    };
  },
  watch: {
    ingresosExtras(val) {
      let totalIncapacidad = 0;

      this.ingresosExtras.some(item => {
        if (item.concept.includes('Definitiva') || item.concept.includes('definitiva')) {
          let data = {
            message: 'Cliente en proceso de retiro',
            variant: 'danger'
          };
          this.alertDefinitiva(data);
          return true;
        }
      });

      this.ingresosExtras.forEach(item => {
        if (item.concept.includes('Incapacidad') || item.concept.includes('incapacidad')) {
          totalIncapacidad += Number(item.ingresos);
        }
      });

      // Valida si el valor de la incapacidad es mayor al valor del ingreso
      if (Number(totalIncapacidad) > Number(this.valorIngreso)) {
        let data = {
          message: 'Cliente no apto por incapacidad',
          variant: 'danger'
        };
        this.alertIncapacidad(data);
      }
    }
  },
  computed: {
    ...mapState('pagaduriasModule', ['coupons', 'pagaduriaType']),
    ...mapGetters('pagaduriasModule', [
      'couponsPerPeriod',
      'valorIngreso',
      'ingresosIncapacidadPerPeriod',
      'incapacidadValida',
      'couponsIngresos',
      'ingresosExtras'
    ]),
    ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresosPlus']),
    totales() {
      const valrSM = 1160000;

      let totalWithoutHealthPension = 0;
      this.couponsIngresos.items.forEach(item => {
        if (item.code !== 'APFPM' && item.code !== 'APEPEN' && item.code !== 'APESDN') {
          totalWithoutHealthPension += Number(item.vaplicado);
        }
      });

      let valorIngreso = 0;
      if (this.pagaduriaType === 'FOPEP') {
        valorIngreso = Number(this.datamesFopep.vpension.replace(/[^0-9]/g, '').slice(0, -2));
      } else if (this.pagaduriaType == 'FIDUPREVISORA') {
        valorIngreso = Number(this.datamesFidu.vpension.replace(/[^0-9]/g, '').slice(0, -2));
      } else if (this.pagaduriaType === 'SEDNARINO') {
        valorIngreso = Number(this.pagadurias.datamesSedNarino.vingreso.replace(/[^0-9]/g, '').slice(0));
      } else {
        valorIngreso = this.couponsPerPeriod.items.filter(item => item.code === 'INGCUP')[0]?.ingresos || 0;
      }

      let increase = 0;
      if (this.cargo == 'Rector Institucion Educativa Completa') {
        increase = valorIngreso * 0.3;
        valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
      } else if (this.cargo == 'Coordinador') {
        increase = valorIngreso * 0.2;
        valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
      } else if (this.cargo == 'Director De Nucleo') {
        increase = valorIngreso * 0.35;
        valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
      }

      let disccount = 0.08;
      if (this.pagaduriaType === 'FOPEP' || this.pagaduriaType == 'FIDUPREVISORA') {
        if (valorIngreso == valrSM) {
          disccount = 0.04;
        } else if (valorIngreso > valrSM && valorIngreso < valrSM * 2) {
          disccount = 0.08;
        } else if (valorIngreso >= valrSM * 2) {
          disccount = 0.12;
        }
      }

      const valorIngresoTemp = valorIngreso - valorIngreso * disccount;
      console.log('valoringresotemp', valorIngresoTemp);

      let items = [];
      let itemslength = [];

      let totalEgresos = 0;
      if (this.pagaduriaType === 'FOPEP' || this.pagaduriaType == 'FIDUPREVISORA') {
        items = this.descapli;
        itemslength = items.length;
        totalEgresos = items.reduce((a, b) => a + Number(b.vaplicado), 0);
      } else {
        items = this.couponsPerPeriod.items.filter(item => item.code !== 'INGCUP' && Number(item.egresos) > 0);
        itemslength = items.length;
        totalEgresos = items.reduce((total, item) => total + Number(item.egresos), 0);
      }

      let totalDescuentos = 0;
      if (this.pagaduriaType === 'FOPEP' || this.pagaduriaType == 'FIDUPREVISORA') {
        totalDescuentos = this.descapli.length;
      } else if (
        this.pagaduriaType === 'SEDATLANTICO' ||
        this.pagaduriaType === 'SEMBARRANQUILLA' ||
        this.pagaduriaType === 'SEDMAGDALENA' ||
        this.pagaduriaType === 'SEMSAHAGUN' ||
        this.pagaduriaType === 'SEDBOLIVAR' ||
        this.pagaduriaType === 'SEDNARINO'
      ) {
        totalDescuentos = 0;
      } else {
        totalDescuentos = this.pagaduriaKey ? this[`descuentos${this.pagaduriaKey}`].length : 0;
      }

      let totalEmbargos = 0;
      if (this.pagaduriaType === 'FOPEP' || this.pagaduriaType == 'FIDUPREVISORA') {
        totalEmbargos = 0; // this.descnoap.length
      } else if (
        this.pagaduriaType === 'SEDATLANTICO' ||
        this.pagaduriaType === 'SEMBARRANQUILLA' ||
        this.pagaduriaType === 'SEDMAGDALENA' ||
        this.pagaduriaType === 'SEMSAHAGUN' ||
        this.pagaduriaType === 'SEDBOLIVAR' ||
        this.pagaduriaType === 'SEDNARINO'
      ) {
        totalEmbargos = 0;
      } else {
        totalEmbargos = this.pagaduriaKey ? this[`embargos${this.pagaduriaKey}`].length : 0;
      }

      let previousDiscount = valorIngresoTemp / 2;

      let libreInversion = 0;
      if (valorIngresoTemp < valrSM * 2) {
        libreInversion = valorIngresoTemp - valrSM - totalWithoutHealthPension;
      } else {
        libreInversion = valorIngresoTemp / 2 - totalWithoutHealthPension;
      }

      let libreInversionSuma = libreInversion;
      console.log('libreinversion original', libreInversion);

      let compraCartera = 0;
      if (previousDiscount < valrSM) {
        compraCartera = valorIngresoTemp - valrSM;
      } else {
        compraCartera = valorIngresoTemp / 2;
      }

      let cuotaMaxima = 0;
      if (previousDiscount < valrSM) {
        cuotaMaxima = valorIngresoTemp - valrSM;
      } else {
        cuotaMaxima = valorIngresoTemp / 2;
      }

      return {
        descuentos: totalDescuentos,
        embargos: totalEmbargos,
        libreInversion: libreInversion < 0 ? 0 : libreInversion,
        libreInversionSuma: libreInversionSuma,
        compraCartera: compraCartera < 0 ? 0 : compraCartera,
        cuotaMaxima: cuotaMaxima < 0 ? 0 : cuotaMaxima
      };
    }
  },
  methods: {
    ...mapActions('pagaduriasModule', ['fetchCoupons']),
    emitInfo(payload) {
      this.isLoading = true;
      this.pagadurias = payload.pagadurias;
      this.pagaduriaKey = payload.pagaduriaKey;
      this.cargo = payload.cargo;

      this.datamesFopep = null;
      this.datamesseceduc = null;
      this.datamesFidu = null;
      this.datamessemcali = null;
      this.visado = payload.visado;

      this.monto = payload.monto;

      if (payload.pagaduria == 'FOPEP') {
        this.getDatames(payload);
      } else if (payload.pagaduria == 'SEDVALLE') {
        this.getDatamesseceduc(payload);
        this.getDescuentossecedu(payload);
      } else if (payload.pagaduria == 'FIDUPREVISORA') {
        this.getDatamesFidu(payload);
      } else if (payload.pagaduria == 'SEMCALI') {
        this.getDatamesSemCali(payload);
      }

      this.getEmbargosseceduc(payload);
      this.getEmbargossedchoco(payload);
      this.getEmbargossedquibdo(payload);
      this.getEmbargossedpopayan(payload);
      this.getEmbargossedcauca(payload);
      this.getEmbargosSemCali(payload);
      this.getDescuentossecedu(payload);
      this.getDescuentossedchoco(payload);
      this.getDescuentossedcauca(payload);
      this.getDescuentosSemCali(payload);
      this.getDescuentossedquibdo(payload);
      this.getDescuentossemsahagun(payload);
      this.getDescuentossedpopayan(payload);
      this.getDescapli(payload);
      this.getDescnoap(payload);
      this.getCoupons(payload);
      this.getFechaVinc(payload).then(response => {
        this.showOthers = true;
        this.isLoading = false;
      });
    },
    async getDatames(payload) {
      const response = await axios.get(`datamesfopep/${payload.doc}`);
      this.datamesFopep = response.data;
    },
    async getDatamesseceduc(payload) {
      const response = await axios.post('/datamesseceduc/consultaUnitaria', { doc: payload.doc });
      this.datamesseceduc = response.data.data;
    },
    async getDatamesFidu(payload) {
      const response = await axios.post('/datamesfidu/consultaUnitaria', { doc: payload.doc });
      this.datamesFidu = response.data.data;
    },
    async getDatamesSemCali(payload) {
      const response = await axios.post('/consultaDatamessemcali', { doc: payload.doc });
      this.datamessemcali = response.data.data;
    },
    async getFechaVinc(payload) {
      const response = await axios.get(`fechavinc/${payload.doc}`);
      this.fechavinc = response.data;
    },
    async getDescapli(payload) {
      const response = await axios.get(`descapli/${payload.doc}`);
      this.descapli = response.data;
    },
    async getDescnoap(payload) {
      const response = await axios.get(`descnoap/${payload.doc}`);
      this.descnoap = response.data;
    },
    async getEmbargosseceduc(payload) {
      const response = await axios.post('/consultaEmbargosseceduc', { doc: payload.doc });
      this.embargossedvalle = response.data.data;
    },
    async getEmbargossedchoco(payload) {
      const response = await axios.post('/consultaEmbargossedchoco', { doc: payload.doc });
      this.embargossedchoco = response.data.data;
    },
    async getEmbargossedcauca(payload) {
      const response = await axios.post('/consultaEmbargossedcauca', { doc: payload.doc });
      this.embargossedcauca = response.data.data;
    },
    async getEmbargossedquibdo(payload) {
      const response = await axios.post('/consultaEmbargossedquibdo', { doc: payload.doc });
      this.embargossedquibdo = response.data.data;
    },
    async getEmbargossedpopayan(payload) {
      const response = await axios.post('/consultaEmbargossedpopayan', { doc: payload.doc });
      this.embargossedpopayan = response.data.data;
    },
    async getEmbargosSemCali(payload) {
      const response = await axios.post('/consultaEmbargossemcali', { doc: payload.doc });
      this.embargossemcali = response.data.data;
    },
    async getDescuentossecedu(payload) {
      const response = await axios.post('/consultaDescuentosseceduc', { doc: payload.doc });
      this.descuentossedvalle = response.data.data;
    },
    async getDescuentossedchoco(payload) {
      const response = await axios.post('/consultaDescuentossedchoco', { doc: payload.doc });
      this.descuentossedchoco = response.data.data;
    },
    async getDescuentossedcauca(payload) {
      const response = await axios.post('/consultaDescuentossedcauca', { doc: payload.doc });
      this.descuentossedcauca = response.data.data;
    },
    async getDescuentosSemCali(payload) {
      const response = await axios.post('/consultaDescuentossemcali', { doc: payload.doc });
      this.descuentossemcali = response.data.data;
    },
    async getDescuentossedquibdo(payload) {
      const response = await axios.post('/consultaDescuentossedquibdo', { doc: payload.doc });
      this.descuentossedquibdo = response.data.data;
    },
    async getDescuentossemsahagun(payload) {
      const response = await axios.post('/consultaDescuentossemsahagun', { doc: payload.doc });
      this.descuentossemsahagun = response.data.data;
    },
    async getDescuentossedpopayan(payload) {
      const response = await axios.post('/consultaDescuentossedpopayan', { doc: payload.doc });
      this.descuentossedpopayan = response.data.data;
    },
    async getCoupons(payload) {
      const data = {
        doc: payload.doc,
        pagaduria: payload.pagaduria
      };

      const response = await axios.post('/get-coupons', data);
      this.fetchCoupons(response.data);

      setTimeout(() => {
        // Valida si el tiene incapacidades
        if (this.incapacidadValida === false) {
          this.$bvToast.show('toast-incapacidad-month');
        }
      }, 1000);
    },
    print() {
      window.print();
    },
    alertIncapacidad(data) {
      this.$bvToast.toast(`${data.message}`, {
        title: data.title ? data.title : 'Alerta del sistema',
        autoHideDelay: 10000,
        variant: data.variant,
        solid: true
      });
    },
    alertDefinitiva(data) {
      this.disabledProspect = true;
      this.$bvToast.toast(`${data.message}`, {
        title: data.title ? data.title : 'Alerta del sistema',
        autoHideDelay: 10000,
        variant: data.variant,
        solid: true
      });
    },

    //Visando consulta
    visadoFunction() {
      let causal = '';
      let obligacionMarcadas = false;
      let embargosSinMora = false;

      const cuotaMaximaDef = this.conteoEgresosPlus + this.totales.libreInversionSuma;
      console.log('cuotaMaximaDef', cuotaMaximaDef);

      const definitivaAlerta = this.ingresosExtras.some(
        item => item.concept.includes('Definitiva') || item.concept.includes('definitiva')
      );

      const cuotaMenor = Number(this.cuotadeseada) < cuotaMaximaDef;
      const cuotaMayor = Number(this.cuotadeseada) > cuotaMaximaDef;

      if (this.pagaduriaType == 'SEDVALLE') {
        if (this.descuentossedvalle.length > 0) {
          obligacionMarcadas = this.descuentossedvalle.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'SEMCALI') {
        if (this.descuentossemcali.length > 0) {
          obligacionMarcadas = this.descuentossemcali.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'SEDCHOCO') {
        if (this.descuentossedchoco.length > 0) {
          obligacionMarcadas = this.descuentossedchoco.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'SEDCAUCA') {
        if (this.descuentossedcauca.length > 0) {
          obligacionMarcadas = this.descuentossedcauca.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'SEDQUIBDO') {
        if (this.descuentossedquibdo.length > 0) {
          obligacionMarcadas = this.descuentossedquibdo.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'SEMSAHAGUN') {
        if (this.descuentossemsahagun.length > 0) {
          obligacionMarcadas = this.descuentossemsahagun.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'SEDMAGDALENA') {
        embargosSinMora = true;
      } else if (this.pagaduriaType == 'SEDPOPAYAN') {
        if (this.descuentossedpopayan.length > 0) {
          obligacionMarcadas = this.descuentossedpopayan.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      } else if (this.pagaduriaType == 'FOPEP') {
        if (this.descnoap.length > 0) {
          obligacionMarcadas = this.descnoap.every(item => item.check == true);
        } else {
          embargosSinMora = true;
        }
      }

      if (cuotaMenor === true && obligacionMarcadas === false) {
        console.log('hola');
        this.visadoValido = 'NO FACTIBLE';
        causal = 'Presenta obligaciones en mora';
      } else if (cuotaMenor === true && obligacionMarcadas === true) {
        console.log('hola2');
        this.visadoValido = 'FACTIBLE';
        causal = 'Sin causal';
      }

      if (cuotaMayor === true && embargosSinMora === true) {
        console.log('hola3');
        this.visadoValido = 'NO FACTIBLE';
        causal += 'Negado por cupo';
      } else if (cuotaMenor === true && embargosSinMora === true) {
        console.log('hola4');
        this.visadoValido = 'FACTIBLE';
        causal = 'Sin causal';
      } else {
        if (cuotaMayor === true && obligacionMarcadas === false && embargosSinMora === false) {
          console.log('hola5');
          this.visadoValido = 'NO FACTIBLE';
          causal = '1. Presenta obligaciones en mora, 2. Negado por cupo';
        } else if (cuotaMayor === true && obligacionMarcadas === true) {
          console.log('hola6');
          this.visadoValido = 'NO FACTIBLE';
          causal = 'Negado por cupo';
        }
      }

      if (definitivaAlerta) {
        console.log('hola7');
        this.visadoValido = 'NO FACTIBLE';
        causal = 'Cliente en proceso de retiro';
      }

      const data = {
        estado: this.visadoValido,
        cuotacredito: this.cuotadeseada,
        monto: this.monto,
        causal: causal
      };

      axios
        .post(`visados/${this.visado.id}`, data)
        .then(response => {
          console.log('response', response);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
<style>
.table-text {
  font-size: 12px;
}

.tables-space {
  margin-top: 15px !important;
}
</style>
