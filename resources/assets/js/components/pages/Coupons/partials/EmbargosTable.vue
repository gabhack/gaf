<template>
    <div>
      <div class="mb-2 mt-5"><h3 class="heading-title">Resumen</h3></div>
      <div class="row d-flex align-items-center justify-content-center py-2">
        <div class="col-4"><h3 class="heading-title">Estado</h3></div>
        <div class="col-4"><h3 class="heading-title">Total Clientes</h3></div>
        <div class="col-4"><h3 class="heading-title">Total Cuotas</h3></div>
        <div class="col-4 pb-2"><label class="label-resumen">Embargado</label></div>
        <div class="col-4 pb-2"><label class="label-resumen">{{ totalRows }}</label></div>
        <div class="col-4 pb-2"><label class="label-resumen">{{ totalCuotas }}</label></div>
      </div>
      <div class="mb-2 mt-5"><h3 class="heading-title">Resultados de la consulta (Cartera Embargada)</h3></div>
      <b-form-input v-model="inputFiltro" placeholder="Buscar por documento..." class="mb-3 form-control2"/>
      <div class="table-responsive">
        <b-table
          head-variant="dark"
          striped
          hover
          :fields="fields"
          :items="filtrados"/>
        <b-pagination
          v-model="localPage"
          :per-page="perPage"
          :total-rows="totalRows"
          @input="pageChanged"/>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'EmbargosTable',
    props: {
      items: Array,
      fields: Array,
      perPage: Number,
      totalRows: Number,
      currentPage: Number
    },
    data () {
      return { inputFiltro: '', filtro: '', localPage: this.currentPage }
    },
    watch: { currentPage (v) { this.localPage = v } },
    computed: {
      filtrados () {
        let arr = this.items || []
        if (this.filtro) arr = arr.filter(i => (i.doc || '').toString().toLowerCase().includes(this.filtro.toLowerCase()))
        const start = (this.localPage - 1) * this.perPage
        const slice = arr.slice(start, start + this.perPage)
        return slice.map(i => ({ ...i, temb: this.formatCurrency(i.temb) }))
      },
      totalCuotas () {
        const n = (this.items || []).reduce((t, i) => t + this.parseNumber(i.temb), 0)
        return this.formatCurrency(n)
      }
    },
    methods: {
      pageChanged (p) { this.$emit('page-change', p) },
      parseNumber (v) { return parseFloat((v || '0').toString().replace(/[^0-9.-]+/g, '')) || 0 },
      formatCurrency (v) {
        const n = this.parseNumber(v)
        return isNaN(n) ? v : n.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 })
      }
    }
  }
  </script>
  