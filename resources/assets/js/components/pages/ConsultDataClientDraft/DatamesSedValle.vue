<template>
    <!-- <div class="col-6">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-6">
                        <b class="panel-label">TIPO DE DOCUMENTO:</b>
                        <div>
                            <p class="panel-value">
                                {{ datamessedvalle.td ? datamessedvalle.td : 'CÉDULA DE CIUDADANÍA' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.doc ? datamessedvalle.doc : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">NOMBRE Y APELLIDO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.nomp ? datamessedvalle.nomp : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">FECHA DE NACIMIENTO:</b>
                        <div>
                            <p class="panel-value">
                                {{ datamessedvalle.fecnacimient ? datamessedvalle.fecnacimient : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">DIRECCIÓN:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.dir ? datamessedvalle.dir : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">DEPARTAMENTO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.dpto ? datamessedvalle.dpto : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <b class="panel-label">MUNICIPIO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.mnpioydep ? datamessedvalle.mnpioydep : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b class="panel-label">TELEFONO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.tel ? datamessedvalle.tel : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b class="panel-label">CELULAR:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.cel ? datamessedvalle.cel : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <b class="panel-label">CORREO:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.correo ? datamessedvalle.correo : '-' }}</p>
                        </div>
                    </div>

                    <div
                        class="col-6"
                        v-if="
                            user.roles_id === 1 ||
                            user.roles_id === '1' ||
                            user.roles_id === 4 ||
                            user.roles_id === '4' ||
                            user.roles_id === 5 ||
                            user.roles_id === '5'
                        "
                    >
                        <b class="panel-label">FECHA CARGA DATA:</b>
                        <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                            <p class="panel-value">{{ datamessedvalle.fecdata }}</p>
                        </div>
                    </div>

                    <div
                        class="col-6"
                        v-if="
                            user.roles_id === 1 ||
                            user.roles_id === '1' ||
                            user.roles_id === 4 ||
                            user.roles_id === '4' ||
                            user.roles_id === 5 ||
                            user.roles_id === '5'
                        "
                    >
                        <b class="panel-label">MES CARGA DATA:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.mesdata ? datamessedvalle.td : '-' }}</p>
                        </div>
                    </div>

                    <div
                        class="col-6"
                        v-if="
                            user.roles_id === 1 ||
                            user.roles_id === '1' ||
                            user.roles_id === 4 ||
                            user.roles_id === '4' ||
                            user.roles_id === 5 ||
                            user.roles_id === '5'
                        "
                    >
                        <b class="panel-label">AÑO CARGA DATA:</b>
                        <div>
                            <p class="panel-value">{{ datamessedvalle.anodata ? datamessedvalle.td : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
export default {
    name: 'DatamesSedValle',
    props: ['datamessedvalle', 'user']
};
</script>

<style scoped></style>
