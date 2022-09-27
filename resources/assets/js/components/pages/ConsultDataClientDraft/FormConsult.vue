<template>
  <div class="panel mb-3 col-md-12">
    <div class="panel-heading">
      <b>REALIZAR CONSULTA</b>
      <!--      <pre><code>{{ dataclient}}</code></pre>-->
    </div>
    <div class="panel-body">
      <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true" />
      <div class="row">
        <div class="col-6">
          <b class="panel-label">CEDULA:</b>
          <input required class="form-control text-center" type="number" v-model="dataclient.doc" />
        </div>
        <div class="col-6">
          <b class="panel-label">NOMBRES Y APELLIDOS:</b>
          <input required class="form-control text-center" type="text" v-model="dataclient.name" />
        </div>

        <div class="col-6">
          <b class="panel-label">PAGADURIAS:</b>
          <b-form-select
            v-if="dataclient.pagadurias"
            v-model="dataclient.pagaduria"
            class="text-center"
            required
            @change="modalConfirmConsultPag"
          >
            <option :value="null" disabled hidden>Elija una pagaduria</option>
            <template v-for="type in pagaduriasType">
              <option v-if="dataclient.pagadurias[type.key]" :value="type.value" :key="type.key">
                {{ type.label }}
              </option>
            </template>
          </b-form-select>

          <b-form-select v-else v-model="dataclient.pagaduria" class="text-center">
            <option :value="null" disabled>Ingresa una cedula y presiona consultar</option>
          </b-form-select>
        </div>

        <div class="col-6 mt-4">
          <b-button
            type="button"
            variant="black-pearl"
            v-if="dataclient.doc && dataclient.name"
            class="px-4"
            @click="getAllPagadurias"
          >
            CONSULTAR PAGADURIAS
          </b-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
  name: 'FormConsult',
  data() {
    return {
      dataclient: {
        doc: '255283',
        name: 'xavier',
        pagaduria: null,
        pagadurias: null
      },
      isLoading: false,
      pagaduriasType: [
        { label: 'FOPEP', value: 'FOPEP', key: 'datames' },
        { label: 'FIDUPREVISORA', value: 'FIDUPREVISORA', key: 'datamesfidu' },
        { label: 'FODE VALLE', value: 'FODE VALLE', key: 'datamesseceduc' },
        { label: 'SEM CALI', value: 'SECCALI', key: 'datamesseccali' },
        { label: 'SED CAUCA', value: 'SEDCAUCA', key: 'datamesSedCauca' },
        { label: 'SED CHOCO', value: 'SEDCHOCO', key: 'datamesSedChoco' }
      ]
    };
  },
  computed: {
    ...mapState('datamesModule', ['datamesSed'])
  },
  methods: {
    ...mapMutations('datamesModule', ['setDatamesSed']),
    selectedPagaduria() {
      if (this.dataclient.pagaduria) {
        const type = this.pagaduriasType.find(type => type.value === this.dataclient.pagaduria);
        const pagaduria = this.dataclient.pagadurias[type.key];
        this.setDatamesSed(pagaduria);
      }
    },
    emitInfo() {
      this.getAllPagadurias();
    },
    async getAllPagadurias() {
      this.isLoading = true;

      const response = await axios.get(`/pagadurias/per-doc/${this.dataclient.doc}`);
      this.dataclient.pagadurias = response.data;

      this.isLoading = false;
      return Promise.resolve(response.data);
    },
    modalConfirmConsultPag(val) {
      this.$bvModal
        .msgBoxConfirm('Esta acción tiene un costo', {
          title: '¿Está seguro que desea realizar la consulta?',
          size: 'sm',
          buttonSize: 'sm',
          okVariant: 'success',
          okTitle: 'Consultar',
          cancelTitle: 'Cancelar',
          cancelVariant: 'danger',
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
    async saveVisados() {
      try {
        this.isLoading = true;

        this.selectedPagaduria();

        const data = {
          pagaduria: this.dataclient.pagaduria,
          nombre: this.datamesSed.nombenef ? this.datamesSed.nombenef : this.datamesSed.nomp,
          doc: this.datamesSed.doc
        };

        const response = await axios.post('/visados', data);
        return Promise.resolve(response.status);
      } catch (e) {
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>