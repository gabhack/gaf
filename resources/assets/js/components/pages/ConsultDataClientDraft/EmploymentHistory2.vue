<template>
    <div class="col-6">
        <div class="panel mb-3">
            <h3 class="heading-title mb-0 pt-5">Información laboral</h3>

            <div class="panel-body pt-0">
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
                        <b-table
                            :items="datamesSedArray"
                            :fields="fields"
                            class="mt-3"
                            responsive
                            thead-class="table-header-nowrap"
                        ></b-table>
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
    data() {
        return {
            fields: [
                {
                    key: 'fecha_ingreso',
                    label: 'Fecha ingreso',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'fecha_ingreso_nomina',
                    label: 'Fecha ingreso nómina',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'fecha_inicio',
                    label: 'Fecha de inicio',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'antiguedad',
                    label: 'Antiguedad laboral',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'cargo',
                    label: 'Cargo',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'tipo_contrato',
                    label: 'Tipo de contrato',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'situacion_laboral',
                    label: 'Situación laboral',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                }
            ]
        };
    },
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
<style lang="scss" scoped>
::v-deep .table-responsive {
    margin-left: -3px;
}
::v-deep .table {
    & thead {
        background-color: #3a5659;
        white-space: nowrap;
        color: white;
        font-size: 14px;
        font-weight: 700;
        line-height: 18.23px;
        & tr th {
            padding: 12px 40px;
            text-align: center;
            min-height: 50px !important;
            & div {
                min-height: 50px;
                display: flex;
                align-items: center;
            }
        }
    }
    & tbody {
        background-color: #fff;
        font-size: 14px;
        font-weight: 400;
        line-height: 18.23px;
        & td {
            text-align: center;
        }
    }
}
</style>
