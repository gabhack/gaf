<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading d-flex justify-content-between">
                <b>DETALLE DE EMBARGOS</b>
                <div v-if="embargos.length > 0" class="d-flex align-items-center">
                    <b class="mr-2">PERIODO:</b>
                    <select class="form-control" @change="setSelectedPeriod($event.target.value)">
                        <option :value="period" v-for="period in embargosPeriodos" :key="period">
                            {{ period }}
                        </option>
                    </select>
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
                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                        </div>
                        <div class="col-2 px-0">
                            <b class="panel-label table-text">INCONSISTENCIA:</b>
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
                            <p>{{ (item.temb || item.valor || '0') | currency }}</p>
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
    computed: {
        ...mapState('embargosModule', ['embargos']),
        ...mapGetters('embargosModule', ['embargosPeriodos', 'embargosPerPeriod'])
    },
    methods: {
        ...mapMutations('embargosModule', ['setSelectedPeriod'])
    }
};
</script>
