<template>
  <div class="col-md-12">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
      <!-- <div class="panel-body">
        <div class="row">
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
            <template v-if="descnoap.length">
              <div v-for="(descnoap, key) in descnoap" :key="key">
                <p class="panel-value">{{ descnoap.nomtercero }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-md-2">
            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
            <template v-if="descnoap.length">
              <div v-for="(descnoap, key) in descnoap" :key="key">
                <p class="panel-value">{{ descnoap.pagare }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">CUOTA DEUDA:</b>
            <template v-if="descnoap.length">
              <div v-for="(descnoap, key) in descnoap" :key="key">
                <p class="panel-value">{{ descnoap.vfijo | currency }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
            <template v-if="descnoap.length">
              <div v-for="(descnoap, key) in descnoap" :key="key">
                <p class="panel-value">{{ descnoap.fgrab }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
            <template v-if="descnoap.length">
              <div v-for="(descnoap, key) in descnoap" :key="key">
                <p class="panel-value">{{ descnoap.nonentant }}</p>
              </div>
            </template>
            <div v-else>
              <p class="panel-value">-</p>
            </div>
          </div>

          <div class="col-2">
            <b class="panel-label table-text">INCONSISTENCIA:</b>
            <template v-if="descnoap.length">
              <div v-for="(descnoap, key) in descnoap" :key="key">
                <p class="panel-value">{{ descnoap.Incon }}</p>
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
            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
          </div>
          <div class="col-1">
            <b class="panel-label table-text">CUOTA DEUDA:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
          </div>
          <div class="col-2">
            <b class="panel-label table-text">INCONSISTENCIA:</b>
          </div>
        </div>

        <div v-for="(item, key) in descnoap" :key="key" class="row panel-br-light-green pt-3">
          <div class="col-1 pr-0">
            <input v-model="item.check" type="checkbox" />
            <!--
              :value="item.id"
              @input="event => AddItem(event.target.value)"
            -->
          </div>
          <div class="col-2 px-0">
            <p>{{ item.nomtercero ? item.nomtercero : '-' }}</p>
          </div>

          <div class="col-2 px-0">
            <p>{{ item.pagare ? item.pagare : '-' }}</p>
          </div>

          <div class="col-1">
            <p>{{ item.vfijo | currency }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.fgrab ? item.fgrab : '-' }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.nonentant ? item.nonentant : '-' }}</p>
          </div>

          <div class="col-2">
            <p>{{ item.Incon ? item.Incon : '-' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Descnoap',
  props: ['descnoap']
};
</script>

<style scoped></style>
