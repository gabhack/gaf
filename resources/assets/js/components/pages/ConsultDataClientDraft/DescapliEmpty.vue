<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-2">
            <b class="panel-label table-text">TIPO ENTIDAD:</b>
            <!--            <template v-if="descapli.length">-->
            <!--              <div v-for="(descapli, key) in descapli" :key="key">-->
            <!--                <p class="panel-value">{{ descapli.clase }}</p>-->
            <!--              </div>-->
            <!--            </template>-->
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
            <!--            <template v-if="descapli.length">-->
            <!--              <div v-for="(descapli, key) in descapli" :key="key">-->
            <!--                <p class="panel-value">{{ descapli.nomtercero }}</p>-->
            <!--              </div>-->
            <!--            </template>-->
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO PAGARE:</b>
            <!--            <template v-if="descapli.length">-->
            <!--              <div v-for="(descapli, key) in descapli" :key="key">-->
            <!--                <p class="panel-value">{{ descapli.pagare }}</p>-->
            <!--              </div>-->
            <!--            </template>-->
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">CUOTA:</b>
            <!--            <template v-if="descapli.length">-->
            <!--              <div v-for="(descapli, key) in descapli" :key="key">-->
            <!--                <p class="panel-value">{{ descapli.vaplicado | currency }}</p>-->
            <!--              </div>-->
            <!--            </template>-->
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <!--            <template v-if="descapli.length">-->
            <!--              <div v-for="(descapli, key) in descapli" :key="key">-->
            <!--                <p class="panel-value">{{ descapli.fgrab }}</p>-->
            <!--              </div>-->
            <!--            </template>-->
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
            <!--            <template v-if="descapli.length">-->
            <!--              <div v-for="(descapli, key) in descapli" :key="key">-->
            <!--                <p class="panel-value">{{ descapli.nonentant }}</p>-->
            <!--              </div>-->
            <!--            </template>-->
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 mb-3">
            <div class="panel-heading"><b>CUPONES DE PAGO</b></div>
          </div>
          <div class="col-md-2">
            <b class="panel-label table-text">CODIGO:</b>
            <div v-for="(coupon, key) in coupons" :key="key">
              <p class="panel-value">
                {{ coupon.code }}
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <b class="panel-label table-text">CONCEPTO:</b>
            <div v-for="(coupon, key) in coupons" :key="key">
              <p class="panel-value">
                {{ coupon.concept }}
              </p>
            </div>
          </div>
          <div class="col-md-2">
            <b class="panel-label table-text">INGRESOS:</b>
            <div v-for="(coupon, key) in coupons" :key="key">
              <p class="panel-value">
                {{ coupon.ingresos | currency }}
              </p>
            </div>
          </div>
          <div class="col-md-2">
            <b class="panel-label table-text">EGRESOS:</b>
            <div v-for="(coupon, key) in coupons" :key="key">
              <p class="panel-value">
                {{ coupon.egresos | currency }}
              </p>
            </div>
          </div>
          <div class="col-md-2">
            <b class="panel-label table-text">PERIODO:</b>
            <div v-for="(coupon, key) in coupons" :key="key">
              <p class="panel-value">
                {{ coupon.period }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Descapli',
  props: ['descapli', 'coupons']
};
</script>