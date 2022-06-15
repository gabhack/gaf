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
          DATAMES FOPEP -
        ==============================-->
        <DatamesComponent v-if="pagaduriaType == 'FOPEP' && datames"
                          :user="user"
                          :datames="datames"
        />


        <!--============================
            FIDUPREVISORA datamesfidu
        ==============================-->
        <DatamesFiduComponent v-if="pagaduriaType == 'FIDUPREVISORA' && datamesfidu"
                              :user="user"
                              :datamesfidu="datamesfidu"
        />


        <!--============================
          DATAMESSEDUC FODE VALLE
      ==============================-->
        <DatamesSeducComponent v-if="pagaduriaType == 'FODE VALLE' && datamesseceduc"
                               :user="user"
                               :datamesseceduc="datamesseceduc"
        />


        <!--================================
         SECCALI datamesseccali
        ===================================-->
        <DatamesCali v-if="pagaduriaType == 'SECCALI' && datamesseccali"
                     :user="user"
                     :datamesseccali="datamesseccali"

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
              :datamesseccali="datamesseccali"
              :user="user"
          />
        </template>


        <template v-if="showOthers">

          <DescapliEmpty v-if="pagaduriaType == 'FIDUPREVISORA' || pagaduriaType == 'FODE VALLE'"/>
          <Descapli v-else :descapli="descapli"/>


          <!--===================================
                OBLIGACIONES VIGENTES EN MORA
          ========================================-->
          <DescnoapEmpty v-if="pagaduriaType == 'FIDUPREVISORA'"/>
          <EmbargosSeceduc
              v-else-if="pagaduriaType == 'FODE VALLE'"
              :embargosseceduc="embargosseceduc"
          />
          <Descnoap v-else :descnoap="descnoap"/>

        </template>


        <Others
            v-if="showOthers && pagadurias"
            :pagaduriaType="pagaduriaType"
            :pagadurias="pagadurias"
            :fechavinc="fechavinc"
            :descapli="descapli"
            :descnoap="descnoap"
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
import FormConsult from "./FormConsult";
import EmploymentHistory from "./EmploymentHistory/Index";
import DatamesComponent from "./Datames";
import DatamesSeducComponent from "./DatamesSeduc";
import DatamesFiduComponent from "./DatamesFidu";
import DatamesCali from "./DatamesCali";
import Descapli from "./Descapli";
import DescapliEmpty from "./DescapliEmpty";
import Descnoap from "./Descnoap";
import DescnoapEmpty from "./DescnoapEmpty";
import Others from "./Others";
import EmbargosSeceduc from "./EmbargosSeceduc";

export default {
  props: ['user'],
  components: {
    FormConsult,
    EmploymentHistory,
    DatamesComponent,
    DatamesSeducComponent,
    DatamesFiduComponent,
    DatamesCali,
    Descapli,
    DescapliEmpty,
    Descnoap,
    DescnoapEmpty,
    Others,
    EmbargosSeceduc
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
      datamesseccali: null,
      descapli: [],
      descnoap: [],
      embargosseceduc: [],
      mensajedeliquidacionseceduc: [],

      pagaduriaType: '',
      showOthers: false,
      pagadurias: null,

    };
  },
  computed: {},
  methods: {
    emitInfo(payload) {
      this.pagadurias = payload.pagadurias;
      this.pagaduriaType = payload.pagaduria;
      if (payload.pagaduria == "FOPEP") {
        this.getDatames(payload);
      } else if (payload.pagaduria == 'FODE VALLE') {
        this.getDatamesseceduc(payload);
        this.getEmbargosseceduc(payload);
        this.getMensajedeliquidacionseceduc(payload);
      } else if (payload.pagaduria == 'FIDUPREVISORA') {
        this.getDatamesfidu(payload);
      } else if (payload.pagaduria == 'SECCALI') {
        this.getDatamesseccali(payload);
      }

      this.getDescapli(payload);
      this.getDescnoap(payload);
      this.getFechaVinc(payload).then(response => {
        this.showOthers = true;
      });
    },
    async getDatames(payload) {
      const response = await axios.get(`datames/${payload.doc}`);
      this.datames = response.data;
      this.datamesseceduc = null;
      this.datamesfidu = null;
      this.datamesseccali = null;
    },
    async getDatamesseceduc(payload) {
      const response = await axios.post('/datamesseceduc/consultaUnitaria', {doc: payload.doc});
      this.datamesseceduc = response.data.data;
      this.datames = null;
      this.datamesfidu = null;
      this.datamesseccali = null;
    },
    async getDatamesfidu(payload) {
      const response = await axios.post('/datamesfidu/consultaUnitaria', {doc: payload.doc});
      this.datamesfidu = response.data.data;
      this.datames = null;
      this.datamesseceduc = null;
      this.datamesseccali = null;
    },
    async getDatamesseccali(payload) {
      const response = await axios.post('/consultaDatamesseccali', {doc: payload.doc});
      this.datamesseccali = response.data.data;
      this.datames = null;
      this.datamesseceduc = null;
      this.datamesfidu = null;
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
      const response = await axios.post('/consultaEmbargosseceduc', {doc: payload.doc});
      console.log(response.data.data);
      this.embargosseceduc = response.data.data;
    },
    async getMensajedeliquidacionseceduc(payload) {
      const response = await axios.post('/consultaMensajedeliquidacionseceduc', {doc: payload.doc});
      this.mensajedeliquidacionseceduc = response.data.data;
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
