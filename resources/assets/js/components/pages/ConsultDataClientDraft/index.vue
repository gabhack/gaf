<template>
  <div class="container-fluid">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="true" color="#0CEDB0" />

    <b-toast id="toast-incapacidad-month" title="Alerta del Sistema" solid auto-hide-delay="10000" variant="info">
      Cliente con incapacidad mayor a 2 meses.
    </b-toast>

    <b-toast id="toast-incapacidad" title="Alerta del Sistema" solid auto-hide-delay="10000" variant="info">
      Cliente no apto por Incapacidad.
    </b-toast>

    <div v-if="type_consult === 'individual'">
      <div class="row mb-5">
        <div class="col-12 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-end" v-if="datames">
            <img src="/img/avatar-img.svg" width="90" class="mr-3" />
            <div>
              <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{ datames.nomp }}</h2>
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
        <DatamesComponent v-if="pagaduriaType == 'FOPEP' && datames" :user="user" :datames="datames" />

        <!--============================
            FIDUPREVISORA datamesfidu
        ==============================-->
        <DatamesFiduComponent
          v-if="pagaduriaType == 'FIDUPREVISORA' && datamesfidu"
          :user="user"
          :datamesfidu="datamesfidu"
        />

        <!--============================
          DATAMESSEDUC FODE VALLE
      ==============================-->
        <DatamesSeducComponent
          v-if="pagaduriaType == 'FODE VALLE' && datamesseceduc"
          :user="user"
          :datamesseceduc="datamesseceduc"
        />

        <!--================================
         SECCALI datamesseccali
        ===================================-->
        <DatamesCali
          v-if="pagaduriaType == 'SECCALI' && datamesseccali"
          :user="user"
          :datamesseccali="datamesseccali"
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
            pagaduriaType == 'SEDBARRANQUILLA' ||
            pagaduriaType == 'SEDATLANTICO' ||
            pagaduriaType == 'SEDNARINO'
          "
        />

        <DatamesSedMagdalena v-if="pagaduriaType == 'SEDMAGDALENA'" />

        <!--============================
          COMPONENTE HISTORIAL LABORAL
        ==============================-->
        <template v-if="fechavinc">
          <EmploymentHistory
            :fechavinc="fechavinc"
            :datames="datames"
            :datamesseceduc="datamesseceduc"
            :datamesfidu="datamesfidu"
            :datamesseccali="datamesseccali"
            :user="user"
          />
          <Detallecliente :descuentossedcauca="descuentossedcauca" :totales="totales" />
        </template>

        <template v-if="showOthers">
          <DescapliEmpty
            v-if="
              pagaduriaType == 'FIDUPREVISORA' ||
              pagaduriaType == 'FODE VALLE' ||
              pagaduriaType == 'SEDCAUCA' ||
              pagaduriaType == 'SEDCHOCO' ||
              pagaduriaType == 'SEDQUIBDO' ||
              pagaduriaType == 'SECCALI' ||
              pagaduriaType == 'SEDMAGDALENA' ||
              pagaduriaType == 'SEDBARRANQUILLA' ||
              pagaduriaType == 'SEDATLANTICO' ||
              pagaduriaType == 'SEDBOLIVAR' ||
              pagaduriaType == 'SEDPOPAYAN'
            "
          />
          <Descapli v-if="pagaduriaType == 'FOPEP'" :descapli="descapli" />

          <!--===================================
                OBLIGACIONES VIGENTES EN MORA
          ========================================-->
          <DescnoapEmpty v-if="pagaduriaType == 'FIDUPREVISORA'" />
          <EmbargosSeccali v-else-if="pagaduriaType == 'SECCALI'" :embargosseccali="embargosseccali" />
          <Descnoap v-if="pagaduriaType == 'FOPEP'" :descnoap="descnoap" />
          <EmbargosSeceduc v-if="pagaduriaType == 'FODE VALLE'" :embargosseceduc="embargosseceduc" />
          <EmbargosSedchoco v-if="pagaduriaType == 'SEDCHOCO'" :embargossedchoco="embargossedchoco" />
          <EmbargosSedcauca v-if="pagaduriaType == 'SEDCAUCA'" :embargossedcauca="embargossedcauca" />
          <EmbargosSedquibdo v-if="pagaduriaType == 'SEDQUIBDO'" :embargossedquibdo="embargossedquibdo" />
          <EmbargosSedpopayan v-if="pagaduriaType == 'SEDPOPAYAN'" :embargossedpopayan="embargossedpopayan" />
          <EmbargosEmpty
            v-if="
              pagaduriaType == 'SEDMAGDALENA' ||
              pagaduriaType == 'SEDBARRANQUILLA' ||
              pagaduriaType == 'SEDATLANTICO' ||
              pagaduriaType == 'SEDBOLIVAR' ||
              pagaduriaType == 'SEDNARINO'
            "
            :embargosempty="embargosempty"
          />

          <Descuentossecedu v-if="pagaduriaType == 'FODE VALLE'" :descuentossecedu="descuentosseceduc" />
          <Descuentossedchoco v-if="pagaduriaType == 'SEDCHOCO'" :descuentossedchoco="descuentossedchoco" />
          <Descuentossedcauca v-if="pagaduriaType == 'SEDCAUCA'" :descuentossedcauca="descuentossedcauca" />
          <Descuentosseccali v-if="pagaduriaType == 'SECCALI'" :descuentosseccali="descuentosseccali" />
          <Descuentossedquibdo v-if="pagaduriaType == 'SEDQUIBDO'" :descuentossedquibdo="descuentossedquibdo" />
          <Descuentossedpopayan v-if="pagaduriaType == 'SEDPOPAYAN'" :descuentossedpopayan="descuentossedpopayan" />
          <DescuentosEmpty
            v-if="
              pagaduriaType == 'SEDMAGDALENA' ||
              pagaduriaType == 'SEDBARRANQUILLA' ||
              pagaduriaType == 'SEDATLANTICO' ||
              pagaduriaType == 'SEDBOLIVAR' ||
              pagaduriaType == 'SEDNARINO'
            "
            :descuentosempty="descuentosempty"
          />
        </template>

        <Others
          v-if="showOthers && pagadurias"
          :pagaduriaType="pagaduriaType"
          :pagadurias="pagadurias"
          :fechavinc="fechavinc"
          :descapli="descapli"
          :descnoap="descnoap"
          :embargosseceduc="embargosseceduc"
          :user="user"
        />
      </div>
    </div>
  </div>
