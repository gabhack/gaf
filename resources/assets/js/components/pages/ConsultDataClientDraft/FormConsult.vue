<template>
  <div class="panel mb-3 col-md-12">
    <div class="panel-heading">
      <b>REALIZAR CONSULTA</b>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-6">
          <b class="panel-label">CEDULA:</b>
          <input required class="form-control text-center" type="number" v-model="dataclient.doc"/>
        </div>
        <div class="col-6">
          <b class="panel-label">NOMBRES Y APELLIDOS:</b>
          <input required class="form-control text-center" type="text" v-model="dataclient.name"/>
        </div>
        <div class="col-6">
          <b class="panel-label">PAGADURIA:</b>
          <select required class="form-control" v-model="dataclient.pagaduria">
            <option value="FOPEP">FOPEP</option>
            <option value="FIDUPREVISORA">FIDUPREVISORA</option>
            <option value="FODE VALLE">FODE VALLE</option>
          </select>
        </div>
        <div class="col-6 mt-4">
          <button
              type="button"
              v-if="dataclient.pagaduria && dataclient.name && dataclient.doc"
              class="btn btn-primary"
              @click="emitInfo">
            CONSULTAR
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "FormConsult",
  data() {
    return {
      dataclient: {
        doc: '',
        name: '',
        pagaduria: '',
      }
    }
  },
  methods: {
    emitInfo() {
      this.$emit('emitInfo', this.dataclient);
    }
  }
}
</script>

<style scoped>

</style>
