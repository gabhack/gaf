<template>
    <div class="col-md-12">
        <div class="panel panel-primary">
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
                        <!-- SVG -->
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
                        Detalle de Embargos
                    </h3>
                </b-col>
                <b-col cols="2" class="d-none d-md-flex justify-content-end align-items-start">
                    <div>
                        <b class="mr-2 periodo">Período</b>
                        <select
                            class="form-control2"
                            :disabled="isLoading"
                            @change="setSelectedPeriod($event.target.value)"
                        >
                            <option :value="period" v-for="period in embargosPeriodos" :key="period">
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
                <b-collapse id="info-laboral" v-model="visible" class="mt-2 w-100">
                    <b-col cols="12">
                        <template v-if="embargosPerPeriod.items.length > 0">
                            <b-table
                                :items="embargosPerPeriod.items"
                                :fields="fields"
                                responsive
                                striped
                                hover
                                class="pt-2"
                            >
                                <template #cell(entidaddeman)="data">
                                    <p>{{ data.item.entidaddeman || data.item.ndem || '--' }}</p>
                                </template>
                                <template #cell(docdeman)="data">
                                    <p>{{ data.item.docdeman || data.item.ndem || '--' }}</p>
                                </template>
                                <template #cell(temb)="data">
                                    <p>{{ data.item.temb || data.item.ndem || '--' | currency }}</p>
                                </template>
                                <template #cell(fembini)="data">
                                    <p>{{ data.item.fembini || '--' }}</p>
                                </template>

                                <template #cell(tipoembargo)="data">
                                    <p class="mb-0">{{ data.item.tipoembargo || '-' }}</p>
                                </template>

                                <template #cell(motemb)="data">
                                    <p class="mb-0">{{ data.item.motemb }}</p>
                                </template>
                            </b-table>
                        </template>
                        <p v-else-if="embargos.length == 0">El cliente no cuenta con embargos registrados.</p>
                        <p v-else>No se encontraron embargos para el periodo seleccionado.</p>
                    </b-col>
                    <b-col cols="12" md="2" class="d-flex d-md-none justify-content-end align-items-start">
                        <div>
                            <b class="mr-2 periodo">Período</b>
                            <select
                                class="form-control2"
                                :disabled="isLoading"
                                @change="setSelectedPeriod($event.target.value)"
                            >
                                <option :value="period" v-for="period in embargosPeriodos" :key="period">
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
                </b-collapse>
            </b-row>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapMutations } from 'vuex';

export default {
    name: 'Embargos',
    props: {
        selectedPeriod: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            fields: [
                { key: 'entidaddeman', label: 'Nombre entidad actual:' },
                { key: 'docdeman', label: 'Documento demandante' },
                { key: 'temb', label: 'Cuota deuda' },
                { key: 'fembini', label: 'Fecha inicio deuda' },
                { key: 'tipoembargo', label: 'Tipo embargo' },
                { key: 'motemb', label: 'Motivo embargo' }
            ],
            internalSelectedPeriod: null,
            visible: true
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
        ...mapState('embargosModule', ['embargos']),
        ...mapGetters('embargosModule', ['embargosPeriodos', 'embargosPerPeriod']),
        isLoading() {
            return !this.embargosPeriodos || this.embargosPeriodos.length === 0;
        }
    },
    methods: {
        ...mapMutations('embargosModule', ['setSelectedPeriod']),
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
            this.embargosPeriodos.forEach(period => {
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
