<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading d-flex justify-content-between">
                <b>OBLIGACIONES VIGENTES AL DIA</b>
                <div class="d-flex align-items-center">
                    <b class="mr-2">PERIODO:</b>
                    <select 
                        class="form-control" 
                        @change="setSelectedPeriod($event.target.value)" 
                        :value="selectedPeriod">
                        <option :value="period" v-for="period in pagaduriaPeriodos" :key="period">
                            {{ period }}
                        </option>
                        <option v-if="isLoading" disabled>CARGANDO...</option>
                    </select>
                </div>
            </div>
            <div class="panel-body">
                <template v-if="arrayCoupons.length > 0">
                    <div class="row">
                        <div class="col-1">
                            <b class="panel-label table-text"></b>
                        </div>
                        <div class="col-4 px-0">
                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                        </div>
                        <div class="col-3 px-0">
                            <b class="panel-label table-text">CUOTA:</b>
                        </div>
                    </div>

                    <div v-for="(item, key) in arrayCoupons" :key="key" class="row panel-br-light-green pt-3">
                        <div class="col-1 pr-0">
                            <input
                                v-model="item.check"
                                type="checkbox"
                                :disabled="
                                    disabledProspect ||
                                    item.code == 'APFPM' ||
                                    item.code == 'APEPEN' ||
                                    item.code == 'APESDN'
                                "
                                @change="calcularEgresos"
                            />
                        </div>

                        <div class="col-4 px-0">
                            <p>{{ item.nomtercero ? item.nomtercero : '-' }}</p>
                        </div>

                        <div class="col-3 px-0">
                            <p>{{ item.vaplicado | currency }}</p>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="offset-2 col-3 text-right">
                            <b>TOTAL:</b>
                        </div>
                        <div class="col-2">
                            <b>{{ couponsIngresos.amount | currency }}</b>
                        </div>
                    </div>
                </template>
                <p v-else-if="coupons.length == 0">El cliente no cuenta con obligaciones vigentes al día.</p>
                <p v-else>No se encontraron obligaciones vigentes al día para el periodo seleccionado.</p>
            </div>
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
            labels: [
                { label: 'TIPO ENTIDAD', field: 'clase' },
                { label: 'NOMBRE ENTIDAD', field: 'nomtercero', colClass: 'col-3' },
                { label: 'CUOTA', field: 'vaplicado', currency: true },
                { label: 'FECHA INICIO DEUDA', field: 'fgrab' },
                { label: 'NOMBRE ENTIDAD CEDIENTE', field: 'nonentant' }
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
    },
    computed: {
        ...mapState('datamesModule', ['cuotadeseada']),
        ...mapState('pagaduriasModule', ['coupons']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos', 'pagaduriaPeriodos']),
        arrayCoupons() {
        console.log('Cupones recalculados en DescapliEmpty:', this.couponsIngresos.items);
        return [...this.couponsIngresos.items];
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
