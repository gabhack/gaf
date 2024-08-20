<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading d-flex justify-content-between">
                <b>DETALLE DE EMBARGOS</b>
                <div class="d-flex align-items-center">
                    <b class="mr-2">PERIODO:</b>
                    <select class="form-control" disabled :disabled="isLoading" @change="setSelectedPeriod($event.target.value)">
                        <option :value="period" v-for="period in embargosPeriodos" :key="period">
                            {{ period }}
                        </option>
                        <option v-if="isLoading" disabled>CARGANDO...</option>
                    </select>
                    <svg v-if="isLoading" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="41" height="41" style="shape-rendering: auto; display: block; background: transparent;" xmlns:xlink="http://www.w3.org/1999/xlink"><g><circle stroke-dasharray="169.64600329384882 58.548667764616276" r="36" stroke-width="10" stroke="#000000" fill="none" cy="50" cx="50">
                        <animateTransform keyTimes="0;1" values="0 50 50;360 50 50" dur="1s" repeatCount="indefinite" type="rotate" attributeName="transform"></animateTransform>
                        </circle><g></g></g>
                    </svg>
                </div>
            </div>
            <div class="panel-body">
                <template v-if="embargosPerPeriod.items.length > 0">
                    <div class="row">
                        <div class="col-1">
                            <b class="panel-label table-text"></b>
                        </div>
                        <div class="col-3 px-0">
                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">DOCUMENTO DEMANDANTE:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">DETALLE DEL EMBARGO:</b>
                        </div>
                    </div>

                    <div
                        v-for="(item, key) in embargosPerPeriod.items"
                        :key="key"
                        class="row panel-br-light-green pt-3"
                    >
                        <div class="col-1 pr-0">
                            <b class="panel-label table-text"></b>
                        </div>
                        <div class="col-3 px-0">
                            <p>{{ item.entidaddeman || item.ndem || '-' }}</p>
                        </div>

                        <div class="col-2 px-0">
                            <p>{{ item.docdeman || item.iddem || '-' }}</p>
                        </div>

                        <div class="col-2 px-0">
                            <p>{{ (item.temb || item.valor || 0) | formatCurrency }}</p>
                        </div>

                        <div class="col-2 px-0">
                            <p>{{ item.fembini || '-' }}</p>
                        </div>

                        <div class="col-2 px-0">
                            <p>{{ item.motemb || '-' }}</p>
                        </div>
                    </div>
                </template>
                <p v-else-if="embargos.length == 0">El cliente no cuenta con embargos registrados.</p>
                <p v-else>No se encontraron embargos para el periodo seleccionado.</p>
            </div>
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
            internalSelectedPeriod: null
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
        },
    },
    methods: {
        ...mapMutations('embargosModule', ['setSelectedPeriod']),
        formatPeriodDate(dateStr) {
            const date = new Date(dateStr);
            const year = date.getFullYear();
            const month = (`0${date.getMonth() + 1}`).slice(-2); // Mes en formato MM
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
