<template>
  <div class="col-6">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-6">
            <b class="panel-label">TIPO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.td ? datamesseceduc.td : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NUMERO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.doc }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NOMBRE Y APELLIDO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.nomp }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">FECHA DE NACIMIENTO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.fecnacimient }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DIRECCIÓN:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.dir }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DEPARTAMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.dpto }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">MUNICIPIO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.mnpioydep }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">TELEFONO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.tel }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CELULAR:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.cel }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CORREO:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.correo }}</p>
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
            <b class="panel-label">FECHA CARGA DATA:</b>
            <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
              <p class="panel-value">{{ datamesseceduc.fecdata }}</p>
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
            <b class="panel-label">MES CARGA DATA:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.mesdata }}</p>
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
            <b class="panel-label">AÑO CARGA DATA:</b>
            <div>
              <p class="panel-value">{{ datamesseceduc.anodata }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DatamesSeduc",
  props: ['datamesseceduc', 'user'],
}
</script>

<style scoped>

</style>
