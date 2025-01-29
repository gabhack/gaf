<template>
  <div class="table-container">

    <!-- Tabla de Créditos -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Tipo Cliente</th>
            <th>Pagaduría ID</th>
            <th>Cuota</th>
            <th>Monto</th>
            <th>Tasa (Mensual)</th>
            <th>Plazo</th>
            <th>Estado</th>
            <th>Score</th> <!-- Nueva columna vacía -->
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="credit in credits" :key="credit.id">
            <td>{{ credit.id }}</td>
            <td>{{ credit.doc }}</td>
            <td>{{ credit.name }}</td>
            <td>{{ credit.client_type }}</td>
            <td>{{ credit.pagaduria_id }}</td>
            <td>{{ formatCurrency(credit.cuota) }}</td>
            <td>{{ formatCurrency(credit.monto) }}</td>
            <td>{{ formatPercentage(credit.tasa) }}</td>
            <td>{{ credit.plazo }}</td>
            <td>{{ credit.status }}</td>
            
            <!-- Columna "Score" (vacía por ahora) -->
            <td></td>

            <td>
              <!-- Botón de Aprobar (si NO está aprobado) -->
              <button
                v-if="credit.status !== 'aprobado'"
                class="btn-credit"
                @click="approveRequest(credit.id)"
              >
                Aprobar
              </button>
              <!-- Texto si está aprobado -->
              <span v-else class="text-success">Aprobado</span>

              <!-- Botón para ver Carteras -->
              <button
                class="btn-credit ml-2"
                @click="showCarteras(credit)"
              >
                <!-- Ícono de ojo (font-awesome) -->
                <i class="fas fa-eye"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal para ver las Carteras -->
    <b-modal
      id="modal-carteras"
      v-model="showCarterasModal"
      title="Detalle de Carteras"
      hide-footer
      centered
    >
      <div v-if="selectedCredit && selectedCredit.carteras && selectedCredit.carteras.length">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tipo de Cartera</th>
              <th>Nombre de la Entidad</th>
              <th>Valor Cuota</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(cart, index) in selectedCredit.carteras" :key="index">
              <td>{{ cart.tipo_cartera }}</td>
              <td>{{ cart.nombre_entidad }}</td>
              <td>{{ formatCurrency(cart.valor_cuota) }}</td>
              <td>{{ formatCurrency(cart.saldo) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <p>No hay carteras registradas.</p>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
// Importa los componentes de BootstrapVue si no los globalizaste
import { BModal, BButton } from 'bootstrap-vue'

export default {
  name: 'CreditRequestsList',
  components: { BModal, BButton },
  data() {
    return {
      credits: [],
      // Manejo del modal de carteras
      showCarterasModal: false,
      selectedCredit: null
    }
  },
  mounted() {
    this.fetchCredits()
  },
  methods: {
    // Carga los créditos y sus carteras
    async fetchCredits() {
      try {
        // Asegúrate de que tu endpoint devuelva la relación con 'carteras' ->with('carteras')
        const response = await axios.get('/credit-requests/all')
        this.credits = response.data
      } catch (error) {
        console.error('Error al obtener la lista de créditos', error)
      }
    },
    // Aprobar solicitud
    async approveRequest(creditId) {
      try {
        const response = await axios.patch(`/credit-requests/${creditId}/status`)
        const index = this.credits.findIndex(c => c.id === creditId)
        if (index !== -1) {
          this.credits[index].status = 'aprobado'
        }
        alert(response.data.message || 'Solicitud aprobada exitosamente')
      } catch (error) {
        console.error('Error al aprobar el crédito', error)
        alert('Ocurrió un error al aprobar la solicitud')
      }
    },
    // Abre el modal con las carteras del crédito seleccionado
    showCarteras(credit) {
      this.selectedCredit = credit
      this.showCarterasModal = true
    },
    /**
     * Formatea valores numéricos como moneda: 100000 => "$100.000"
     * Puedes ajustarlo a solo puntos si no quieres símbolo.
     */
    formatCurrency(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '$0'
      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
      }).format(num)
    },
    /**
     * Formatea valores numéricos como porcentaje: 1.5 => "1.50%"
     */
    formatPercentage(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '0%'
      return `${num.toFixed(2)}%`
    }
  }
}
</script>

<style scoped>
.table-container {
  width: 100%;
  padding: 20px;
}

.table-responsive {
  overflow-x: auto;
  width: 100%;
}

.table {
  width: 100%;
  margin-top: 20px;
  border-collapse: collapse;
}

th,
td {
  text-align: center;
  vertical-align: middle;
}

/* Botón verde degradado */
.btn-credit {
  color: white;
  background-image: linear-gradient(
    to right,
    #0cedb0 0%,
    #0cedb0 55%,
    #0cedb0 100%
  );
  transition: 0.5s;
  background-size: 200% auto;
  border: none;
  border-radius: 5px;
  padding: 7px 14px;
  font-size: 14px;
  cursor: pointer;
  margin: 2px;
}

.btn-credit:hover {
  background-position: right center;
}
</style>
