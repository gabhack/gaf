<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>DETALLE DE EMBARGOS</b></div>
      <!-- <div class="panel-body">
        <div class="row">
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
            <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
              <p class="panel-value">{{ embargossemcali.entidaddeman }}</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
            <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
              <p class="panel-value">{{ embargossemcali.docdeman }}</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">CUOTA DEUDA:</b>
            <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
              <p class="panel-value">{{ embargossemcali.temb | currency }}</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
              <p class="panel-value">{{ embargossemcali.fembini }}</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">INCONSISTENCIA:</b>
            <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
              <p class="panel-value">{{ embargossemcali.motemb }}</p>
            </div>
          </div>
        </div>
      </div> -->
      <div class="panel-body">
        <div class="row">
          <div class="col-1">
            <b class="panel-label table-text"></b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
          </div>
          <div class="col-1">
            <b class="panel-label table-text">CUOTA DEUDA:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">INCONSISTENCIA:</b>
          </div>
        </div>

        <div v-for="(item, key) in embargossemcali" :key="key" class="row panel-br-light-green pt-3">
          <div class="col-1 pr-0">
            <!-- <input v-model="item.check" type="checkbox" /> -->
            <b class="panel-label table-text"></b>
            <!--
              :value="item.id"
              @input="event => AddItem(event.target.value)"
            -->
          </div>
          <div class="col-2 px-0">
            <p>{{ item.entidaddeman ? item.entidaddeman : '-' }}</p>
          </div>

          <div class="col-2 px-0">
            <p>{{ item.docdeman ? item.docdeman : '-' }}</p>
          </div>

          <div class="col-1">
            <p>{{ item.temb | currency }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.fembini ? item.fembini : '-' }}</p>
          </div>

          <div class="col-2">
            <p>{{ '-' }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.motemb ? item.motemb : '-' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmbargosSemCali',
  props: ['embargossemcali']
};
</script>
