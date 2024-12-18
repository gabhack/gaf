<template>
    <div class="col-md-12">
        <div class="panel panel-primary pt-5">
            <b-row>
                <b-col cols="12" md="10">
                    <h3 class="heading-title mb-0 pb-3">Obligaciones vigentes al día</h3>
                </b-col>
                <b-col cols="2" class="d-none d-md-flex justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Período:</b>
                        <select
                            class="form-control2"
                            v-model="internalSelectedPeriod"
                            @change="onPeriodChange"
                        >
                            <option v-for="period in pagaduriaPeriodos" :key="period" :value="period">
                                {{ period }}
                            </option>
                            <option v-if="isLoading" disabled>CARGANDO...</option>
                        </select>
                        <svg v-if="isLoading" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                             preserveAspectRatio="xMidYMid" width="41" height="41" style="display:block;background:transparent">
                            <g>
                                <circle stroke-dasharray="169.64600329384882 58.548667764616276" r="36" stroke-width="10" stroke="#000000" fill="none" cy="50" cx="50">
                                    <animateTransform keyTimes="0;1" values="0 50 50;360 50 50" dur="1s" repeatCount="indefinite" type="rotate" attributeName="transform"></animateTransform>
                                </circle>
                            </g>
                        </svg>
                    </div>
                </b-col>
                <b-col cols="12">
                    <template v-if="arrayCoupons && arrayCoupons.length > 0">
                        <b-table :items="arrayCoupons" :fields="fields" responsive striped hover class="pt-2">
                            <template #cell(check)="data">
                                <input
                                    v-model="data.item.check"
                                    type="checkbox"
                                    :disabled="disabledProspect || ['APFPM', 'APEPEN', 'APESDN'].includes(data.item.code)"
                                    @change="handleCheckboxChange"
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
                                <b>{{ (couponsIngresos && couponsIngresos.amount ? couponsIngresos.amount : 0) | currency }}</b>
                            </b-col>
                        </b-row>
                    </template>
                    <p v-else-if="coupons && coupons.length === 0">El cliente no cuenta con obligaciones vigentes al día.</p>
                    <p v-else>No se encontraron obligaciones vigentes al día para el periodo seleccionado.</p>
                </b-col>
                <b-col cols="12" class="d-flex d-md-none justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Período:</b>
                        <select
                            class="form-control2"
                            v-model="internalSelectedPeriod"
                            @change="onPeriodChange"
                        >
                            <option v-for="period in pagaduriaPeriodos" :key="period" :value="period">
                                {{ period }}
                            </option>
                            <option v-if="isLoading" disabled>CARGANDO...</option>
                        </select>
                        <svg v-if="isLoading" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                             preserveAspectRatio="xMidYMid" width="41" height="41" style="display:block;background:transparent">
                            <g>
                                <circle stroke-dasharray="169.64600329384882 58.548667764616276" r="36" stroke-width="10" stroke="#000000" fill="none" cy="50" cx="50">
                                    <animateTransform keyTimes="0;1" values="0 50 50;360 50 50" dur="1s" repeatCount="indefinite" type="rotate" attributeName="transform"></animateTransform>
                                </circle>
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
            internalSelectedPeriod: ''
        };
    },
    mounted() {
        this.internalSelectedPeriod = this.selectedPeriodFromStore;
        try {
            this.updateArrayCoupons();
        } catch (error) {
            console.error("Error en mounted:", error);
        }
    },
    watch: {
        selectedPeriodFromStore(newVal) {
            try {
                this.internalSelectedPeriod = newVal;
                this.updateArrayCoupons();
            } catch (error) {
                console.error("Error en watcher de selectedPeriodFromStore:", error);
            }
        },
        couponsIngresos() {
            try {
                this.updateArrayCoupons();
            } catch (error) {
                console.error("Error en watcher de couponsIngresos:", error);
            }
        }
    },
    computed: {
        ...mapState('datamesModule', ['cuotadeseada']),
        ...mapState('pagaduriasModule', ['coupons', 'selectedPeriod']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos', 'pagaduriaPeriodos']),
        selectedPeriodFromStore() {
            return this.selectedPeriod;
        },
        isLoading() {
            return this.pagaduriaPeriodos.length < 2;
        }
    },
    methods: {
        ...mapMutations('datamesModule', ['setConteoEgresos', 'setConteoEgresosPlus']),
        ...mapMutations('pagaduriasModule', ['setSelectedPeriod']),

        updateArrayCoupons() {
            try {
                const items = this.couponsIngresos || [];
                if (Array.isArray(items) && items.length > 0) {
                    this.arrayCoupons = items.map(item => ({ ...item, check: false }));
                } else {
                    this.arrayCoupons = [];
                }
                this.setConteoEgresos(0);
                this.setConteoEgresosPlus(0);
            } catch (error) {
                console.error("Error en updateArrayCoupons:", error);
                this.arrayCoupons = [];
            }
        },

        handleCheckboxChange() {
            try {
                this.calcularEgresos();
            } catch (error) {
                console.error("Error en handleCheckboxChange:", error);
            }
        },

        calcularEgresos() {
            try {
                if (!Array.isArray(this.arrayCoupons)) {
                    return;
                }
                const { totalEgresos, totalEgresosPlus } = this.arrayCoupons.reduce((acc, item) => {
                    if (!item.check && item.code !== 'APFPM') {
                        acc.totalEgresos += Number(item.vaplicado);
                    } else if (item.check && item.code !== 'APFPM') {
                        acc.totalEgresosPlus += Number(item.vaplicado);
                    }
                    return acc;
                }, { totalEgresos: 0, totalEgresosPlus: 0 });

                this.setConteoEgresos(totalEgresos);
                this.setConteoEgresosPlus(totalEgresosPlus);
            } catch (error) {
                console.error("Error en calcularEgresos:", error);
            }
        },

        onPeriodChange() {
            try {
                this.setSelectedPeriod(this.internalSelectedPeriod);
            } catch (error) {
                console.error("Error en onPeriodChange:", error);
            }
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
    }
    & tbody {
        background-color: #fff;
        font-size: 14px;
        font-weight: 400;
        line-height: 18.23px;
    }
}
</style>
