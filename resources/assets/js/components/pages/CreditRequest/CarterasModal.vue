<template>
    <!-- Modal Carteras asociadas -->
    <b-modal
      id="modal-carteras"
      v-model="visible"
      title="Carteras asociadas"
      hide-footer
      centered
    >
      <!-- ======= TABLA DE CARTERAS ======= -->
      <div v-if="carteras && carteras.length">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Tipo de Cartera</th>
                <th>Nombre de la Entidad</th>
                <th>Valor Cuota</th>
                <th>Saldo</th>
                <th>Opera&nbsp;X&nbsp;Desprendible</th>
              </tr>
            </thead>
  
            <tbody>
              <tr v-for="(car, idx) in carteras" :key="idx">
                <td>{{ car.tipo_cartera }}</td>
                <td>{{ car.nombre_entidad }}</td>
                <td>{{ formatCurrency(car.valor_cuota) }}</td>
                <td>{{ formatCurrency(car.saldo) }}</td>
                <td>
                  <span v-if="car.opera_x_desprendible">Sí</span>
                  <span v-else>No</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- ======= SIN REGISTROS ======= -->
      <div v-else>
        <p class="text-center mb-0">No hay carteras registradas</p>
      </div>
    </b-modal>
  </template>
  
  <script>
  import { BModal } from 'bootstrap-vue'
  
  export default {
    name: 'CarterasModal',
  
    components: { BModal },
  
    props: {
      /* v-model: visibilidad del modal */
      value: {
        type: Boolean,
        default: false
      },
      /* Array de carteras asociadas */
      carteras: {
        type: Array,
        default: () => []
      }
    },
  
    computed: {
      /* Sincroniza v-model */
      visible: {
        get() {
          return this.value
        },
        set(v) {
          this.$emit('input', v)
        }
      }
    },
  
    methods: {
      /* Formatear moneda COP sin decimales */
      formatCurrency(value) {
        const num = parseFloat(value)
        if (!num || isNaN(num)) return '$0'
        return new Intl.NumberFormat('es-CO', {
          style: 'currency',
          currency: 'COP',
          minimumFractionDigits: 0
        }).format(num)
      }
    }
  }
  </script>
  
  <style scoped>
  .table-responsive {
    overflow-x: auto;
  }
  
  .table thead th {
    background: #fdf8e4; /* amarillo muy claro para consistencia */
    color: #2d2d2d;
    font-weight: 600;
  }
  
  .table tbody td {
    vertical-align: middle;
  }
  
  p {
    font-weight: 500;
  }
  
  /* Opcional: coherencia con btn-credit del proyecto */
  .btn-credit {
    color: #fff;
    background: #0cedb0;
    border: none;
    border-radius: 5px;
    padding: 7px 14px;
    font-size: 14px;
    cursor: pointer;
    margin: 2px;
  }
  </style>
  