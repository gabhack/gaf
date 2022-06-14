<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-2">
            <b class="panel-label table-text">TIPO ENTIDAD:</b>
            <div v-for="(descapli, key) in descapli" :key="key">
              <p class="panel-value">{{ descapli.clase }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
            <div v-for="(descapli, key) in descapli" :key="key">
              <p class="panel-value">{{ descapli.nomtercero }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO PAGARE:</b>
            <div v-for="(descapli, key) in descapli" :key="key">
              <p class="panel-value">{{ descapli.pagare }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">CUOTA:</b>
            <div v-for="(descapli, key) in descapli" :key="key">
              <p class="panel-value">{{ descapli.vaplicado | currency }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <div v-for="(descapli, key) in descapli" :key="key">
              <p class="panel-value">{{ descapli.fgrab }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
            <div v-for="(descapli, key) in descapli" :key="key">
              <p class="panel-value">{{ descapli.nonentant }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Descapli",
  props: ['descapli']
}
</script>

<style scoped>

</style>
