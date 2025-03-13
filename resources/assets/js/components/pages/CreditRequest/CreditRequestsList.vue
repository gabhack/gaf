<template>
  <div class="table-container">
    <loading :active.sync="isLoading" :is-full-page="true" color="#0CEDB0" :can-cancel="false" />
    
    <div v-if="showTable" class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Tipo Cliente</th>
            <th>Pagaduría</th>
            <th>Cuota</th>
            <th>Monto</th>
            <th>Tasa (Mensual)</th>
            <th>Plazo</th>
            <th>Estado</th>
            <th>Documentos</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="credit in credits" :key="credit.id">
            <td>{{ credit.id }}</td>
            <td>{{ credit.doc }}</td>
            <td>{{ credit.name }}</td>
            <td>{{ credit.client_type }}</td>
            <td>{{ getPagaduriaNameById(credit.pagaduria_id) }}</td>
            <td>{{ formatCurrency(credit.cuota) }}</td>
            <td>{{ formatCurrency(credit.monto) }}</td>
            <td>{{ formatPercentage(credit.tasa) }}</td>
            <td>{{ credit.plazo }}</td>
            <td>{{ credit.status }}</td>
            <td>
              <span v-if="credit.documents && credit.documents.length">
                <div v-for="doc in credit.documents" :key="doc.id">
                  <a :href="getDownloadUrl(doc.file_path)" target="_blank">
                    {{ extractFilename(doc.file_path) }}
                  </a>
                </div>
              </span>
              <span v-else>No hay documentos</span>
            </td>
            <td>
              <button v-if="credit.status !== 'aprobado'" class="btn-credit" @click="approveRequest(credit.id)">
                Aprobar
              </button>
              <span v-else class="text-success">Aprobado</span>
              <button class="btn-credit ml-2" @click="showCarteras(credit)">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn-credit ml-2" @click="markAsVisado(credit)">
                Visar
              </button>
              <button class="btn-credit ml-2" @click="openManualVisadoModal(credit)">
                Visar Manualmente
              </button>
              <button class="btn-credit ml-2" @click="openUploadModal(credit)">
                Subir Doc
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <b-modal id="modal-visar-manualmente" v-model="showVisadoManualModal" title="Visar Manualmente" hide-footer centered>
      <div v-if="selectedCreditToVisadoManual">
        <form @submit.prevent="submitVisadoManual">
          <div class="form-group">
            <label for="doc">Documento</label>
            <input type="text" class="form-control" id="doc" :value="selectedCreditToVisadoManual.doc" readonly>
          </div>
          <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" :value="selectedCreditToVisadoManual.name" readonly>
          </div>
          <div class="form-group">
            <label for="pagaduria">Pagaduría</label>
            <input type="text" class="form-control" id="pagaduria" :value="getPagaduriaNameById(selectedCreditToVisadoManual.pagaduria_id)" readonly>
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="btn-credit">Guardar</button>
            <button type="button" class="btn-credit ml-2" @click="showVisadoManualModal = false">Cancelar</button>
          </div>
        </form>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios';
import Loading from 'vue-loading-overlay';
import { BModal } from 'bootstrap-vue';

export default {
  name: 'CreditRequestsListWithVisado',
  components: {
    Loading,
    BModal,
  },
  data() {
    return {
      credits: [],
      showTable: true,
      isLoading: false,
      showVisadoManualModal: false,
      selectedCreditToVisadoManual: null,
      showCarterasModal: false,
      selectedCredit: null,
      showUploadModal: false,
      selectedCreditToUpload: null,
    };
  },
  methods: {
    async fetchCredits() {
      try {
        const response = await axios.get('/credit-requests/all');
        this.credits = response.data;
      } catch (error) {
        console.error('Error al obtener lista de créditos', error);
      }
    },
    openManualVisadoModal(credit) {
      this.selectedCreditToVisadoManual = credit;
      this.showVisadoManualModal = true;
    },
    async submitVisadoManual() {
      try {
        this.isLoading = true;
        await axios.post('/visados', { id: this.selectedCreditToVisadoManual.id });
        alert('El crédito ha sido visado y ha pasado al módulo de visados.');
        this.showVisadoManualModal = false;
        this.fetchCredits();
      } catch (error) {
        console.error('Error al guardar el visado manualmente:', error);
        alert('Error al guardar el visado manualmente.');
      } finally {
        this.isLoading = false;
      }
    },
    getPagaduriaNameById(id) {
      const pagaduriasMap = {
        1: 'SED Amazonas',
        156: 'SEM Zipaquira',
      };
      return pagaduriasMap[id] || id;
    },
    showCarteras(credit) {
      this.selectedCredit = credit;
      this.showCarterasModal = true;
    },
    openUploadModal(credit) {
      this.selectedCreditToUpload = credit;
      this.showUploadModal = true;
    },
  },
  mounted() {
    this.fetchCredits();
  },
};
</script>

<style scoped>
.table-container {
  width: 100%;
  padding: 20px;
}
.btn-credit {
  color: white;
  background: #0cedb0;
  border: none;
  border-radius: 5px;
  padding: 7px 14px;
  font-size: 14px;
  cursor: pointer;
  margin: 2px;
}
</style>
