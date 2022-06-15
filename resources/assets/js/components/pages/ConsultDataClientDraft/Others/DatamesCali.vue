<template>
  <div class="col-md-6">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-6">
            <b class="panel-label">TIPO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NUMERO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesseccali.nvinc ? datamesseccali.nvinc : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NOMBRE Y APELLIDO:</b>
            <div>
              <p class="panel-value">{{ datamesseccali.nomp ? datamesseccali.nomp : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">FECHA DE NACIMIENTO:</b>
            <div>
              <p class="panel-value">{{ datamesseccali.fecnacimient ? datamesseccali.fecnacimient : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DIRECCIÓN:</b>
            <div>
              <p class="panel-value">{{ datamesseccali.dir ? datamesseccali.dir : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DEPARTAMENTO:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">MUNICIPIO:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">TELEFONO:</b>
            <div>
              <p class="panel-value">{{ datamesseccali.tel ? datamesseccali.mesdata : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CELULAR:</b>
            <div>
              <p class="panel-value">-</p>
            </div>
          </div>
<!--          <div class="col-md-6">-->
<!--            <b class="panel-label">CORREO:</b>-->
<!--            <div v-for="(datamesseccali, key) in datames" :key="key">-->
<!--              <p class="panel-value">{{ datamesseccali.email }}</p>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div-->
<!--              class="col-6"-->
<!--              v-if="-->
<!--                    user.roles_id === 1 ||-->
<!--                    user.roles_id === '1' ||-->
<!--                    user.roles_id === 4 ||-->
<!--                    user.roles_id === '4' ||-->
<!--                    user.roles_id === 5 ||-->
<!--                    user.roles_id === '5'-->
<!--                  "-->
<!--          >-->
<!--            <b class="panel-label">DESCRIPCIÓN:</b>-->
<!--            <div v-for="(datamesfidu, key) in datamesfidu" :key="key">-->
<!--              <p class="panel-value">{{ datamesfidu.desctipvinc }}</p>-->
<!--            </div>-->
<!--          </div>-->

<!--          <div-->
<!--              class="col-6"-->
<!--              v-if="-->
<!--                    user.roles_id === 1 ||-->
<!--                    user.roles_id === '1' ||-->
<!--                    user.roles_id === 4 ||-->
<!--                    user.roles_id === '4' ||-->
<!--                    user.roles_id === 5 ||-->
<!--                    user.roles_id === '5'-->
<!--                  "-->
<!--          >-->
<!--            <b class="panel-label">RECURSOS:</b>-->
<!--            <div v-for="(datamesfidu, key) in datamesfidu" :key="key">-->
<!--              <p class="panel-value">{{ datamesfidu.descfuenrecurso }}</p>-->
<!--            </div>-->
<!--          </div>-->

<!--          <div-->
<!--              class="col-6"-->
<!--              v-if="-->
<!--                    user.roles_id === 1 ||-->
<!--                    user.roles_id === '1' ||-->
<!--                    user.roles_id === 4 ||-->
<!--                    user.roles_id === '4' ||-->
<!--                    user.roles_id === 5 ||-->
<!--                    user.roles_id === '5'-->
<!--                  "-->
<!--          >-->
<!--            <b class="panel-label">FECHA DE CARGA DATA:</b>-->
<!--            <div v-for="(datamesfidu, key) in datamesfidu" :key="key">-->
<!--              <p class="panel-value">{{ datamesfidu.fecdata }}</p>-->
<!--            </div>-->
<!--          </div>-->

<!--          <div-->
<!--              class="col-6"-->
<!--              v-if="-->
<!--                    user.roles_id === 1 ||-->
<!--                    user.roles_id === '1' ||-->
<!--                    user.roles_id === 4 ||-->
<!--                    user.roles_id === '4' ||-->
<!--                    user.roles_id === 5 ||-->
<!--                    user.roles_id === '5'-->
<!--                  "-->
<!--          >-->
<!--            <b class="panel-label">MES DE CARGA DATA:</b>-->
<!--            <div v-for="(datamesfidu, key) in datamesfidu" :key="key">-->
<!--              <p class="panel-value">{{ datamesfidu.mesdata }}</p>-->
<!--            </div>-->
<!--          </div>-->

<!--          <div-->
<!--              class="col-6"-->
<!--              v-if="-->
<!--                    user.roles_id === 1 ||-->
<!--                    user.roles_id === '1' ||-->
<!--                    user.roles_id === 4 ||-->
<!--                    user.roles_id === '4' ||-->
<!--                    user.roles_id === 5 ||-->
<!--                    user.roles_id === '5'-->
<!--                  "-->
<!--          >-->
<!--            <b class="panel-label">AÑO DE CARGA DATA:</b>-->
<!--            <div v-for="(datamesfidu, key) in datamesfidu" :key="key">-->
<!--              <p class="panel-value">{{ datamesfidu.anodata }}</p>-->
<!--            </div>-->
<!--          </div>-->
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: "Datames",
  props: ['datamesseccali', 'user'],
}
</script>

<style scoped>

</style>
