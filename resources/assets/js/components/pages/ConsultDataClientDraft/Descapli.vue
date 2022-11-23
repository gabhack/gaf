<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
      <!-- <div class="panel-body">
        <div class="row">
          <div class="col-md-2">
            <b class="panel-label table-text">TIPO ENTIDAD:</b>
            <template v-if="descapli.length">
              <div v-for="(descapli, key) in descapli" :key="key">
                <p class="panel-value">{{ descapli.clase }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
            <template v-if="descapli.length">
              <div v-for="(descapli, key) in descapli" :key="key">
                <p class="panel-value">{{ descapli.nomtercero }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO PAGARE:</b>
            <template v-if="descapli.length">
              <div v-for="(descapli, key) in descapli" :key="key">
                <p class="panel-value">{{ descapli.pagare }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">CUOTA:</b>
            <template v-if="descapli.length">
              <div v-for="(descapli, key) in descapli" :key="key">
                <p class="panel-value">{{ descapli.vaplicado | currency }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <template v-if="descapli.length">
              <div v-for="(descapli, key) in descapli" :key="key">
                <p class="panel-value">{{ descapli.fgrab }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
            <template v-if="descapli.length">
              <div v-for="(descapli, key) in descapli" :key="key">
                <p class="panel-value">{{ descapli.nonentant }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>
        </div>
      </div> -->
      <div class="panel-body">
        <div class="row">
          <div class="col-1">
            <b class="panel-label table-text"></b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">TIPO ENTIDAD:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NUMERO PAGARE:</b>
          </div>
          <div class="col-1">
            <b class="panel-label table-text">CUOTA:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
          </div>
        </div>

        <div v-for="(item, key) in descapli" :key="key" class="row panel-br-light-green pt-3">
          <div class="col-1 pr-0">
            <input v-model="item.check" type="checkbox" />
            <!--
              :value="item.id"
              @input="event => AddItem(event.target.value)"
            -->
          </div>
          <div class="col-2 px-0">
            <p>{{ item.clase ? item.clase : '-' }}</p>
          </div>

          <div class="col-2 px-0">
            <p>{{ item.nomtercero ? item.nomtercero : '-' }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.pagare ? item.pagare : '-' }}</p>
          </div>

          <div class="col-1">
            <p>{{ item.vaplicado | currency }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.fgrab ? item.fgrab : '-' }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.nonentant ? item.nonentant : '-' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Descapli',
  props: ['descapli']
};
</script>
