<template>
  <div class="table-container">
    <!-- Overlay global de carga -->
    <loading
      :active.sync="isLoading"
      :is-full-page="true"
      color="#0CEDB0"
      :can-cancel="false"
    />

    <!-- ================= TABLA PRINCIPAL ================= -->
    <CreditRequestTable
      v-if="showTable"
      :credits="credits"
      :is-admin="isAdminSistema"
      @open-visado="onOpenVisado"
      @view-carteras="onViewCarteras"
      @visar="modalConfirmConsultPag"
    />

    <!-- ================= MODAL VISAR MANUAL ================= -->
    <ManualVisadoModal
      v-model="showVisadoManualModal"
      :visado-form="visadoForm"
      @saved="fetchCredits"
    />

    <!-- ================= MODAL CARTERAS ================= -->
    <CarterasModal
      v-model="showCarterasModal"
      :carteras="selectedCarteras"
    />
  </div>
</template>

<script>
/* ───────────── IMPORTS ───────────── */
import axios from 'axios'
import { mapState, mapMutations } from 'vuex'

/* Componentes hijos extraídos */
import CreditRequestTable from './CreditRequestTable.vue'
import ManualVisadoModal  from './ManualVisadoModal.vue'
import CarterasModal      from './CarterasModal.vue'

/* Loading overlay (vue-loading-overlay) */
import 'vue-loading-overlay/dist/vue-loading.css'
import Loading from 'vue-loading-overlay'

