<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-1">
                        <b class="panel-label table-text"></b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">MENSAJE:</b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">FECHA:</b>
                    </div>
                </div>

                <div v-for="(item, key) in descuentossemsahagun" :key="key" class="row panel-br-light-green pt-3">
                    <div class="col-1 pr-0">
                        <input v-model="item.check" type="checkbox" />
                    </div>
                    <div class="col-2 px-0">
                        <p>{{ item.mliquid ? item.mliquid : '-' }}</p>
                    </div>
                    <div class="col-2 px-0">
                        <p>{{ item.fecdata ? item.fecdata : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Descuentossemsahagun',
    props: ['descuentossemsahagun'],
    data() {
        return {
            labels: [
                { label: 'MENSAJE', field: 'mliquid', colClass: 'col-4' },
                { label: 'FECHA', field: 'fecdata' }
            ]
        };
    }
};
</script>
