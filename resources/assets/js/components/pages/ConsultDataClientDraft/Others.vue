<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OTROS POSIBLES INGRESOS Y DEDUCCIONES</b></div>
      <div class="panel-body">

        <div class="row">


          <!--============================
             DATAMES COMPONENTE -
          ==============================-->
          <div class="col-12 tables-space mb-4" v-if="pagadurias.datames && pagaduriaType != 'FOPEP'">
            <b class="panel-label">OTRO POSIBLE INGRESO: 1</b>
            <div>
              <p class="panel-value">{{ pagadurias.datames.vpension | currency }}</p>
            </div>

            <button type="button" class="btn btn-primary" v-on:click="showDatames = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="showDatames = false">Cerrar</button>
          </div>
          <template v-if="showDatames">
            <Datames :datames="pagadurias.datames" :user="user"/>
            <EmploymentHistory :user="user" :fechavinc="fechavinc" :datames="pagadurias.datames"/>
            <Descapli :descapli="descapli"/>
            <Descnoap :descnoap="descnoap"/>
          </template>


          <!--============================
                  DATAMES FIDU
          ==============================-->

          <div class="col-12 tables-space mb-4" v-if="pagadurias.datamesfidu && pagaduriaType != 'FIDUPREVISORA'">
            <b class="panel-label">OTRO POSIBLE INGRESO: 2</b>
            <div>
              <p class="panel-value">{{ pagadurias.datamesfidu.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="showDatamesFidu = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="showDatamesFidu = false">Cerrar</button>
          </div>

          <template v-if="showDatamesFidu">
            <DatamesFidu v-if="showDatamesFidu" :datamesfidu="pagadurias.datamesfidu" :user="user"/>
            <EmploymentHistory :user="user" :fechavinc="fechavinc" :datamesfidu="pagadurias.datamesfidu"/>
            <Descapli :descapli="descapli"/>
            <Descnoap :descnoap="descnoap"/>
          </template>


          <!--============================
              DATAMESSEDUC COMPONENTE
          ==============================-->

          <div class="col-12 tables-space  mb-4" v-if="pagadurias.datamesseceduc && pagaduriaType != 'FODE VALLE'">
            <b class="panel-label">OTRO POSIBLE INGRESO: 3</b>
            <div>
              <p class="panel-value">{{ pagadurias.datamesseceduc.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="showDatamesseceduc = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="showDatamesseceduc = false">Cerrar</button>
          </div>

          <template v-if="showDatamesseceduc">
            <DatamesSeduc :datamesseceduc="pagadurias.datamesseceduc" :user="user"/>
            <EmploymentHistory :user="user" :fechavinc="fechavinc" :datamesseceduc="pagadurias.datamesseceduc"/>
            <Descapli :descapli="descapli"/>
            <Descnoap :descnoap="descnoap"/>
          </template>


          <!--============================
               DATAMESCALISECCALI
          ==============================-->

          <div class="col-12 tables-space mb-4" v-if="pagadurias.datamesseccali && pagaduriaType != 'SECCALI'">
            <b class="panel-label">OTRO POSIBLE INGRESO: 4</b>
            <div>
              <p class="panel-value">{{ pagadurias.datamesseccali.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="showDatamesseccali = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="showDatamesseccali = false">Cerrar</button>
          </div>

          <template v-if="showDatamesseccali">
            <DatamesCali :datamesseccali="pagadurias.datamesseccali" :user="user"/>
            <EmploymentHistory :user="user" :fechavinc="fechavinc" :datamesseceduc="pagadurias.datamesseccali"/>
            <Descapli :descapli="descapli"/>
            <Descnoap :descnoap="descnoap"/>
          </template>
        </div>

      </div>
    </div>



  </div>
</template>

<script>
import DatamesFidu from "./Others/DatamesFidu";
import DatamesCali from "./Others/DatamesCali";
import DatamesSeduc from "./Others/DatamesSeduc";
import Datames from "./Others/Datames";
import EmploymentHistory from './EmploymentHistory/Index'
import Descapli from "./Descapli";
import Descnoap from "./Descnoap";

export default {
  name: "Others",
  props: ['pagaduriaType', 'pagadurias', 'fechavinc', 'descapli', 'descnoap', 'user'],
  components: {
    Datames,
    DatamesFidu,
    DatamesCali,
    DatamesSeduc,
    EmploymentHistory,
    Descapli,
    Descnoap
  },
  created() {
    console.log(this.pagaduriaType);
    console.log(this.pagadurias);
    // if (this.pagaduriaType == 'FOPEP') {
    //   this.pagadurias.datames = null;
    // } else if (this.pagaduriaType == 'FIDUPREVISORA') {
    //   this.pagadurias.datamesfidu = null;
    // } else if (this.pagaduriaType == 'FODE VALLE') {
    //   this.pagadurias.datamesseceduc = null;
    // } else if (this.pagaduriaType == 'SECCALI') {
    //   this.pagadurias.datamesseccali = null;
    // }
  },
  data() {
    return {
      showDatames: false,
      showDatamesFidu: false,
      showDatamesseceduc: false,
      showDatamesseccali: false,
    }
  }
}
</script>

<style scoped>

</style>
