<template>
  <div class="table-container">
    <loading :active.sync="isLoading" :is-full-page="true" color="#0CEDB0" :can-cancel="false" />

    <CreditRequestTable
      v-if="showTable"
      :credits="credits"
      :is-admin="isAdminSistema"
      @open-visado="onOpenVisado"
      @view-carteras="onViewCarteras"
      @visar="modalConfirmConsultPag"
    />

    <ManualVisadoModal
      v-model="showVisadoManualModal"
      :visado-form="visadoForm"
      @saved="fetchCredits"
    />

    <CarterasModal
      v-model="showCarterasModal"
      :carteras="selectedCarteras"
    />
  </div>
</template>

<script>
import axios from 'axios'
import { mapState, mapMutations } from 'vuex'
import CreditRequestTable from './CreditRequestTable.vue'
import ManualVisadoModal from './ManualVisadoModal.vue'
import CarterasModal from './CarterasModal.vue'
import 'vue-loading-overlay/dist/vue-loading.css'
import Loading from 'vue-loading-overlay'

export default {
  name: 'CreditRequestsList',
  components: { Loading, CreditRequestTable, ManualVisadoModal, CarterasModal },
  props: { user: { type: Object, required: true } },
  data () {
    return {
      credits: [],
      isLoading: false,
      showTable: true,
      showVisadoManualModal: false,
      visadoForm: null,
      showCarterasModal: false,
      selectedCarteras: [],
      flag: false,
      dataclient: {
        doc: '', name: '', cuotadeseada: 0, monto: 0, plazo: null,
        pagaduria: null, pagadurias: null, pagaduriaKey: null, visado: null
      },
      allPagaduriasMap: {
        200:'COLPENSIONES',201:'FOPEP',297:'FIDUPREVISORA',296:'CASUR',
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
        136:'SEM CARTAGO',45:'SEM CHIA',103:'SEM CIENAGA',286:'SEM CUCUTA',
        112:'SEM DOSQUEBRADAS',49:'SEM DUITAMA',115:'SEM ENVIGADO',168:'SEM ESTRELLA',
        164:'SEM FACATATIVA',55:'SEM FLORENCIA',170:'SEM FLORIDABLANCA',117:'SEM FUNZA',
        151:'SEM FUSAGASUGA',179:'SEM GIRARDOT',287:'SEM GIRON',116:'SEM GUAINIA',
        147:'SEM IBAGUE',134:'SEM IPIALES',135:'SEM ITAGUI',146:'SEM JAMUNDI',
        67:'SEM LORICA',133:'SEM MAGANGUE',69:'SEM MAICAO',161:'SEM MALAMBO',
        174:'SEM MANIZALES',180:'SEM MEDELLIN',176:'SEM MONTERIA',153:'SEM MOSQUERA',
        105:'SEM NEIVA',152:'SEM PALMIRA',125:'SEM PASTO',78:'SEM PEREIRA',
        79:'SEM PIEDECUESTA',138:'SEM PITALITO',162:'SEM QUIBDO',150:'SEM RIOHACHA',
        129:'SEM RIONEGRO',108:'SEM SABANETA',142:'SEM SAHAGUN',158:'SEM SAN ANDRES',
        126:'SEM SANTA MARTA',119:'SEM SOACHA',172:'SEM SOGAMOSO',123:'SEM SOLEDAD',
        120:'SEM TULUA',93:'SEM TUMACO',141:'SEM TUNJA',137:'SEM TURBO',
        144:'SEM URIBIA',171:'SEM VALLEDUPAR',124:'SEM VILLAVICENCIO',289:'SEM YOPAL',
        169:'SEM YUMBO',156:'SEM ZIPAQUIRA'
      }
    }
  },
  computed: {
    ...mapState('pagaduriasModule', ['pagaduriasTypes']),
    isAdminSistema () { return this.user.role_id === 1 },
    cleanTypes () {
      return this.pagaduriasTypes
        .filter(x => x && x.label)
        .map(t => ({
          key: t.label.trim().toUpperCase(),
          label: t.label.trim().toUpperCase(),
          value: t.label.trim().toUpperCase().replace(/\s+/g,''),
          slug: (t.slug || t.label).trim()
        }))
    }
  },
  async mounted () {
    await this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
    this.fetchCredits()
  },
  methods: {
    ...mapMutations('datamesModule', ['setDatamesSed', 'setCuotaDeseada']),
    ...mapMutations('pagaduriasModule', [
      'setPagaduriaType','setPagaduriaLabel','setCouponsType','setSelectedPeriod'
    ]),
    ...mapMutations('embargosModule', ['setEmbargosType']),
    ...mapMutations('descuentosModule', ['setDescuentosType']),

    async fetchCredits () {
      this.isLoading = true
      try {
        const { data } = await axios.get('/credit-requests/all')
        this.credits = data
      } finally { this.isLoading = false }
    },

    onOpenVisado (c) {
      this.visadoForm = {
        doc:c.doc||'',nombre:c.name||'',pagaduria:this.getPagaduriaNameById(c.pagaduria_id),
        plazo:c.plazo||'',monto:c.monto||'',cuotacredito:c.cuota||'',
        estado:c.status||'factible',causal:'',visado_id:c.visado_id||null,
        creditId:c.id,observacion:''
      }
      this.showVisadoManualModal = true
    },

    onViewCarteras (c) {
      this.selectedCarteras = c.carteras || []
      this.showCarterasModal = true
    },

    async modalConfirmConsultPag (credit) {
      const ok = await this.$bvModal.msgBoxConfirm('Esta acción tiene un costo',{
        title:'¿Está seguro que desea realizar la consulta?', size:'sm', buttonSize:'sm',
        okVariant:'success', okTitle:'Consultar', cancelTitle:'Cancelar', cancelVariant:'danger', centered:true
      })
      if (!ok) return
      this.isLoading = true
      try {
        if (!this.pagaduriasTypes.length) {
          await this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
        }

        const { data: pagadurias } = await axios.get(`/pagadurias/per-doc/${credit.doc}`)
        if (!Object.keys(pagadurias).length) { toastr.info('No tenemos información de este documento'); return }
        this.dataclient.pagadurias = pagadurias
        this.setCuotaDeseada(credit.cuota)

        const pagaduriaName = this.getPagaduriaNameById(credit.pagaduria_id)
        const pagValue = pagaduriaName.toUpperCase().replace(/\s+/g,'')
        this.setPagaduriaType(pagValue)

        const type = this.cleanTypes.find(t => t.value === pagValue)
        if (!type) { toastr.warning('Pagaduría no encontrada.'); return }

        if (!this.dataclient.pagadurias[type.key]) {
          toastr.warning('La pagaduría no está disponible para este cliente')
          return
        }

        this.dataclient.pagaduriaKey = type.key.slice(3).toLowerCase()
        this.setPagaduriaLabel(type.key)
        this.setCouponsType(`Coupons${type.key.replace(/\s+/g,'')}`)
        this.setEmbargosType(`Embargos${type.key.replace(/\s+/g,'')}`)
        this.setDescuentosType(`Descuentos${type.key.replace(/\s+/g,'')}`)
        this.setDatamesSed(this.dataclient.pagadurias[type.key])

        const status = await this.saveVisados(credit)
        if (status === 201) {
          const payload = {...credit, carteras: credit.carteras||[], visado:this.dataclient.visado, creditId:credit.id}
          this.$emit('emitInfo', payload)
          this.flag = true
          this.showTable = false
        }
      } finally { this.isLoading = false }
    },

    async saveVisados (credit) {
      const { data: demografico } = await axios.get(`/demografico/${credit.doc}`)
      if (!demografico.nombre_usuario) { toastr.error('No se encontró el nombre del usuario'); return }
      const payload = {
        pagaduria: this.getPagaduriaNameById(credit.pagaduria_id),
        nombre: demografico.nombre_usuario, doc: credit.doc, plazo: credit.plazo
      }
      const res = await axios.post('/visados', payload)
      this.dataclient.visado = res.data
      return res.status
    },

    getPagaduriaNameById (id) { return this.allPagaduriasMap[id] || `ID:${id}` }
  }
}
</script>

<style scoped>
.table-container{width:100%;padding:20px}
.btn-credit{color:#fff;background:#0cedb0;border:none;border-radius:5px;padding:7px 14px;font-size:14px;cursor:pointer;margin:2px}
</style>
