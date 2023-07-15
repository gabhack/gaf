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
              <p class="panel-value">{{ datamesFopep.fondo ? datamesFopep.fondo : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">TIPO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.td ? datamesFopep.td : 'CÉDULA DE CIUDADANÍA' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NUMERO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.doc ? datamesFopep.doc : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NOMBRE Y APELLIDO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.nomp ? datamesFopep.nomp : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">FECHA DE NACIMIENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.fecnacimient ? datamesFopep.fecnacimient : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DIRECCIÓN:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.dir ? datamesFopep.dir : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DEPARTAMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.dpto ? datamesFopep.dpto : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">MUNICIPIO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.mnpio ? datamesFopep.mnpio : '-' }}</p>
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
              <p class="panel-value">{{ datamesFopep.nbanco ? datamesFopep.nbanco : '-' }}</p>
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
              <p class="panel-value">{{ datamesFopep.sucursal ? datamesFopep.sucursal : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">TELEFONO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.tel ? datamesFopep.tel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CELULAR:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.cel ? datamesFopep.cel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CORREO:</b>
            <div>
              <p class="panel-value">{{ datamesFopep.correo ? datamesFopep.correo : '-' }}</p>
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
  props: ['datamesFopep', 'user']
};
</script>
