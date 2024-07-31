<template>
    <div class="col-6">
        <div class="panel mb-3">
            <div class="panel-heading">
                <b>HISTORIAL LABORAL</b>
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
                            SED VALLE
                    ==============================-->

                    <template v-if="datamessedvalle">
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
                                    <p class="panel-value" v-else>{{ datamessedvalle.vpension | currency }}</p>
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
                                <p class="panel-value">{{ datamessedvalle.fechingr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">AREA DE DESEMPEÑO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.esquema }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CARGO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.cargo }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.fecnombr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label"> TIPO DE CONTRATO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.ncontr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">GRADO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.grado ? datamessedvalle.grado : '-' }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">PRINCIPAL :</b>
                            <div>
                                <p class="panel-value">
                                    {{ datamessedvalle.dependencia ? datamessedvalle.dependencia : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">SEDE:</b>
                            <div>
                                <p class="panel-value">
                                    {{ datamessedvalle.centrocosto ? datamessedvalle.centrocosto : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">TIPO VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.nivcontr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">ESTADO LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.estlaboral }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CENTRO DE EDUCACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.centrocosto }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.sedecoleg }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CIUDAD LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.ciudad ? datamessedvalle.ciudad : '-' }}</p>
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
                                        <p class="panel-value" v-else>
                                            {{ datamesSed.vingresos || datamesSed.basico | currency }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                <p class="panel-value">{{ datamesSed.fingr || datamesSed.fchingreso }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.fnombramiento || datamesSed.nomfecha }}</p>
                            </div>
                        </div>

                        <div class="col-6" v-if="datamesSed.esquema || datamesSed.areaedu">
                            <b class="panel-label">AREA DE DESEMPEÑO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.esquema || datamesSed.areaedu }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CARGO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.cargo }}</p>
                            </div>
                        </div>

                        <div class="col-6" v-if="datamesSed.ncontr || datamesSed.ncontrata || datamesSed.nivelcontratacion">
                            <b class="panel-label"> TIPO DE CONTRATO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.ncontr || datamesSed.ncontrata || datamesSed.nivelcontratacion }}</p>
                            </div>
                        </div>

                        <div class="col-6" v-if="datamesSed.grado || datamesSed.grad">
                            <b class="panel-label"> GRADO:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.grado || datamesSed.grad }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label"> PRINCIPAL :</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.depen || datamesSed.neducdb }}</p>
                            </div>
                        </div>

                        <div class="col-6" v-if="datamesSed.cencosto || datamesSed.estabedu || datamesSed.ie_sede_area">
                            <b class="panel-label"> SEDE:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.cencosto || datamesSed.estabedu || datamesSed.ie_sede_area}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label"> CIUDAD LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamesSed.ciudad || datamesSed.ciud }}</p>
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
                    <div class="col-6" v-if="fechavinc.vinc">
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
    props: ['fechavinc', 'datamessedvalle', 'datamesfidu', 'datamessemcali', 'user'],
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        ...mapState('pagaduriasModule', ['pagaduriaType']),
        ...mapGetters('pagaduriasModule', ['ingresosExtras', 'salarioBasico', 'valorIngreso'])
    }
};
</script>
