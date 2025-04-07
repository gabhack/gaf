<template>
  <div class="table-container">
    <!-- Loading overlay -->
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
            <!-- Nueva columna: Tipo de Crédito -->
            <th>Tipo Crédito</th>
            <th>Documentos</th>
            <!-- Solo se muestra la columna "Acción" si es ADMIN_SISTEMA -->
            <th v-if="isAdminSistema">Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="credit in credits" :key="credit.id">
            <td>{{ credit.id }}</td>
            <td>{{ credit.doc }}</td>
            <td>{{ credit.name }}</td>
            <td>{{ credit.client_type }}</td>
            <!-- Pagaduría -->
            <td>{{ getPagaduriaNameById(credit.pagaduria_id) }}</td>
            <td>{{ formatCurrency(credit.cuota) }}</td>
            <td>{{ formatCurrency(credit.monto) }}</td>
            <td>{{ formatPercentage(credit.tasa) }}</td>
            <td>{{ credit.plazo }}</td>
            <td>{{ credit.status }}</td>
            <!-- Tipo de Crédito -->
            <td>{{ credit.tipo_credito }}</td>

            <!-- Documentos -->
            <td>
              <span v-if="credit.documents && credit.documents.length">
                <div v-for="(doc, docIndex) in credit.documents" :key="doc.id">
                  <a
                    :href="getDownloadUrl(doc.file_path)"
                    target="_blank"
                  >
                    doc-{{ docIndex + 1 }}
                  </a>
                </div>
              </span>
              <span v-else>
                No hay documentos
              </span>
            </td>

            <!-- Columna de botones si user.role_id === 1 (ADMIN_SISTEMA) -->
            <td v-if="isAdminSistema">
              <!-- Visar Manualmente (abre modal) -->
              <button
                class="btn-credit ml-2"
                @click="openManualVisadoModal(credit)"
              >
                Visar Manualmente
              </button>

              <!-- Ver Carteras (abre modal) -->
              <button
                class="btn-credit ml-2"
                @click="showCarteras(credit)"
              >
                <i class="fas fa-eye"></i>
              </button>

              <!-- Visar (emitClientData) -->
              <button
                class="btn-credit ml-2"
                @click="emitClientData(credit)"
              >
                Visar
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
            <select class="form-control" id="estado" v-model="visadoForm.estado">
              <option value="factible">factible</option>
              <option value="no factible">no factible</option>
            </select>
          </div>

          <!-- Causal (select con motivos) -->
          <div class="form-group">
            <label for="causal">Causal</label>
            <select
                class="form-control"
                id="causal"
                v-model="visadoForm.causal"
              >
                <option
                  v-for="c in causalesOptions"
                  :key="c.value"
                  :value="c.value"
                >
                  {{ c.text }}
                </option>
              </select>

          </div>

          <!-- Observación (para la tabla visados) -->
            <div class="form-group">
              <label for="observacion">Observación</label>
              <textarea
                class="form-control"
                id="observacion"
                rows="2"
                v-model="visadoForm.observacion"
              ></textarea>
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

    <!-- Modal para ver Carteras -->
    <!-- Modal para ver Carteras -->
<b-modal
  id="modal-carteras"
  v-model="showCarterasModal"
  title="Carteras asociadas"
  hide-footer
  centered
>
  <div v-if="selectedCarteras && selectedCarteras.length">
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Tipo de Cartera</th>
            <th>Nombre de la Entidad</th>
            <th>Valor Cuota</th>
            <th>Saldo</th>
            <!-- NUEVO -->
            <th>Opera X Desprendible</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(car, idx) in selectedCarteras" :key="idx">
            <td>{{ car.tipo_cartera }}</td>
            <td>{{ car.nombre_entidad }}</td>
            <td>{{ formatCurrency(car.valor_cuota) }}</td>
            <td>{{ formatCurrency(car.saldo) }}</td>
            <!-- NUEVO: muestra si es true/false -->
            <td>
              <span v-if="car.opera_x_desprendible">Sí</span>
              <span v-else>No</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div v-else>
    <p>No hay carteras registradas</p>
  </div>
