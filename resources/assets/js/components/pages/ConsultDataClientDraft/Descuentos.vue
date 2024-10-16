<template>
    <div class="col-md-12">
        <div class="panel panel-primary">
            <b-row>
                <b-col cols="12" md="10">
                    <h3 class="heading-title mb-0 pb-3">Obligaciones vigentes en mora</h3>
                </b-col>
                <b-col cols="2" class="d-none d-md-flex justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Periodo</b>
                        <select class="form-control2" v-model="internalSelectedPeriod" @change="onPeriodChange">
                            <option :value="period" v-for="period in descuentosPeriodos" :key="period">
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
                    <template v-if="descuentosPerPeriod.items.length > 0">
                        <b-table :items="descuentosPerPeriod.items" :fields="fields" responsive striped hover class="pt-2">
                            <template #cell(check)="data">
                                <input v-model="data.item.check" type="checkbox" />
                            </template>

                            <template #cell(mliquid)="data">
                                <p class="mb-0">{{ data.item.mliquid || '-' }}</p>
                            </template>

                            <template #cell(valor)="data">
                                <p class="mb-0">{{ (data.item.valor || '-') | currency }}</p>
                            </template>
                        </b-table>
                    </template>
                    <p v-else-if="descuentos.length == 0">El cliente no cuenta con obligaciones vigentes en mora.</p>
                    <p v-else-if="descuentos[0].mliquid == 'ALERTA'">Solo existen <b>ALERTAS</b> para este periodo.</p>
                    <p v-else>No se encontraron liquidaciones para el periodo seleccionado.</p>
                </b-col>
                <b-col cols="12" class="d-flex d-md-none justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Periodo</b>
                        <select class="form-control2" v-model="internalSelectedPeriod" @change="onPeriodChange">
                            <option :value="period" v-for="period in descuentosPeriodos" :key="period">
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
    name: 'Descuentos',
    props: {
        selectedPeriod: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            internalSelectedPeriod: null,
            fields: [
                { key: 'check', label: '' },
                { key: 'mliquid', label: 'Nombre entidad' },
                { key: 'valor', label: 'Valor cuota en mora' }
            ],
        };
    },
    watch: {
        selectedPeriod: {
            immediate: true,
            handler(newVal) {
                this.setSelectedPeriodByDate(newVal);
            }
        }
    },
    computed: {
        ...mapState('descuentosModule', ['descuentos']),
        ...mapGetters('descuentosModule', ['descuentosPeriodos', 'descuentosPerPeriod']),
        isLoading() {
            return !this.descuentosPeriodos || this.descuentosPeriodos.length === 0;
        }
    },
    methods: {
        ...mapMutations('descuentosModule', ['setSelectedPeriod']),
        formatPeriodDate(dateStr) {
            const date = new Date(dateStr);
            const year = date.getFullYear();
            const month = `0${date.getMonth() + 1}`.slice(-2); // Mes en formato MM
            return `${year}-${month}`;
        },
        setSelectedPeriodByDate(dateStr) {
            const formattedDate = this.formatPeriodDate(dateStr);
            console.log(`Periodo seleccionado: ${dateStr}`);
            console.log(`Selected period to match: ${formattedDate}`);

            let matchedPeriod = null;
            this.descuentosPeriodos.forEach(period => {
                const formattedPeriod = this.formatPeriodDate(period);
                console.log(`Comparing with: ${formattedPeriod}`);

                if (formattedPeriod === formattedDate) {
                    matchedPeriod = period;
                }
            });

            if (matchedPeriod) {
                let [year, month, day] = matchedPeriod.split('-');
                month = parseInt(month) - 1;

                if (month === 0) {
                    month = 12;
                    year = parseInt(year) - 1;
                }

                const previousMonthPeriod = `${year}-${month.toString().padStart(2, '0')}-${day}`;
                console.log(`Final selected period: ${previousMonthPeriod}`);
                this.internalSelectedPeriod = previousMonthPeriod;
                this.setSelectedPeriod(previousMonthPeriod); // Actualiza el periodo seleccionado en Vuex
            } else {
                console.log('No matching period found');
            }
        },
        onPeriodChange() {
            this.setSelectedPeriod(this.internalSelectedPeriod);
        }
    },
    filters: {
        formatCurrency(value) {
            if (isNaN(value)) return value;

            const parts = value.toString().split('.');

            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return '$' + parts.join(',');
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