</template>
<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css"/>
<script>
import printJS from 'print-js';
import FormConsult from './FormConsult';
import EmploymentHistory from './EmploymentHistory';
import DatamesComponent from './Datames';

import DatamesData from './DatamesData';
import DatamesSedMagdalena from './DatamesSedMagdalena';

import DatamesSeducComponent from './DatamesSeduc';
import DatamesFiduComponent from './DatamesFidu';
import DatamesCali from './DatamesCali';
import Descapli from './Descapli';
import DescapliEmpty from './DescapliEmpty';
import Descnoap from './Descnoap';
import DescnoapEmpty from './DescnoapEmpty';
import Others from './Others';
import EmbargosSeceduc from './EmbargosSeceduc';
import EmbargosSedchoco from './EmbargosSedchoco';
import EmbargosSedquibdo from './EmbargosSedquibdo';
import EmbargosSedcauca from './EmbargosSedcauca';
import EmbargosSedpopayan from './EmbargosSedpopayan';
import EmbargosSeccali from './EmbargosSeccali';
import EmbargosEmpty from './EmbargosEmpty';
import Descuentossecedu from './Descuentossecedu';
import DescuentosEmpty from './DescuentosEmpty';
import Descuentossedchoco from './Descuentossedchoco';
import Descuentossedcauca from './Descuentossedcauca';
import Detallecliente from './Detallecliente';
import Descuentosseccali from './Descuentosseccali';
import Descuentossedquibdo from './Descuentossedquibdo';
import Descuentossedpopayan from './Descuentossedpopayan';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

import { mapState, mapMutations, mapGetters } from 'vuex';

