<template>
  <div class="table-container">
    <loading
      :active.sync="isLoading"
      :is-full-page="true"
      color="#0CEDB0"
      :can-cancel="false"
    />
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
            <td></td>
            <td>
              <button
                v-if="credit.status !== 'aprobado'"
                class="btn-credit"
                @click="approveRequest(credit.id)"
              >
                Aprobar
              </button>
              <span v-else class="text-success">Aprobado</span>

              <!-- Botón para ver Cartera -->
              <button class="btn-credit ml-2" @click="showCarteras(credit)">
                <i class="fas fa-eye"></i>
              </button>

              <!-- Botón para "Visar" -->
              <button class="btn-credit ml-2" @click="emitClientData(credit)">
                Visar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

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
import Loading from 'vue-loading-overlay'
import { BModal } from 'bootstrap-vue'
import { mapMutations, mapState } from 'vuex'

/**
 * Relación de pagadurías en un objeto para "mapear" su ID numérico -> Nombre
 */
const pagaduriasMap = {
  'sed amazonas': 1,
  'sed antioquia': 130,
  'sed arauca': 109,
  'sed atlantico': 121,
  'sed bolivar': 5,
  'sed boyaca': 110,
  'sed caldas': 139,
  'sed caqueta': 140,
  'sed casanare': 104,
  'sed cauca': 177,
  'sed cesar': 11,
  'sed choco': 12,
  'sed cordoba': 182,
  'sed cundinamarca': 163,
  'sed guajira': 192,
  'sed guaviare': 173,
  'sed huila': 178,
  'sed magdalena': 145,
  'sed meta': 113,
  'sed narino': 143,
  'sed norte de santander': 154,
  'sed putumayo': 184,
  'sed quindio': 166,
  'sed risaralda': 114,
  'sed santander': 26,
  'sed sucre': 175,
  'sed tolima': 122,
  'sed valle': 165,
  'sed vaupes': 132,
  'sed vichada': 32,
  'sem sincelejo': 27,
  'sem armenia': 34,
  'sem barrancabermeja': 160,
  'sem barranquilla': 106,
  'sem bello': 111,
  'sem bucaramanga': 39,
  'sem buenaventura': 40,
  'sem buga': 157,
  'sem cali': 42,
  'sem cartagena': 43,
  'sem cartago': 136,
  'sem chia': 45,
  'sem cienaga': 103,
  'sem cucuta': 47,
  'sem dosquebradas': 112,
  'sem duitama': 49,
  'sem envigado': 115,
  'sem estrella': 168,
  'sem facatativa': 164,
  'sem florencia': 55,
  'sem floridablanca': 170,
  'sem funza': 117,
  'sem fusagasuga': 151,
  'sem girardot': 179,
  'sem giron': 61,
  'sem guainia': 116,
  'sem ibague': 147,
  'sem ipiales': 134,
  'sem itagui': 135,
  'sem jamundi': 146,
  'sem lorica': 67,
  'sem magangue': 133,
  'sem maicao': 69,
  'sem malambo': 161,
  'sem manizales': 174,
  'sem medellin': 180,
  'sem monteria': 176,
  'sem mosquera': 153,
  'sem neiva': 105,
  'sem palmira': 152,
  'sem pasto': 125,
  'sem pereira': 78,
  'sem piedecuesta': 79,
  'sem pitalito': 138,
  'sem popayan': 159,
  'sem quibdo': 162,
  'sem riohacha': 150,
  'sem rionegro': 129,
  'sem sabaneta': 108,
  'sem sahagun': 142,
  'sem san andres': 158,
  'sem santa marta': 126,
  'sem soacha': 119,
  'sem sogamoso': 172,
  'sem soledad': 123,
  'sem tulua': 120,
  'sem tumaco': 93,
  'sem tunja': 141,
  'sem turbo': 137,
  'sem uribia': 144,
  'sem valledupar': 171,
  'sem villavicencio': 124,
  'sem yopal': 100,
  'sem yumbo': 169,
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
      showCarterasModal: false,
      selectedCredit: null,
      carteras: null
    }
  },
  computed: {
    ...mapState('pagaduriasModule', ['pagaduriasTypes'])
  },
  mounted() {
    this.fetchCredits()
  },
  methods: {
    // Mutaciones de Vuex que permiten setear store.pagaduriaType, store.pagaduriaLabel, etc.
    ...mapMutations('pagaduriasModule', [
      'setPagaduriaType',
      'setPagaduriaLabel',
      'setCouponsType',
      'setSelectedPeriod'
    ]),
    ...mapMutations('embargosModule', ['setEmbargosType']),
    ...mapMutations('descuentosModule', ['setDescuentosType']),
    ...mapMutations('datamesModule', ['setDatamesSed']),

    /**
     * Al montar, traemos la lista de créditos
     */
    async fetchCredits() {
      try {
        const response = await axios.get('/credit-requests/all')
        this.credits = response.data
      } catch (error) {
        console.error('Error al obtener la lista de créditos', error)
      }
    },

    /**
     * Formatear moneda
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
     * Formatear porcentaje
     */
    formatPercentage(value) {
      const num = parseFloat(value)
      if (!num || isNaN(num)) return '0%'
      return `${num.toFixed(2)}%`
    },

    /**
     * Aprobar Solicitud
     */
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

    /**
     * Ver la "tabla" de cartera en modal
     */
    showCarteras(credit) {
      this.selectedCredit = credit
      this.showCarterasModal = true
    },

    /**
     * Tomar el ID de la pagaduría y convertirlo al nombre en minúsculas que usa el "map"
     */
    getPagaduriaNameById(id) {
      for (const [name, mappedId] of Object.entries(pagaduriasMap)) {
        if (parseInt(mappedId) === parseInt(id)) {
          return name.toUpperCase()
        }
      }
      return id
    },

    /**
     * Lógica para "Visar" la solicitud (similar a selectedPagaduria + saveVisados)
     */
    async emitClientData(credit) {
      // Preparamos la data base
      const dataclient = {
        doc: credit.doc,
        name: credit.name,
        cuotadeseada: credit.cuota,
        monto: credit.monto,
        plazo: credit.plazo,
        pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
        pagadurias: credit.pagadurias || null,
        pagaduriaKey: credit.pagaduriaKey || null,
        visado: null,
        carteras: credit.carteras || []
      }

      this.isLoading = true
      try {
        // 1. Llamar a demográfico (opcional) para "actualizar" el nombre real
        const demograficoResponse = await axios.get(`/demografico/${dataclient.doc}`)
        const demograficoData = demograficoResponse.data
        if (!demograficoData.nombre_usuario) {
          // si no se encontró el nombre, puede continuar o abortar
          console.warn('No se encontró nombre del usuario en demografico')
        } else {
          // Actualizamos
          dataclient.name = demograficoData.nombre_usuario
        }

        // 2. (Opcional) Llamada para cargar las pagadurías del doc, por si no las tenemos
        const pagRes = await axios.get(`/pagadurias/per-doc/${dataclient.doc}`)
        if (Object.keys(pagRes.data).length > 0) {
          dataclient.pagadurias = pagRes.data
        }

        // 3. Resetear store
        this.setDatamesSed(null)
        this.setPagaduriaType('')
        this.setSelectedPeriod('')

        // 4. Buscar en "pagaduriasTypes" la pagaduría actual para setear store
        const found = this.pagaduriasTypes.find(
          item => item.label && item.label.toUpperCase() === dataclient.pagaduria
        )
        if (found) {
          // Actualizamos store
          this.setPagaduriaType(found.value)
          this.setPagaduriaLabel(found.label)

          // setCouponsType, setEmbargosType, setDescuentosType
          if (found.key && found.key.includes('datames')) {
            // p.ej. "datamesFopep" => "CouponsFopep", etc.
            this.setCouponsType(`Coupons${found.key.slice(7)}`)
            this.setEmbargosType(`Embargos${found.key.slice(7)}`)
            this.setDescuentosType(`Descuentos${found.key.slice(7)}`)
          } else if (found.key) {
            // p.ej. si la key no contiene "datames"
            this.setCouponsType(found.key)
            this.setEmbargosType(found.key)
            this.setDescuentosType(found.key)
          }

          // Enviamos algo a setDatamesSed
          const dummyPagaduria = {
            cargo: credit.client_type || 'Cargo no especificado',
            doc: credit.doc
          }
          this.setDatamesSed(dummyPagaduria)
        }

        // 5. Creamos el visado (similar a saveVisados)
        const visadoResponse = await axios.post('/visados', {
          pagaduria: dataclient.pagaduria,
          nombre: dataclient.name,
          doc: dataclient.doc,
          plazo: dataclient.plazo
        })
        dataclient.visado = visadoResponse.data

        // 6. Emitimos la data final al padre (ej. index.vue) con la info para getCoupons etc.
        console.log('Dataclient que se va a emitir:', dataclient)
        this.$emit('emitInfo', dataclient)

        // 7. ocultamos la tabla actual
        this.showTable = false
      } catch (error) {
        console.error('Error en emitir/visar:', error)
      } finally {
        this.isLoading = false
      }
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
</style>
