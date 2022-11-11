<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading d-flex justify-content-between">
        <b>OBLIGACIONES VIGENTES AL DIA</b>
        <div class="d-flex align-items-center">
          <b class="mr-2">PERIODO:</b>
          <select class="form-control" @change="setSelectedPeriod($event.target.value)">
            <option :value="period" v-for="period in pagaduriaPeriodos" :key="period">
              {{ period }}
            </option>
          </select>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-1" >
            <b class="panel-label table-text"></b>
          </div>
          <div class="col-2" >
            <b class="panel-label table-text">TIPO ENTIDAD:</b>
          </div>
          <div class="col-3" >
            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
          </div>
          <div class="col-2" >
            <b class="panel-label table-text">CUOTA:</b>
          </div>
          <div class="col-2" >
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
          </div>
          <div class="col-2" >
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
          </div>
        </div>

        <div v-for="(item, key) in couponsIngresos.items" :key="key" class="row panel-br-light-green pt-3">
          <div class="col-1 pr-0" >
            <input
              type="checkbox"
              :value="item.id"
              :disabled="Number(item.vaplicado) > Number(cuotadeseada)"
              @input="event => AddItem(event.target.value)"
            />
          </div>
          <div class="col-2 px-0" >
            <p>{{ item.clase ? item.clase : '-'}}</p>
          </div>    

          <div class="col-3 px-0" >
            <p>{{ item.nomtercero ? item.nomtercero : '-' }}</p>
          </div>    

          <div class="col-2" >
            <p>{{ item.vaplicado | currency }}</p>
          </div>

          <div class="col-2" >
            <p>{{ item.fgrab ? item.fgrab : '-' }}</p>
          </div>   

          <div class="col-2" >
            <p>{{ item.nonentant ? item.nonentant : '-' }}</p>
          </div>    
        </div>

        <!--
        <div class="row">
          <div v-for="label in labels" :key="label.field">
            <template v-for="(item, key) in couponsIngresos.items">
              <div class="col-1" v-if="label.currency" :key="`check-${key}`">
                <div class="panel-value">
                  <input
                    type="checkbox"
                    :value="item.id"
                    :disabled="Number(item[label.field]) > Number(cuotadeseada)"
                    @input="event => AddItem(event.target.value)"
                  />
                </div>
              </div>
            </template>
          </div>
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="`label-${label.field}`">
            <b class="panel-label table-text">{{ label.label }}:</b>
            <template v-if="couponsIngresos.items.length > 0">
              <div v-for="(item, key) in couponsIngresos.items" :key="`field-${key}`">
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
        -->
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters, mapMutations } from 'vuex';
export default {
  name: 'DescapliEmpty',
  data() {
    return {
      labels: [
        { label: 'TIPO ENTIDAD', field: 'clase' },
        { label: 'NOMBRE ENTIDAD', field: 'nomtercero', colClass: 'col-3' },
        { label: 'CUOTA', field: 'vaplicado', currency: true },
        { label: 'FECHA INICIO DEUDA', field: 'fgrab' },
        { label: 'NOMBRE ENTIDAD CEDIENTE', field: 'nonentant' }
      ],
      itemsCheckbox: []
    };
  },
  computed: {
    ...mapState('datamesModule', ['cuotadeseada']),
    ...mapState('pagaduriasModule', ['coupons']),
    ...mapGetters('pagaduriasModule', ['couponsIngresos', 'pagaduriaPeriodos'])
  },
  methods: {
    ...mapMutations('datamesModule', ['setConteoEgresos']),
    ...mapMutations('pagaduriasModule', ['setSelectedPeriod']),
    AddItem(value) {
      const index = this.itemsCheckbox.findIndex(item => item.id == value);
      if (index == -1) {
        this.couponsIngresos.items.find(item => {
          if (item.id == value) {
            this.itemsCheckbox.push(item);
          }
        });
      } else {
        this.itemsCheckbox.splice(index, 1);
      }
      this.ValueEgresos();
    },
    ValueEgresos() {
      const total = this.itemsCheckbox.reduce((a, b) => a + Number(b.vaplicado), 0);
      this.setConteoEgresos(total);
    }   
  }
};
</script>
