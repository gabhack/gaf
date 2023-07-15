<template>
  <div class="container-fluid">
    <div>
      <div class="row mb-5">
        <div class="col-12 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-end">
            <img src="/img/avatar-img.svg" width="90" class="mr-3" />
            <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{ detalleConsulta.nombre }}</h2>
          </div>
          <button type="button" v-on:click="print" class="btn btn-black-pearl px-3">
            <span>Descargar PDF</span>
            <download-icon></download-icon>
          </button>
        </div>
      </div>

      <div id="consulta-container" class="row">
        <Datames v-if="pagaduriaType == 'FOPEP' && datames" :user="user" :datames="datames" />

        <!--============================
          FIDUPREVISORA datamesfidu
        ==============================-->
        <DatamesFiduComponent
          v-if="pagaduriaType == 'FIDUPREVISORA' && datamesfidu"
          :user="user"
          :datamesfidu="datamesfidu"
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

        <template v-if="fechavinc">
          <EmploymentHistory
            :fechavinc="fechavinc"
            :pagaduriaType="pagaduriaType"
            :datames="datames"
            :datamesseceduc="datamesseceduc"
            :datamesfidu="datamesfidu"
            :datamessemcali="datamessemcali"
            :user="user"
          />
        </template>

        <DescapliEmpty v-if="pagaduriaType == 'FIDUPREVISORA' || pagaduriaType == 'SEDVALLE'" />
        <Descapli v-else :descapli="descapli" />

        <DescnoapEmpty v-if="pagaduriaType == 'FIDUPREVISORA'" />
        <EmbargosSeceduc v-else-if="pagaduriaType == 'SEDVALLE'" :embargosseceduc="embargosseceduc" />
        <Descnoap v-else :descnoap="descnoap" />
      </div>
    </div>
  </div>
</template>

<script>
import Datames from '../ConsultDataClientDraft/Datames';
import DatamesFiduComponent from '../ConsultDataClientDraft/DatamesFidu';
import DatamesSeducComponent from '../ConsultDataClientDraft/DatamesSeduc';
import DatamesSemCali from '../ConsultDataClientDraft/DatamesSemCali.vue';
import EmploymentHistory from './EmploymentHistory';
import Descapli from '../ConsultDataClientDraft/Descapli';
import DescapliEmpty from '../ConsultDataClientDraft/DescapliEmpty';
import Descnoap from '../ConsultDataClientDraft/Descnoap';
import DescnoapEmpty from '../ConsultDataClientDraft/DescnoapEmpty';
import EmbargosSeceduc from '../ConsultDataClientDraft/EmbargosSeceduc';

export default {
  props: ['id', 'user', 'pagaduriaType'],
  components: {
    Datames,
    DatamesFiduComponent,
    DatamesSeducComponent,
    DatamesSemCali,
    EmploymentHistory,
    DescapliEmpty,
    Descapli,
    DescnoapEmpty,
    EmbargosSeceduc,
    Descnoap
  },
  name: 'detailhistory',
  data() {
    return {
      datames: null,
      datamesfidu: null,
      datamesseceduc: null,
      datamessemcali: null,
      embargosedu: null,
      detalleConsulta: {},
      fechavinc: null,
      obligacionSelected: null,
      descnoap: null,
      descapli: null
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      axios.post('detalleConsulta', { id: this.id }).then(result => {
        console.log(result);
        console.log(result.data.data);
        this.detalleConsulta = result.data.data.detalle_consulta;
        this.datames = result.data.data.info_datames;
        this.fechavinc = result.data.data.info_fechavinc;
        this.obligacionSelected = result.data.data.info_obligaciones;
        this.datamesfidu = result.data.data.datamesfidu;
        this.datamesseceduc = result.data.data.datamesseceduc;
        this.datamessemcali = result.data.data.datamessemcali;
        this.embargosedu = result.data.data.embargosedu;
        this.descnoap = result.data.data.descnoap;
        this.descapli = result.data.data.descapli;
      });
    },
    print() {
      window.print();
    }
  }
};
</script>

<style scoped></style>
