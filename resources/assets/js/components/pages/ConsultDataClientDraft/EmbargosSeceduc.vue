<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>DETALLE DE EMBARGOS</b></div>
      <!-- <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="embargossedvalle.length > 0">
              <div v-for="(embargossedvalle, key) in embargossedvalle" :key="key">
                <p class="panel-value">
                  <template v-if="embargossedvalle[label.field]">
                    {{ embargossedvalle[label.field] }}
                  </template>
                  <template v-else-if="embargossedvalle[label.field] || label.currency">
                    {{ embargossedvalle[label.field] | currency }}
                  </template>
                  <template v-else> - </template>
                </p>
              </div>
            </template>
            <template v-else>
              <p class="panel-value">-</p>
            </template>
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

        <div v-for="(item, key) in embargossedvalle" :key="key" class="row panel-br-light-green pt-3">
          <div class="col-1 pr-0">
            <b class="panel-label table-text"></b>

            <!-- <input v-model="item.check" type="checkbox" /> -->
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
  name: 'EmbargosSeceduc',
  props: ['embargossedvalle'],
  data() {
    return {
      labels: [
        { label: 'NOMBRE ENTIDAD ACTUAL', field: 'entidaddeman' },
        { label: 'NUMERO DE PAGARE', field: 'docdeman' },
        { label: 'CUOTA DEUDA', field: 'temb', currency: true },
        { label: 'FECHA INICIO DEUDA', field: 'fembini' },
        { label: 'NOMBRE ENTIDAD CEDIENTE', field: '' },
        { label: 'INCONSISTENCIA', field: 'motemb' }
      ]
    };
  }
};
</script>
