<template>
    <div>
      <div class="mb-2 mt-5">
        <h3 class="heading-title">Resumen</h3>
      </div>
      <div class="row d-flex align-items-center justify-content-center py-2">
        <div class="col-4"><label class="heading-title">Estado</label></div>
        <div class="col-4"><label class="heading-title">Total Clientes</label></div>
        <div class="col-4"><label class="heading-title">Total Cuotas</label></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" value="Al día"/></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="totalRows"/></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="totalCuotas"/></div>
      </div>
      <div class="mb-2 mt-5">
        <h3 class="heading-title">Resultados de la consulta (Cartera al Día)</h3>
      </div>
      <div class="row">
        <div class="col-sm-10">
          <b-form-input v-model="inputFiltro" placeholder="Buscar por documento..." class="mb-3 form-control2"/>
        </div>
        <div class="col-sm-2">
          <b-button variant="primary" class="button_style_b" @click="aplicarFiltro">Filtrar</b-button>
        </div>
      </div>
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
    name: 'CouponsTable',
    props: {
      items: Array,
      fields: Array,
      perPage: Number,
      totalRows: Number,
      currentPage: Number
    },
    data () {
      return {
        inputFiltro: '',
        filtro: '',
        localPage: this.currentPage
      }
    },
    watch: {
      currentPage (v) { this.localPage = v }
    },
    computed: {
      filtrados () {
        let arr = this.items || []
        if (this.filtro) arr = arr.filter(i => (i.doc || '').toString().toLowerCase().includes(this.filtro.toLowerCase()))
        const start = (this.localPage - 1) * this.perPage
        const slice = arr.slice(start, start + this.perPage)
        return slice.map(i => ({ ...i, egresos: this.formatCurrency(i.egresos) }))
      },
      totalCuotas () {
        const n = (this.items || []).reduce((t, i) => t + this.parseNumber(i.egresos), 0)
        return this.formatCurrency(n)
      }
    },
    methods: {
      aplicarFiltro () { this.filtro = this.inputFiltro },
      pageChanged (p) { this.$emit('page-change', p) },
      parseNumber (v) { return parseFloat((v || '0').toString().replace(/[^0-9.-]+/g, '')) || 0 },
      formatCurrency (v) {
        const n = this.parseNumber(v)
        return isNaN(n) ? v : n.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 })
      }
    }
  }
  </script>
  