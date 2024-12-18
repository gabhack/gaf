<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <h3
                class="heading-title w-100 d-flex align-items-center justify-content-start"
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

                Detalle del cliente
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
                </b-collapse>
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
            ],
            visible: true
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
    mounted() {
        this.updateValores();
    },
    methods: {
        ...mapMutations('datamesModule', ['setCuotaDeseada']),
        updateValores() {
            const libreInversion = this.totales?.libreInversion || 0;
            const compraCartera = this.totales?.compraCartera || 0;

            // Cuota Máxima = suma de valores que afectan "libre inversión"
            let cuotaMaxima = libreInversion;

            if (this.conteoEgresosPlus) {
                cuotaMaxima += this.conteoEgresosPlus + this.totales.libreInversionSuma;
            } else {
                cuotaMaxima += this.totales.libreInversionSuma;
            }

            this.items[0].Valor = this.formatCurrency(libreInversion); // Libre Inversión
            this.items[1].Valor = this.formatCurrency(compraCartera); // Compra Cartera
            this.items[2].Valor = this.formatCurrency(cuotaMaxima); // Cuota Máxima
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
