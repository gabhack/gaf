<template>
  <div class="table-container">
    <loading :active.sync="isLoading" :is-full-page="true" color="#0CEDB0" :can-cancel="false" />

    <!-- Tabla principal con la lista de créditos -->
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
              <!-- Botón de ejemplo para "Aprobar" -->
              <button
                v-if="credit.status !== 'aprobado'"
                class="btn-credit"
                @click="approveRequest(credit.id)"
              >
                Aprobar
              </button>
              <span v-else class="text-success">Aprobado</span>

              <!-- Botón para abrir modal de visado manual -->
              <button class="btn-credit ml-2" @click="openManualVisadoModal(credit)">
                Visar Manualmente
              </button>

              <!-- Otros botones de ejemplo -->
              <button class="btn-credit ml-2" @click="showCarteras(credit)">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn-credit ml-2" @click="markAsVisado(credit)">
                Visar
              </button>
              <button class="btn-credit ml-2" @click="openUploadModal(credit)">
                Subir Doc
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal para visado manual (store/update) -->
    <b-modal
      id="modal-visar-manualmente"
      v-model="showVisadoManualModal"
      title="Visar Manualmente"
      hide-footer
      centered
    >
      <div v-if="visadoForm">
        <form @submit.prevent="submitVisadoManual">

          <!-- Documento -->
          <div class="form-group">
            <label for="doc">Documento</label>
            <input
              type="text"
              class="form-control"
              id="doc"
              v-model="visadoForm.doc"
            />
          </div>

          <!-- Nombre -->
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input
              type="text"
              class="form-control"
              id="nombre"
              v-model="visadoForm.nombre"
            />
          </div>

          <!-- Pagaduría -->
          <div class="form-group">
            <label for="pagaduria">Pagaduría</label>
            <input
              type="text"
              class="form-control"
              id="pagaduria"
              v-model="visadoForm.pagaduria"
            />
          </div>

          <!-- Plazo -->
          <div class="form-group">
            <label for="plazo">Plazo</label>
            <input
              type="number"
              class="form-control"
              id="plazo"
              v-model="visadoForm.plazo"
            />
          </div>

          <!-- Monto -->
          <div class="form-group">
            <label for="monto">Monto</label>
            <input
              type="number"
              class="form-control"
              id="monto"
              v-model="visadoForm.monto"
            />
          </div>

          <!-- Cuota Crédito -->
          <div class="form-group">
            <label for="cuotacredito">Cuota Crédito</label>
            <input
              type="number"
              class="form-control"
              id="cuotacredito"
              v-model="visadoForm.cuotacredito"
            />
          </div>

          <!-- Estado (factible / no factible) -->
          <div class="form-group">
            <label for="estado">Estado</label>
            <select
              class="form-control"
              id="estado"
              v-model="visadoForm.estado"
            >
              <option value="factible">factible</option>
              <option value="no factible">no factible</option>
            </select>
          </div>

          <!-- Causal -->
          <div class="form-group">
            <label for="causal">Causal</label>
            <input
              type="text"
              class="form-control"
              id="causal"
              v-model="visadoForm.causal"
              placeholder="Describe causa de visado..."
            />
          </div>

          <div class="text-center mt-3">
            <button type="submit" class="btn-credit">Guardar</button>
            <button
              type="button"
              class="btn-credit ml-2"
              @click="showVisadoManualModal = false"
            >
              Cancelar
            </button>
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
    BModal
  },
  data() {
    return {
      credits: [],
      showTable: true,
      isLoading: false,

      // Control del modal
      showVisadoManualModal: false,

      // Objeto que maneja los datos para store/update
      visadoForm: null
    };
  },
  methods: {
    /* Obtiene la lista de créditos */
    async fetchCredits() {
      try {
        this.isLoading = true;
        const response = await axios.get('/credit-requests/all');
        this.credits = response.data;
      } catch (error) {
        console.error('Error al obtener lista de créditos', error);
      } finally {
        this.isLoading = false;
      }
    },

    /* Abre modal para visado manual */
    openManualVisadoModal(credit) {
      // Llenamos 'visadoForm' con los datos de la fila o en blanco
      this.visadoForm = {
        doc: credit.doc || '',
        nombre: credit.name || '',
        pagaduria: this.getPagaduriaNameById(credit.pagaduria_id) || '',
        plazo: credit.plazo || '',
        monto: credit.monto || '',
        cuotacredito: credit.cuota || '',
        estado: credit.status || 'factible', // Valor por defecto
        causal: '',

        // Si ya existe un visado_id, implica update
        visado_id: credit.visado_id || null,
        creditId: credit.id
      };
      this.showVisadoManualModal = true;
    },

    /* Lógica unificada para store/update */
    async submitVisadoManual() {
      try {
        this.isLoading = true;

        if (!this.visadoForm.visado_id) {
          // No existe "visado_id" => creamos un nuevo visado (store)
          await this.storeVisado();
        } else {
          // Sí existe => actualizamos (update)
          await this.updateVisado(this.visadoForm.visado_id);
        }

        // Cerrar modal y refrescar tabla
        this.showVisadoManualModal = false;
        this.fetchCredits();
      } catch (error) {
        console.error('Error en visado manual:', error);
        alert('Ocurrió un error al guardar el visado.');
      } finally {
        this.isLoading = false;
      }
    },

    /* Llamada al store (POST /visados) */
    async storeVisado() {
      // Ajusta el payload según lo que requiera tu controlador "store"
      const payload = {
        doc: this.visadoForm.doc,
        nombre: this.visadoForm.nombre,
        pagaduria: this.visadoForm.pagaduria,
        plazo: this.visadoForm.plazo
        // Si tu store() necesita otros campos (monto, cuotacredito, estado), agrégalos
      };

      const response = await axios.post('/visados', payload);
      console.log('Store Visado response:', response.data);
    },

    /* Llamada al update (POST /visados/{id}) */
    async updateVisado(id) {
      // Tu controlador "update()" espera estado, cuotacredito, monto, causal
      const payload = {
        estado: this.visadoForm.estado,
        cuotacredito: this.visadoForm.cuotacredito,
        monto: this.visadoForm.monto,
        causal: this.visadoForm.causal
      };
      const response = await axios.post(`/visados/${id}`, payload);
      console.log('Update Visado response:', response.data);
    },

    /* ============== FUNCIONES AUXILIARES ============== */

    getPagaduriaNameById(id) {
      const pagaduriasMap = {
        1: 'SED Amazonas',
        156: 'SEM Zipaquira',
        200: 'COLPENSIONES',
        201: 'FOPEP',
        202: 'FIDUPREVISORA'
      };
      return pagaduriasMap[id] || `ID: ${id}`;
    },
    formatCurrency(value) {
      if (!value) return '$0';
      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP'
      }).format(value);
    },
    formatPercentage(value) {
      if (!value) return '0%';
      return `${parseFloat(value).toFixed(2)}%`;
    },
    // Funciones de ejemplo para otros botones
    approveRequest(id) {
      console.log('Aprobar el crédito:', id);
    },
    showCarteras(credit) {
      console.log('Mostrar carteras de:', credit);
    },
    markAsVisado(credit) {
      console.log('Marcar como visado:', credit);
    },
    openUploadModal(credit) {
      console.log('Abrir modal para subir doc:', credit);
    }
  },
  mounted() {
    this.fetchCredits();
  }
};
</script>

<style scoped>
/* Contenedor principal */
.table-container {
  width: 100%;
  padding: 20px;
}

/* Botones */
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

/* Estilos del modal */
.modal-content,
.modal-body {
  background-color: #ffffff !important; /* Fondo blanco para modal */
  color: #000000 !important; /* Texto negro */
}

label {
  color: #000 !important;
  font-weight: 600;
}

.form-control {
  color: #000 !important;
  background-color: #fff !important;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>
