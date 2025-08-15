<template>
  <div class="container-fluid bg-soft p-0">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="true" color="#0CEDB0" />
    <b-toast id="toast-incapacidad-month" title="Alerta del Sistema" solid auto-hide-delay="10000" variant="info">
      Cliente con incapacidad mayor a 2 meses.
    </b-toast>

    <div v-if="type_consult === 'individual'" class="page-wide">
      <div class="mb-3" v-if="datamesFopep">
        <div class="panel">
          <div class="panel-body d-flex align-items-center">
            <img src="/img/avatar-img.svg" width="56" class="mr-3" />
            <h2 class="heading-title mb-0">{{ datamesFopep.nomp }}</h2>
          </div>
        </div>
      </div>

      <div id="consulta-container" class="w-100">
        <!-- Formulario (se oculta cuando se bloquea tras elegir pagaduría) -->
        <FormConsult
          v-if="!formLocked"
          ref="formConsult"
          :user="user"
          @emitInfo="emitInfo"
          @downloadPdf="print"
        />

        <!-- Barra de acciones resumida una vez bloqueado -->
        <div class="mb-3" v-if="currentDoc && formLocked">
          <div class="panel">
            <div class="panel-body action-bar">
              <div class="d-flex align-items-center">
                <label class="mr-2 mb-0 label-strong">Cédula</label>
                <input class="ui-input mr-2 input-inline" :value="currentDoc" readonly />
                <button class="btn btn-sm btn-outline-primary" @click="refreshConsulta" :disabled="isLoading">Actualizar</button>
              </div>
              <div class="d-flex align-items-center">
                <span class="chip mr-2">Pagaduría: {{ pagaduriaLabel || '—' }}</span>
                <button class="btn btn-sm btn-outline-secondary" @click="print">Descargar PDF</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenidos del índice (aparecen cuando formLocked = true) -->
        <div v-if="formLocked" class="w-100">
          <div class="panel" v-if="normalizedPagaduriaType === 'FOPEP' && datamesFopep">
            <div class="panel-body">
              <h4 class="section-title mb-3">Detalle FOPEP</h4>
              <DatamesComponent :user="user" :datamesFopep="datamesFopep" />
            </div>
          </div>

          <div class="panel" v-if="normalizedPagaduriaType === 'FIDUPREVISORA' && datamesFidu">
            <div class="panel-body">
              <h4 class="section-title mb-3">Detalle Fiduprevisora</h4>
              <DatamesFidu :user="user" :datamesFidu="datamesFidu" />
            </div>
          </div>

          <div
            class="panel"
            v-if="datamesSed && !['FOPEP','FIDUPREVISORA','CASUR','COLPENSIONES'].includes(normalizedPagaduriaType)"
          >
            <div class="panel-body">
              <h4 class="section-title mb-3">Detalle Pagaduría</h4>
              <DatamesData class="col-12 p-0" />
            </div>
          </div>

          <template v-if="fechavinc">
            <div class="panel">
              <div class="panel-body">
                <h4 class="section-title mb-3">Historial laboral</h4>
                <EmploymentHistory2 class="col-12 p-0" :fechavinc="fechavinc" :datamesFidu="datamesFidu" :user="user" />
              </div>
            </div>
            <div class="panel">
              <div class="panel-body">
                <EmploymentHistory
                  class="col-12 p-0"
                  :fechavinc="fechavinc"
                  :datamesFidu="datamesFidu"
                  :datamessemcali="datamessemcali"
                  :user="user"
                />
              </div>
            </div>
            <div class="panel detallecliente-top-margin">
              <div class="panel-body">
                <h4 class="section-title mb-3">Totales</h4>
                <Detallecliente :totales="totalesData" />
              </div>
            </div>
          </template>

          <div class="panel mb-3" v-if="carterasCargadas && carteras.length > 0">
            <div class="panel-body">
              <h4 class="section-title mb-3">Carteras de la Solicitud</h4>
              <div class="table-responsive">
                <table class="table table-modern">
                  <thead>
                    <tr>
                      <th>Tipo de Cartera</th>
                      <th>Entidad</th>
                      <th>Valor Cuota</th>
                      <th>Saldo</th>
                      <th>Opera en desprendible</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(cart, idx) in carteras" :key="idx">
                      <td>{{ cart.tipo_cartera }}</td>
                      <td>{{ cart.nombre_entidad }}</td>
                      <td>{{ cart.valor_cuota }}</td>
                      <td>{{ cart.saldo }}</td>
                      <td>
                        <span :class="['pill', cart.opera_x_desprendible ? 'pill-success' : 'pill-muted']">
                          {{ cart.opera_x_desprendible ? 'Sí' : 'No' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <template v-if="showOthers">
            <div class="panel">
              <div class="panel-body">
                <h4 class="section-title mb-3">Descuentos y Embargos</h4>
                <DescapliEmpty v-if="showDescapli" :disabledProspect="disabledProspect" />
                <hr class="divider" />
                <DescnoapEmpty v-if="normalizedPagaduriaType === 'FIDUPREVISORA'" />
                <Descnoap v-if="normalizedPagaduriaType === 'FOPEP'" :descnoap="descnoap" />
                <EmbargosEmpty v-if="normalizedPagaduriaType === 'SED'" :embargosempty="embargosempty" />
                <Embargos v-else :selectedPeriod="selectedPeriod" />
                <hr class="divider" />
                <DescuentosEmpty v-if="normalizedPagaduriaType === 'SED'" :descuentosempty="descuentosempty" />
                <Descuentos :selectedPeriod="selectedPeriod" v-else />
                <hr class="divider" />
                <div class="d-flex justify-content-end align-items-center">
                  <CustomButton class="primary" text="Visar" style="width: 164px" @click="visadoFunction" />
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormConsult from './FormConsult'
import EmploymentHistory from './EmploymentHistory'
import EmploymentHistory2 from './EmploymentHistory2'
import DatamesComponent from './Datames.vue'
import CustomButton from '../../customComponents/CustomButton.vue'
import DatamesData from './DatamesData'
import DatamesFidu from './DatamesFidu'
import DescapliEmpty from './DescapliEmpty.vue'
import Descnoap from './Descnoap'
import DescnoapEmpty from './DescnoapEmpty'
import Embargos from './Embargos.vue'
import EmbargosEmpty from './EmbargosEmpty'
import Descuentos from './Descuentos.vue'
import DescuentosEmpty from './DescuentosEmpty'
import Detallecliente from './Detallecliente'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import { mapActions, mapState, mapGetters } from 'vuex'

export default {
  props: ['user'],
  components: {
    FormConsult,
    EmploymentHistory,
    EmploymentHistory2,
    DatamesComponent,
    DatamesData,
    DatamesFidu,
    DescapliEmpty,
    Descnoap,
    DescnoapEmpty,
    Embargos,
    Detallecliente,
    Descuentos,
    DescuentosEmpty,
    EmbargosEmpty,
    CustomButton,
    Loading
  },
  data() {
    return {
      type_consult: 'individual',
      formLocked: false,
      fechavinc: null,
      datamesFopep: null,
      datamessedvalle: null,
      datamesFidu: null,
      datamessemcali: null,
      descapli: [],
      descnoap: [],
      embargossedvalle: [],
      embargossedchoco: [],
      embargossedatlantico: [],
      embargossedcauca: [],
      embargossemquibdo: [],
      embargossempopayan: [],
      embargossemcali: [],
      embargosempty: [],
      descuentossedvalle: [],
      descuentosempty: [],
      descuentossedchoco: [],
      descuentossedcauca: [],
      descuentossemcali: [],
      descuentossemquibdo: [],
      descuentossemsahagun: [],
      descuentossempopayan: [],
      selectedPeriod: '',
      monto: 0,
      pagaduriaKey: '',
      cargo: null,
      showOthers: false,
      pagadurias: null,
      isLoading: false,
      disabledProspect: false,
      visado: null,
      visadoValido: 'NO FACTIBLE',
      totalesData: {
        libreInversion: 0,
        libreInversionSuma: 0,
        compraCartera: 0,
        cuotaMaxima: 0
      },
      showCarterasModal: false,
      carteras: [],
      carterasCargadas: false,
      lastQuery: null
    }
  },
  watch: {
    couponsIngresos: {
      handler() { this.calcularTotales() },
      deep: true,
      immediate: true
    },
    ingresosExtras() {
      let totalIncapacidad = 0
      this.ingresosExtras.some(item => {
        if (item.concept.toLowerCase().includes('definitiva')) {
          this.alertDefinitiva({ message: 'Cliente en proceso de retiro', variant: 'danger' })
          return true
        }
      })
      this.ingresosExtras.forEach(item => {
        if (item.concept.toLowerCase().includes('incapacidad')) totalIncapacidad += Number(item.ingresos)
      })
      if (Number(totalIncapacidad) > Number(this.valorIngreso)) {
        this.alertIncapacidad({ message: 'Cliente no apto por incapacidad', variant: 'danger' })
      }
    }
  },
  mounted() {
    if (this.couponsIngresos) this.calcularTotales()
  },
  computed: {
    ...mapState('pagaduriasModule', ['coupons', 'couponsType', 'pagaduriaType', 'pagaduriaLabel', 'pagaduriasTypes']),
    ...mapGetters('pagaduriasModule', [
      'couponsPerPeriod',
      'valorIngreso',
      'ingresosIncapacidadPerPeriod',
      'incapacidadValida',
      'couponsIngresos',
      'ingresosExtras'
    ]),
    ...mapState('embargosModule', ['embargosType']),
    ...mapState('descuentosModule', ['descuentosType']),
    ...mapState('datamesModule', ['datamesSed', 'cuotadeseada', 'conteoEgresosPlus']),
    ...mapGetters('descuentosModule', ['descuentosPerPeriod']),
    normalizedPagaduriaType() {
      return (this.pagaduriaType || '').toString().trim().toUpperCase()
    },
    showDescapli() {
      const normalize = s => (s || '').replace(/\s+/g, '').toUpperCase()
      const current = normalize(this.pagaduriaType)
      return this.pagaduriasTypes.some(p => normalize(p.value) === current)
    },
    currentDoc() {
      if (this.lastQuery?.doc) return this.lastQuery.doc
      const a = this.couponsIngresos?.items?.[0]?.doc
      return a || null
    }
  },
  created() {
    if (!this.pagaduriasTypes.length) this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
  },
  methods: {
    ...mapActions('pagaduriasModule', ['fetchCoupons']),
    ...mapActions('embargosModule', ['fetchEmbargos']),
    ...mapActions('descuentosModule', ['fetchDescuentos']),
    emitInfo(payload) {
      this.formLocked = true
      this.isLoading = true
      this.lastQuery = payload
      this.pagadurias = payload.pagadurias
      this.pagaduriaKey = payload.pagaduriaKey
      this.cargo = payload.cargo
      this.datamesFopep = this.datamessedvalle = this.datamesFidu = this.datamessemcali = null
      this.visado = payload.visado
      this.monto = payload.monto
      if (payload.carteras) {
        this.carteras = payload.carteras
        this.carterasCargadas = true
      }
      this.getCoupons({ doc: payload.doc, pagaduria: this.couponsType, pagaduriaLabel: payload.pagaduria })
      this.getDescapli(payload)
      this.getDescnoap(payload)
      this.getEmbargos({ doc: payload.doc, pagaduria: this.embargosType, pagaduriaLabel: payload.pagaduria })
      this.getDescuentos({ doc: payload.doc, pagaduria: this.descuentosType, pagaduriaLabel: payload.pagaduria })
      const pag = (payload.pagaduria || '').toString().trim().toUpperCase()
      if (pag === 'FOPEP') this.getDatames(payload)
      else if (pag === 'SEDVALLE') this.getDatamesSedValle(payload)
      else if (pag === 'FIDUPREVISORA') this.getDatamesFidu(payload)
      else if (pag === 'SEMCALI') this.getDatamesSemCali(payload)
      this.getFechaVinc(payload).then(() => {
        this.showOthers = true
        this.isLoading = false
      })
    },
    async getDatames(payload) { this.datamesFopep = (await axios.get(`/datamesfopep/${payload.doc}`)).data },
    async getDatamesSedValle(payload) { this.datamessedvalle = (await axios.post('/datamessedvalle/consultaUnitaria', { doc: payload.doc })).data.data },
    async getDatamesFidu(payload) { this.datamesFidu = (await axios.post('/datamesfidu/consultaUnitaria', { doc: payload.doc })).data.data },
    async getDatamesSemCali(payload) { this.datamessemcali = (await axios.post('/consultaDatamessemcali', { doc: payload.doc })).data.data },
    async getFechaVinc(payload) { this.fechavinc = (await axios.get(`/fechavinc/${payload.doc}`)).data },
    async getDescapli(payload) { this.descapli = (await axios.get(`/descapli/${payload.doc}`)).data },
    async getDescnoap(payload) { this.descnoap = (await axios.get(`/descnoap/${payload.doc}`)).data },
    async getCoupons(payload) {
      const res = await axios.post('/get-coupons', payload)
      this.fetchCoupons(res.data.items || res.data)
      setTimeout(() => { if (!this.incapacidadValida) this.$bvToast.show('toast-incapacidad-month') }, 1000)
    },
    async getEmbargos(payload) { this.fetchEmbargos((await axios.post('/get-embargos', payload)).data) },
    async getDescuentos(payload) { this.fetchDescuentos((await axios.post('/get-descuentos', payload)).data) },
    alertIncapacidad(data) {
      this.$bvToast.toast(data.message, { title: 'Alerta del sistema', autoHideDelay: 10000, variant: 'info', solid: true })
    },
    alertDefinitiva(data) {
      this.disabledProspect = true
      this.$bvToast.toast(data.message, { title: 'Alerta del sistema', autoHideDelay: 10000, variant: 'danger', solid: true })
    },
    print() { window.print() },
    visadoFunction() {
      let causal = ''
      let obligacionMarcadas = false
      let embargosSinMora = false
      const cuotaMaximaDef = Number(this.conteoEgresosPlus) + Number(this.totalesData.libreInversion)
      const definitivaAlerta = this.ingresosExtras.some(i => i.concept.toLowerCase().includes('definitiva'))
      const cuotaMenor = Number(this.cuotadeseada) < cuotaMaximaDef
      const cuotaMayor = Number(this.cuotadeseada) > cuotaMaximaDef
      if (this.descuentosPerPeriod.total > 0) obligacionMarcadas = this.descuentosPerPeriod.items.some(i => i.check === true)
      else embargosSinMora = true
      if (cuotaMenor && !obligacionMarcadas) { this.visadoValido = 'NO FACTIBLE'; causal = 'Presenta obligaciones en mora' }
      else if (cuotaMenor && obligacionMarcadas) { this.visadoValido = 'FACTIBLE'; causal = 'Sin causal' }
      if (cuotaMayor && embargosSinMora) { this.visadoValido = 'NO FACTIBLE'; causal += (causal ? ', ' : '') + 'Negado por cupo' }
      else if (cuotaMenor && embargosSinMora) { this.visadoValido = 'FACTIBLE'; causal = 'Sin causal' }
      else if (cuotaMayor && !obligacionMarcadas && !embargosSinMora) { this.visadoValido = 'NO FACTIBLE'; causal = '1. Presenta obligaciones en mora, 2. Negado por cupo' }
      else if (cuotaMayor && obligacionMarcadas) { this.visadoValido = 'NO FACTIBLE'; causal = 'Negado por cupo' }
      if (definitivaAlerta) { this.visadoValido = 'NO FACTIBLE'; causal = 'Cliente en proceso de retiro' }
      const data = { estado: this.visadoValido, cuotacredito: this.cuotadeseada, monto: this.monto, causal }
      axios.post(`/visados/${this.visado.id}`, data).then(() => (window.location.href = '/historyClient'))
    },
    async calcularTotales() {
      try {
        if (!this.couponsIngresos?.items?.length) return
        const { finperiodo, doc } = this.couponsIngresos.items[0]
        const [year, month] = finperiodo.split('-')
        this.isLoading = true
        const res = await fetch(`/demografico/calcular-cupo/${doc}/${parseInt(month)}/${parseInt(year)}`)
        if (!res.ok) throw new Error(`HTTP ${res.status}`)
        const r = await res.json()
        this.totalesData = {
          libreInversion: r.cupo_libre,
          libreInversionSuma: r.libreInversionSuma,
          compraCartera: r.compra_cartera,
          cuotaMaxima: r.cuotaMaxima
        }
      } finally { this.isLoading = false }
    },
    refreshConsulta() {
      if (!this.lastQuery?.doc || !this.lastQuery?.pagaduria) {
        this.$bvToast.toast('Primero realiza una consulta.', { title: 'Atención', autoHideDelay: 6000, variant: 'warning', solid: true })
        return
      }
      this.emitInfo({ ...this.lastQuery })
    },
    nuevaConsulta() {
      this.type_consult = 'individual'
      this.formLocked = false
      this.fechavinc = null
      this.datamesFopep = null
      this.datamessedvalle = null
      this.datamesFidu = null
      this.datamessemcali = null
      this.descapli = []
      this.descnoap = []
      this.embargosempty = []
      this.descuentosempty = []
      this.selectedPeriod = ''
      this.monto = 0
      this.pagaduriaKey = ''
      this.cargo = null
      this.showOthers = false
      this.pagadurias = null
      this.carteras = []
      this.carterasCargadas = false
      this.visado = null
      this.lastQuery = null
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  }
}
</script>

<style>
/* Lienzo y paneles */
.bg-soft { background-color: #f9fafc; }
.page-wide { width: 100%; padding: 12px 16px 24px; }
.heading-title { font-size: 28px; font-weight: 800; color: #121b26; }
.section-title { font-size: 18px; font-weight: 700; color: #121b26; }
.panel { background: #ffffff; border: 1px solid #e7eaee; border-radius: 14px; box-shadow: 0 6px 20px rgba(16,24,40,.06); }
.panel-body { padding: 20px; }
@media (min-width: 992px){ .panel-body{ padding: 26px 30px; } }

/* === ESTILOS CONSISTENTES CON FORMCONSULT PARA INPUTS Y SELECTS === */
.page-wide .ui-input,
.page-wide .form-control,
.page-wide .form-control-sm,
.page-wide select,
.page-wide .custom-select,
.page-wide .b-form-select {
  height: 54px;
  min-height: 54px;
  border-radius: 12px;
  border: 1.25px solid #d0d5dd;
  background: #ffffff;
  color: #111827;
  font-size: 16px;
  padding: 10px 14px;
  transition: all .15s ease;
  box-shadow: none;
}
.page-wide .form-control::placeholder,
.page-wide .ui-input::placeholder {
  color: #9aa4af;
  font-weight: 600;
}
.page-wide .ui-input:focus,
.page-wide .form-control:focus,
.page-wide select:focus,
.page-wide .custom-select:focus,
.page-wide .b-form-select:focus {
  border-color: #0CEDB0;
  box-shadow: 0 0 0 3px rgba(12,237,176,.18);
  outline: none;
}

/* Barra de acciones */
.action-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.label-strong { font-weight: 700; color: #121b26; }
.input-inline { min-width: 260px; }

/* Chips / badges */
.chip { display:inline-flex; align-items:center; padding:6px 10px; border:1px solid #e7eaee; background:#fff; border-radius:10px; font-size:13px; color:#121b26; }

/* Tablas */
.table-modern { width:100%; border-collapse: separate; border-spacing: 0; }
.table-modern thead th { background:#f7f9fc; color:#111827; font-weight:700; border-bottom:1px solid #e7eaee; padding:12px 14px; }
.table-modern tbody td { border-bottom:1px solid #eef1f5; padding:12px 14px; font-size:14px; color:#121b26; }
.table-modern tbody tr:hover { background:#fafcff; }

/* Pills para Sí/No */
.pill { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; border:1px solid #e7eaee; }
.pill-success { background:#e6f7f1; color:#0c876b; border-color:#cdeee3; }
.pill-muted { background:#f3f4f6; color:#6b7280; }

/* Otros */
.divider { width: 100%; height: 2px; background-color: #e7eaee; border: none; margin: 20px 0; }
.detallecliente-top-margin { margin-top: 20px; }
.primary { background:#0c876b !important; color:#fff !important; }
</style>
