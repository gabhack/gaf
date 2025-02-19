<template>
  <div class="table-container">
    <!-- Loading Overlay -->
    <loading
      :active.sync="isLoading"
      :is-full-page="true"
      color="#0CEDB0"
      :can-cancel="false"
    />

    <!-- Tabla principal de Créditos -->
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
            <th>Score</th>
            <!-- Nueva columna para documentos -->
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
            <td><!-- Aquí iría el Score si lo tienes --></td>

            <!-- Celda para Documentos -->
            <td>
              <span v-if="credit.documents && credit.documents.length">
                <!-- Mostrar un enlace por cada documento -->
                <div
                  v-for="doc in credit.documents"
                  :key="doc.id"
                >
                  <a
                    :href="getDownloadUrl(doc.file_path)"
                    target="_blank"
                  >
                    {{ extractFilename(doc.file_path) }}
                  </a>
                </div>
              </span>
              <span v-else>No hay documentos</span>
            </td>

            <!-- Celda de Acciones -->
            <td>
              <!-- Botón Aprobar -->
              <button
                v-if="credit.status !== 'aprobado'"
                class="btn-credit"
                @click="approveRequest(credit.id)"
              >
                Aprobar
              </button>
              <span v-else class="text-success">Aprobado</span>

              <!-- Botón Ver Carteras -->
              <button class="btn-credit ml-2" @click="showCarteras(credit)">
                <i class="fas fa-eye"></i>
              </button>

              <!-- Botón Visar -->
              <button class="btn-credit ml-2" @click="emitClientData(credit)">
                Visar
              </button>

              <!-- Botón Subir Documento -->
              <button class="btn-credit ml-2" @click="openUploadModal(credit)">
                Subir Doc
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal: Detalle Carteras -->
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

    <!-- Modal: Subir Documento -->
    <b-modal
      id="modal-upload-document"
      v-model="showUploadModal"
      title="Subir Documento"
      hide-footer
      centered
    >
      <div>
        <!-- Input para seleccionar archivo -->
        <input type="file" @change="handleFileUpload" accept=".pdf,image/*" />

        <!-- Botón para subir archivo -->
        <button class="btn-credit mt-2" @click="uploadDocument">
          Subir
        </button>
      </div>

      <!-- Lista de documentos ya subidos para el crédito actual -->
      <hr />
      <div
        v-if="selectedCreditToUpload
               && selectedCreditToUpload.documents
               && selectedCreditToUpload.documents.length"
      >
        <h5>Documentos existentes:</h5>
        <ul>
          <li
            v-for="doc in selectedCreditToUpload.documents"
            :key="doc.id"
          >
            <a :href="getDownloadUrl(doc.file_path)" target="_blank">
              {{ extractFilename(doc.file_path) }}
            </a>
          </li>
        </ul>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import { BModal } from 'bootstrap-vue'
import { mapMutations, mapState } from 'vuex'

const pagaduriasMap = {
  'sed amazonas': 1,
  // Resto de las pagadurías...
  'sem zipaquira': 156
}

