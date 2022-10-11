<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="descuentossedcauca.length > 0">
              <div v-for="(item, key) in descuentossedcauca" :key="key">
                <p class="panel-value">
                  <template v-if="item[label.field]">
                    {{ item[label.field] }}
                  </template>
                  <template v-else-if="item[label.field] || label.currency">
                    {{ item[label.field] | currency }}
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
  name: 'Descuentossedcauca',
  props: ['descuentossedcauca'],
  data() {
    return {
      labels: [
        { label: 'MENSAJE', field: 'mliquid', colClass: 'col-4' },
        { label: 'FECHA', field: 'fecdata' }
      ]
    };
  }
};
</script>