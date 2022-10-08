<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="embargosseceduc.length > 0">
              <div v-for="(embargosseceduc, key) in embargosseceduc" :key="key">
                <p class="panel-value">
                  <template v-if="embargosseceduc[label.field]">
                    {{ embargosseceduc[label.field] }}
                  </template>
                  <template v-else-if="embargosseceduc[label.field] || label.currency">
                    {{ embargosseceduc[label.field] | currency }}
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
  name: 'EmbargosSeceduc',
  props: ['embargosseceduc'],
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