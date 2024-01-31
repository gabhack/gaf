<template>
    <!-- <div class="col-md-6">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>INFORMACIÓN PERSONALLLL</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-6">
                        <b class="panel-label">TIPO DE DOCUMENTO:</b>
                        <div>
                            <p class="panel-value">CÉDULA DE CIUDADANÍA</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.doc || '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">NOMBRE Y APELLIDO:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.nomp || '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">FECHA DE NACIMIENTO:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.fecnacimient || '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">DIRECCIÓN:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.dir || '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">DEPARTAMENTO:</b>
                        <div>
                            <p class="panel-value">-</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">MUNICIPIO:</b>
                        <div>
                            <p class="panel-value">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b class="panel-label">TELÉFONO:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.tel || '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b class="panel-label">CELULAR:</b>
                        <div>
                            <p class="panel-value">{{ datamesSed.tel || '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'DatamesSedChoco',
    computed: {
        ...mapState('datamesModule', ['datamesSed'])
    }
};
</script>
