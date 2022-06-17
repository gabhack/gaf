<template>
  <div class="col-6">
    <div class="panel mb-3">
      <div class="panel-heading">
        <b>HISTORIAL LABORAL {{ pagaduriaType }}</b>
      </div>
      <div class="panel-body">
        <div class="row">
          <!--<div class="col-6">
                          <b class="panel-label">Documento de identidad Pensionado:</b>
                          <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                              <p class="panel-value">{{fechavinc.doc}}</p>
                          </div>
                      </div> -->
          <div class="col-6">
            <b class="panel-label">ANTIGUEDAD LABORAL:</b>
            <div>
              <p class="panel-value">{{ fechavinc.vinc }}</p>
            </div>
          </div>

          <!--============================
                 FOPEP
          ==============================-->
          <template v-if="datames && pagaduriaType == 'FOPEP'">

            <div class="col-6">
              <b class="panel-label">TIPO PENSION:</b>
              <div>
                <p class="panel-value">{{ fechavinc.tp }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datames.vpension | currency }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <b class="panel-label">VALOR SALUD:</b>
              <div>
                <p class="panel-value">{{ datames.vsalud | currency }}</p>
              </div>
            </div>

            <div class="col-md-6">
              <b class="panel-label">VALOR DESCUENTOS:</b>
              <div>
                <p class="panel-value">{{ datames.vdesc | currency }}</p>
              </div>
            </div>

            <div class="col-md-6">
              <b class="panel-label">VALOR CUPO:</b>
              <div>
                <p class="panel-value">{{ datames.cupo | currency }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <b class="panel-label">VALOR EMBARGOS:</b>
              <div>
                <p class="panel-value">{{ datames.venbargos | currency }}</p>
              </div>
            </div>

          </template>


          <!--============================
                FODE VALLE
          ==============================-->

          <template v-if="datamesseceduc && pagaduriaType == 'FODE VALLE'">


            <div class="col-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.vpension | currency }}</p>
              </div>
            </div>


            <div class="col-6">
              <b class="panel-label">FECHA INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.fechingr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">AREA DE DESEMPEÑO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.esquema }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">CARGO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.cargo }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA VINCULACIÓN:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.fecnombr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">TIPO VINCULACIÓN:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.nivcontr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">ESTADO LABORAL:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.estlaboral }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">CENTRO DE EDUCACIÓN:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.centrocosto }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.sedecoleg }}</p>
              </div>
            </div>

          </template>

          <!--============================
               FIDUPREVISORA
          ==============================-->
          <template v-if="datamesfidu  && pagaduriaType == 'FIDUPREVISORA'">

            <div class="col-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.vpension  | currency }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">VINCULACION:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.vinc }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA DE PAGO PENSION:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.fechpago }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">VALOR DESCUENTO:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.vdescbruto | currency }}</p>
              </div>
            </div>
          </template>


          <!--============================
            DATAMESCALI SECCALI
          ==============================-->
          <template v-if="datamesseccali && pagaduriaType == 'SECCALI'">

            <div class="col-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesseccali.vpension | currency }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">VINCULACION:</b>
              <div>
                <p class="panel-value">{{ datamesseccali.vinc }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA DE PAGO PENSION:</b>
              <div>
                <p class="panel-value">{{ datamesseccali.fechpago }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">VALOR DESCUENTO:</b>
              <div>
                <p class="panel-value">{{ datamesseccali.vdescbruto | currency }}</p>
              </div>
            </div>
          </template>


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
            <div>
              <p class="panel-value">{{ fechavinc.fecdata }}</p>
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
              <p class="panel-value">{{ fechavinc.mesdata }}</p>
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
              <p class="panel-value">{{ fechavinc.anodata }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "EmploymentHistory",
  props: ['fechavinc', 'pagaduriaType', 'datames', 'datamesseceduc', 'datamesfidu', 'datamesseccali', 'user'],
  created() {

  },
}
</script>

<style scoped>

</style>
