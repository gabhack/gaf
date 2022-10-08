<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="embargossedcauca.length > 0">
              <div v-for="(embargossedcauca, key) in embargossedcauca" :key="key">
                <p class="panel-value">
                  <template v-if="embargossedcauca[label.field]">
                    {{ embargossedcauca[label.field] }}
                  </template>
                  <template v-else-if="embargossedcauca[label.field] || label.currency">
                    {{ embargossedcauca[label.field] | currency }}
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
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmbargosSedcauca',
  props: ['embargossedcauca'],
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