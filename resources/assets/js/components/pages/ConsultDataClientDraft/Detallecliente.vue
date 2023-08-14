<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>DETALLE DEL CLIENTE</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <label class="label-consulta col-12 mb-2" for="pad"
                            ><b>OBLIGACIONES:</b>
                            <input
                                class="form-control text-left"
                                type="text"
                                name="proc_en_contra"
                                id="proc_en_contra"
                                placeholder="Obligaciones vigentes al dia"
                                value="OBLIGACIONES VIGENTES AL DIA"
                                min="1"
                                max="99"
                                disabled
                            />
                            <input
                                class="form-control text-left"
                                type="text"
                                name="proc_en_contra"
                                id="proc_en_contra"
                                placeholder="Obligaciones vigentes en mora"
                                value="OBLIGACIONES VIGENTES EN MORA"
                                min="1"
                                max="99"
                                disabled
                            />
                            <input
                                class="form-control text-left"
                                type="text"
                                name="proc_en_contra"
                                id="proc_en_contra"
                                placeholder="EMBARGOS"
                                value="EMBARGOS"
                                min="1"
                                max="99"
                                disabled
                            />
                        </label>
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="label-consulta col-12 mb-2" for="pad"
                            ><b>CANTIDAD OBLIGACIONES:</b>
                            <input
                                class="form-control"
                                type="number"
                                name="proc_en_contra"
                                id="proc_en_contra"
                                placeholder="Opcional"
                                :value="couponsIngresos.total"
                                min="1"
                                max="99"
                                disabled
                            />
                            <input
                                class="form-control"
                                type="number"
                                name="proc_en_contra"
                                id="proc_en_contra"
                                placeholder="Opcional"
                                :value="totales.descuentos"
                                min="1"
                                max="99"
                                disabled
                            />
                            <input
                                class="form-control"
                                type="number"
                                name="proc_en_contra"
                                id="proc_en_contra"
                                placeholder="Opcional"
                                :value="totales.embargos"
                                min="1"
                                max="99"
                                disabled
                            />
                        </label>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="row">
                            <div class="col-6">
                                <label class="label-consulta" for="pad"
                                    ><b>CUPO APROXIMADO:</b>
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="proc_en_contra"
                                        id="proc_en_contra"
                                        placeholder="Opcional"
                                        value="LIBRE INVERSION"
                                        min="1"
                                        max="99"
                                        disabled
                                    />
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="proc_en_contra"
                                        id="proc_en_contra"
                                        placeholder="Opcional"
                                        value="COMPRA CARTERA"
                                        min="1"
                                        max="99"
                                        disabled
                                    />
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="proc_en_contra"
                                        id="proc_en_contra"
                                        placeholder="Opcional"
                                        value="CUOTA MÁXIMA"
                                        min="1"
                                        max="99"
                                        disabled
                                    />
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="proc_en_contra"
                                        id="proc_en_contra"
                                        placeholder="Opcional"
                                        value="CUOTA DESEADA"
                                        min="1"
                                        max="99"
                                        disabled
                                    />
                                </label>
                            </div>
                            <div class="col-6">
                                <b>VALOR</b>
                                <p class="panel-value">
                                    {{ totales.libreInversion | currency }}
                                </p>
                                <p class="panel-value">
                                    {{ totales.compraCartera | currency }}
                                </p>
                                <p class="panel-value">
                                    {{
                                        (conteoEgresosPlus
                                            ? conteoEgresosPlus + totales.libreInversionSuma
                                            : totales.libreInversionSuma) | currency
                                    }}
                                </p>
                                <input
                                    type="number"
                                    class="form-control"
                                    :value="cuotadeseada"
                                    @input="event => setCuotaDeseada(event.target.value)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapGetters } from 'vuex';

export default {
    name: 'DetalleCliente',
    props: ['totales'],
    computed: {
        ...mapState('datamesModule', ['cuotadeseada', 'conteoEgresos', 'conteoEgresosPlus']),
        ...mapGetters('pagaduriasModule', ['couponsIngresos'])
    },
    methods: {
        ...mapMutations('datamesModule', ['setCuotaDeseada'])
    }
};
</script>
