<template>
    <div class="col-6" v-if="datamesSed">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title" style="color: #2c8c73">Información laboral</h3>
                        <thead>
                            <tr>
                                <th style="color:#2c8c73">Fecha ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.fecha_ingreso || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color:#2c8c73">Fecha ingreso nómina</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.fecha_ingreso_nomina || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color:#2c8c73">Fecha de inicio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.fecha_inicio || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color:#2c8c73">Antiguedad laboral</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.antiguedad ? datamesSed.antiguedad + ' años' : '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color:#2c8c73">Cargo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.cargo || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color:#2c8c73">Tipo de contrato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.tipo_contrato || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73">Situación laboral</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.situacion_laboral || '--' }}</td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="color: #2c8c73">Área de desempeño</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ datamesSed.area_desempeño || '--' }}</td>
                            </tr>
                        </tbody>
                         <div class="panel-body pt-0 pb-0">
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
                    <template v-else-if="datamesSed"> </template>

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
        antiguedad() {
            return this.calcularAntiguedad(this.fechaIngreso);
        },
        datamesSedArray() {
            return [this.datamesSed];
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
        }
    }
};
</script>
