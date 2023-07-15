<template>
  <div class="col-6">
    <div class="panel mb-3">
      <div class="panel-heading">
        <b>HISTORIAL LABORAL</b>
      </div>
      <div class="panel-body">
        <div class="row">
          <!--<div class="col-6">
                          <b class="panel-label">Documento de identidad Pensionado:</b>
                          <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                              <p class="panel-value">{{fechavinc.doc}}</p>
                          </div>
                      </div> -->
          <!--============================
                 FOPEP
          ==============================-->
          <template v-if="pagaduriaType === 'FOPEP'">
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">VALOR INGRESO:</b>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ valorIngreso | currency }}</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">SUELDO BASICO:</b>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                    <p class="panel-value" v-else>{{ datamesSed.pension | currency }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12" v-if="ingresosExtras.length > 0">
              <b class="panel-label">INGRESOS EXTRAS:</b>
              <div class="row">
                <div class="col-6">
                  <b class="panel-label table-text">CONCEPTO:</b>
                </div>
                <div class="col-6">
                  <b class="panel-label table-text">VALOR:</b>
                </div>
              </div>
              <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ extra.concept }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ extra.ingresos | currency }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">TIPO PENSION:</b>
              <div>
                <p class="panel-value">{{ datamesSed.tp }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.vpension | currency }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <b class="panel-label">VALOR SALUD:</b>
              <div>
                <p class="panel-value">{{ datamesSed.vsalud | currency }}</p>
              </div>
            </div>

            <div class="col-md-6">
              <b class="panel-label">VALOR DESCUENTOS:</b>
              <div>
                <p class="panel-value">{{ datamesSed.vdesc | currency }}</p>
              </div>
            </div>

            <div class="col-md-6">
              <b class="panel-label">VALOR CUPO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.cupo | currency }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <b class="panel-label">VALOR EMBARGOS:</b>
              <div>
                <p class="panel-value">{{ datamesSed.vembargos | currency }}</p>
              </div>
            </div>
          </template>

          <!--============================
                SED VALLE
          ==============================-->

          <template v-if="datamesseceduc">
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">VALOR INGRESO:</b>
                </div>
                <div class="col-6">
                  <p class="panel-value">{{ valorIngreso | currency }}</p>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">SUELDO BASICO:</b>
                </div>
                <div class="col-6">
                  <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                  <p class="panel-value" v-else>{{ datamesseceduc.vpension | currency }}</p>
                </div>
              </div>
            </div>

            <div class="col-12" v-if="ingresosExtras.length > 0">
              <b class="panel-label">INGRESOS EXTRAS:</b>
              <div class="row">
                <div class="col-6">
                  <b class="panel-label table-text">CONCEPTO:</b>
                </div>
                <div class="col-6">
                  <b class="panel-label table-text">VALOR:</b>
                </div>
              </div>
              <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ extra.concept }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ extra.ingresos | currency }}</p>
                  </div>
                </div>
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
              <b class="panel-label"> TIPO DE CONTRATO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.ncontr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">GRADO:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.grado ? datamesseceduc.grado : '-' }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">PRINCIPAL :</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.dependencia ? datamesseceduc.dependencia : '-' }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">SEDE:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.centrocosto ? datamesseceduc.centrocosto : '-' }}</p>
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

            <div class="col-6">
              <b class="panel-label">CIUDAD LABORAL:</b>
              <div>
                <p class="panel-value">{{ datamesseceduc.ciudad ? datamesseceduc.ciudad : '-' }}</p>
              </div>
            </div>
          </template>

          <!--============================
               FIDUPREVISORA
          ==============================-->
          <template v-if="datamesfidu">
            <div class="col-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
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

            <!-- <div class="col-6">
              <b class="panel-label">VALOR POSIBLE DESCUENTO:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.vdescbruto | currency }}</p>
              </div>
            </div>


            <div class="col-6">
              <b class="panel-label">VALOR POSIBLE DESCUENTO SALUD:</b>
              <div>
                <p class="panel-value">{{ datamesfidu.vdescbruto | currency }}</p>
              </div>
            </div> -->
          </template>

          <!--============================
            DATAMESCALI SEMCALI
          ==============================-->
          <!-- <template v-if="datamessemcali">
            <div class="col-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamessemcali.vingreso | currency }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamessemcali.fingr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA VINCULACION:</b>
              <div>
                <p class="panel-value">{{ datamessemcali.fnombramiento }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">AREA DE DESEMPEÑO:</b>
              <div>
                <p class="panel-value">{{ datamessemcali.esquema }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">CARGO:</b>
              <div>
                <p class="panel-value">{{ datamessemcali.cargo }}</p>
              </div>
            </div>
          </template> -->

          <!-- DATAMES SED -->
          <template v-if="datamesfidu || datamesseceduc || pagaduriaType === 'FOPEP'"> </template>
          <template v-else-if="datamesSed">
            <div class="col-12" v-if="ingresosExtras.length >= 0">
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">VALOR INGRESO:</b>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ valorIngreso | currency }}</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">SUELDO BASICO:</b>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                    <p class="panel-value" v-else>{{ datamesSed.vingresos || datamesSed.basico | currency }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div class="col-6">
              <b class="panel-label">VALOR INGRESO:</b>
              <div>
                <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                <p class="panel-value" v-else>{{ datamesSed.vingresos | currency }}</p>
              </div>
            </div>
            <div class="col-6">
              <b class="panel-label">SUELDO BASICO:</b>
              <div>
                <p class="panel-value" v-if="salarioBasico">{{ salarioBasico | currency }}</p>
                <p class="panel-value" v-else>{{ datamesSed.vingresos | currency }}</p>
              </div>
            </div> -->

            <div class="col-12" v-if="ingresosExtras.length > 0">
              <b class="panel-label">INGRESOS EXTRAS:</b>
              <div class="row">
                <div class="col-6">
                  <!-- <b class="panel-label table-text">CONCEPTO:</b> -->
                </div>
                <div class="col-6">
                  <!-- <b class="panel-label table-text">VALOR:</b> -->
                </div>
              </div>
              <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ extra.concept }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <div>
                    <p class="panel-value">{{ extra.ingresos | currency }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA INGRESO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.fingr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">FECHA VINCULACIÓN:</b>
              <div>
                <p class="panel-value">{{ datamesSed.fnombramiento }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">AREA DE DESEMPEÑO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.esquema }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label">CARGO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.cargo }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label"> TIPO DE CONTRATO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.ncontr }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label"> GRADO:</b>
              <div>
                <p class="panel-value">{{ datamesSed.grado }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label"> PRINCIPAL :</b>
              <div>
                <p class="panel-value">{{ datamesSed.depen ? datamesSed.depen : '-' }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label"> SEDE:</b>
              <div>
                <p class="panel-value">{{ datamesSed.cencosto ? datamesSed.cencosto : '-' }}</p>
              </div>
            </div>

            <div class="col-6">
              <b class="panel-label"> CIUDAD LABORAL:</b>
              <div>
                <p class="panel-value">{{ datamesSed.ciudad }}</p>
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
          <div class="col-6">
            <b class="panel-label">ANTIGUEDAD LABORAL:</b>
            <div>
              <p class="panel-value">{{ fechavinc.vinc }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
  name: 'EmploymentHistory',
  props: ['fechavinc', 'datamesseceduc', 'datamesfidu', 'datamessemcali', 'user'],
  computed: {
    ...mapState('datamesModule', ['datamesSed']),
    ...mapState('pagaduriasModule', ['pagaduriaType']),
    ...mapGetters('pagaduriasModule', ['ingresosExtras', 'salarioBasico', 'valorIngreso'])
  }
};
</script>
