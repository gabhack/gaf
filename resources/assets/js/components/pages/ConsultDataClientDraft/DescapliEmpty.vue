<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading d-flex justify-content-between">
        <b>OBLIGACIONES VIGENTES AL DIA</b>
        <div class="d-flex align-items-center">
          <b>PERIODO:</b>
          <select class="form-control mr-2" v-model="selectedPeriod">
            <option :value="period" v-for="period in periods" :key="period">
              {{ period }}
            </option>
          </select>
          <b-button @click="selectedPeriod = ''" variant="black-pearl">X</b-button>
        </div>
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
  name: 'DescapliEmpty',
  props: ['descapli', 'coupons'],
  data() {
    return {
      labels: [
        { label: 'TIPO ENTIDAD', field: 'clase' },
        { label: 'NOMBRE ENTIDAD', field: 'nomtercero', colClass: 'col-4' },
        { label: 'CUOTA', field: 'vaplicado', currency: true },
        { label: 'FECHA INICIO DEUDA', field: 'fgrab' },
        { label: 'NOMBRE ENTIDAD CEDIENTE', field: 'nonentant' }
      ],
      selectedPeriod: ''
    };
  },
  computed: {
    periods() {
      return this.coupons.reduce((acc, coupon) => {
        // if (acc.indexOf(coupon.finperiodo) === -1) {
        //   acc.push(coupon.finperiodo);
        // }
        if (acc.indexOf(coupon.inicioperiodo) === -1) {
          acc.push(coupon.inicioperiodo);
        }
        return acc;
      }, []);
    },
    periodDate() {
      return this.coupons.length > 0 ? this.coupons[0].period : null;
    },
    couponsAsDescapli() {
      const items = this.coupons.filter(item => item.code !== 'SUEBA' && Number(item.egresos) > 0);
      return items.map(item => {
        return {
          ...item,
          nomtercero: item.concept,
          vaplicado: item.egresos
        };
      });
    },
    data() {
      const descaplis = this.descapli ? this.descapli : [];
      const items = [...descaplis, ...this.couponsAsDescapli];

      if (this.selectedPeriod) {
        return items.filter(
          item => item.finperiodo === this.selectedPeriod || item.inicioperiodo === this.selectedPeriod
        );
      } else {
        return items;
      }
    }
  }
};
</script>