</b-modal>

  </div>
</template>

<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import { BModal } from 'bootstrap-vue'
import { mapState, mapMutations } from 'vuex';


// Mapa de Pagadurías
const allPagaduriasMap = {
  // Pensionados
  200: "COLPENSIONES",
  201: "FOPEP",
  297: "FIDUPREVISORA",
  296: "CASUR",
  // Docentes (ordenados alfabéticamente, p. ej.)
  1: "sed amazonas",
  130: "sed antioquia",
  109: "sed arauca",
  121: "sed atlantico",
  293: "sed bolivar",
  110: "sed boyaca",
  139: "sed caldas",
  140: "sed caqueta",
  104: "sed casanare",
  177: "sed cauca",
  11: "sed cesar",
  12: "sed choco",
  182: "sed cordoba",
  163: "sed cundinamarca",
  192: "sed guajira",
  173: "sed guaviare",
  178: "sed huila",
  145: "sed magdalena",
  113: "sed meta",
  143: "sed narino",
  154: "sed norte de santander",
  184: "sed putumayo",
  166: "sed quindio",
  114: "sed risaralda",
  26: "sed santander",
  175: "sed sucre",
  122: "sed tolima",
  165: "sed valle",
  132: "sed vaupes",
  32: "sed vichada",
  27: "sem sincelejo",
  34: "sem armenia",
  160: "sem barrancabermeja",
  106: "sem barranquilla",
  111: "sem bello",
  39: "sem bucaramanga",
  40: "sem buenaventura",
  157: "sem buga",
  191: "sem cali",
  189: "sem cartagena",
  136: "sem cartago",
  45: "sem chia",
  103: "sem cienaga",
  47: "sem cucuta",
  112: "sem dosquebradas",
  49: "sem duitama",
  115: "sem envigado",
  168: "sem estrella",
  164: "sem facatativa",
  55: "sem florencia",
  170: "sem floridablanca",
  117: "sem funza",
  151: "sem fusagasuga",
  179: "sem girardot",
  287: "sem giron",
  116: "sem guainia",
  147: "sem ibague",
  134: "sem ipiales",
  135: "sem itagui",
  146: "sem jamundi",
  67: "sem lorica",
  133: "sem magangue",
  69: "sem maicao",
  161: "sem malambo",
  174: "sem manizales",
  180: "sem medellin",
  176: "sem monteria",
  153: "sem mosquera",
  105: "sem neiva",
  152: "sem palmira",
  125: "sem pasto",
  78: "sem pereira",
  79: "sem piedecuesta",
  138: "sem pitalito",
  159: "sem popayan",
  162: "sem quibdo",
  150: "sem riohacha",
  129: "sem rionegro",
  108: "sem sabaneta",
  142: "sem sahagun",
  158: "sem san andres",
  126: "sem santa marta",
  119: "sem soacha",
  172: "sem sogamoso",
  123: "sem soledad",
  120: "sem tulua",
  93: "sem tumaco",
  141: "sem tunja",
  137: "sem turbo",
  144: "sem uribia",
  171: "sem valledupar",
  124: "sem villavicencio",
  289: "sem yopal",
  169: "sem yumbo",
  156: "sem zipaquira",
  296: "casur",
  297: "fiduprevisora"
}

