<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="embargossedquibdo.length > 0">
              <div v-for="(embargossedquibdo, key) in embargossedquibdo" :key="key">
                <p class="panel-value">
                  <template v-if="embargossedquibdo[label.field]">
                    {{ embargossedquibdo[label.field] }}
                  </template>
                  <template v-else-if="embargossedquibdo[label.field] || label.currency">
                    {{ embargossedquibdo[label.field] | currency }}
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
  name: 'EmbargosSedquibdo',
  props: ['embargossedquibdo'],
  data() {
    return {
      labels: [
        { label: 'NOMBRE ENTIDAD ACTUAL', field: 'ndeman' },
        { label: 'CUENTA', field: 'cuenta' },
        { label: 'CUOTA DEUDA', field: 'valor', currency: true },
        { label: 'JUZGADO', field: 'juzgado' },
        { label: 'NOMBRE ENTIDAD CEDIENTE', field: '' },
        { label: 'EXPEDIENTE', field: 'expediente' }
      ]
    };
  }
};
</script>