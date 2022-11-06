<template>
  <div class="col-6">
    <div class="panel mb-3">
      <div class="panel-heading">
        <b>INFORMACIÓN PERSONAL</b>
      </div>
      <div class="panel-body">
        <div class="row">
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
            <b class="panel-label">FONDO:</b>
            <div>
              <p class="panel-value">{{ datames.fondo ? datames.fondo : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">TIPO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datames.td ? datames.td : 'CÉDULA DE CIUDADANÍA' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NUMERO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datames.doc ? datames.doc : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NOMBRE Y APELLIDO:</b>
            <div>
              <p class="panel-value">{{ datames.nomp ? datames.nomp : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">FECHA DE NACIMIENTO:</b>
            <div>
              <p class="panel-value">{{ datames.fecnacimient ? datames.fecnacimient : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DIRECCIÓN:</b>
            <div>
              <p class="panel-value">{{ datames.dir ? datames.dir : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DEPARTAMENTO:</b>
            <div>
              <p class="panel-value">{{ datames.dpto ? datames.dpto : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">MUNICIPIO:</b>
            <div>
              <p class="panel-value">{{ datames.mnpio ? datames.mnpio : '-' }}</p>
            </div>
          </div>
          <div
            class="col-md-6"
            v-if="
              user.roles_id === 1 ||
              user.roles_id === '1' ||
              user.roles_id === 4 ||
              user.roles_id === '4' ||
              user.roles_id === 5 ||
              user.roles_id === '5'
            "
          >
            <b class="panel-label">NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b>
            <div>
              <p class="panel-value">{{ datames.nbanco ? datames.nbanco : '-' }}</p>
            </div>
          </div>
          <div
            class="col-md-6"
            v-if="
              user.roles_id === 1 ||
              user.roles_id === '1' ||
              user.roles_id === 4 ||
              user.roles_id === '4' ||
              user.roles_id === 5 ||
              user.roles_id === '5'
            "
          >
            <b class="panel-label">SUCURSAL BANCO:</b>
            <div>
              <p class="panel-value">{{ datames.sucursal ? datames.sucursal : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">TELEFONO:</b>
            <div>
              <p class="panel-value">{{ datames.tel ? datames.tel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CELULAR:</b>
            <div>
              <p class="panel-value">{{ datames.cel ? datames.cel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CORREO:</b>
            <div>
              <p class="panel-value">{{ datames.correo ? datames.correo : '-' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Datames',
  props: ['datames', 'user']
};
</script>

<style scoped>
</style>
