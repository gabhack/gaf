<template>
    <div class="col-md-12 px-0">
        <div class="panel panel-primary mb-3">
            <!-- Encabezado colapsable -->
            <h3
                class="heading-title w-100 d-flex align-items-center justify-content-start"
                :class="visible ? null : 'collapsed'"
                :aria-expanded="visible"
                aria-controls="info-laboral"
                @click="visible = !visible"
                style="cursor:pointer;gap:10px;"
            >
                <svg
                    :class="{ rotate180: visible }"
                    xmlns="http://www.w3.org/2000/svg"
                    width="15"
                    height="9"
                    viewBox="0 0 15 9"
                >
                    <path
                        fill="#3a5659"
                        d="M6.4 8.6a1.8 1.8 0 0 0 2.2 0l6-6c.4-.4.6-1.1.3-1.6A1.4 1.4 0 0 0 13.5 0h-12C.9 0 .3.4.1.9S0 2.1.4 2.6l6 6Z"
                    />
                </svg>
                Detalle del Cliente
            </h3>

            <b-row>
                <b-collapse id="info-laboral" v-model="visible" class="mt-2 w-100">
                    <b-col cols="12" class="px-3">
                        <b-table
                            :items="items"
                            :fields="fields"
                            class="mt-3"
                            responsive
                            striped
                            hover
                            thead-class="table-header-nowrap"
                        />
                        <b-row class="pt-2">
                            <b-col cols="12" class="d-flex justify-content-end">
                                <div class="d-flex flex-column align-items-center">
                                    <p>Cuota Deseada</p>
                                    <input
                                        type="number"
                                        class="form-control2"
                                        style="max-width:90px;border-color:#ced3da!important;"
                                        :value="cuotadeseada"
                                        @input="e => setCuotaDeseada(e.target.value)"
                                    />
                                </div>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-collapse>
            </b-row>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapGetters } from 'vuex';

export default {
    name: 'DetalleCliente',
    props: ['totales'],

    data() {
        return {
            fields: [
                { key: 'Obligaciones', label: 'Obligaciones' },
                { key: 'Cantidad_obligaciones', label: 'Cantidad de Obligaciones' },
                { key: 'Cupo_aproximado', label: 'Cupo Aproximado' },
                { key: 'Valor', label: 'Valor' }
            ],
            items: [
                {
                    Obligaciones: 'Obligaciones vigentes al día',
                    Cantidad_obligaciones: 0,
                    Cupo_aproximado: 'Libre Inversión',
                    Valor: '$0.00'
                },
                {
                    Obligaciones: 'Obligaciones vigentes en mora',
                    Cantidad_obligaciones: 0,
                    Cupo_aproximado: 'Compra Cartera',
                    Valor: '$0.00'
                },
                {
                    Obligaciones: 'Embargos',
                    Cantidad_obligaciones: 0,
                    Cupo_aproximado: 'Cuota Máxima',
                    Valor: '$0.00'
                }
            ],
            visible: true
        };
    },

    computed: {
        ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresosPlus']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos']),
        ...mapGetters('embargosModule', ['embargosPerPeriod']),
        ...mapGetters('descuentosModule', ['descuentosPerPeriod'])
    },

    watch: {
        couponsIngresos(v) {
            this.items[0].Cantidad_obligaciones = v?.total || 0;
        },
        descuentosPerPeriod(v) {
            this.items[1].Cantidad_obligaciones = v?.total || 0;
        },
        embargosPerPeriod(v) {
            this.items[2].Cantidad_obligaciones = v?.total || 0;
        },
        'totales.libreInversion': 'updateValores',
        'totales.compraCartera': 'updateValores',
        'totales.cuotaMaxima': 'updateValores',
        conteoEgresosPlus: 'updateValores'
    },

    mounted() {
        this.updateValores();
    },

    methods: {
        ...mapMutations('datamesModule', ['setCuotaDeseada']),

        updateValores() {
            const libreInversion = this.totales?.libreInversion ?? 0;
            const compraCartera = this.totales?.compraCartera ?? 0;

            let cuotaMaxima = libreInversion;
            cuotaMaxima += (this.conteoEgresosPlus || 0) + (this.totales?.libreInversionSuma || 0);

            console.log('[DETALLE CLIENTE] >> Libre Inversión:', libreInversion);
            console.log('[DETALLE CLIENTE] >> Compra Cartera:', compraCartera);
            console.log('[DETALLE CLIENTE] >> Cuota Máxima:', cuotaMaxima);

            /*  MANTENEMOS EL ORDEN ORIGINAL  */
            this.items[0].Valor = this.formatCurrency(libreInversion); // Libre Inversión
            this.items[1].Valor = this.formatCurrency(compraCartera);  // Compra Cartera
            this.items[2].Valor = this.formatCurrency(cuotaMaxima);    // Cuota Máxima
        },

        formatCurrency(v) {
            return new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
                .format(v)
                .replace('COP', '')
                .trim();
        }
    }
};
</script>

<style lang="scss" scoped>
::v-deep .table {
    thead {
        background:#3a5659;
        color:#fff;
        font-size:14px;
        font-weight:700;
        & tr th{
            padding:12px 40px;
            text-align:center;
            white-space:nowrap;
        }
    }
    tbody{
        background:#fff;
        font-size:14px;
        & td{ text-align:center; }
    }
}
p{ font-size:14px;margin-bottom:14px; }
.rotate180{ transform:rotate(180deg); }
</style>
