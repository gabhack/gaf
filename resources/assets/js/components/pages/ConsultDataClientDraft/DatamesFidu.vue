<template>
  <div class="col-6">
    <div class="panel panel-primary mb-3">
      <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-6">
            <b class="panel-label">TIPO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.tdd ? datamesFidu.tdd : 'CÉDULA DE CIUDADANÍA' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NUMERO DE DOCUMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.doc ? datamesFidu.doc : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">NOMBRE Y APELLIDO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.nombenef ? datamesFidu.nombenef : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">FECHA DE NACIMIENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.fecnacimient ? datamesFidu.fecnacimient : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DIRECCIÓN:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.dir ? datamesFidu.dir : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">DEPARTAMENTO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.dpto ? datamesFidu.dpto : '-' }}</p>
            </div>
          </div>
          <div class="col-6">
            <b class="panel-label">MUNICIPIO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.mnpio ? datamesFidu.mnpio : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">TELEFONO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.tel ? datamesFidu.tel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CELULAR:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.tel ? datamesFidu.tel : '-' }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <b class="panel-label">CORREO:</b>
            <div>
              <p class="panel-value">{{ datamesFidu.correo ? datamesFidu.correo : '-' }}</p>
            </div>
          </div>
          <!--<div class="col-6">
                              <b class="panel-label">ESTADO CIVIL:</b>
                              <div v-for="(datamesFidu, key) in datamesFidu" :key="key">
                                  <p class="panel-value">{{datamesFidu.estcivil}}</p>
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
              <p class="panel-value">{{ datamesFidu.desctipvinc ? datamesFidu.desctipvinc : '-' }}</p>
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
              <p class="panel-value">{{ datamesFidu.descfuenrecurso ? datamesFidu.descfuenrecurso : '-' }}</p>
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
              <p class="panel-value">{{ datamesFidu.fecdata ? datamesFidu.fecdata : '-' }}</p>
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
              <p class="panel-value">{{ datamesFidu.mesdata ? datamesFidu.mesdata : '-' }}</p>
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
              <p class="panel-value">{{ datamesFidu.anodata ? datamesFidu.anodata : '-' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DatamesFidu',
  props: ['datamesFidu', 'user']
};
</script>

<style scoped></style>
