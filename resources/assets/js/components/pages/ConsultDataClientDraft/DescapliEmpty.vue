<template>
    <div class="col-md-12">
        <div class="panel panel-primary pt-5">
            <b-row>
                <b-col cols="12" md="10">
                    <h3
                        class="heading-title mb-0 pb-3 w-100 d-flex align-items-center justify-content-start"
                        :class="visible ? null : 'collapsed'"
                        :aria-expanded="visible ? 'true' : 'false'"
                        aria-controls="info-laboral"
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
                        Obligaciones Vigentes al Día
                    </h3>
                </b-col>
                <b-col cols="2" class="d-none d-md-flex justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Período:</b>
                        <select class="form-control2" v-model="internalSelectedPeriod" @change="onPeriodChange">
                            <option :value="period" v-for="period in pagaduriaPeriodos" :key="period">
                                {{ period }}
                            </option>
                            <option v-if="isLoading" disabled>CARGANDO...</option>
                        </select>
                        <svg
                            v-if="isLoading"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 100 100"
                            preserveAspectRatio="xMidYMid"
                            width="41"
                            height="41"
                            style="shape-rendering: auto; display: block; background: transparent"
                        >
                            <g>
                                <circle
                                    stroke-dasharray="169.64600329384882 58.548667764616276"
                                    r="36"
                                    stroke-width="10"
                                    stroke="#000000"
                                    fill="none"
                                    cy="50"
                                    cx="50"
                                >
                                    <animateTransform
                                        keyTimes="0;1"
                                        values="0 50 50;360 50 50"
                                        dur="1s"
                                        repeatCount="indefinite"
                                        type="rotate"
                                        attributeName="transform"
                                    ></animateTransform>
                                </circle>
                            </g>
                        </svg>
                    </div>
                </b-col>
                <b-collapse id="info-laboral" v-model="visible" class="mt-2 w-100">
                    <b-col cols="12">
                        <template v-if="arrayCoupons.length > 0">
                            <b-table :items="arrayCoupons" :fields="fields" responsive striped hover class="pt-2">
                                <template #cell(check)="data">
                                    <input
                                        v-model="data.item.check"
                                        type="checkbox"
                                        :disabled="disabledProspect || ['APFPM','APEPEN','APESDN'].includes(data.item.code)"
                                        @change="calcularEgresos"
                                    />
                                </template>
                                <template #cell(nomtercero)="data">
                                    <p class="mb-0">{{ data.item.nomtercero || '-' }}</p>
                                </template>
                                <template #cell(vaplicado)="data">
                                    <p class="mb-0">{{ Math.abs(data.item.vaplicado) | currency }}</p>
                                </template>
                            </b-table>
                            <b-row>
                                <b-col cols="12" class="d-flex justify-content-center align-items-center">
                                    <b class="mr-2">TOTAL:</b>
                                    <b>{{ couponsIngresos.amount | currency }}</b>
                                </b-col>
                            </b-row>
                        </template>
                        <p v-else-if="coupons.length == 0">El cliente no cuenta con obligaciones vigentes al día.</p>
                        <p v-else>No se encontraron obligaciones vigentes al día para el periodo seleccionado.</p>
                    </b-col>
                    <b-col cols="12" class="d-flex d-md-none justify-content-end align-items-start">
                        <div>
                            <b class="mr-2 periodo">Período:</b>
                            <select class="form-control2" v-model="internalSelectedPeriod" @change="onPeriodChange">
                                <option :value="period" v-for="period in pagaduriaPeriodos" :key="period">
                                    {{ period }}
                                </option>
                                <option v-if="isLoading" disabled>CARGANDO...</option>
                            </select>
                            <svg
                                v-if="isLoading"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 100 100"
                                preserveAspectRatio="xMidYMid"
                                width="41"
                                height="41"
                                style="shape-rendering: auto; display: block; background: transparent"
                            >
                                <g>
                                    <circle
                                        stroke-dasharray="169.64600329384882 58.548667764616276"
                                        r="36"
                                        stroke-width="10"
                                        stroke="#000000"
                                        fill="none"
                                        cy="50"
                                        cx="50"
                                    >
                                        <animateTransform
                                            keyTimes="0;1"
                                            values="0 50 50;360 50 50"
                                            dur="1s"
                                            repeatCount="indefinite"
                                            type="rotate"
                                            attributeName="transform"
                                        ></animateTransform>
                                    </circle>
                                </g>
                            </svg>
                        </div>
                    </b-col>
                </b-collapse>
            </b-row>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapMutations } from 'vuex'

export default {
    name: 'DescapliEmpty',
    props: {
        disabledProspect: {
            type: Boolean,
            default: false
        },
        selectedPeriod: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            fields: [
                { key: 'check', label: '' },
                { key: 'nomtercero', label: 'Nombre entidad' },
                { key: 'vaplicado', label: 'Cuota' }
            ],
            arrayCoupons: [],
            internalSelectedPeriod: null,
            visible: true
        }
    },
    mounted() {
        this.internalSelectedPeriod = this.selectedPeriod
        this.updateArrayCoupons()
    },
    watch: {
        selectedPeriod(newVal) {
            this.internalSelectedPeriod = newVal
            this.updateArrayCoupons()
        },
        couponsIngresos() {
            this.updateArrayCoupons()
        }
    },
    computed: {
        ...mapState('datamesModule', ['cuotadeseada']),
        ...mapState('pagaduriasModule', ['coupons']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos', 'pagaduriaPeriodos']),
        isLoading() {
            return this.pagaduriaPeriodos.length < 2
        }
    },
    methods: {
        ...mapMutations('datamesModule', ['setConteoEgresos', 'setConteoEgresosPlus']),
        ...mapMutations('pagaduriasModule', ['setSelectedPeriod']),
        updateArrayCoupons() {
            this.arrayCoupons = (this.couponsIngresos && this.couponsIngresos.items)
                ? this.couponsIngresos.items.map(item => ({ ...item, check: false }))
                : []
            this.setConteoEgresos(0)
            this.setConteoEgresosPlus(0)
        },
        calcularEgresos() {
            const { totalEgresos, totalEgresosPlus } = this.arrayCoupons.reduce(
                (acc, item) => {
                    if (!item.check && item.code !== 'APFPM') {
                        acc.totalEgresos += Number(item.vaplicado)
                    } else if (item.check && item.code !== 'APFPM') {
                        acc.totalEgresosPlus += Number(item.vaplicado)
                    }
                    return acc
                },
                { totalEgresos: 0, totalEgresosPlus: 0 }
            )
            this.setConteoEgresos(totalEgresos)
            this.setConteoEgresosPlus(totalEgresosPlus)
        },
        onPeriodChange() {
            this.setSelectedPeriod(this.internalSelectedPeriod)
        }
    }
}
</script>

<style scoped lang="scss">
::v-deep .table {
    & thead {
        background-color: #3a5659;
        white-space: nowrap;
        color: white;
        font-size: 14px;
        font-weight: 700;
        line-height: 18.23px;
    }
    & tbody {
        background-color: #fff;
        font-size: 14px;
        font-weight: 400;
        line-height: 18.23px;
    }
}
</style>
