<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OTROS POSIBLES INGRESOS Y DEDUCCIONES</b></div>
      <div class="panel-body">
        <!--============================
           FOPE datames
        ==============================-->
        <div class="row" v-if="pagaduriaType === 'FOPEP'">
          <div class="col-12">
            <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
            <div v-if="datamesseceduc">
              <p class="panel-value">{{ datamesseceduc.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="dataPlusFode = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="dataPlusFode = false">Cerrar</button>
          </div>
          <div class="col-12 tables-space">
            <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
            <div v-if="datamesseceduc">
              <p class="panel-value">{{ datamesseceduc.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="dataPlusFidu = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="dataPlusFidu = false">Cerrar</button>
            <!-- Mostrando respuesta nueva en btn Ver mas FIDU -->
          </div>
        </div>
        <!--============================
           FIDUPREVISORA
        ==============================-->
        <div class="row" v-if="pagaduriaType === 'FIDUPREVISORA'">
          <div class="col-12">
            <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
            <div v-if="datamesseceduc">
              <p class="panel-value">{{ datamesseceduc.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="dataPlusFode = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="dataPlusFode = false">Cerrar</button>
          </div>
          <div class="col-12 tables-space">
            <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
            <div v-if="datames">
              <p class="panel-value">{{ datames.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="dataPlusFopep = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="dataPlusFopep = false">Cerrar</button>
            <!-- Mostrando respuesta nueva en btn Ver mas FOPEP -->
          </div>
        </div>

        <!--============================
           FODEVALLE
        ==============================-->
        <div class="row" v-if="pagaduriaType === 'FODE VALLE'">
          <div class="col-12">
            <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
            <div v-if="datames">
              <p class="panel-value">{{ datames.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="dataPlusFopep = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="dataPlusFopep = false">Cerrar</button>
          </div>
          <div class="col-12 tables-space">
            <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
            <div v-if="datamesseceduc">
              <p class="panel-value">{{ datamesseceduc.vpension | currency }}</p>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="dataPlusFidu = true">Ver mas</button>
            <button type="button" class="btn btn-secondary" v-on:click="dataPlusFidu = false">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Others",
  props: ['pagaduriaType', 'datames', 'datamesseceduc', 'datamesfidu', 'fechavinc', 'descapli', 'descnoap'],
  created() {
    alert(this.pagaduriaType)
  },
  data() {
    return {
      dataPlusFode: false,
      dataPlusFopep: false,
      dataPlusFidu: false,
    }
  }
}
</script>

<style scoped>

</style>
