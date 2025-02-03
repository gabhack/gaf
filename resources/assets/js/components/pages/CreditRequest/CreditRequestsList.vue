<template>
  <div class="table-container">
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
            <th>Score</th>
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
            <td></td>
            <td>
              <!-- Botón para aprobar (si aún no está aprobado) -->
              <button
                v-if="credit.status !== 'aprobado'"
                class="btn-credit"
                @click="approveRequest(credit.id)"
              >
                Aprobar
              </button>
              <span v-else class="text-success">Aprobado</span>

              <!-- Botón para ver carteras -->
              <button class="btn-credit ml-2" @click="showCarteras(credit)">
                <i class="fas fa-eye"></i>
              </button>

              <!-- Nuevo botón para llamar al componente client-data-component-draft -->
              <button class="btn-credit ml-2" @click="openClientData(credit)">
                Visar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal que carga el componente client-data-component-draft -->
    <b-modal
      id="client-data-modal"
      v-model="showClientDataModal"
      title="Visado de Crédito"
      hide-footer
      centered
    >
      <!-- Se le pasa la data del crédito seleccionado mediante la prop "clientData" -->
      <client-data-component-draft
        :client-data="selectedCredit"
        @close="closeClientData"
      />
    </b-modal>
  </div>
</template>

<script>
// Nota: Se asume que el componente 'client-data-component-draft' está registrado globalmente, 
// como indicas: Vue.component('client-data-component-draft', require('./components/pages/ConsultDataClientDraft/index.vue').default);
import axios from 'axios';

export default {
  name: 'CreditRequestsListWithVisado',
  data() {
    return {
      credits: [],
      showClientDataModal: false,
      selectedCredit: null
    };
  },
  mounted() {
    this.fetchCredits();
  },
  methods: {
    async fetchCredits() {
      try {
        // Se asume que este endpoint devuelve la lista de créditos (y, opcionalmente, la información que requiere el componente)
        const response = await axios.get('/credit-requests/all');
        this.credits = response.data;
      } catch (error) {
        console.error('Error al obtener la lista de créditos', error);
      }
    },
    formatCurrency(value) {
      const num = parseFloat(value);
      if (!num || isNaN(num)) return '$0';
      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
      }).format(num);
    },
    formatPercentage(value) {
      const num = parseFloat(value);
      if (!num || isNaN(num)) return '0%';
      return `${num.toFixed(2)}%`;
    },
    approveRequest(creditId) {
      // Aquí colocar la lógica para aprobar el crédito
      console.log('Aprobando crédito con ID:', creditId);
    },
    showCarteras(credit) {
      // Aquí colocar la lógica para mostrar las carteras (por ejemplo, abriendo otro modal)
      console.log('Mostrando carteras para crédito:', credit);
    },
    openClientData(credit) {
      // Asigna la información de la fila seleccionada
      this.selectedCredit = credit;
      // Abre el modal para cargar el componente client-data-component-draft
      this.showClientDataModal = true;
    },
    closeClientData() {
      this.showClientDataModal = false;
      this.selectedCredit = null;
    }
  }
};
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

/* Estilos para el botón */
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

.ml-2 {
  margin-left: 0.5rem;
}
</style>
