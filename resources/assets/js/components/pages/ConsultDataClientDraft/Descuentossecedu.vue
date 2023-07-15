<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <!-- <div class="panel-body">
        <div class="row">
          <div :class="label.colClass || 'col-2'" v-for="label in labels" :key="label.field">
            <b class="panel-label table-text">{{ label.label }}</b>
            <template v-if="descuentossedvalle.length > 0">
              <div v-for="(item, key) in descuentossedvalle" :key="key">
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
        </div>
      </div> -->
      <div class="panel-body">
        <div class="row">
          <div class="col-1">
            <b class="panel-label table-text"></b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">MENSAJE:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">FECHA:</b>
          </div>
        </div>

        <div v-for="(item, key) in descuentossedvalle" :key="key" class="row panel-br-light-green pt-3">
          <div class="col-1 pr-0">
            <input v-model="item.check" type="checkbox" />
            <!--
              :value="item.id"
              @input="event => AddItem(event.target.value)"
            -->
          </div>
          <div class="col-2 px-0">
            <p>{{ item.mliquid ? item.mliquid : '-' }}</p>
          </div>
          <div class="col-2 px-0">
            <p>{{ item.fecdata ? item.fecdata : '-' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DescuentosSedValle',
  props: ['descuentossedvalle'],
  data() {
    return {
      labels: [
        { label: 'MENSAJE', field: 'mliquid', colClass: 'col-4' },
        { label: 'FECHA', field: 'fecdata' }
      ]
    };
  }
};
</script>
