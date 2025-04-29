<template>
    <div class="col-6">
        <div class="panel mb-3">
            <div class="panel-heading">
                <b>HISTORIAL LABORAL {{ pagaduriaType }}</b>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-6">
                        <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                        <div>
                            <p class="panel-value">{{ fechavinc.vinc }}</p>
                        </div>
                    </div>

                    <!--============================
                            FOPEP
                    ==============================-->
                    <template v-if="datames && pagaduriaType == 'FOPEP'">
                        <div class="col-6">
                            <b class="panel-label">TIPO PENSION:</b>
                            <div>
                                <p class="panel-value">{{ fechavinc.tp }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <b class="panel-label">VALOR INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datames.vpension | currency }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <b class="panel-label">VALOR SALUD:</b>
                            <div>
                                <p class="panel-value">{{ datames.vsalud | currency }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <b class="panel-label">VALOR DESCUENTOS:</b>
                            <div>
                                <p class="panel-value">{{ datames.vdesc | currency }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <b class="panel-label">VALOR CUPO:</b>
                            <div>
                                <p class="panel-value">{{ datames.cupo | currency }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <b class="panel-label">VALOR EMBARGOS:</b>
                            <div>
                                <p class="panel-value">{{ datames.venbargos | currency }}</p>
                            </div>
                        </div>
                    </template>

                    <!--============================
                            SEDVALLE
                    ==============================-->

                    <template v-if="datamessedvalle && pagaduriaType == 'SEDVALLE'">
                        <div class="col-6">
                            <b class="panel-label">VALOR INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.vpension | currency }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.fechingr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">AREA DE DESEMPEÑO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.esquema }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CARGO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.cargo }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.fecnombr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">TIPO VINCULACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.nivcontr }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">ESTADO LABORAL:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.estlaboral }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CENTRO DE EDUCACIÓN:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.centrocosto }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
                            <div>
                                <p class="panel-value">{{ datamessedvalle.sedecoleg }}</p>
                            </div>
                        </div>
                    </template>

                    <!--============================
                        FIDUPREVISORA
                    ==============================-->
                    <template v-if="datamesFidu && pagaduriaType == 'FIDUPREVISORA'">
                        <div class="col-6">
                            <b class="panel-label">VALOR INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamesFidu.vpension | currency }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">VINCULACION:</b>
                            <div>
                                <p class="panel-value">{{ datamesFidu.vinc }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA DE PAGO PENSION:</b>
                            <div>
                                <p class="panel-value">{{ datamesFidu.fechpago }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">VALOR DESCUENTO:</b>
                            <div>
                                <p class="panel-value">{{ datamesFidu.vdescbruto | currency }}</p>
                            </div>
                        </div>
                    </template>

                    <!--============================
                        DATAMESCALI SEMCALI
                    ==============================-->
                    <template v-if="datamessemcali && pagaduriaType == 'SEMCALI'">
                        <div class="col-6">
                            <b class="panel-label">VALOR INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamessemcali.vingreso | currency }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">FECHA INGRESO:</b>
                            <div>
                                <p class="panel-value">{{ datamessemcali.fingr }}</p>
                            </div>
                        </div>

                     

                        <div class="col-6">
                            <b class="panel-label">AREA DE DESEMPEÑO:</b>
                            <div>
                                <p class="panel-value">{{ datamessemcali.esquema }}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">CARGO:</b>
                            <div>
                                <p class="panel-value">{{ datamessemcali.cargo }}</p>
                            </div>
                        </div>
                    </template>

                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EmploymentHistory',
    props: ['fechavinc', 'pagaduriaType', 'datames', 'datamessedvalle', 'datamesFidu', 'datamessemcali', 'user'],
    created() {}
};
</script>
