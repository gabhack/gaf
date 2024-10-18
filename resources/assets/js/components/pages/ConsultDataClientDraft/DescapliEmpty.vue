<template>
    <div class="col-md-12">
        <div class="panel panel-primary pt-5">
            <b-row>
                <b-col cols="12" md="10">
                    <h3 class="heading-title mb-0 pb-3">Obligaciones vigentes al día</h3>
                </b-col>
                <b-col cols="2" class="d-none d-md-flex justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Periodo:</b>
                        <select
                            class="form-control2"
                            @change="setSelectedPeriod($event.target.value)"
                            :value="selectedPeriod"
                        >
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
                            xmlns:xlink="http://www.w3.org/1999/xlink"
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
                                <g></g>
                            </g>
                        </svg>
                    </div>
                </b-col>
                <b-col cols="12">
                    <template v-if="arrayCoupons.length > 0">
                        <b-table :items="arrayCoupons" :fields="fields" responsive striped hover class="pt-2">
                            <template #cell(check)="data">
                                <input
                                    v-model="data.item.check"
                                    type="checkbox"
                                    :disabled="
                                        disabledProspect ||
                                        data.item.code === 'APFPM' ||
                                        data.item.code === 'APEPEN' ||
                                        data.item.code === 'APESDN'
                                    "
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
                        <b class="mr-2 periodo">Periodo:</b>
                        <select
                            class="form-control2"
                            @change="setSelectedPeriod($event.target.value)"
                            :value="selectedPeriod"
                        >
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
                            xmlns:xlink="http://www.w3.org/1999/xlink"
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
                                <g></g>
                            </g>
                        </svg>
                    </div>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapMutations } from 'vuex';
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
            itemsCheckbox: [],
            arrayCoupons: []
        };
    },
    mounted() {
        console.log('Cupones recibidos en DescapliEmpty:', this.arrayCoupons);

        this.arrayCoupons = [...this.couponsIngresos.items];
        this.arrayCoupons.map(item => {
            item.check = false;
            return item;
        });
        console.log('REVISAR ACA' + this.couponsIngresos.items);
    },
    computed: {
        ...mapState('datamesModule', ['cuotadeseada']),
        ...mapState('pagaduriasModule', ['coupons']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos', 'pagaduriaPeriodos']),
        isLoading() {
            console.log(this.pagaduriaPeriodos);
            if (this.pagaduriaPeriodos.length < 2) {
                return true;
            } else {
                return false;
            }
        }
    },
    methods: {
        ...mapMutations('datamesModule', ['setConteoEgresos', 'setConteoEgresosPlus']),
        ...mapMutations('pagaduriasModule', ['setSelectedPeriod']),
        calcularEgresos() {
            let totalEgresos = 0;
            let totalEgresosPlus = 0;
            this.couponsIngresos.items.forEach(item => {
                if (!item.check && item.code !== 'APFPM') {
                    totalEgresos += Number(item.vaplicado);
                }
                if (item.check && item.code !== 'APFPM') {
                    totalEgresosPlus += Number(item.vaplicado);
                }
            });
            this.setConteoEgresos(totalEgresos);
            this.setConteoEgresosPlus(totalEgresosPlus);
        }
    },
    watch: {
        couponsIngresos() {
            this.arrayCoupons = [];
            this.arrayCoupons = [...this.couponsIngresos.items];
            this.arrayCoupons.map(item => {
                item.check = false;
                return item;
            });
            this.setConteoEgresos(0);
            this.setConteoEgresosPlus(0);
        }
    }
};
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
        & tr th {
            min-height: 50px !important;
        }
    }
    & tbody {
        background-color: #fff;
        font-size: 14px;
        font-weight: 400;
        line-height: 18.23px;
    }
}
</style>