export default {
  name: 'CreditRequestsListWithVisado',
  components: {
    Loading,
    BModal
  },
  props: {
    // Se asume que el componente padre pasa "user" (objeto con role_id, etc.)
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      credits: [],
      showTable: true,
      isLoading: false,
      selectedPeriod: null ,
      // Modal para visado manual
      showVisadoManualModal: false,
      visadoForm: null,

      // Modal para ver Carteras
      showCarterasModal: false,
      selectedCarteras: []
    }
  },
  computed: {
    // Si user.role_id === 1 => admin
    isAdminSistema() {
      return this.user.role_id === 1
    },
    causalesOptions() {
    if (this.visadoForm.estado === 'factible') {
      // Solo "Sin causal"
      return [
        { value: 'Sin causal', text: 'Sin causal' },
      ];
    } else {
      // Cuando es 'no factible', NO mostrar 'Sin causal' sino los demás
      return [
        { value: 'Presenta obligaciones en mora', text: 'Presenta obligaciones en mora' },
        { value: 'Negado por cupo', text: 'Negado por cupo' },
        { value: 'Cliente en proceso de retiro', text: 'Cliente en proceso de retiro' },
        { value: 'No factible por pagaduria', text: 'No factible por pagaduria' },
        { value: 'Ingresa descuento nuevo', text: 'Ingresa descuento nuevo' },
      ];
    }
  }
  },
  methods: {
    ...mapMutations('datamesModule', ['setDatamesSed', 'setCuotaDeseada']),
        ...mapMutations('pagaduriasModule', [
            'setPagaduriaType',
            'setPagaduriaLabel',
            'setCouponsType',
            'setSelectedPeriod'
        ]),
        ...mapMutations('embargosModule', ['setEmbargosType']),
        ...mapMutations('descuentosModule', ['setDescuentosType']),
    async fetchCredits() {
      try {
        this.isLoading = true
        const response = await axios.get('/credit-requests/all')
        this.credits = response.data
      } catch (error) {
        console.error('Error al obtener lista de créditos', error)
      } finally {
        this.isLoading = false
      }
    },

    // "Visar Manualmente": abre modal
    openManualVisadoModal(credit) {
      this.visadoForm = {
        doc: credit.doc || '',
        nombre: credit.name || '',
        pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
        plazo: credit.plazo || '',
        monto: credit.monto || '',
        cuotacredito: credit.cuota || '',
        estado: credit.status || 'factible',
        causal: '',
        visado_id: credit.visado_id || null,
        creditId: credit.id
      }
      this.showVisadoManualModal = true
    },
async submitVisadoManual() {
  try {
    this.isLoading = true;
    await axios.post(`/visados/${this.visadoForm.visado_id}`, {
      estado: this.visadoForm.estado,
      cuotacredito: this.visadoForm.cuotacredito,
      monto: this.visadoForm.monto,
      causal: this.visadoForm.causal,
      observacion: this.visadoForm.observacion 
    });
    
    alert("Visado manual guardado con éxito.");
    this.showVisadoManualModal = false;
    // etc.
  } catch (err) {
    console.error(err);
    alert("Error guardando visado manual.");
  } finally {
    this.isLoading = false;
  }
},
    async emitClientData(credit) {
      this.isLoading = true
      try {

        const dataclient = {
          doc: credit.doc,
          name: credit.name,
          cuotadeseada: credit.cuota,
          monto: credit.monto,
          plazo: credit.plazo,
          pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
          carteras: credit.carteras || []
        }
        this.setPagaduriaType(dataclient.pagaduria);

        // Ejemplo de endpoints
        console.log("emitClientData =>", dataclient);
        this.$emit('emitInfo', dataclient)
        this.showTable = false
      } catch (error) {
        console.error('Error al ejecutar emitClientData:', error)
      } finally {
        this.isLoading = false
      }
    },

    // Ver Carteras => modal
    showCarteras(credit) {
      if (credit.carteras && credit.carteras.length) {
        this.selectedCarteras = credit.carteras
      } else {
        this.selectedCarteras = []
      }
      this.showCarterasModal = true
    },

    // Helpers
    getPagaduriaNameById(id) {
      return allPagaduriasMap[id] || `ID: ${id}`
    },
    formatCurrency(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '$0'
      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
      }).format(num)
    },
    formatPercentage(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '0%'
      return `${num.toFixed(2)}%`
    },
    getDownloadUrl(filePath) {
      // Ajusta según tu storage
      return `/storage/${filePath.replace('public/', '')}`
    }
  },
  mounted() {
    // Cargar créditos al inicio
    this.fetchCredits()
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
.modal-content,
.modal-body {
  background-color: #ffffff !important;
  color: #000000 !important;
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
