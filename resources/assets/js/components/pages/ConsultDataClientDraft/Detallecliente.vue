<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>DETALLE DEL CLIENTE</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4 text-center">
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
                    <div class="col-lg-4 text-center">
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
                                :value="descuentosPerPeriod.total"
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
                                :value="embargosPerPeriod.total"
                                min="1"
                                max="99"
                                disabled
                            />
                        </label>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="row d-flex align-items-center">
                            <div class="col-6">
                                <b>CUPO APROXIMADO:</b>
                            </div>
                            <div class="col-6">
                                <b>VALOR</b>
                            </div>
                            <div class="col-6">
                                <label class="label-consulta mb-0" for="pad"
                                    >
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
                                </label>
                            </div>
                            <div class="col-6">
                                <p class="panel-value mb-0">
                                    {{ totales.libreInversion | currency }}
                                </p>
                            </div>
                            <div class="col-6">
                                <label class="label-consulta mb-0" for="pad">
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
                                </label>
                            </div>
                            <div class="col-6">
                                <p class="panel-value mb-0">
                                    {{ totales.compraCartera | currency }}
                                </p>
                            </div>
                            <div class="col-6">
                                <label class="label-consulta mb-0" for="pad">
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
                                </label>
                            </div>
                            <div class="col-6">
                                <p class="panel-value mb-0">
                                    {{
                                        (conteoEgresosPlus
                                            ? conteoEgresosPlus + totales.libreInversionSuma
                                            : totales.libreInversionSuma) | currency
                                    }}
                                </p>
                            </div>
                            <div class="col-6">
                                <label class="label-consulta mb-0" for="pad">
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
        ...mapGetters('pagaduriasModule', ['couponsIngresos']),
        ...mapGetters('embargosModule', ['embargosPerPeriod']),
        ...mapGetters('descuentosModule', ['descuentosPerPeriod'])
    },
    methods: {
        ...mapMutations('datamesModule', ['setCuotaDeseada'])
    }
};
</script>
