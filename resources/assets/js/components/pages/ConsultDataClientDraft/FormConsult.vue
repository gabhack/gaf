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
          <b class="panel-label">CUOTA DESEADA:</b>
          <input required class="form-control text-center" type="number" v-model.number="dataclient.cuotadeseada" />
        </div>
        <div class="col-6">
          <b class="panel-label">MONTO:</b>
          <input required class="form-control text-center" type="text" v-model.number="dataclient.monto" />
        </div>
        <div class="col-6">
          <b class="panel-label">PLAZO:</b>
          <input required class="form-control text-center" type="text" />
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
        doc: '4612830',
        name: 'Garcia',
        cuotadeseada: 0,
        monto: 0,
        pagaduria: null,
        pagadurias: null,
        pagaduriaKey: null,
        visado: null
      },
      isLoading: false,
      pagaduriasType: [
        { label: 'FIDUPREVISORA', value: 'FIDUPREVISORA', key: 'datamesFidu' },
        { label: 'FOPEP', value: 'FOPEP', key: 'datamesFopep' },
        { label: 'SED ANTIOQUIA', value: 'SEDANTIOQUIA', key: 'datamesSedAntioquia' },
        { label: 'SED ARAUCA', value: 'SEDARAUCA', key: 'datamesSedArauca' },
        { label: 'SED ATLANTICO', value: 'SEDATLANTICO', key: 'datamesSedAtlantico' },
        { label: 'SED BOLIVAR', value: 'SEDBOLIVAR', key: 'datamesSedBolivar' },
        { label: 'SED BOYACA', value: 'SEDBOYACA', key: 'datamesSedBoyaca' },
        { label: 'SED CALDAS', value: 'SEDCALDAS', key: 'datamesSedCaldas' },
        { label: 'SED CASANARE', value: 'SEDCASANARE', key: 'datamesSedCasanare' },
        { label: 'SED CAUCA', value: 'SEDCAUCA', key: 'datamesSedCauca' },
        { label: 'SED CESAR', value: 'SEDCESAR', key: 'datamesSedCesar' },
        { label: 'SED CHOCO', value: 'SEDCHOCO', key: 'datamesSedChoco' },
        { label: 'SED CORDOBA', value: 'SEDCORDOBA', key: 'datamesSedCordoba' },
        { label: 'SED CUNDINAMARCA', value: 'SEDCUNDINAMARCA', key: 'datamesSedCundinamarca' },
        { label: 'SED GUAJIRA', value: 'SEDGUAJIRA', key: 'datamesSedGuajira' },
        { label: 'SED HUILA', value: 'SEDHUILA', key: 'datamesSedHuila' },
        { label: 'SED MAGDALENA', value: 'SEDMAGDALENA', key: 'datamesSedMagdalena' },
        { label: 'SED META', value: 'SEDMETA', key: 'datamesSedMeta' },
        { label: 'SED NARIÑO', value: 'SEDNARINO', key: 'datamesSedNarino' },
        { label: 'SED NORTE SANTANDER', value: 'SEDNORTESANTANDER', key: 'datamesSedNorteSantander' },
        { label: 'SED RISARALDA', value: 'SEDRISARALDA', key: 'datamesSedRisaralda' },
        { label: 'SED SANTANDER', value: 'SEDSANTANDER', key: 'datamesSedSantander' },
        { label: 'SED SUCRE', value: 'SEDSUCRE', key: 'datamesSedSucre' },
        { label: 'SED TOLIMA', value: 'SEDTOLIMA', key: 'datamesSedTolima' },
        { label: 'SED VALLE', value: 'SEDVALLE', key: 'datamesSedValle' },
        { label: 'SEM BARRANQUILLA', value: 'SEMBARRANQUILLA', key: 'datamesSemBarranquilla' },
        { label: 'SEM BUGA', value: 'SEMBUGA', key: 'datamesSemBuga' },
        { label: 'SEM CALI', value: 'SEMCALI', key: 'datamesSemCali' },
        { label: 'SEM CARTAGENA', value: 'SEMCARTAGENA', key: 'datamesSemCartagena' },
        { label: 'SEM GIRARDOT', value: 'SEMGIRARDOT', key: 'datamesSemGirardot' },
        { label: 'SEM IBAGUE', value: 'SEMIBAGUE', key: 'datamesSemIbague' },
        { label: 'SEM IPIALES', value: 'SEMIPIALES', key: 'datamesSemIpiales' },
        { label: 'SEM JAMUNDI', value: 'SEMJAMUNDI', key: 'datamesSemJamundi' },
        { label: 'SEM MAGANGUE', value: 'SEMMAGANGUE', key: 'datamesSemMagangue' },
        { label: 'SEM MEDELLIN', value: 'SEMMEDELLIN', key: 'datamesSemMedellin' },
        { label: 'SEM MONTERIA', value: 'SEMMONTERIA', key: 'datamesSemMonteria' },
        { label: 'SEM MOSQUERA', value: 'SEMMOSQUERA', key: 'datamesSemMosquera' },
        { label: 'SEM NEIVA', value: 'SEMNEIVA', key: 'datamesSemNeiva' },
        { label: 'SEM PALMIRA', value: 'SEMPALMIRA', key: 'datamesSemPalmira' },
        { label: 'SEM PASTO', value: 'SEMPASTO', key: 'datamesSemPasto' },
        { label: 'SEM POPAYAN', value: 'SEMPOPAYAN', key: 'datamesSemPopayan' },
        { label: 'SEM QUIBDO', value: 'SEMQUIBDO', key: 'datamesSemQuibdo' },
        { label: 'SEM RIONEGRO', value: 'SEMRIONEGRO', key: 'datamesSemRioNegro' },
        { label: 'SEM SABANETA', value: 'SEMSABANETA', key: 'datamesSemSabaneta' },
        { label: 'SEM SAHAGUN', value: 'SEMSAHAGUN', key: 'datamesSemSahagun' },
        { label: 'SEM SINCELEJO', value: 'SEMSINCELEJO', key: 'datamesSemSincelejo' },
        { label: 'SEM SOLEDAD', value: 'SEMSOLEDAD', key: 'datamesSemSoledad' },
        { label: 'SEM VALLEDUPAR', value: 'SEMVALLEDUPAR', key: 'datamesSemValledupar' },
        { label: 'SEM YOPAL', value: 'SEMYOPAL', key: 'datamesSemYopal' },
        { label: 'SEM YUMBO', value: 'SEMYUMBO', key: 'datamesSemYumbo' },
        { label: 'SEM ZIPAQUIRA', value: 'SEMZIPAQUIRA', key: 'datamesSemZipaquira' }
      ]
    };
  },
  computed: {
    ...mapState('datamesModule', ['datamesSed', 'cuotadeseada'])
  },
  methods: {
    ...mapMutations('datamesModule', ['setDatamesSed', 'setCuotaDeseada']),
    ...mapMutations('pagaduriasModule', ['setPagaduriaType', 'setSelectedPeriod']),
    selectedPagaduria() {
      this.setPagaduriaType(this.dataclient.pagaduria);

      if (this.dataclient.pagaduria) {
        const type = this.pagaduriasType.find(type => type.value === this.dataclient.pagaduria);
        const pagaduria = this.dataclient.pagadurias[type.key];
        this.dataclient.pagaduriaKey = type.key.slice(7).toLowerCase();
        pagaduria.documentType = 'documentType';
        this.dataclient.cargo = pagaduria.cargo;
        this.setDatamesSed(pagaduria);
      }
    },
    emitInfo() {
      this.getAllPagadurias();
    },
    async getAllPagadurias() {
      this.isLoading = true;
      this.dataclient.pagadurias = null;

      this.setDatamesSed(null);
      this.setPagaduriaType('');
      this.setSelectedPeriod('');

      const response = await axios.get(`/pagadurias/per-doc/${this.dataclient.doc}`);

      if (Object.keys(response.data).length > 0) {
        this.dataclient.pagadurias = response.data;
        this.setCuotaDeseada(this.dataclient.cuotadeseada);
      } else {
        toastr.info('No tenemos información de este documento en el momento');
      }

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
          nombre: this.datamesSed.nombenef || this.datamesSed.empleado || this.datamesSed.nomp,
          doc: this.datamesSed.doc || this.datamesSed.codempleado
        };

        const response = await axios.post('/visados', data);

        this.dataclient.visado = response.data;

        return Promise.resolve(response.status);
      } catch (e) {
        toastr.error('Error al guardar el visado');
        return Promise.reject(e);
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>
