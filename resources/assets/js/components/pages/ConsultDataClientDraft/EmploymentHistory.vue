<template>
    <div class="col-12 px-0">
        <div class="panel panel-primary mb-3">
            <h3
                class="heading-title w-100 d-flex align-items-center justify-content-start"
                :class="visible ? null : 'collapsed'"
                :aria-expanded="visible ? 'true' : 'false'"
                aria-controls="info-historial"
                @click="visible = !visible"
                style="cursor: pointer; gap: 10px"
            >
                <svg
                    version="1.1"
                    :class="{ rotate180: visible }"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
                    x="0px"
                    y="0px"
                    width="15px"
                    height="9px"
                    viewBox="0 0 15 9"
                    style="enable-background: new 0 0 15 9"
                    xml:space="preserve"
                >
                    <defs></defs>
                    <path
                        fill="#3a5659"
                        d="M6.4,8.6C7,9.1,8,9.1,8.6,8.6l6-6c0.4-0.4,0.6-1.1,0.3-1.6C14.6,0.4,14.1,0,13.5,0l-12,0C0.9,0,0.3,0.4,0.1,0.9
                        S0,2.1,0.4,2.6L6.4,8.6L6.4,8.6z"
                    />
                </svg>
                Historial Laboral
            </h3>
            <b-collapse id="info-historial" v-model="visible" class="mt-2">
                <div v-if="datamesSedArray.length > 0" class="general-info col-12 px-0">
                    <div class="col-12 px-0 d-flex align-items-start justify-content-between flex-column flex-sm-row">
                        <div class="col-12 col-48 px-0">
                            <div v-if="datamesSed">
                                <div class="mt-3 table-responsive">
                                    <table role="table" aria-colcount="1" class="table b-table table-striped table-hover">
                                        <thead role="rowgroup" class="table-header-nowrap">
                                            <tr role="row">
                                                <th role="columnheader" scope="col" aria-colindex="1">Cargo</th>
                                            </tr>
                                        </thead>
                                        <tbody role="rowgroup">
                                            <tr role="row" v-for="(item, index) in datamesSedArray" :key="index">
                                                <td aria-colindex="1" role="cell">{{ item.cargo || 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 px-0 d-flex align-items-start justify-content-between flex-column flex-sm-row">
                        <div class="col-12 col-48 px-0">
                            <div class="mt-3 table-responsive">
                                <div v-if="datamesSed">
                                    <table
                                        role="table"
                                        aria-colcount="1"
                                        class="table b-table table-striped table-hover"
                                    >
                                        <thead role="rowgroup" class="table-header-nowrap">
                                            <tr role="row">
                                                <th role="columnheader" scope="col" aria-colindex="1">Principal</th>
                                            </tr>
                                        </thead>
                                        <tbody role="rowgroup">
                                            <tr role="row" v-for="(item, index) in datamesSedArray" :key="index">
                                                <td aria-colindex="1" role="cell">{{ item.depen || 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-48 px-0">
                            <div class="mt-3 table-responsive">
                                <table role="table" aria-colcount="1" class="table b-table table-striped table-hover">
                                    <thead role="rowgroup" class="table-header-nowrap">
                                        <tr role="row">
                                            <th role="columnheader" scope="col" aria-colindex="1">Fecha de Vinculación</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <template v-if="pagaduriaType === 'FOPEP'">
                    <div class="col-12 px-0 info-general">
                        <div class="col-12 col-48 px-0">
                            <div class="mt-3 table-responsive">
                                <table role="table" aria-colcount="1" class="table b-table table-striped table-hover">
                                    <thead role="rowgroup" class="table-header-nowrap">
                                        <tr role="row">
                                            <th role="columnheader" scope="col" aria-colindex="2">VALOR INGRESO:</th>
                                        </tr>
                                    </thead>
                                    <tbody role="rowgroup">
                                        <tr role="row">
                                            <td aria-colindex="1" role="cell">{{ valorIngreso | currency }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-48 px-0">
                            <div class="mt-3 table-responsive">
                                <table role="table" aria-colcount="1" class="table b-table table-striped table-hover">
                                    <thead role="rowgroup" class="table-header-nowrap">
                                        <tr role="row">
                                            <th role="columnheader" scope="col" aria-colindex="2">SUELDO BASICO:</th>
                                        </tr>
                                    </thead>
                                    <tbody role="rowgroup">
                                        <tr role="row">
                                            <td aria-colindex="2" role="cell">
                                                <p class="panel-value" v-if="salarioBasico">
                                                    {{ salarioBasico | currency }}
                                                </p>
                                                <p class="panel-value" v-else>{{ datamesSed.pension | currency }}</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" v-if="ingresosExtras.length > 0">
                        <b class="panel-label">INGRESOS EXTRAS:</b>
                        <div class="row">
                            <div class="col-2">
                                <b class="panel-label">CONCEPTO:</b>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">VALOR:</b>
                            </div>
                        </div>
                        <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                            <div class="col-2">
                                <p class="panel-value">{{ extra.concept }}</p>
                            </div>
                            <div class="col-2">
                                <p class="panel-value">{{ extra.ingresos | currency }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">TIPO PENSION:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.tp || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b class="panel-label">VALOR INGRESO:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.vpension | currency }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b class="panel-label">VALOR SALUD:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.vsalud | currency }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b class="panel-label">VALOR DESCUENTOS:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.vdesc | currency }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b class="panel-label">VALOR CUPO:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.cupo | currency }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b class="panel-label">VALOR EMBARGOS:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.vembargos | currency }}</p>
                        </div>
                    </div>
                </template>
                <div v-if="datamessedvalle">
                    <div class="col-12">
                        <div class="mt-3 table-responsive">
                            <table role="table" aria-colcount="2" class="table b-table table-striped table-hover">
                                <thead role="rowgroup" class="table-header-nowrap">
                                    <tr role="row">
                                        <th role="columnheader" scope="col" aria-colindex="1" colspan="1">
                                            VALOR INGRESO:
                                        </th>
                                        <th role="columnheader" scope="col" aria-colindex="2" colspan="1">
                                            <b class="panel-label">SUELDO BASICO:</b>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody role="rowgroup">
                                    <tr role="row">
                                        <td aria-colindex="1" role="cell" colspan="1">
                                            <p class="panel-value">{{ valorIngreso | currency }}</p>
                                        </td>
                                        <td aria-colindex="2" role="cell" colspan="1">
                                            <p class="panel-value" v-if="salarioBasico">
                                                {{ salarioBasico | currency }}
                                            </p>
                                            <p class="panel-value" v-else>{{ datamessedvalle.vpension | currency }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-4" v-if="ingresosExtras.length > 0">
                        <b class="panel-label">INGRESOS EXTRAS:</b>
                        <div class="row">
                            <div class="col-2">
                                <b class="panel-label">CONCEPTO:</b>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">VALOR:</b>
                            </div>
                        </div>
                        <div class="row" v-for="extra in ingresosExtras" :key="extra.code">
                            <div class="col-2">
                                <p class="panel-value">{{ extra.concept }}</p>
                            </div>
                            <div class="col-2">
                                <p class="panel-value">{{ extra.ingresos | currency }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">FECHA INGRESO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.fechingr || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">AREA DE DESEMPEÑO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.esquema || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">CARGO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.cargo || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">FECHA VINCULACIÓN:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.fecnombr || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label"> TIPO DE CONTRATO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.ncontr || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">GRADO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.grado ? datamessedvalle.grado : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">PRINCIPAL :</b>
                        <div>
                            <p class="panel-value">
                                {{ datamessedvalle.dependencia ? datamessedvalle.dependencia : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">SEDE:</b>
                        <div>
                            <p class="panel-value">
                                {{ datamessedvalle.centrocosto ? datamessedvalle.centrocosto : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">TIPO VINCULACIÓN:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.nivcontr || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">ESTADO LABORAL:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.estlaboral || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">CENTRO DE EDUCACIÓN:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.centrocosto || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.sedecoleg || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">CIUDAD LABORAL:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.ciudad ? datamessedvalle.ciudad : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <template v-if="datamesfidu">
                    <div class="col-2">
                        <b class="panel-label">VALOR INGRESO:</b>
                        <div>
                            <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">VINCULACION:</b>
                        <div>
                            <p class="panel-value">{{ datamesfidu.vinc || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">FECHA DE PAGO PENSION:</b>
                        <div>
                            <p class="panel-value">{{ datamesfidu.fechpago || 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <b class="panel-label">VALOR DESCUENTO:</b>
                        <div>
                            <p class="panel-value">{{ datamesfidu.vdescbruto | currency }}</p>
                        </div>
                    </div>
                </template>
                <template v-if="datamesfidu || datamessedvalle || pagaduriaType === 'FOPEP'"></template>
                <div v-else-if="datamesSed">
                    <div v-if="arrayCoupons.length > 0" class="col-12 px-0 d-flex align-items-start justify-content-between flex-column flex-sm-row">
                        <div class="col-12 col-48 px-0">
                            <div class="mt-3 table-responsive">
                                <table role="table" aria-colcount="2" class="table b-table table-striped table-hover">
                                    <thead role="rowgroup" class="table-header-nowrap">
                                        <tr role="row">
                                            <th role="columnheader" scope="col" aria-colindex="1" colspan="1">
                                                Valor de Ingreso
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody role="rowgroup">
                                        <tr role="row">
                                            <td aria-colindex="1" role="cell" colspan="1">
                                                {{ valorIngreso | currency }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-48 px-0">
                            <div class="mt-3 table-responsive">
                                <table role="table" aria-colcount="2" class="table b-table table-striped table-hover">
                                    <thead role="rowgroup" class="table-header-nowrap">
                                        <tr role="row">
                                            <th role="columnheader" scope="col" aria-colindex="2" colspan="1">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody role="rowgroup">
                                        <tr role="row">
                                            <td aria-colindex="2" role="cell" colspan="1">
                                                {{ valorIngreso | currency }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div v-if="ingresosExtras.length > 0" style="width: 100%">
                        <div class="mt-3 table-responsive">
                            <table role="table" aria-colcount="2" class="table b-table table-striped table-hover">
                                <thead role="rowgroup" class="table-header-nowrap">
                                    <tr role="row">
                                        <th role="columnheader" scope="col" aria-colindex="1" colspan="2">
                                            Ingresos Extras
                                        </th>
                                    </tr>
                                </thead>
                                <tbody role="rowgroup">
                                    <tr role="row" v-for="(item, index) in ingresosExtras" :key="index">
                                        <td colspan="1">
                                            {{ item.concept }}
                                        </td>
                                        <td colspan="1">
                                            {{ item.ingresos | currency }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-if="arrayCoupons.length > 0" style="width: 100%">
                        <div class="mt-3 table-responsive">
                            <table role="table" aria-colcount="2" class="table b-table table-hover">
                                <thead role="rowgroup" class="table-header-nowrap">
                                    <tr role="row">
                                        <th class="text-left" role="columnheader" scope="col" aria-colindex="2" colspan="2">
                                            Días Laborados
                                        </th>
                                    </tr>
                                </thead>
                                <tbody role="rowgroup">
                                    <tr role="row">
                                        <td class="text-left" aria-colindex="1" role="cell" colspan="1">Total días</td>
                                        <td class="text-right" aria-colindex="2" role="cell" colspan="1">
                                            <b style="font-weight: normal; color: inherit; padding-left: 10px; text-align: right;">
                                                {{ arrayCoupons.length > 0 ? arrayCoupons[0].dias_laborados : 'N/A' }}
                                            </b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6" v-if="user.role_id === 1 || user.role_id === '1' || user.role_id === 4 || user.role_id === '4' || user.role_id === 5 || user.role_id === '5'">
                    <b class="panel-label">FECHA CARGA DATA:</b>
                    <div>
                        <p class="panel-value">{{ fechavinc.fecdata || 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-6" v-if="user.role_id === 1 || user.role_id === '1' || user.role_id === 4 || user.role_id === '4' || user.role_id === 5 || user.role_id === '5'">
                    <b class="panel-label">MES CARGA DATA:</b>
                    <div>
                        <p class="panel-value">{{ fechavinc.mesdata || 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-6" v-if="user.role_id === 1 || user.role_id === '1' || user.role_id === 4 || user.role_id === '4' || user.role_id === 5 || user.role_id === '5'">
                    <b class="panel-label">AÑO CARGA DATA:</b>
                    <div>
                        <p class="panel-value">{{ fechavinc.anodata || 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-6" v-if="fechavinc.vinc">
                    <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                    <div>
                        <p class="panel-value">{{ fechavinc.vinc || 'N/A' }}</p>
                    </div>
                </div>
            </b-collapse>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
    name: 'EmploymentHistory',
    props: ['fechavinc', 'datamessedvalle', 'datamesfidu', 'datamessemcali', 'user'],
    data() {
        return {
            Sueldo: [
                { key: 'concept', label: 'Sueldo básico' },
                { key: 'ingresos', label: 'Valor' }
            ],
            Extras: [
                { key: 'concept', label: 'Ingresos extras' },
                { key: 'ingresos', label: 'Valor' }
            ],
            Ingreso: [
                { key: 'label', label: 'Valor ingreso' },
                { key: 'value', label: 'Valor' }
            ],
            fields: [
                {
                    key: 'fecha_ingreso',
                    label: 'Fecha ingreso',
                    sortable: false,
                    formatter: value => {
                        return value || 'N/A'
                    }
                },
                {
                    key: 'cargo',
                    label: 'Cargo',
                    sortable: false,
                    formatter: value => {
                        return value || 'N/A'
                    }
                },
                {
                    key: 'grado',
                    label: 'Grado',
                    sortable: false,
                    formatter: value => {
                        return value || 'N/A'
                    }
                },
                {
                    key: 'depen',
                    label: 'Principal',
                    sortable: false,
                    formatter: value => {
                        return value || 'N/A'
                    }
                },
                {
                    key: 'ciudad',
                    label: 'Ciudad laboral',
                    sortable: false,
                    formatter: value => {
                        return value || 'N/A'
                    }
                }
            ],
            arrayCoupons: [],
            visible: true
        }
    },
    computed: {
        ...mapState('datamesModule', ['datamesSed']),
        ...mapState('pagaduriasModule', ['pagaduriaType', 'setSelectedPeriod']),
        ...mapGetters('pagaduriasModule', ['ingresosExtras', 'salarioBasico', 'valorIngreso', 'couponsIngresos', 'pagaduriaPeriodos']),
        datamesSedArray() {
            return [this.datamesSed]
        },
        revisarAca() {
            return this.couponsIngresos.items
        }
    },
    created() {
    },
    mounted() {
    },
    watch: {
        couponsIngresos() {
            this.arrayCoupons = []
            this.arrayCoupons = [...this.couponsIngresos.items]
            const extractNumber = str => {
                const match = str.match(/\d+/)
                return match ? match[0] : null
            }
            this.arrayCoupons.forEach(coupon => {
                coupon.dias_laborados = extractNumber(coupon.dias_laborados)
            })
        }
    }
}
</script>

<style scoped>
th {
    font-size: 14px;
}
ul {
    margin: 0;
    padding: 0;
}
li {
    list-style-type: none;
    margin-bottom: 10px;
    padding: 0;
    border-bottom: none;
}
p {
    margin: 0;
    font-size: 14px;
}
b {
    font-size: 14px;
}
.table thead th {
    font-weight: 600;
    vertical-align: middle;
}
@media (min-width: 770px) {
    .col-48 {
        max-width: 48%;
    }
}
</style>
