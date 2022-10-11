<template>
  <div class="col-6" v-if="datamesSed">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading">
        <b>INFORMACIÓN PERSONAL</b>
      </div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-6'" v-for="label in labels" :key="label.field">
            <b class="panel-label">{{ label.label }}:</b>
            <div>
              <p class="panel-value">
                <template v-if="Object.keys(datamesSed).length > 0 && datamesSed[label.field]">
                  <template v-if="label.currency">
                    {{ datamesSed[label.field] | currency }}
                  </template>
                  <template v-else>
                    {{ datamesSed[label.field] }}
                  </template>
                </template>
                <template v-else> - </template>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'DatamesData',
  data() {
    return {
      labels: [
        { label: 'TIPO DOCUMENTO', field: '' },
        { label: 'NÚMERO DOCUMENTO', field: 'nvinc' },
        { label: 'NOMBRE Y APELLIDO', field: 'nomp' },
        { label: 'FECHA DE NACIMIENTO', field: 'fecnacimient' },
        { label: 'DIRECCIÓN', field: 'dir' },
        { label: 'DEPARTAMENTO', field: '' },
        { label: 'MUNICIPIO', field: '' },
        { label: 'TELÉFONO', field: 'tel' },
        { label: 'CELULAR', field: '' }
      ]
    };
  },
  computed: {
    ...mapState('datamesModule', ['datamesSed'])
  }
};
</script>
