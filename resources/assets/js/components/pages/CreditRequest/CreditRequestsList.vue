<template>
  <div class="table-container">
    <loading :active.sync="isLoading" :is-full-page="true" color="#0CEDB0" :can-cancel="false" />
    <div class="filters">
      <div class="row">
        <div class="col-sm-3 mb-2">
          <label>Buscar</label>
          <input v-model.trim="filters.q" type="text" class="form-control" placeholder="Cédula, nombre, empresa" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Estado</label>
          <select v-model="filters.status" class="form-control">
            <option value="">Todos</option>
            <option value="aprobado">Aprobado</option>
            <option value="rechazado">Rechazado</option>
            <option value="pendiente">Pendiente</option>
          </select>
        </div>
        <div class="col-sm-3 mb-2">
          <label>Pagaduría</label>
          <select v-model="filters.pagaduria_id" class="form-control">
            <option value="">Todas</option>
            <option v-for="opt in pagaduriaOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>
        <div class="col-sm-2 mb-2">
          <label>Tipo crédito</label>
          <select v-model="filters.tipo_credito" class="form-control">
            <option value="">Todos</option>
            <option v-for="t in tipoCreditoOptions" :key="t" :value="t">{{ t }}</option>
          </select>
        </div>
        <div class="col-sm-2 mb-2">
          <label>Empresa</label>
          <select v-model="filters.empresa" class="form-control">
            <option value="">Todas</option>
            <option v-for="e in empresaOptions" :key="e" :value="e">{{ e }}</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-2 mb-2">
          <label>Fecha desde</label>
          <input v-model="filters.from" type="date" class="form-control" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Fecha hasta</label>
          <input v-model="filters.to" type="date" class="form-control" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Monto mín</label>
          <input v-model.number="filters.monto_min" type="number" class="form-control" min="0" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Monto máx</label>
          <input v-model.number="filters.monto_max" type="number" class="form-control" min="0" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Plazo mín</label>
          <input v-model.number="filters.plazo_min" type="number" class="form-control" min="0" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Plazo máx</label>
          <input v-model.number="filters.plazo_max" type="number" class="form-control" min="0" />
        </div>
      </div>

      <div class="row">
        <div class="col-sm-2 mb-2">
          <label>Tasa mín (%)</label>
          <input v-model.number="filters.tasa_min" type="number" class="form-control" min="0" step="0.01" />
        </div>
        <div class="col-sm-2 mb-2">
          <label>Tasa máx (%)</label>
          <input v-model.number="filters.tasa_max" type="number" class="form-control" min="0" step="0.01" />
        </div>
        <div class="col-sm-2 d-flex align-items-end mb-2">
          <button class="btn btn-outline-secondary w-100" @click="resetFilters">Limpiar</button>
        </div>
        <div class="col-sm-2 d-flex align-items-end mb-2">
          <button class="btn btn-success w-100" @click="exportCSV">Exportar CSV</button>
        </div>
        <div class="col-sm-4 d-flex align-items-end justify-content-end mb-2">
          <div class="results-pill">Mostrando {{ filteredCredits.length }} de {{ credits.length }}</div>
        </div>
      </div>
    </div>

    <CreditRequestTable
      v-if="showTable"
      :credits="filteredCredits"
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
      filters: {
        q: '',
        status: '',
        pagaduria_id: '',
        tipo_credito: '',
        empresa: '',
        from: '',
        to: '',
        monto_min: null,
        monto_max: null,
        plazo_min: null,
        plazo_max: null,
        tasa_min: null,
        tasa_max: null
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
    pagaduriaOptions () {
      return Object.entries(this.allPagaduriasMap)
        .map(([value,label]) => ({ value: Number(value), label }))
        .sort((a,b)=>a.label.localeCompare(b.label))
    },
    empresaOptions () {
      const set = new Set(this.credits.map(c => c.empresa).filter(Boolean))
      return Array.from(set).sort((a,b)=>a.localeCompare(b))
    },
    tipoCreditoOptions () {
      const set = new Set(this.credits.map(c => c.tipo_credito).filter(Boolean))
      return Array.from(set).sort((a,b)=>a.localeCompare(b))
    },
    filteredCredits () {
      const q = (this.filters.q || '').toLowerCase()
      const status = (this.filters.status || '').toLowerCase()
      const pid = this.filters.pagaduria_id
      const tcred = this.filters.tipo_credito || ''
      const emp = this.filters.empresa || ''
      const from = this.filters.from ? new Date(this.filters.from + 'T00:00:00') : null
      const to = this.filters.to ? new Date(this.filters.to + 'T23:59:59') : null
      const mmin = this.filters.monto_min ?? null
      const mmax = this.filters.monto_max ?? null
      const pmin = this.filters.plazo_min ?? null
      const pmax = this.filters.plazo_max ?? null
      const rmin = this.filters.tasa_min ?? null
      const rmax = this.filters.tasa_max ?? null

      return this.credits.filter(c => {
        if (q) {
          const hay = [c.doc, c.name, c.empresa].map(v => (v || '').toString().toLowerCase())
          if (!hay.some(v => v.includes(q))) return false
        }
        if (status) {
          const s = (c.status || '').toString().toLowerCase()
          if (status === 'aprobado' && !(s.includes('factible') || s.includes('approved'))) return false
          if (status === 'rechazado' && !(s.includes('no factible') || s.includes('rejected'))) return false
          if (status === 'pendiente' && (s.includes('factible') || s.includes('no factible') || s.includes('approved') || s.includes('rejected'))) return false
        }
        if (pid && Number(c.pagaduria_id) !== Number(pid)) return false
        if (tcred && (c.tipo_credito || '') !== tcred) return false
        if (emp && (c.empresa || '') !== emp) return false
        if (from || to) {
          const created = c.created_at ? new Date(c.created_at) : null
          if (!created) return false
          if (from && created < from) return false
          if (to && created > to) return false
        }
        if (mmin !== null && Number(c.monto || 0) < Number(mmin)) return false
        if (mmax !== null && Number(c.monto || 0) > Number(mmax)) return false
        if (pmin !== null && Number(c.plazo || 0) < Number(pmin)) return false
        if (pmax !== null && Number(c.plazo || 0) > Number(pmax)) return false
        if (rmin !== null && Number(c.tasa || 0) < Number(rmin)) return false
        if (rmax !== null && Number(c.tasa || 0) > Number(rmax)) return false
        return true
      })
    }
  },
  async mounted () {
    await this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
    this.restoreFilters()
    this.fetchCredits()
  },
  watch: {
    filters: {
      deep: true,
      handler () { this.persistFilters() }
    }
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
        this.credits = data || []
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
        const type = (this.pagaduriasTypes||[])
          .filter(x => x && x.label)
          .map(t => ({
            key: t.label.trim().toUpperCase(),
            value: t.label.trim().toUpperCase().replace(/\s+/g,'')
          }))
          .find(t => t.value === pagValue)
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
    getPagaduriaNameById (id) { return this.allPagaduriasMap[id] || `ID:${id}` },
    resetFilters () {
      this.filters = {
        q: '',
        status: '',
        pagaduria_id: '',
        tipo_credito: '',
        empresa: '',
        from: '',
        to: '',
        monto_min: null,
        monto_max: null,
        plazo_min: null,
        plazo_max: null,
        tasa_min: null,
        tasa_max: null
      }
    },
    persistFilters () {
      try { localStorage.setItem('cr_filters', JSON.stringify(this.filters)) } catch(e) {}
    },
    restoreFilters () {
      try {
        const raw = localStorage.getItem('cr_filters')
        if (raw) this.filters = Object.assign({}, this.filters, JSON.parse(raw))
      } catch(e) {}
    },
    exportCSV () {
      const rows = this.filteredCredits
      const headers = ['ID','Creado','Cédula','Nombre','Tipo Solicitante','Tipo Pensión','Resolución','Empresa','Pagaduría','Cuota','Monto','Tasa','Plazo','Estado','Tipo Crédito']
      const body = rows.map(r => ([
        r.id,
        r.created_at,
        r.doc,
        r.name,
        r.client_type || '',
        r.tipo_pension || '',
        r.resolucion || '',
        r.empresa || '',
        this.getPagaduriaNameById(r.pagaduria_id),
        r.cuota ?? '',
        r.monto ?? '',
        r.tasa ?? '',
        r.plazo ?? '',
        r.status || '',
        r.tipo_credito || ''
      ]))
      const csv = [headers, ...body].map(line => line.map(v => `"${(v??'').toString().replace(/"/g,'""')}"`).join(',')).join('\n')
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
      const url = URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = 'solicitudes_filtradas.csv'
      a.click()
      URL.revokeObjectURL(url)
    }
  }
}
</script>

<style scoped>
.table-container{width:100%;padding:20px}
.filters{background:#fff;border:1px solid #e7eaee;border-radius:8px;padding:12px 12px 4px;margin-bottom:16px}
.results-pill{background:#0cedb0;color:#003b2c;padding:6px 12px;border-radius:999px;font-weight:600}
.btn-success{background:#0cedb0;border-color:#0cedb0}
</style>
