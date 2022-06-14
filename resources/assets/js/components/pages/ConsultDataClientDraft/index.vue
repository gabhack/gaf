<template>
  <div class="container-fluid">
    <div v-if="type_consult === 'individual'">

      <div class="row mb-5" v-if="datames">
        <div class="col-12 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-end">
            <img src="/img/avatar-img.svg" width="90" class="mr-3"/>
            <div>
              <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{ datames.nomp }}</h2>
            </div>
          </div>
          <!--          <button type="button" v-on:click="print" class="btn btn-black-pearl px-3">-->
          <!--            <span>Descargar PDF</span>-->
          <!--            <download-icon></download-icon>-->
          <!--          </button>-->
        </div>
      </div>

      <div id="consulta-container" class="row">


        <FormConsult @emitInfo="emitInfo"></FormConsult>


        <!--============================
          DATAMES COMPONENTE -
        ==============================-->
        <DatamesComponent v-if="pagaduriaType == 'FOPEP' && datames"
                          :user="user"
                          :datames="datames"
        />

        <!--============================
            DATAMESSEDUC COMPONENTE
        ==============================-->
        <DatamesSeducComponent v-if="pagaduriaType == 'FODE VALLE' && datamesseceduc"
                               :user="user"
                               :datamesseceduc="datamesseceduc"
        />

        <!--============================

        ==============================-->
        <DatamesFiduComponent v-if="pagaduriaType == 'FIDUPREVISORA' && datamesfidu"
                              :user="user"
                              :datamesfidu="datamesfidu"
        />

        <!--============================
          COMPONENTE HISTORIAL LABORAL
        ==============================-->
        <template v-if="fechavinc">
          <EmploymentHistory
              :fechavinc="fechavinc"
              :pagaduriaType="pagaduriaType"
              :datames="datames"
              :datamesseceduc="datamesseceduc"
              :datamesfidu="datamesfidu"
              :user="user"
          />
        </template>


        <Descapli
            v-if="descapli.length"
            :descapli="descapli"
        />


        <Descnoap
            v-if="descnoap.length"
            :descnoap="descnoap"
        />

        <Others
            v-if="showOthers"
            :pagaduriaType="pagaduriaType"
            :datames="datames"
            :datamesseceduc="datamesseceduc"
            :datamesfidu="datamesfidu"
            :fechavinc="fechavinc"
            :descapli="descapli"
            :descnoap="descnoap"
        />

      </div>
    </div>
  </div>
</template>
<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css"/>
<script>
import printJS from 'print-js';
import FormConsult from "./FormConsult";
import EmploymentHistory from "./EmploymentHistory/Index";
import DatamesComponent from "./Datames";
import DatamesSeducComponent from "./DatamesSeduc";
import DatamesFiduComponent from "./DatamesFidu";
import Descapli from "./Descapli";
import Descnoap from "./Descnoap";
import Others from "./Others";

export default {
  props: ['user'],
  components: {
    FormConsult,
    EmploymentHistory,
    DatamesComponent,
    DatamesSeducComponent,
    DatamesFiduComponent,
    Descapli,
    Descnoap,
    Others
  },
  created() {
  },
  data() {
    return {
      type_consult: 'individual',
      fechavinc: null,
      datames: null,
      datamesseceduc: null,
      datamesfidu: null,
      descapli: [],
      descnoap: [],
      pagaduriaType: '',
      showOthers: false,

    };
  },
  computed: {},
  methods: {
    emitInfo(payload) {
      this.pagaduriaType = payload.pagaduria;
      if (payload.pagaduria == "FOPEP") {
        this.getDatames(payload);
      } else if (payload.pagaduria == 'FODE VALLE') {
        this.getDatamesseceduc(payload);
      } else if (payload.pagaduria == 'FIDUPREVISORA') {
        this.getDatamesfidu(payload);
      }
      this.getFechaVinc(payload);
      this.getDescapli(payload);
      this.getDescnoap(payload).then(response =>{
        this.showOthers = true;
      });

    },
    async getDatames(payload) {
      const response = await axios.get(`datames/${payload.doc}`);
      this.datames = response.data;
      this.datamesseceduc = null;
      this.datamesfidu = null;
    },
    async getDatamesseceduc(payload) {
      const response = await axios.post('/datamesseceduc/consultaUnitaria', {doc: payload.doc});
      console.log(response.data)
      this.datamesseceduc = response.data.data;
      this.datames = null;
      this.datamesfidu = null;
    },
    async getDatamesfidu(payload) {
      const response = await axios.post('/datamesfidu/consultaUnitaria', {doc: payload.doc});
      this.datamesfidu = response.data.data;
      this.datames = null;
      this.datamesseceduc = null;
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
