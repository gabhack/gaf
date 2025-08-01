<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>DETALLE DE EMBARGOSO</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-1">
                        <b class="panel-label table-text"></b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">CUENTA:</b>
                    </div>
                    <div class="col-1">
                        <b class="panel-label table-text">CUOTA DEUDA:</b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">JUZGADO:</b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                    </div>
                    <div class="col-2">
                        <b class="panel-label table-text">EXPEDIENTE:</b>
                    </div>
                </div>

                <div v-for="(item, key) in embargossempopayan" :key="key" class="row panel-br-light-green pt-3">
                    <div class="col-1 pr-0">
                        <b class="panel-label table-text"></b>
                    </div>
                    <div class="col-2 px-0">
                        <p>{{ item.ndem ? item.ndem : '-' }}</p>
                    </div>

                    <div class="col-2 px-0">
                        <p>{{ item.cuenta ? item.cuenta : '-' }}</p>
                    </div>

                    <div class="col-1">
                        <p>{{ item.valor | currency }}</p>
                    </div>

                    <div class="col-2">
                        <p>{{ item.juzgado ? item.juzgado : '-' }}</p>
                    </div>

                    <div class="col-2">
                        <p>{{ '-' }}</p>
                    </div>

                    <div class="col-2">
                        <p>{{ item.expediente ? item.expediente : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EmbargosSemPopayan',
    props: ['embargossempopayan']
};
</script>
