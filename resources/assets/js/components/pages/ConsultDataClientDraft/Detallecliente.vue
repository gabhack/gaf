<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>DETALLE DEL CLIENTE</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4 text-center">
            <label class="label-consulta col-12 mb-2" for="pad"
              ><b>OBLIGACIONES:</b>
              <input
                class="form-control text-left"
                type="text"
                name="proc_en_contra"
                id="proc_en_contra"
                placeholder="Obligaciones vigentes al dia"
                value="OBLIGACIONES VIGENTES AL DIA"
                min="1"
                max="99"
                disabled
              />
              <input
                class="form-control text-left"
                type="text"
                name="proc_en_contra"
                id="proc_en_contra"
                placeholder="Obligaciones vigentes en mora"
                value="OBLIGACIONES VIGENTES EN MORA"
                min="1"
                max="99"
                disabled
              />
              <input
                class="form-control text-left"
                type="text"
                name="proc_en_contra"
                id="proc_en_contra"
                placeholder="EMBARGOS"
                value="EMBARGOS"
                min="1"
                max="99"
                disabled
              />
            </label>
          </div>
          <div class="col-md-4 text-center">
            <label class="label-consulta col-12 mb-2" for="pad"
              ><b>CANTIDAD OBLIGACIONES:</b>
              <input
                class="form-control"
                type="number"
                name="proc_en_contra"
                id="proc_en_contra"
                placeholder="Opcional"
                :value="couponsIngresos.total"
                min="1"
                max="99"
                disabled
              />
              <input
                class="form-control"
                type="number"
                name="proc_en_contra"
                id="proc_en_contra"
                placeholder="Opcional"
                :value="totales.descuentos"
                min="1"
                max="99"
                disabled
              />
              <input
                class="form-control"
                type="number"
                name="proc_en_contra"
                id="proc_en_contra"
                placeholder="Opcional"
                :value="totales.embargos"
                min="1"
                max="99"
                disabled
              />
            </label>
          </div>
          <div class="col-md-4 text-center">
            <div class="row">
              <div class="col-6">
                <label class="label-consulta" for="pad"
                  ><b>CUPO APROXIMADO:</b>
                  <input
                    class="form-control"
                    type="text"
                    name="proc_en_contra"
                    id="proc_en_contra"
                    placeholder="Opcional"
                    value="LIBRE INVERSION"
                    min="1"
                    max="99"
                    disabled
                  />
                  <input
                    class="form-control"
                    type="text"
                    name="proc_en_contra"
                    id="proc_en_contra"
                    placeholder="Opcional"
                    value="COMPRA CARTERA"
                    min="1"
                    max="99"
                    disabled
                  />
                  <input
                    class="form-control"
                    type="text"
                    name="proc_en_contra"
                    id="proc_en_contra"
                    placeholder="Opcional"
                    value="CUOTA MÁXIMA"
                    min="1"
                    max="99"
                    disabled
                  />
                  <input
                    class="form-control"
                    type="text"
                    name="proc_en_contra"
                    id="proc_en_contra"
                    placeholder="Opcional"
                    value="CUOTA DESEADA"
                    min="1"
                    max="99"
                    disabled
                  />
                </label>
              </div>
              <div class="col-6">
                <b>VALOR</b>
                <p class="panel-value">
                  {{ totales.libreInversion | currency }}
                </p>
                <p class="panel-value">
                  {{ totales.compraCartera | currency }}
                </p>
                <p class="panel-value">
                  {{
                    (conteoEgresosPlus ? conteoEgresosPlus + totales.libreInversionSuma : totales.libreInversionSuma)
                      | currency
                  }}
                </p>
                <input
                  type="number"
                  class="form-control"
                  :value="cuotadeseada"
                  @input="event => setCuotaDeseada(event.target.value)"
                />
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="descuentossedcauca.length > 0">
              <div v-for="(item, key) in descuentossedcauca" :key="key">
                <p class="panel-value">
                  <template v-if="item[label.field]">
                    {{ item[label.field] }}
                  </template>
                  <template v-else-if="item[label.field] || label.currency">
                    {{ item[label.field] | currency }}
                  </template>
                  <template v-else> - </template>
                </p>
              </div>
            </template>
            <template v-else>
              <p class="panel-value">-</p>
            </template>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations, mapGetters } from 'vuex';

export default {
  name: 'DetalleCliente',
  props: ['descuentossedcauca', 'totales'],
  data() {
    return {
      labels: [
        { label: 'MENSAJE', field: 'mliquid', colClass: 'col-4' },
        { label: 'FECHA', field: 'fecdata' }
      ]
    };
  },
  computed: {
    ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresos', 'conteoEgresosPlus']),
    ...mapGetters('pagaduriasModule', ['couponsIngresos'])
  },
  methods: {
    ...mapMutations('datamesModule', ['setCuotaDeseada'])
  }
};
</script>
