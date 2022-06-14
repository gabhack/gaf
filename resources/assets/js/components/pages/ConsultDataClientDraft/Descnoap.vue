<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
            <div v-for="(descnoap, key) in descnoap" :key="key">
              <p class="panel-value">{{ descnoap.nomtercero }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
            <div v-for="(descnoap, key) in descnoap" :key="key">
              <p class="panel-value">{{ descnoap.pagare }}</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">CUOTA DEUDA:</b>
            <div v-for="(descnoap, key) in descnoap" :key="key">
              <p class="panel-value">{{ descnoap.vfijo | currency }}</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <div v-for="(descnoap, key) in descnoap" :key="key">
              <p class="panel-value">{{ descnoap.fgrab }}</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
            <div v-for="(descnoap, key) in descnoap" :key="key">
<!--              <p class="panel-value">{{ descnoap.nonentant.len }}</p>-->
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">INCONSISTENCIA:</b>
            <div v-for="(descnoap, key) in descnoap" :key="key">
              <p class="panel-value">{{ descnoap.Incon }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Descnoap",
  props: ['descnoap'],
}
</script>

<style scoped>

</style>