export default {
  name: 'CreditRequestsListWithVisado',
  components: { Loading, BModal },
  data() {
    return {
      credits: [],
      showTable: true,
      isLoading: false,

      // Para Carteras
      showCarterasModal: false,
      selectedCredit: null,

      // Para Subir Documentos
      showUploadModal: false,
      selectedCreditToUpload: null,
      fileToUpload: null
    }
  },
  computed: {
    ...mapState('pagaduriasModule', ['pagaduriasTypes'])
  },
  mounted() {
    this.fetchCredits()
  },
  methods: {
    ...mapMutations('pagaduriasModule', [
      'setPagaduriaType',
      'setPagaduriaLabel',
      'setCouponsType',
      'setSelectedPeriod'
    ]),
    ...mapMutations('embargosModule', ['setEmbargosType']),
    ...mapMutations('descuentosModule', ['setDescuentosType']),
    ...mapMutations('datamesModule', ['setDatamesSed']),

    // Obtener lista de créditos
    async fetchCredits() {
      try {
        const response = await axios.get('/credit-requests/all')
        this.credits = response.data
      } catch (error) {
        console.error('Error al obtener lista de créditos', error)
      }
    },

    // Formatear moneda
    formatCurrency(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '$0'
      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
      }).format(num)
    },

    // Formatear porcentaje
    formatPercentage(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '0%'
      return `${num.toFixed(2)}%`
    },

    // Aprobar una solicitud de crédito
    async approveRequest(creditId) {
      try {
        const response = await axios.patch(`/credit-requests/${creditId}/status`)
        const index = this.credits.findIndex(c => c.id === creditId)
        if (index !== -1) this.credits[index].status = 'aprobado'
        alert(response.data.message || 'Solicitud aprobada exitosamente')
      } catch (error) {
        console.error('Error al aprobar el crédito', error)
        alert('Error al aprobar la solicitud')
      }
    },

    // Mostrar Carteras de un crédito
    showCarteras(credit) {
      console.log('Carteras del crédito:', credit.carteras)
      this.selectedCredit = credit
      this.showCarterasModal = true
    },

    // Obtener el nombre de la pagaduría a partir de su ID
    getPagaduriaNameById(id) {
      for (const [name, mappedId] of Object.entries(pagaduriasMap)) {
        if (parseInt(mappedId) === parseInt(id)) {
          return name.toUpperCase()
        }
      }
      return id
    },

    // Emitir datos del cliente (Visar)
    async emitClientData(credit) {
      const dataclient = {
        doc: credit.doc,
        name: credit.name,
        cuotadeseada: credit.cuota,
        monto: credit.monto,
        plazo: credit.plazo,
        pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
        pagadurias: null,
        pagaduriaKey: null,
        visado: null,
        carteras: credit.carteras || []
      }
      this.isLoading = true
      try {
        // Ejemplo: obtener datos demográficos
        const demografico = await axios.get(`/demografico/${dataclient.doc}`)
        if (demografico.data.nombre_usuario) {
          dataclient.name = demografico.data.nombre_usuario
        }

        // Reseteo de ciertos estados en Vuex
        this.setDatamesSed(null)
        this.setPagaduriaType('')
        this.setSelectedPeriod('')

        // Ejemplo: obtener info de pagadurías
        const pagResponse = await axios.get(`/pagadurias/per-doc/${dataclient.doc}`)
        if (Object.keys(pagResponse.data).length !== 0) {
          dataclient.pagadurias = pagResponse.data
          const found = this.pagaduriasTypes.find(
            t => t.label && t.label.toUpperCase() === dataclient.pagaduria
          )
          if (found && dataclient.pagadurias[found.key]) {
            dataclient.pagaduriaKey = found.key
            this.setPagaduriaType(found.value)
            this.setPagaduriaLabel(found.label)

            if (found.key.includes('datames')) {
              this.setCouponsType(`Coupons${found.key.slice(7)}`)
              this.setEmbargosType(`Embargos${found.key.slice(7)}`)
              this.setDescuentosType(`Descuentos${found.key.slice(7)}`)
            } else {
              this.setCouponsType(found.key)
              this.setEmbargosType(found.key)
              this.setDescuentosType(found.key)
            }
            this.setDatamesSed(dataclient.pagadurias[found.key])
          }
        }

        // Ejemplo: llamar un servicio "visados"
        const visado = await axios.post('/visados', {
          pagaduria: dataclient.pagaduria,
          nombre: dataclient.name,
          doc: dataclient.doc,
          plazo: dataclient.plazo
        })
        dataclient.visado = visado.data

        // Emitir al padre (o siguiente componente)
        this.$emit('emitInfo', dataclient)
        this.showTable = false
      } catch (error) {
        console.error('Error:', error)
      } finally {
        this.isLoading = false
      }
    },

    // Abrir modal para subir documento
    openUploadModal(credit) {
      this.selectedCreditToUpload = credit
      this.showUploadModal = true
    },

    // Manejar el evento "change" del input de tipo file
    handleFileUpload(event) {
      this.fileToUpload = event.target.files[0] || null
    },

    // Subir el documento al backend
    async uploadDocument() {
      if (!this.fileToUpload) {
        alert("Por favor selecciona un archivo antes de subir.")
        return
      }

      this.isLoading = true
      try {
        const formData = new FormData()
        formData.append("document", this.fileToUpload)

        const response = await axios.post(
          `/credit-requests/${this.selectedCreditToUpload.id}/documents`,
          formData,
          { headers: { "Content-Type": "multipart/form-data" } }
        )

        alert(response.data.message || 'Documento subido exitosamente')

        // Agregar el documento subido al array documents del crédito
        if (!this.selectedCreditToUpload.documents) {
          this.selectedCreditToUpload.documents = []
        }
        this.selectedCreditToUpload.documents.push(response.data.data)

        // Cerrar modal y resetear
        this.showUploadModal = false
        this.fileToUpload = null
      } catch (error) {
        console.error("Error al subir documento:", error)
        alert("Ocurrió un error al subir el documento")
      } finally {
        this.isLoading = false
      }
    },

    // Convertir la ruta "public/documents/archivo.pdf" en "/storage/documents/archivo.pdf"
    getDownloadUrl(filePath) {
      if (!filePath) return '#'
      return filePath.replace("public", "/storage")
    },

    // De la ruta completa, extraer solo el nombre de archivo
    extractFilename(filePath) {
      if (!filePath) return ''
      return filePath.split('/').pop()
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
  margin: 2px;
}
.btn-credit:hover {
  background-position: right center;
}

.ml-2 {
  margin-left: 0.5rem;
}

.mt-2 {
  margin-top: 0.5rem;
}
</style>
