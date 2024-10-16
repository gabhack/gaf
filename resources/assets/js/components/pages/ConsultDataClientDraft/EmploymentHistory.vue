<template>
    <div class="col-6">
        <div class="panel mb-3">
            <h3 class="heading-title mb-0 pt-5">Historial laboral</h3>
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
                        <b-table
                            :items="datamesSedArray"
                            :fields="fields"
                            class="mt-3"
                            responsive
                            thead-class="table-header-nowrap"
                        ></b-table>
                        <b-row style="width: 100%">
                            <b-col cols="12" md="6">
                                <table v-if="arrayCoupons.length > 0" style="width: 100%; border-collapse: collapse">
                                    <thead
                                        style="height: 78.5px; font-size: 14px; font-weight: 700; line-height: 18.23px"
                                    >
                                        <tr style="background-color: #f2f2f2">
                                            <th
                                                style="
                                                    padding: 10px;
                                                    padding-left: 25px;
                                                    text-align: left;
                                                    background-color: #3a5659;
                                                    color: white;
                                                "
                                            >
                                                Valor ingreso
                                            </th>
                                            <th style="padding: 10px; text-align: left; background-color: #3a5659"></th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 14px; font-weight: 400; line-height: 18.23px">
                                        <tr style="background-color: white">
                                            <td style="padding: 10px; padding-left: 25px">
                                                {{ valorIngreso | currency }}
                                            </td>
                                            <td style="padding: 10px; text-align: center"></td>
                                        </tr>
                                        <tr style="background-color: #f2f2f2">
                                            <td style="padding: 10px; padding-left: 25px"><b>Total</b></td>
                                            <td style="padding: 10px; text-align: center">
                                                <b>{{ valorIngreso | currency }}</b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </b-col>
                            <b-col cols="12" md="6" class="pr-0">
                                <b-table
                                    v-if="salarioBasico.length > 0"
                                    :items="salarioBasico"
                                    :fields="fieldsSueldo"
                                    responsive
                                    striped
                                    hover
                                >
                                    <template #cell(concept)="data">
                                        <p class="mb-0">
                                            {{ data.item.concept }}
                                        </p>
                                    </template>

                                    <template #cell(ingresos)="data">
                                        <p class="mb-0">{{ data.item.ingresos | currency }}</p>
                                    </template>
                                </b-table>
                            </b-col>
                            <b-col cols="12" class="pr-0">
                                <b-table
                                    v-if="ingresosExtras.length > 0"
                                    :items="ingresosExtras"
                                    :fields="fieldsExtras"
                                    responsive
                                    striped
                                    hover
                                    class="pt-2"
                                >
                                    <template #cell(concept)="data">
                                        <p class="mb-0">
                                            {{ data.item.concept }}
                                        </p>
                                    </template>

                                    <template #cell(ingresos)="data">
                                        <p class="mb-0">{{ data.item.ingresos | currency }}</p>
                                    </template>
                                </b-table>
                            </b-col>
                            <b-col cols="12" class="pr-0">
                                <table v-if="arrayCoupons.length > 0" style="width: 100%; border-collapse: collapse">
                                    <thead
                                        style="height: 78.5px; font-size: 14px; font-weight: 700; line-height: 18.23px"
                                    >
                                        <tr style="background-color: #f2f2f2">
                                            <th
                                                style="
                                                    padding: 10px;
                                                    padding-left: 25px;
                                                    text-align: left;
                                                    background-color: #3a5659;
                                                    color: white;
                                                "
                                            >
                                                Días laborados
                                            </th>
                                            <th style="padding: 10px; text-align: left; background-color: #3a5659"></th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        style="
                                            font-size: 14px;
                                            font-weight: 400;
                                            line-height: 18.23px;
                                            background-color: white;
                                        "
                                    >
                                        <tr>
                                            <td style="padding: 10px; padding-left: 25px">Total días</td>
                                            <td style="padding: 10px; text-align: center">
                                                {{ arrayCoupons.length > 0 ? arrayCoupons[0].dias_laborados : '--' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </b-col>
                        </b-row>
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
    data() {
        return {
            fieldsSueldo: [
                { key: 'concept', label: 'Sueldo básico' },
                { key: 'ingresos', label: 'Valor' }
            ],
            fieldsExtras: [
                { key: 'concept', label: 'Ingresos extras' },
                { key: 'ingresos', label: 'Valor' }
            ],
            fieldsIngreso: [
                { key: 'label', label: 'Valor ingreso' },
                { key: 'value', label: 'Valor' }
            ],
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
                    key: 'fnombramiento',
                    label: 'Fecha vinculacíon',
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
                    key: 'grado',
                    label: 'Grado',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'depen',
                    label: 'Principal',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                },
                {
                    key: 'ciudad',
                    label: 'Ciudad laboral',
                    sortable: false,
                    formatter: value => {
                        return value || '--';
                    }
                }
            ],
            arrayCoupons: []
        };
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        ...mapState('pagaduriasModule', ['pagaduriaType', 'setSelectedPeriod']),
        ...mapGetters('pagaduriasModule', [
            'ingresosExtras',
            'salarioBasico',
            'valorIngreso',
            'couponsIngresos',
            'pagaduriaPeriodos'
        ]),

        datamesSedArray() {
            return [this.datamesSed];
        },
        revisarAca() {
            return this.couponsIngresos.items;
        }
    },
    watch: {
        couponsIngresos() {
            this.arrayCoupons = [];
            this.arrayCoupons = [...this.couponsIngresos.items];
            function extractNumber(str) {
                const match = str.match(/\d+/);
                return match ? match[0] : null;
            }
            this.arrayCoupons.forEach(coupon => {
                coupon.dias_laborados = extractNumber(coupon.dias_laborados);
            });
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
                justify-content: center;
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
