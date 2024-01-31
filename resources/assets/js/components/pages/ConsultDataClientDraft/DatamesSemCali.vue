<template>
    <!-- <div class="col-md-6">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>INFORMACIÓN PERSONALllllll</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-6">
                        <b class="panel-label">TIPO DE DOCUMENTO00000:</b>
                        <div>
                            <p class="panel-value">CÉDULA DE CIUDADANÍA</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                        <div>
                            <p class="panel-value">{{ datamessemcali.nvinc ? datamessemcali.nvinc : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">NOMBRE Y APELLIDO:</b>
                        <div>
                            <p class="panel-value">{{ datamessemcali.nomp ? datamessemcali.nomp : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">FECHA DE NACIMIENTO:</b>
                        <div>
                            <p class="panel-value">
                                {{ datamessemcali.fecnacimient ? datamessemcali.fecnacimient : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">DIRECCIÓN:</b>
                        <div>
                            <p class="panel-value">{{ datamessemcali.dir ? datamessemcali.dir : '-' }}</p>
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
                        <b class="panel-label">TELEFONO:</b>
                        <div>
                            <p class="panel-value">{{ datamessemcali.tel ? datamessemcali.mesdata : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b class="panel-label">CELULAR:</b>
                        <div>
                            <p class="panel-value">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
export default {
    name: 'DatamesSemCali',
    props: ['datamessemcali', 'user']
};
</script>

<style scoped></style>
