<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading d-flex justify-content-between">
        <b>OBLIGACIONES VIGENTES AL DIA</b>
        <b v-if="periodDate">PERIODO: {{ periodDate }}</b>
      </div>
      <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}:</b>
            <template v-if="data.length > 0">
              <div v-for="(item, key) in data" :key="key">
                <p class="panel-value">
                  <template v-if="item[label.field]">
                    <template v-if="label.currency">
                      {{ item[label.field] | currency }}
                    </template>
                    <template v-else>
                      {{ item[label.field] }}
                    </template>
                  </template>
                  <template v-else> - </template>
                </p>
              </div>
            </template>
            <template v-else>
              <p class="panel-value">-</p>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Descapli',
  props: ['descapli', 'coupons'],
  data() {
    return {
      labels: [
        { label: 'TIPO ENTIDAD', field: 'clase' },
        { label: 'NOMBRE ENTIDAD', field: 'nomtercero', colClass: 'col-4' },
        { label: 'CUOTA', field: 'vaplicado', currency: true },
        { label: 'FECHA INICIO DEUDA', field: 'fgrab' },
        { label: 'NOMBRE ENTIDAD CEDIENTE', field: 'nonentant' }
      ]
    };
  },
  computed: {
    periodDate() {
      return this.coupons.length > 0 ? this.coupons[0].period : null;
    },
    couponsAsDescapli() {
      const items = this.coupons.filter(item => item.code !== 'SUEBA' && Number(item.egresos) > 0);
      return items.map(item => {
        return {
          nomtercero: item.concept,
          vaplicado: item.egresos
        };
      });
    },
    data() {
      const descaplis = this.descapli ? this.descapli : [];
      return [...descaplis, ...this.couponsAsDescapli];
    }
  }
};
</script>
