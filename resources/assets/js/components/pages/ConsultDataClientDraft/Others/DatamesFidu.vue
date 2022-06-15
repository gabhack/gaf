<template>
  <div class="col-6">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-6">
            <b class="panel-label">TIPO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.td ? datamesfidu.td : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NUMERO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.doc ? datamesfidu.doc : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NOMBRE Y APELLIDO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.nombenef ? datamesfidu.nombenef : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">FECHA DE NACIMIENTO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.fecnacimient ? datamesfidu.fecnacimient : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DIRECCIÓN:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.dir ? datamesfidu.dir : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DEPARTAMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.dpto ? datamesfidu.dpto : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">MUNICIPIO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.mnpio ? datamesfidu.mnpio : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">TELEFONO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.tel ? datamesfidu.tel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CELULAR:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.tel ? datamesfidu.tel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CORREO:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.correo ? datamesfidu.correo : '-' }}</p>
            </div>
          </div>
          <!--<div class="col-6">
                              <b class="panel-label">ESTADO CIVIL:</b>
                              <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                  <p class="panel-value">{{datamesfidu.estcivil}}</p>
                              </div>
                          </div>-->

          <div
              class="col-6"
              v-if="
                          user.roles_id === 1 ||
                          user.roles_id === '1' ||
                          user.roles_id === 4 ||
                          user.roles_id === '4' ||
                          user.roles_id === 5 ||
                          user.roles_id === '5'
                        "
          >
            <b class="panel-label">DESCRIPCIÓN:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.desctipvinc ? datamesfidu.desctipvinc : '-' }}</p>
            </div>
          </div>

          <div
              class="col-6"
              v-if="
                          user.roles_id === 1 ||
                          user.roles_id === '1' ||
                          user.roles_id === 4 ||
                          user.roles_id === '4' ||
                          user.roles_id === 5 ||
                          user.roles_id === '5'
                        "
          >
            <b class="panel-label">RECURSOS:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.descfuenrecurso ? datamesfidu.descfuenrecurso : '-' }}</p>
            </div>
          </div>

          <div
              class="col-6"
              v-if="
                          user.roles_id === 1 ||
                          user.roles_id === '1' ||
                          user.roles_id === 4 ||
                          user.roles_id === '4' ||
                          user.roles_id === 5 ||
                          user.roles_id === '5'
                        "
          >
            <b class="panel-label">FECHA DE CARGA DATA:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.fecdata ? datamesfidu.fecdata : '-' }}</p>
            </div>
          </div>

          <div
              class="col-6"
              v-if="
                          user.roles_id === 1 ||
                          user.roles_id === '1' ||
                          user.roles_id === 4 ||
                          user.roles_id === '4' ||
                          user.roles_id === 5 ||
                          user.roles_id === '5'
                        "
          >
            <b class="panel-label">MES DE CARGA DATA:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.mesdata ? datamesfidu.mesdata : '-' }}</p>
            </div>
          </div>

          <div
              class="col-6"
              v-if="
                          user.roles_id === 1 ||
                          user.roles_id === '1' ||
                          user.roles_id === 4 ||
                          user.roles_id === '4' ||
                          user.roles_id === 5 ||
                          user.roles_id === '5'
                        "
          >
            <b class="panel-label">AÑO DE CARGA DATA:</b>
            <div>
              <p class="panel-value">{{ datamesfidu.anodata ? datamesfidu.anodata : '-' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DatamesFidu",
  props: ['datamesfidu', 'user'],
  created() {
  }
}
</script>

<style scoped>

</style>
