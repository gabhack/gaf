<template>
    <div>
      <div class="mb-2 mt-5"><h3 class="heading-title">Resumen</h3></div>
      <div class="row d-flex align-items-center justify-content-center py-2">
        <div class="col-4"><label class="heading-title">Estado</label></div>
        <div class="col-4"><label class="heading-title">Total Clientes</label></div>
        <div class="col-4"><label class="heading-title">Total Cuotas</label></div>
  
        <div class="col-4 pb-2"><label>Al día</label></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="rowsAldia"/></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="totalCuotasAldia"/></div>
  
        <div class="col-4 pb-2"><label>En mora</label></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="rowsMora"/></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="totalCuotasMora"/></div>
  
        <div class="col-4 pb-2"><label>Embargado</label></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="rowsEmbargo"/></div>
        <div class="col-4 pb-2"><b-form-input disabled class="form-control2" :value="totalCuotasEmbargo"/></div>
  
        <div class="col-4"><label>Total</label></div>
        <div class="col-4"><b-form-input disabled class="form-control2" :value="totalClientes"/></div>
        <div class="col-4"><b-form-input disabled class="form-control2" :value="totalCuotas"/></div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ResumenTodas',
    props: {
      rowsAldia: Number,
      rowsMora: Number,
      rowsEmbargo: Number,
      totalCuotasAldia: String,
      totalCuotasMora: String,
      totalCuotasEmbargo: String
    },
    computed: {
      totalClientes () { return this.rowsAldia + this.rowsMora + this.rowsEmbargo },
      totalCuotas () {
        const a = this.parseNumber(this.totalCuotasAldia)
        const m = this.parseNumber(this.totalCuotasMora)
        const e = this.parseNumber(this.totalCuotasEmbargo)
        return this.formatCurrency(a + m + e)
      }
    },
    methods: {
      parseNumber (v) { return parseFloat((v || '0').toString().replace(/[^0-9.-]+/g, '')) || 0 },
      formatCurrency (n) { return n.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }) }
    }
  }
  </script>
  