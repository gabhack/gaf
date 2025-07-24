<template>
  <div class="card-main p-4">
    <div class="d-flex flex-wrap align-items-end gap-2 mb-3 w-100">
      <b-form-select v-model="year" :options="years" class="form-control2"/>
      <b-form-select v-model="month" :options="months" class="form-control2"/>
      <b-button variant="success" :disabled="isSearching" @click="start">Buscar</b-button>
      <template v-if="isSearching">
        <b-progress :max="total" :value="done" height="22px" class="flex-grow-1"/>
        <span class="ms-2 fw-bold">{{ done }} / {{ total }}</span>
      </template>
    </div>
    <b-table small hover :items="rows" :fields="fields">
      <template #cell(datames)="d">
        <Status :loading="loading[d.item.id]" :value="d.item.datames"/>
      </template>
      <template #cell(cupones)="d">
        <Status :loading="loading[d.item.id]" :value="d.item.cupones"/>
      </template>
      <template #cell(descuentos)="d">
        <Status :loading="loading[d.item.id]" :value="d.item.descuentos"/>
      </template>
      <template #cell(embargos)="d">
        <Status :loading="loading[d.item.id]" :value="d.item.embargos"/>
      </template>
    </b-table>
  </div>
</template>

<script>
import axios from 'axios'
import { BTable, BFormSelect, BButton, BProgress, BSpinner } from 'bootstrap-vue'

const Status = {
  props: { loading: Boolean, value: null },
  components: { BSpinner },
  template: `<div class="text-center" style="min-width:34px">
    <b-spinner small v-if="loading"/>
    <span v-else>{{ value === null ? '-' : value }}</span>
  </div>`
}

export default {
  components: { BTable, BFormSelect, BButton, BProgress, Status },
  data() {
    const n = new Date()
    return {
      pagadurias: [],
      rows: [],
      loading: {},
      done: 0,
      total: 0,
      isSearching: false,
      year: String(n.getFullYear()),
      month: String(n.getMonth() + 1).padStart(2, '0'),
      batchSize: 1,
      concurrency: 3,
      fields: [
        { key: 'pagaduria', label: 'Pagaduría' },
        { key: 'datames', label: 'DataMes' },
        { key: 'cupones', label: 'Cupones' },
        { key: 'descuentos', label: 'Descuentos' },
        { key: 'embargos', label: 'Embargos' }
      ],
      years: Array.from({ length: 6 }, (_, i) => ({ value: String(2020 + i), text: 2020 + i })),
      months: Array.from({ length: 12 }, (_, i) => {
        const m = i + 1
        return { value: String(m).padStart(2, '0'), text: String(m).padStart(2, '0') }
      })
    }
  },
  async mounted() {
    const { data } = await axios.get('/admin/data-coverage/list')
    this.pagadurias = data
    this.total = data.length
    this.rows = data.map(p => ({
      id: p.id,
      pagaduria: p.nombre,
      datames: null,
      cupones: null,
      descuentos: null,
      embargos: null
    }))
  },
  methods: {
    async start() {
      if (this.isSearching) return
      this.isSearching = true
      this.done = 0
      this.pagadurias.forEach(p => this.$set(this.loading, p.id, true))
      const offsets = []
      for (let o = 0; o < this.total; o += this.batchSize) offsets.push(o)
      const worker = async () => {
        while (offsets.length) {
          const off = offsets.shift()
          const { data } = await axios.get('/admin/data-coverage/batch', {
            params: { year: this.year, month: this.month, offset: off, limit: this.batchSize },
            timeout: 300000
          })
          data.forEach(d => {
            this.$set(this.loading, d.id, false)
            const idx = this.rows.findIndex(r => r.id === d.id)
            if (idx > -1) Object.assign(this.rows[idx], d)
          })
          this.done += data.length
        }
      }
      await Promise.all([...Array(this.concurrency)].map(() => worker()))
      this.isSearching = false
    }
  }
}
</script>

<style scoped lang="scss">
.card-main { border-radius: .75rem; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,.08) }
.form-control2 { background: #fafafa; height: 38px }
</style>
