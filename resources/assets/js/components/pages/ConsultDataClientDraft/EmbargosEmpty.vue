<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading d-flex justify-content-between">
        <b>DETALLE DE EMBARGOS aja</b>
        <div class="d-flex align-items-center">
          <b class="mr-2">PERIODO:</b>
          <select 
            class="form-control" 
            @change="onPeriodChange($event.target.value)" 
            :value="selectedPeriod"
            
          >
            <option 
              :value="period" 
              v-for="period in pagaduriaPeriodos" 
              :key="period"
            >
              {{ period }}
            </option>
          </select>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">CUOTA DEUDA:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <div>
              <p class="panel-value">-</p>
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
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmbargosEmpty',
  props: {
    embargosempty: Array,
    selectedPeriod: {
      type: String,
      default: null
    }
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
