<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading d-flex justify-content-between">
                <b>OBLIGACIONES VIGENTES EN MORA</b>
                <div class="d-flex align-items-center">
                    <b class="mr-2">PERIODO:</b>
                    <select class="form-control" @change="setSelectedPeriod($event.target.value)">
                        <option :value="period" v-for="period in descuentosPeriodos" :key="period">
                            {{ period }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-1">
                        <b class="panel-label table-text"></b>
                    </div>
                    <div class="col-3 px-0">
                        <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                    </div>
                    <div class="col-3 px-0">
                        <b class="panel-label table-text">VALOR CUOTA EN MORA:</b>
                    </div>
                </div>

                <div v-for="(item, key) in descuentosPerPeriod.items" :key="key" class="row panel-br-light-green pt-3">
                    <div class="col-1 pr-0">
                        <input v-model="item.check" type="checkbox" />
                    </div>
                    <div class="col-3 px-0">
                        <p>{{ item.mliquid || '-' }}</p>
                    </div>
                    <div class="col-3 px-0">
                        <p>{{ (item.valor || '-') | currency }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';

export default {
    name: 'Descuentos',
    computed: {
        ...mapGetters('descuentosModule', ['descuentosPeriodos', 'descuentosPerPeriod'])
    },
    methods: {
        ...mapMutations('descuentosModule', ['setSelectedPeriod'])
    }
};
</script>
