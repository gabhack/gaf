<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <h3 class="heading-title">Detalle del cliente</h3>
            <b-row>
                <b-col cols="12" class="px-3">
                    <b-table
                        :items="items"
                        :fields="fields"
                        class="mt-3"
                        responsive
                        striped
                        hover
                        thead-class="table-header-nowrap"
                    ></b-table>
                    <b-row class="pt-2">
                        <b-col cols="12" class="d-flex justify-content-end align-items-center">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <p>Cuota deseada</p>
                                <input
                                    type="number"
                                    class="form-control2"
                                    style="max-width: 90px; border-color: #ced3da !important"
                                    :value="cuotadeseada"
                                    @input="event => setCuotaDeseada(event.target.value)"
                                />
                            </div>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapGetters } from 'vuex';

export default {
    name: 'DetalleCliente',
    data() {
        return {
            fields: [
                { key: 'Obligaciones', label: 'Obligaciones' },
                { key: 'Cantidad_obligaciones', label: 'Cantidad de obligaciones' },
                { key: 'Cupo_aproximado', label: 'Cupo aproximado' },
                { key: 'Valor', label: 'Valor' }
            ],
            items: [
                {
                    isActive: true,
                    Obligaciones: 'Obligaciones vigentes al día',
                    Cantidad_obligaciones: 0,
                    Cupo_aproximado: 'Libre inversión',
                    Valor: '$0'
                },
                {
                    isActive: false,
                    Obligaciones: 'Obligaciones vigentes en mora',
                    Cantidad_obligaciones: 0,
                    Cupo_aproximado: 'Cuota Máxima',
                    Valor: '$0'
                },
                {
                    isActive: false,
                    Obligaciones: 'Embargos',
                    Cantidad_obligaciones: 0,
                    Cupo_aproximado: 'Compra Cartera',
                    Valor: '$0'
                }
            ]
        };
    },
    props: ['totales'],
    computed: {
        ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresos', 'conteoEgresosPlus']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos']),
        ...mapGetters('embargosModule', ['embargosPerPeriod']),
        ...mapGetters('descuentosModule', ['descuentosPerPeriod'])
    },
    watch: {
        couponsIngresos(newVal) {
            this.items[0].Cantidad_obligaciones = newVal?.total || 0;
        },
        descuentosPerPeriod(newVal) {
            this.items[1].Cantidad_obligaciones = newVal?.total || 0;
        },
        embargosPerPeriod(newVal) {
            this.items[2].Cantidad_obligaciones = newVal?.total || 0;
        },
        'totales.libreInversion'() {
            this.updateValores();
        },
        'totales.compraCartera'() {
            this.updateValores();
        },
        conteoEgresosPlus() {
            this.updateValores();
        }
    },
    methods: {
        ...mapMutations('datamesModule', ['setCuotaDeseada']),
        updateValores() {
            const libreInversion = this.totales?.libreInversion || 0;
            const compraCartera = this.totales?.compraCartera || 0;
            let egresosplus = 0;

            if (this.conteoEgresosPlus) {
                egresosplus = this.conteoEgresosPlus + this.totales.libreInversionSuma;
            } else {
                egresosplus = this.totales.libreInversionSuma;
            }

            this.items[0].Valor = this.formatCurrency(libreInversion);
            this.items[1].Valor = this.formatCurrency(libreInversion); // Cuota Máxima usa el valor inicial de Libre Inversión
            this.items[2].Valor = this.formatCurrency(egresosplus);
        },
        formatCurrency(value) {
            return `$${new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            })
                .format(value)
                .replace('COP', '')
                .trim()}`;
        }
    },
    mounted() {
        // Inicializar Cuota Máxima con el valor de Libre Inversión al cargar el componente
        this.updateValores();
    }
};
</script>

<style lang="scss" scoped>
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
p {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
    margin-bottom: 14px;
}
</style>
