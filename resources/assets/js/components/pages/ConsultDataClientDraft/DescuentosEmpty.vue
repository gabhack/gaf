<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading d-flex justify-content-between">
        <b>OBLIGACIONES VIGENTES EN MORA 2</b>
        <div class="d-flex align-items-center">
          <b class="mr-2">PERIODO:</b>
          
          <select 
    class="form-control" 
    @change="setSelectedPeriod($event.target.value)" 
    :value="selectedPeriod">
    <option :value="period" v-for="period in pagaduriaPeriodos" :key="period">
        {{ period }}
    </option>
</select>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="descuentosempty.length > 0">
              <div v-for="(item, key) in descuentosempty" :key="key">
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
  name: 'DescuentosEmpty',
  props: {
    descuentosempty: Array,
    selectedPeriod: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      labels: [
        { label: 'MENSAJE', field: '-', colClass: 'col-4' },
        { label: 'FECHA', field: '-' }
      ]
    };
  },
  computed: {
    pagaduriaPeriodos() {
      // Asumiendo que este array viene del componente padre o Vuex
      return ['Periodo 1', 'Periodo 2', 'Periodo 3'];
    }
  },
  methods: {
    onPeriodChange(newPeriod) {
      console.log("embargos date");
      this.$emit('update-period', newPeriod); // Emitir el nuevo período al componente padre
    }
  }
};
</script>