export default {
  name: 'CreditRequestsList',

  /* ───────────── COMPONENTES ───────────── */
  components: {
    Loading,
    CreditRequestTable,
    ManualVisadoModal,
    CarterasModal
  },

  /* ───────────── PROPS ───────────── */
  props: {
    user: { type: Object, required: true }
  },

  /* ───────────── DATOS ───────────── */
  data() {
    return {
      credits: [],
      isLoading: false,
      showTable: true,

      /* ===== Visado Manual ===== */
      showVisadoManualModal: false,
      visadoForm: null,

      /* ===== Carteras ===== */
      showCarterasModal: false,
      selectedCarteras: [],

      /* ===== Flags y helpers ===== */
      flag: false,
      dataclient: {
        doc: '',
        name: '',
        cuotadeseada: 0,
        monto: 0,
        plazo: null,
        pagaduria: null,
        pagadurias: null,
        pagaduriaKey: null,
        visado: null
      },

      /* Mapa completo de pagadurías (docentes + pensionados) */
      allPagaduriasMap: {
        /* Pensionados */
        200: 'COLPENSIONES', 201: 'FOPEP', 297: 'FIDUPREVISORA', 296: 'CASUR',
        /* Docentes (ordenados) */
        1:'SED AMAZONAS',130:'SED ANTIOQUIA',109:'SED ARAUCA',121:'SED ATLANTICO',
        293:'SED BOLIVAR',110:'SED BOYACA',139:'SED CALDAS',140:'SED CAQUETA',
        104:'SED CASANARE',177:'SED CAUCA',11:'SED CESAR',294:'SED CHOCO',
        182:'SED CORDOBA',163:'SED CUNDINAMARCA',192:'SED GUAJIRA',173:'SED GUAVIARE',
        178:'SED HUILA',145:'SED MAGDALENA',113:'SED META',143:'SED NARIÑO',
        154:'SED N. SANTANDER',184:'SED PUTUMAYO',166:'SED QUINDIO',114:'SED RISARALDA',
        26:'SED SANTANDER',175:'SED SUCRE',122:'SED TOLIMA',165:'SED VALLE',
        132:'SED VAUPES',32:'SED VICHADA',27:'SEM SINCELEJO',34:'SEM ARMENIA',
        160:'SEM BARRANCABERMEJA',106:'SEM BARRANQUILLA',111:'SEM BELLO',39:'SEM BUCARAMANGA',
        40:'SEM BUENAVENTURA',157:'SEM BUGA',191:'SEM CALI',189:'SEM CARTAGENA',
        136:'SEM CARTAGO',45:'SEM CHÍA',103:'SEM CIÉNAGA',286:'SEM CÚCUTA',
        112:'SEM DOSQUEBRADAS',49:'SEM DUITAMA',115:'SEM ENVIGADO',168:'SEM ESTRELLA',
        164:'SEM FACATATIVA',55:'SEM FLORENCIA',170:'SEM FLORIDABLANCA',117:'SEM FUNZA',
        151:'SEM FUSAGASUGÁ',179:'SEM GIRARDOT',287:'SEM GIRÓN',116:'SEM GUAINÍA',
        147:'SEM IBAGUÉ',134:'SEM IPIALES',135:'SEM ITAGÜÍ',146:'SEM JAMUNDÍ',
        67:'SEM LORICA',133:'SEM MAGANGUÉ',69:'SEM MAICAO',161:'SEM MALAMBO',
        174:'SEM MANIZALES',180:'SEM MEDELLÍN',176:'SEM MONTERÍA',153:'SEM MOSQUERA',
        105:'SEM NEIVA',152:'SEM PALMIRA',125:'SEM PASTO',78:'SEM PEREIRA',
        79:'SEM PIEDECUESTA',138:'SEM PITALITO',162:'SEM QUIBDÓ',150:'SEM RIOHACHA',
        129:'SEM RIONEGRO',108:'SEM SABANETA',142:'SEM SAHAGÚN',158:'SEM SAN ANDRES',
        126:'SEM SANTA MARTA',119:'SEM SOACHA',172:'SEM SOGAMOSO',123:'SEM SOLEDAD',
        120:'SEM TULUÁ',93:'SEM TUMACO',141:'SEM TUNJA',137:'SEM TURBO',
        144:'SEM URIBIA',171:'SEM VALLEDUPAR',124:'SEM VILLAVICENCIO',289:'SEM YOPAL',
        169:'SEM YUMBO',156:'SEM ZIPAQUIRÁ'
      }
    }
  },

  /* ───────────── COMPUTED ───────────── */
  computed: {
    ...mapState('datamesModule', ['datamesSed']),
    ...mapState('pagaduriasModule', ['pagaduriasTypes']),
    isAdminSistema() {
      return this.user.role_id === 1
    }
  },

  /* ───────────── CICLO DE VIDA ───────────── */
  mounted() {
    this.fetchCredits()
  },

  /* ───────────── MÉTODOS ───────────── */
  methods: {
    /* ====== Vuex mutations usadas ====== */
    ...mapMutations('datamesModule', ['setDatamesSed', 'setCuotaDeseada']),
    ...mapMutations('pagaduriasModule', [
      'setPagaduriaType',
      'setPagaduriaLabel',
      'setCouponsType',
      'setSelectedPeriod'
    ]),
    ...mapMutations('embargosModule', ['setEmbargosType']),
    ...mapMutations('descuentosModule', ['setDescuentosType']),

    /* ---------------- 1. Traer créditos ---------------- */
    async fetchCredits() {
      this.isLoading = true
      try {
        const { data } = await axios.get('/credit-requests/all')
        this.credits = data
      } catch (err) {
        console.error('Error al obtener créditos', err)
      } finally {
        this.isLoading = false
      }
    },

    /* ---------------- 2. Abrir Modal Visar Manualmente ---------------- */
    onOpenVisado(credit) {
      this.visadoForm = {
        doc:          credit.doc || '',
        nombre:       credit.name || '',
        pagaduria:    this.getPagaduriaNameById(credit.pagaduria_id),
        plazo:        credit.plazo || '',
        monto:        credit.monto || '',
        cuotacredito: credit.cuota || '',
        estado:       credit.status || 'factible',
        causal:       '',
        visado_id:    credit.visado_id || null,
        creditId:     credit.id,
        observacion:  ''
      }
      this.showVisadoManualModal = true
    },

    /* ---------------- 3. Ver Carteras ---------------- */
    onViewCarteras(credit) {
      this.selectedCarteras = credit.carteras || []
      this.showCarterasModal = true
    },

    /* ---------------- 4. Confirmar Visado Automático ---------------- */
    async modalConfirmConsultPag(credit) {
      const ok = await this.$bvModal.msgBoxConfirm(
        'Esta acción tiene un costo',
        {
          title: '¿Está seguro que desea realizar la consulta?',
          size: 'sm',
          buttonSize: 'sm',
          okVariant: 'success',
          okTitle: 'Consultar',
          cancelTitle: 'Cancelar',
          cancelVariant: 'danger',
          centered: true
        }
      )
      if (!ok) return

      this.isLoading = true
      try {
        /* --- Paso 1: obtener pagadurías --- */
        const { data: pagadurias } = await axios.get(
          `/pagadurias/per-doc/${credit.doc}`
        )
        if (Object.keys(pagadurias).length === 0) {
          toastr.info('No tenemos información de este documento')
          return
        }
        this.dataclient.pagadurias = pagadurias
        this.setCuotaDeseada(credit.cuota)

        /* --- Paso 2: mapear pagaduría --- */
        const pagaduriaName = this.getPagaduriaNameById(credit.pagaduria_id)
        const normalize = str => str?.toString().toUpperCase().replace(/\s+/g, '')
        const pagaduriaValue = normalize(pagaduriaName)
        this.setPagaduriaType(pagaduriaValue)

        const type = this.pagaduriasTypes.find(
          t => normalize(t.value) === pagaduriaValue
        )
        if (!type || !this.dataclient.pagadurias[type.key]) {
          toastr.warning('Formato de pagaduría no válido o no encontrado.')
          return
        }

        const pagaduria = this.dataclient.pagadurias[type.key]
        this.dataclient.pagaduriaKey = type.key.slice(7).toLowerCase()
        this.setPagaduriaLabel(type.label)

        /* Ajustes específicos */
        const baseKey = type.key.includes('datames')
          ? type.key.slice(7)
          : type.key
        this.setCouponsType(
          type.key.includes('datames') ? `Coupons${baseKey}` : type.key
        )
        this.setEmbargosType(
          type.key.includes('datames') ? `Embargos${baseKey}` : type.key
        )
        this.setDescuentosType(
          type.key.includes('datames') ? `Descuentos${baseKey}` : type.key
        )
        this.setDatamesSed(pagaduria)

        /* --- Paso 3: guardar visado --- */
        const status = await this.saveVisados(credit)
        if (status === 201) {
          const payload = { ...credit, carteras: credit.carteras || [], visado:   this.dataclient.visado, creditId: credit.id    }
          this.$emit('emitInfo', payload)
          this.flag = true
          this.showTable = false
        }
      } catch (err) {
        console.error('Error en visado automático', err)
        toastr.error('Error durante el proceso de visado')
      } finally {
        this.isLoading = false
      }
    },

    /* ---------------- 5. Guardar Visado (API) ---------------- */
    async saveVisados(credit) {
      try {
        const { data: demografico } = await axios.get(
          `/demografico/${credit.doc}`
        )
        if (!demografico.nombre_usuario) {
          toastr.error('No se encontró el nombre del usuario')
          return
        }
        const payload = {
          pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
          nombre: demografico.nombre_usuario,
          doc: credit.doc,
          plazo: credit.plazo
        }
        const res = await axios.post('/visados', payload)
        this.dataclient.visado = res.data
        return res.status
      } catch (e) {
        toastr.error('Error al guardar el visado')
        throw e
      }
    },

    /* ---------------- Helpers ---------------- */
    getPagaduriaNameById(id) {
      return this.allPagaduriasMap[id] || `ID: ${id}`
    }
  }
}
</script>

<style scoped>
.table-container {
  width: 100%;
  padding: 20px;
}

/* Reutilizamos estilo del proyecto para botones credit */
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
