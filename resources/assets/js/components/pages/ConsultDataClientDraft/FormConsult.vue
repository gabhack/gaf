<template>
  <div class="panel mb-3 col-md-12">
    <div class="panel-heading">
      <b>REALIZAR CONSULTA</b>
      <!--      <pre><code>{{ dataclient}}</code></pre>-->
    </div>
    <div class="panel-body">
      <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true"/>
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
          <b class="panel-label">PAGADURIAS:</b>
          <template v-if="dataclient.pagadurias">
            <select required class="form-control" v-model="dataclient.pagaduria">
              <option disabled :value="null">Elija una pagaduria</option>
              <option v-if="dataclient.pagadurias.datames" value="FOPEP">FOPEP</option>
              <option v-if="dataclient.pagadurias.datamesfidu" value="FIDUPREVISORA">FIDUPREVISORA</option>
              <option v-if="dataclient.pagadurias.datamesseceduc" value="FODE VALLE">FODE VALLE</option>
              <option v-if="dataclient.pagadurias.datamesseccali" value="SECCALI">SECCALI</option>
            </select>
          </template>
          <select class="form-control" v-else>
            <option class="text-muted" selected disabled :value="null">Ingresa una cedula y presiona consultar para ver
              las pagadurias disponibles
            </option>
          </select>
        </div>

        <div class="col-6 mt-4">
          <button
              type="button"
              v-if="dataclient.doc && dataclient.name"
              class="btn btn-primary"
              @click="getAllPagadurias">
            CONSULTAR PAGADURIAS
          </button>
        </div>


        <!--        <div class="col-6 mt-4">-->
        <!--          <button-->
        <!--              type="button"-->
        <!--              v-if="dataclient.pagaduria && dataclient.name && dataclient.doc"-->
        <!--              class="btn btn-primary"-->
        <!--              @click="emitInfo">-->
        <!--            CONSULTAR-->
        <!--          </button>-->
        <!--        </div>-->


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
        pagaduria: null,
        pagadurias: null,
      },
      isLoading: false,
      isFirstTime: false
    }
  },
  methods: {
    emitInfo() {
      this.getAllPagadurias();
      // this.$emit('emitInfo', this.dataclient);
    },
    async getAllPagadurias() {
      this.isLoading = true;
      const response = await axios.post('/pagadurias/consultaUnitaria', {doc: this.dataclient.doc});
      toastr.success('Pagadurias consultadas');
      this.dataclient.pagadurias = response.data;
      this.isLoading = false;
      return Promise.resolve(response.data);
    },
    modalConfirmConsultPag(val) {
      this.$bvModal.msgBoxConfirm('Esta acción tiene un costo', {
        title: '¿Está seguro que desea realizar la consulta?',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'success',
        okTitle: 'Consultar',
        cancelTitle: 'Cancelar',
        cancelVariant: "danger",
        headerClass: 'p-2 border-bottom-0',
        footerClass: 'p-2 border-top-0',
        centered: true
      })
          .then(value => {
            if (!value) return;
            this.saveVisados(val).then(status => {
              if (status != 200) return;
              this.$emit('emitInfo', this.dataclient);
            });
          });
    },
    async saveVisados(val) {
      try {
        this.isLoading = true
        const response = await axios.post('/visados', {...this.dataclient});
        return Promise.resolve(response.status);
      } catch (e) {
        toastr.error('No se pudo realizar la consulta');
      } finally {
        this.isLoading = false
      }
    },
  },
  watch: {
    'dataclient.pagaduria': function (val) {
      this.modalConfirmConsultPag(val);
      //
    }
  }
}
</script>

<style scoped>

</style>
