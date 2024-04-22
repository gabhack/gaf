<template>
    <div class="col-6">
        <div class="panel mb-3">
            <div class="panel-heading">
                <b>INFORMACIÓN LABORAL</b>
            </div>
            <div class="panel-body">
                <div class="row">
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
                    </template>

                    <!-- DATAMES SED -->
                    <template v-if="datamesfidu || datamessedvalle || pagaduriaType === 'FOPEP'"> </template>
                    <template v-else-if="datamesSed">
                        <div class="col-6">
                            <b class="panel-label">FECHA INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.fecha_ingreso || datamesSed.fchingreso }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA DE INGRESO NÓMINA:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.fecha_ingreso_nomina || datamesSed.fecha_ingreso }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA DE INICIO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.fecha_inicio || datamesSed.fecha_ingreso  }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ antiguedad }} años</p>
                            </div>
                        </div>

                        <!-- <div class="col-6">
                            <b class="panel-label">PUESTO DE TRABAJO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.puesto_trabajo }}</p>
                            </div>
                        </div> -->

                        <div class="col-6">
                            <b class="panel-label">CARGO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.cargo}}</p>
                            </div>
                        </div>

                        <!-- <div class="col-6">
                            <b class="panel-label"> ENCARGO:</b>
                            <div>
                                <p class="panel-value">{{ coupons.encargo }}</p> 
                                
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> FECHA DE NOMBRAMIENTO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.fecha_nombramiento }}</p>
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> FECHA DE POSESIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.fecha_posesion }}</p>
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> CONTINUIDAD:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.continuidad }}</p>
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> FECHA DE CONTINUIDAD:</b>
                            <div>
                                <p class="panel-value" v-if="datamesSed.fecha_continuidad === 'nan' "> </p>
                                <p class="panel-value" v-else>{{ datamesSed.fecha_continuidad }}</p>
                            </div>
                        </div> -->

                        <div class="col-6">
                            <b class="panel-label"> TIPO DE CONTRATO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.tipo_contrato }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label"> SITUACIÓN LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.situacion_laboral}}</p>
                            </div>
                        </div>

                        <!-- <div class="col-6">
                            <b class="panel-label"> ESTADO DE VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.estado_vinculacion}}</p>
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> SEDE PRINCIPAL:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.sede_principal}}</p>
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> SEDE LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.sede_laboral }}</p>
                            </div>
                        </div> -->

                        <!-- <div class="col-6">
                            <b class="panel-label"> PROFESIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.profesion }}</p>
                            </div>
                        </div> -->

                        <div class="col-6">
                            <b class="panel-label"> ÁREA DE DESEMPEÑO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.area_desempeño }}</p>
                            </div>
                        </div>

                        <!-- <div class="col-6">
                            <b class="panel-label"> MESADA COMPARTIDA:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.mesa_compartida }}</p>
                            </div>
                        </div> -->
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
import { mapState, mapGetters } from 'vuex';

export default {
    name: 'EmploymentHistory',
    props: ['fechavinc', 'datamessedvalle', 'datamesfidu', 'datamessemcali', 'user'],
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        ...mapState('pagaduriasModule', ['pagaduriaType']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos']),
        ...mapState('pagaduriasModule', ['coupons']),
        ...mapGetters('pagaduriasModule', ['ingresosExtras', 'salarioBasico', 'valorIngreso']),
        fechaIngreso() {
            return this.datamesSed.fecha_ingreso;
        },
        antiguedad(){
            return this.calcularAntiguedad(this.fechaIngreso);
        }
    },

    methods: {
        calcularAntiguedad(fechaIngreso) {
            var partes = fechaIngreso.split('/');
            var fechaNacimientoFormatoCorrecto = partes[1] + '/' + partes[0] + '/' + partes[2];
            var hoy = new Date();
            var ingreso = new Date(fechaNacimientoFormatoCorrecto);
            var antiguedad = hoy.getFullYear() - ingreso.getFullYear();
            var m = hoy.getMonth() - ingreso.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < ingreso.getDate())) {
                antiguedad = antiguedad - 1;
            }
            return antiguedad;
            
        },
    }
};
</script>
