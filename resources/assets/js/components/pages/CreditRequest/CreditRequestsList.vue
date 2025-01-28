<template>
    <div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Documento</th>
              <th>Nombre</th>
              <th>Monto</th>
              <th>Plazo</th>
              <th>Estado</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="credit in credits" :key="credit.id">
              <td>{{ credit.id }}</td>
              <td>{{ credit.doc }}</td>
              <td>{{ credit.name }}</td>
              <td>{{ credit.monto }}</td>
              <td>{{ credit.plazo }}</td>
              <td>{{ credit.status }}</td>
              <td>
                <button
                  v-if="credit.status !== 'aprobado'"
                  class="btn-credit"
                  @click="approveRequest(credit.id)"
                >
                  Aprobar
                </button>
                <span v-else class="text-success">Aprobado</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'CreditRequestsList',
    data() {
      return {
        credits: []
      };
    },
    mounted() {
      this.fetchCredits();
    },
    methods: {
      async fetchCredits() {
        try {
          const response = await axios.get('/credit-requests/all');
          this.credits = response.data;
        } catch (error) {
          console.error('Error al obtener la lista de créditos', error);
        }
      },
      async approveRequest(creditId) {
        try {
          const response = await axios.patch(`/credit-requests/${creditId}/status`);
          // Actualizar la fila localmente
          const index = this.credits.findIndex(c => c.id === creditId);
          if (index !== -1) {
            this.credits[index].status = 'aprobado';
          }
          alert(response.data.message);
        } catch (error) {
          console.error('Error al aprobar el crédito', error);
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .table {
    margin-top: 20px;
  }
  
  /* Botón verde degradado */
  .btn-credit {
    color: white;
    background-image: linear-gradient(to right, #0cedb0 0%, #0cedb0 55%, #0cedb0 100%);
    transition: 0.5s;
    background-size: 200% auto;
    border: none;
    border-radius: 5px;
    padding: 7px 14px;
    font-size: 14px;
    cursor: pointer;
    &:hover {
      background-position: right center;
    }
  }
  </style>
  