export default {
  props: ['user'],
  created() {},
  components: {
    FormConsult,
    EmploymentHistory,
    DatamesComponent,
    DatamesData,
    DatamesSedMagdalena,
    DatamesSeducComponent,
    DatamesFiduComponent,
    DatamesCali,
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
    EmbargosSeccali,
    Descuentossecedu,
    Descuentossedchoco,
    Descuentossedcauca,
    Detallecliente,
    Descuentosseccali,
    Descuentossedquibdo,
    Descuentossedpopayan,
    DescuentosEmpty,
    EmbargosEmpty,
    Loading
  },

  data() {
    return {
      type_consult: 'individual',
      fechavinc: null,
      datames: null,
      datamesseceduc: null,
      datamesfidu: null,
      datamesseccali: null,
      descapli: [],
      descnoap: [],
      embargosseceduc: [],
      embargossedchoco: [],
      embargossedcauca: [],
      embargossedquibdo: [],
      embargossedpopayan: [],
      embargosseccali: [],
      embargosempty: [],
      descuentosseceduc: [],
      descuentosempty: [],
      descuentossedchoco: [],
      descuentossedcauca: [],
      descuentosseccali: [],
      descuentossedquibdo: [],
      descuentossedpopayan: [],

      pagaduriaKey: '',
      cargo: null,
      showOthers: false,
      pagadurias: null,
      isLoading: false
    };
  },
  computed: {
    ...mapState('pagaduriasModule', ['coupons', 'pagaduriaType']),
    ...mapGetters('pagaduriasModule', [
      'couponsPerPeriod',
      'valorIngreso',
      'ingresosIncapacidadPerPeriod',
      'incapacidadValida',
      'couponsIngresos'
    ]),
    totales() {
      const valrSM = 1000000;

      let totalWithoutHealthPension = 0
      this.couponsIngresos.items.forEach(item => {
        if(item.code !== 'APFPM' && item.code !== 'APFSM'){
          totalWithoutHealthPension += Number(item.vaplicado)
        } 
      })

      let valorIngreso = 0;
      if (this.pagaduriaType === 'FOPEP') {
        valorIngreso = Number(this.datames.vpension.replace(/[^0-9]/g, '').slice(0, -2));
      } else if (this.pagaduriaType == 'FIDUPREVISORA') {
        valorIngreso = Number(this.datamesfidu.vpension.replace(/[^0-9]/g, '').slice(0, -2));
      } else if (this.pagaduriaType === 'SEDNARINO') {
        valorIngreso = Number(this.pagadurias.datamesSedNarino.vingreso.replace(/[^0-9]/g, '').slice(0));
      } else {
        valorIngreso = this.couponsPerPeriod.items.filter(item => item.code === 'INGCUP')[0]?.ingresos || 0;
      }

      let increase = 0;
      if (this.cargo == 'RECTOR') {
        increase = valorIngreso * 0.3;
        valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
      } else if (this.cargo == 'CORDINADOR') {
        increase = valorIngreso * 0.2;
        valorIngreso = parseFloat(valorIngreso) + parseFloat(increase);
      } else if (this.cargo == 'DIRECTOR DE NUCLEO') {
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
        this.pagaduriaType === 'SEDBARRANQUILLA' ||
        this.pagaduriaType === 'SEDMAGDALENA' ||
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
        this.pagaduriaType === 'SEDBARRANQUILLA' ||
        this.pagaduriaType === 'SEDMAGDALENA' ||
        this.pagaduriaType === 'SEDBOLIVAR' ||
        this.pagaduriaType === 'SEDNARINO'
      ) {
        totalEmbargos = 0;
      } else {
        totalEmbargos = this.pagaduriaKey ? this[`embargos${this.pagaduriaKey}`].length : 0;
      }

      let previousDiscount = valorIngresoTemp / 2

      let libreInversion = 0
      if(previousDiscount < valrSM) {
        libreInversion = valorIngresoTemp - valrSM - totalWithoutHealthPension 
      } else {
        libreInversion = valorIngresoTemp / 2 - totalWithoutHealthPension
      }

      let compraCartera = 0
      if(previousDiscount < valrSM) {
        compraCartera = valorIngresoTemp - valrSM
      } else {
        compraCartera = valorIngresoTemp / 2
      }

      let cuotaMaxima = 0
      if(previousDiscount < valrSM) {
        cuotaMaxima = valorIngresoTemp - valrSM 
      } else {
        cuotaMaxima = valorIngresoTemp / 2
      }

      return {
        descuentos: totalDescuentos,
        embargos: totalEmbargos,
        libreInversion: libreInversion < 0 ? 0 : libreInversion,
        compraCartera: compraCartera < 0 ? 0 : compraCartera,
        cuotaMaxima: cuotaMaxima < 0 ? 0 : cuotaMaxima,
      }
    }
  },
  methods: {
    ...mapMutations('pagaduriasModule', ['setCoupons']),
    emitInfo(payload) {
      this.isLoading = true;
      this.pagadurias = payload.pagadurias;
      this.pagaduriaKey = payload.pagaduriaKey;
      this.cargo = payload.cargo;

      this.datames = null;
      this.datamesseceduc = null;
      this.datamesfidu = null;
      this.datamesseccali = null;

      if (payload.pagaduria == 'FOPEP') {
        this.getDatames(payload);
      } else if (payload.pagaduria == 'FODE VALLE') {
        this.getDatamesseceduc(payload);
        this.getDescuentossecedu(payload);
      } else if (payload.pagaduria == 'FIDUPREVISORA') {
        this.getDatamesfidu(payload);
      } else if (payload.pagaduria == 'SECCALI') {
        this.getDatamesseccali(payload);
      }

      this.getEmbargosseceduc(payload);
      this.getEmbargossedchoco(payload);
      this.getEmbargossedquibdo(payload);
      this.getEmbargossedpopayan(payload);
      this.getEmbargossedcauca(payload);
      this.getEmbargosseccali(payload);
      this.getDescuentossecedu(payload);
      this.getDescuentossedchoco(payload);
      this.getDescuentossedcauca(payload);
      this.getDescuentosseccali(payload);
      this.getDescuentossedquibdo(payload);
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
      const response = await axios.get(`datames/${payload.doc}`);
      this.datames = response.data;
    },
    async getDatamesseceduc(payload) {
      const response = await axios.post('/datamesseceduc/consultaUnitaria', { doc: payload.doc });
      this.datamesseceduc = response.data.data;
    },
    async getDatamesfidu(payload) {
      const response = await axios.post('/datamesfidu/consultaUnitaria', { doc: payload.doc });
      this.datamesfidu = response.data.data;
    },
    async getDatamesseccali(payload) {
      const response = await axios.post('/consultaDatamesseccali', { doc: payload.doc });
      this.datamesseccali = response.data.data;
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
      this.embargosseceduc = response.data.data;
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
    async getEmbargosseccali(payload) {
      const response = await axios.post('/consultaEmbargosseccali', { doc: payload.doc });
      this.embargosseccali = response.data.data;
    },
    async getDescuentossecedu(payload) {
      const response = await axios.post('/consultaDescuentosseceduc', { doc: payload.doc });
      this.descuentosseceduc = response.data.data;
    },
    async getDescuentossedchoco(payload) {
      const response = await axios.post('/consultaDescuentossedchoco', { doc: payload.doc });
      this.descuentossedchoco = response.data.data;
    },
    async getDescuentossedcauca(payload) {
      const response = await axios.post('/consultaDescuentossedcauca', { doc: payload.doc });
      this.descuentossedcauca = response.data.data;
    },
    async getDescuentosseccali(payload) {
      const response = await axios.post('/consultaDescuentosseccali', { doc: payload.doc });
      this.descuentosseccali = response.data.data;
    },
    async getDescuentossedquibdo(payload) {
      const response = await axios.post('/consultaDescuentossedquibdo', { doc: payload.doc });
      this.descuentossedquibdo = response.data.data;
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
      this.setCoupons(response.data);

      setTimeout(() => {
        // Valida si el tiene incapacidades
        if (this.incapacidadValida === false) {
          this.$bvToast.show('toast-incapacidad-month');
        }

        // Valida si el valor de la incapacidad es mayor al valor del ingreso
        if (this.ingresosIncapacidadPerPeriod.amount >= Number(this.valorIngreso)) {
          this.$bvToast.show('toast-incapacidad');
        }
      }, 1000);
    },
    print() {
      window.print();
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
