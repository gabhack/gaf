<template>
    <div class="container-fluid">
        <div v-if="type_consult === 'individual'">
            <!-- DESCARGAR PDF -->
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-end">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3" />
                        <div v-for="(datames, key) in datames" :key="key">
                            <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">
                                {{ datames.nomp }}
                            </h2>
                        </div>
                    </div>
                    <button type="button" v-on:click="print" class="btn btn-black-pearl px-3">
                        <span>Descargar PDF</span>
                        <download-icon></download-icon>
                    </button>
                </div>
            </div>

            <!-- REALIZAR CONSULTA -->
            <div id="consulta-container" class="row">
                <div class="panel mb-3 col-md-12">
                    <div class="panel-heading">
                        <b>REALIZAR CONSULTA</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">CEDULA:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="number"
                                    v-model="dataclient.doc"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">NOMBRES Y APELLIDOS:</b>
                                <input
                                    required
                                    class="form-control text-center"
                                    type="text"
                                    v-model="dataclient.name"
                                />
                            </div>
                            <div class="col-6">
                                <b class="panel-label">PAGADURIA:</b>
                                <select required class="form-control" v-model="dataclient.pagaduria">
                                    <option value="FOPEP">FOPEP</option>
                                    <option value="FIDUPREVISORA">FIDUPREVISORA</option>
                                    <option value="SEDVALLE">SED VALLE</option>
                                    <option value="SEMCALI">SEM CALI</option>
                                </select>
                            </div>
                            <div class="col-6 mt-4">
                                <button
                                    type="button"
                                    v-if="dataclient.pagaduria && dataclient.name && dataclient.doc"
                                    class="btn btn-primary"
                                    v-on:click="getData"
                                >
                                    CONSULTAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PAGADURIA FOPEP -->
                <div class="col-6" v-if="datames.length > 0 && dataclient.pagaduria === 'FOPEP'">
                    <div class="panel mb-3">
                        <div class="panel-heading">
                            <b>INFORMACIÃ“N PERSONAL</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
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
                                    <b class="panel-label">FONDO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.fondo }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.td }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.doc }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.nomp }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.fecnacimient }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DIRECCIÓN:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.dir }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DEPARTAMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.dpto }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">MUNICIPIO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.mnpio }}</p>
                                    </div>
                                </div>
                                <div
                                    class="col-md-6"
                                    v-if="
                                        user.roles_id === 1 ||
                                        user.roles_id === '1' ||
                                        user.roles_id === 4 ||
                                        user.roles_id === '4' ||
                                        user.roles_id === 5 ||
                                        user.roles_id === '5'
                                    "
                                >
                                    <b class="panel-label">NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.nbanco }}</p>
                                    </div>
                                </div>
                                <div
                                    class="col-md-6"
                                    v-if="
                                        user.roles_id === 1 ||
                                        user.roles_id === '1' ||
                                        user.roles_id === 4 ||
                                        user.roles_id === '4' ||
                                        user.roles_id === 5 ||
                                        user.roles_id === '5'
                                    "
                                >
                                    <b class="panel-label">SUCURSAL BANCO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.sucursal }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">TELEFONO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.tel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CELULAR:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.cel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CORREO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.correo }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PAGADURIA FIDUPREVISORA -->
                <div class="col-md-6" v-if="datamesfidu.length > 0 && dataclient.pagaduria === 'FIDUPREVISORA'">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>INFORMACIÃ“N PERSONAL</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.td }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.doc }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.nomp }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.fecnacimient }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DIRECCIÃ“N:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.dir }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DEPARTAMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.dpto }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">MUNICIPIO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.mnpio }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">TELEFONO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.tel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CELULAR:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.cel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CORREO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.correo }}</p>
                                    </div>
                                </div>
                                <!--<div class="col-6">
                                    <b class="panel-label">ESTADO CIVIL:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.estcivil}}</p>
                                    </div>
                                </div>-->

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
                                    <b class="panel-label">DESCRIPCIÃ“N:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.desctipvinc }}</p>
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
                                    <b class="panel-label">RECURSOS:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.descfuenrecurso }}</p>
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
                                    <b class="panel-label">FECHA DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.fecdata }}</p>
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
                                    <b class="panel-label">MES DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.mesdata }}</p>
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
                                    <b class="panel-label">AÃ‘O DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.anodata }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PAGADURIA SED VALLE -->
                <div class="col-md-6" v-if="datamessedvalle.length > 0 && dataclient.pagaduria === 'SEDVALLE'">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>INFORMACIÃ“N PERSONAL</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">TIPO DE DOCUMENTOf:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.td }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.doc }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.nomp }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.fecnacimient }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DIRECCIÃ“N:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.dir }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DEPARTAMENTO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.dpto }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">MUNICIPIO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.mnpio }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">TELEFONO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.tel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CELULAR:</b>
                                    
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.cel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CORREO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.correo }}</p>
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
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.mesdata }}</p>
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
                                    <b class="panel-label">AÃ‘O CARGA DATA:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.anodata }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PAGADURIA SEMCALI -->
                <div class="col-md-6" v-if="datamessemcali.length > 0 && dataclient.pagaduria === 'SEMCALI'">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>INFORMACIÃ“N PERSONAL</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.nvinc }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.nomp }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.fecnacimient }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">DIRECCIÃ“N:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.dir }}</p>
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
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.tel }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CELULAR:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">CORREO:</b>
                                    <div v-for="(datamessemcali, key) in datames" :key="key">
                                        <p class="panel-value">{{ datamessemcali.email }}</p>
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
                                    <b class="panel-label">DESCRIPCIÃ“N:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.desctipvinc }}</p>
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
                                    <b class="panel-label">RECURSOS:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.descfuenrecurso }}</p>
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
                                    <b class="panel-label">FECHA DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.fecdata }}</p>
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
                                    <b class="panel-label">MES DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.mesdata }}</p>
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
                                    <b class="panel-label">AÃ‘O DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.anodata }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HISTORIAL LABORAL -->
                <div class="col-6" v-if="fechaVinc.length >= 0">
                    <div class="panel mb-3">
                        <div class="panel-heading">
                            <b>HISTORIAL LABORAL</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <!-- ANTIGUEDAD LABORAL -->
                                <div class="col-6">
                                    <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                    <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <p class="panel-value">{{ fechavinc.vinc }}</p>
                                    </div>
                                </div>
                                <!-- TIPO PENSION -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label">TIPO PENSION:</b>
                                    <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <p class="panel-value">{{ fechavinc.tp }}</p>
                                    </div>
                                </div>
                                <!-- VALOR INGRESO -->
                                <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label">VALOR INGRESO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.vpension | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label">VALOR INGRESO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">VALOR INGRESO:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.vpension | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label">VALOR INGRESO:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.vingreso | currency }}</p>
                                    </div>
                                </div>
                                <!-- VALOR SALUD -->
                                <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label">VALOR SALUD:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.vsalud | currency }}</p>
                                    </div>
                                </div>
                                <!-- VALOR DESCUENTOS -->
                                <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label">VALOR DESCUENTOS:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.vdesc | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label">VALOR DESCUENTO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.vdescbruto | currency }}</p>
                                    </div>
                                </div>
                                <!-- VALOR CUPO -->
                                <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label">VALOR CUPO:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.cupo | currency }}</p>
                                    </div>
                                </div>
                                <!-- VALOR EMBARGOS -->
                                <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label">VALOR EMBARGOS:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.venbargos | currency }}</p>
                                    </div>
                                </div>
                                <!-- FECHA INGRESO -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">FECHA INGRESO:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.fechingr }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label">FECHA INGRESO:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.fingr }}</p>
                                    </div>
                                </div>
                                <!-- AREA DE DESEMPEÃ‘O -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">AREA DE DESEMPEÃ‘O:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.esquema }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label">AREA DE DESEMPEÃ‘O:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.esquema }}</p>
                                    </div>
                                </div>
                                <!-- CARGO -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">CARGO:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.cargo }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label">CARGO:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.cargo }}</p>
                                    </div>
                                </div>
                                <!-- FECHA VINVULACION -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">FECHA VINCULACIÃ“N:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.fecnombr }}</p>
                                    </div>
                                </div>
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label">FECHA VINCULACIÃ“N:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.fnombramiento }}</p>
                                    </div>
                                </div>
                                <!-- TIPO VINCULACION  -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">TIPO VINCULACIÃ“N:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.nivcontr }}</p>
                                    </div>
                                </div>
                                <!-- ESTADO LABORAL -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">ESTADO LABORAL:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.estlaboral }}</p>
                                    </div>
                                </div>
                                <!-- CENTRO DE EDUCACION -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">CENTRO DE EDUCACIÃ“N:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.centrocosto }}</p>
                                    </div>
                                </div>
                                <!-- SEDE EN LA QUE PRESTA EL SERVICIO -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.sedecoleg }}</p>
                                    </div>
                                </div>
                                <!-- VINCULACION -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label">VINCULACION:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.vinc }}</p>
                                    </div>
                                </div>
                                <!-- FECHA DE PAGO PENSION -->
                                <div class="col-6" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label">FECHA DE PAGO PENSION:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.fechpago }}</p>
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
                                    <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <p class="panel-value">{{ fechavinc.fecdata }}</p>
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
                                    <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <p class="panel-value">{{ fechavinc.mesdata }}</p>
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
                                    <b class="panel-label">AÃ‘O CARGA DATA:</b>
                                    <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <p class="panel-value">{{ fechavinc.anodata }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OBLIGACIONES VIGENTES AL DIA -->
                <div class="col-md-12" v-if="descapli.length >= 0">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <!-- <div class="col-md-2" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label table-text">SELECCIONE PERIODO DE DATA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <input type="checkbox" class="mr-2" v-on:click="(e)=>vAplicado(e.target.checked, descapli, descapli.pagare, descapli.nomtercero)"/><p class="panel-value">{{descapli.periodo}}</p>
                                    </div>
                                </div> -->

                                <!-- TIPO ENTIDAD -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.clase }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>

                                <!-- NOMBRE ENTIDAD -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.nomtercero }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                    <div v-for="(deduccionessemcali, key) in deduccionessemcali" :key="key">
                                        <p class="panel-value">{{ deduccionessemcali.centrocostdeduc }}</p>
                                    </div>
                                </div>

                                <!-- NUMERO DE PAGARE -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">NUMERO PAGARE:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.pagare }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">NUMERO PAGARE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">NUMERO PAGARE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">NUMERO PAGARE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>

                                <!-- CUOTA DE DEUDA -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">CUOTA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.vaplicado | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">CUOTA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">CUOTA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">CUOTA:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.valordeduc | currency }}</p>
                                    </div>
                                </div>

                                <!-- FECHA INICIO DEUDA -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.fgrab }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>

                                <!-- NOMBRE ENTIDAD CEDIENTE -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.nonentant }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>

                                <div
                                    class="col-md-6"
                                    v-if="
                                        user.roles_id === 1 ||
                                        user.roles_id === '1' ||
                                        user.roles_id === 4 ||
                                        user.roles_id === '4' ||
                                        user.roles_id === 5 ||
                                        user.roles_id === '5'
                                    "
                                >
                                    <b class="panel-label table-text">VALOR TOTAL DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.vtotal | currency }}</p>
                                    </div>
                                </div>
                                <!-- <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">VALOR TOTAL DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">VALOR TOTAL DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>-->

                                <div
                                    class="col-md-6"
                                    v-if="
                                        user.roles_id === 1 ||
                                        user.roles_id === '1' ||
                                        user.roles_id === 4 ||
                                        user.roles_id === '4' ||
                                        user.roles_id === 5 ||
                                        user.roles_id === '5'
                                    "
                                >
                                    <b class="panel-label table-text">VALOR PAGADO DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{ descapli.vpagado | currency }}</p>
                                    </div>
                                </div>
                                <!--<div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">VALOR PAGADO DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">VALOR PAGADO DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OBLIGACIONES VIGENTES EN MORA -->
                <div class="col-md-12" v-if="descnoap.length >= 0">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div
                                    class="col-3"
                                    v-if="
                                        user.roles_id === 1 ||
                                        user.roles_id === '1' ||
                                        user.roles_id === 4 ||
                                        user.roles_id === '4' ||
                                        user.roles_id === 5 ||
                                        user.roles_id === '5'
                                    "
                                >
                                    <b class="panel-label">SELECCIONE PERIODO DE DATA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <input
                                            type="checkbox"
                                            v-on:click="
                                                e =>
                                                    vAplicado(
                                                        e.target.checked,
                                                        descnoap,
                                                        descnoap.pagare,
                                                        descnoap.nomtercero
                                                    )
                                            "
                                        />
                                        <p>{{ descnoap.clase }}</p>
                                    </div>
                                </div>

                                <!-- NOMBRE ENTIDAD ACTUAL -->
                                <div class="col-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.nomtercero }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                    <div v-for="(embargossedvalle, key) in embargossedvalle" :key="key">
                                        <p class="panel-value">{{ embargossedvalle.ndeman }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                    <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
                                        <p class="panel-value">{{ embargossemcali.entidaddeman }}</p>
                                    </div>
                                </div>

                                <!-- NUMERO DE PAGARE -->
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.pagare }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                    <div v-for="(embargossedvalle, key) in embargossedvalle" :key="key">
                                        <p class="panel-value">{{ embargossedvalle.nitdeman }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                    <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
                                        <p class="panel-value">{{ embargossemcali.docdeman | currency }}</p>
                                    </div>
                                </div>

                                <!-- CUOTA DEUDA -->
                                <div class="col-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">CUOTA DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.vfijo | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">CUOTA DEUDA:</b>
                                    <div v-for="(embargossedvalle, key) in embargossedvalle" :key="key">
                                        <p class="panel-value">{{ embargossedvalle.temb | currency }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">CUOTA DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">CUOTA DEUDA:</b>
                                    <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
                                        <p class="panel-value">{{ embargossemcali.temb | currency }}</p>
                                    </div>
                                </div>

                                <!-- FECHA INICIO DEUDA -->
                                <div class="col-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.fgrab }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div v-for="(embargossedvalle, key) in embargossedvalle" :key="key">
                                        <p class="panel-value">{{ embargossedvalle.finiemb }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
                                        <p class="panel-value">{{ embargossemcali.fembini }}</p>
                                    </div>
                                </div>

                                <!-- NOMBRE ENTIDAD CEDIENTE -->
                                <div class="col-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.nonentant }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div>
                                        <p class="panel-value">-</p>
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
                                    <b class="panel-label">VALOR TOTAL DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.vtotal | currency }}</p>
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
                                    <b class="panel-label">VALOR PAGADO DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.vpagado | currency }}</p>
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
                                    <b class="panel-label table-text">SALDO DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.saldo | currency }}</p>
                                    </div>
                                </div>

                                <!-- INCONSISTENCIA -->
                                <div class="col-2" v-if="dataclient.pagaduria === 'FOPEP'">
                                    <b class="panel-label table-text">INCONSISTENCIA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{ descnoap.Incon }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                    <b class="panel-label table-text">INCONSISTENCIA:</b>
                                    <div v-for="(embargossedvalle, key) in embargossedvalle" :key="key">
                                        <p class="panel-value">{{ embargossedvalle.memb }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                    <b class="panel-label table-text">INCONSISTENCIA:</b>
                                    <div>
                                        <p class="panel-value">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="dataclient.pagaduria === 'SEMCALI'">
                                    <b class="panel-label table-text">INCONSISTENCIA:</b>
                                    <div v-for="(embargossemcali, key) in embargossemcali" :key="key">
                                        <p class="panel-value">{{ embargossemcali.motemb }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OTROS POSIBLES INGRESOS Y DEDUCCIONES -->
                <div class="col-md-12" v-if="datamesfidu.length >= 0">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>OTROS POSIBLES INGRESOS Y DEDUCCIONES</b></div>
                        <div class="panel-body">
                            <!-- OTROS INGRESOS FOPEP -->
                            <div class="row" v-if="dataclient.pagaduria === 'FOPEP'">
                                <div class="col-12">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFidu = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFidu = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FIDU -->
                                    <div class="col-12 tables-space" v-if="dataPlusFidu">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR DESCUENTO:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.vdescbruto | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">VINCULACION:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE PAGO PENSION:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.fechpago }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 tables-space">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFode = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFode = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FODE -->
                                    <div class="col-md-12 tables-space" v-if="dataPlusFode">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.fechingr }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">AREA DE DESEMPEÃ‘O:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.esquema }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">CARGO:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.cargo }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA VINCULACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.fecnombr }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO VINCULACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.nivcontr }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">ESTADO LABORAL:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.estlaboral }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">CENTRO DE EDUCACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.centrocosto }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label"
                                                                    >SEDE EN LA QUE PRESTA EL SERVICIO:</b
                                                                >
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.sedecoleg }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossedvalle.ndeman }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossedvalle.nitdeman }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossedvalle.temb | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossedvalle.finiemb }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossedvalle.memb }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 tables-space">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.vingreso | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusSeca = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusSeca = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas SEMCALI -->
                                    <div class="col-12 tables-space" v-if="dataPlusSeca">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div>
                                                                    <p class="panel-value">-</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.nvinc }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamessemcali.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamessemcali.dir }}</p>
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
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamessemcali.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div>
                                                                    <p class="panel-value">-</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datames"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.email }}
                                                                    </p>
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
                                                                <b class="panel-label">DESCRIPCIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.desctipvinc }}
                                                                    </p>
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
                                                                <b class="panel-label">RECURSOS:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.descfuenrecurso }}
                                                                    </p>
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
                                                                <b class="panel-label">FECHA DE CARGA DATA:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.fecdata }}</p>
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
                                                                <b class="panel-label">MES DE CARGA DATA:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.mesdata }}</p>
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
                                                                <b class="panel-label">AÃ‘O DE CARGA DATA:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.anodata }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.vingreso | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.fingr }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">AREA DE DESEMPEÃ‘O:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.esquema }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">CARGO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.cargo }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA VINCULACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.fnombramiento }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div
                                                                v-for="(deduccionessemcali, key) in deduccionessemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ deduccionessemcali.centrocostdeduc }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div
                                                                v-for="(datamessemcali, key) in datamessemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ datamessemcali.valordeduc | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossemcali.entidaddeman }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossemcali.docdeman | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossemcali.temb | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossemcali.fembini }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossemcali.fembini }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- OTROS INGRESOS FIDUPREVISORA -->
                            <div class="row" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                <div class="col-12 tables-space">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFopep = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFopep = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FOPEP -->
                                    <div class="col-12 tables-space" v-if="dataPlusFopep">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO PENSION:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.tp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR SALUD:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vsalud | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR DESCUENTOS:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vdesc | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR CUPO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.cupo | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR EMBARGOS:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.venbargos | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-primary mb-3">
                                                    <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.clase }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.nomtercero }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.pagare }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">CUOTA:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ descapli.vaplicado | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text"
                                                                    >FECHA INICIO DEUDA:</b
                                                                >
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.fgrab }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD CEDIENTE:</b
                                                                >
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.nonentant }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-primary mb-3">
                                                    <div class="panel-heading">
                                                        <b>OBLIGACIONES VIGENTES EN MORA</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD ACTUAL:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.nomtercero }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.pagare }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ descnoap.vfijo | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >FECHA INICIO DEUDA:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.fgrab }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD CEDIENTE:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.nonentant }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.Incon }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datamessedvalle, key) in datamessedvalle" :key="key">
                                        <p class="panel-value">{{ datamessedvalle.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFode = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFode = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FODE -->
                                    <div class="col-md-12 tables-space" v-if="dataPlusFode">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.fechingr }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">AREA DE DESEMPEÃ‘O:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.esquema }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">CARGO:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.cargo }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA VINCULACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.fecnombr }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO VINCULACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.nivcontr }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">ESTADO LABORAL:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.estlaboral }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">CENTRO DE EDUCACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.centrocosto }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label"
                                                                    >SEDE EN LA QUE PRESTA EL SERVICIO:</b
                                                                >
                                                                <div
                                                                    v-for="(datamessedvalle, key) in datamessedvalle"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessedvalle.sedecoleg }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossedvalle.ndeman }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossedvalle.nitdeman }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossedvalle.temb | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossedvalle.finiemb }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div
                                                                v-for="(embargossedvalle, key) in embargossedvalle"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossedvalle.memb }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 tables-space">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datamessemcali, key) in datamessemcali" :key="key">
                                        <p class="panel-value">{{ datamessemcali.vingreso | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusSeca = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusSeca = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas SEMCALI -->
                                    <div class="col-12 tables-space" v-if="dataPlusSeca">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div>
                                                                    <p class="panel-value">-</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.nvinc }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamessemcali.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamessemcali.dir }}</p>
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
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamessemcali.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div>
                                                                    <p class="panel-value">-</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datames"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.email }}
                                                                    </p>
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
                                                                <b class="panel-label">DESCRIPCIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.desctipvinc }}
                                                                    </p>
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
                                                                <b class="panel-label">RECURSOS:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.descfuenrecurso }}
                                                                    </p>
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
                                                                <b class="panel-label">FECHA DE CARGA DATA:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.fecdata }}</p>
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
                                                                <b class="panel-label">MES DE CARGA DATA:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.mesdata }}</p>
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
                                                                <b class="panel-label">AÃ‘O DE CARGA DATA:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.anodata }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.vingreso | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA INGRESO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.fingr }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">AREA DE DESEMPEÃ‘O:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.esquema }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">CARGO:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.cargo }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA VINCULACIÃ“N:</b>
                                                                <div
                                                                    v-for="(datamessemcali, key) in datamessemcali"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamessemcali.fnombramiento }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div
                                                                v-for="(deduccionessemcali, key) in deduccionessemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ deduccionessemcali.centrocostdeduc }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div
                                                                v-for="(datamessemcali, key) in datamessemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ datamessemcali.valordeduc | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossemcali.entidaddeman }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossemcali.docdeman | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">
                                                                    {{ embargossemcali.temb | currency }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossemcali.fembini }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div
                                                                v-for="(embargossemcali, key) in embargossemcali"
                                                                :key="key"
                                                            >
                                                                <p class="panel-value">{{ embargossemcali.fembini }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- OTROS INGRESOS SED VALLE -->
                            <div class="row" v-if="dataclient.pagaduria === 'SEDVALLE'">
                                <div class="col-12">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFopep = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFopep = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FOPEP -->
                                    <div class="col-12 tables-space" v-if="dataPlusFopep">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO PENSION:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.tp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR SALUD:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vsalud | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR DESCUENTOS:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vdesc | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR CUPO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.cupo | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR EMBARGOS:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.venbargos | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-primary mb-3">
                                                    <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.clase }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.nomtercero }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.pagare }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">CUOTA:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ descapli.vaplicado | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text"
                                                                    >FECHA INICIO DEUDA:</b
                                                                >
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.fgrab }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD CEDIENTE:</b
                                                                >
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.nonentant }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-primary mb-3">
                                                    <div class="panel-heading">
                                                        <b>OBLIGACIONES VIGENTES EN MORA</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD ACTUAL:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.nomtercero }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.pagare }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ descnoap.vfijo | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >FECHA INICIO DEUDA:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.fgrab }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD CEDIENTE:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.nonentant }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.Incon }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 tables-space">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFidu = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFidu = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FIDU -->
                                    <div class="col-12 tables-space" v-if="dataPlusFidu">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR DESCUENTO:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.vdescbruto | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">VINCULACION:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE PAGO PENSION:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.fechpago }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- OTROS INGRESOS SEMCALI -->
                            <div class="row" v-if="dataclient.pagaduria === 'SEMCALI'">
                                <div class="col-12">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{ datames.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFopep = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFopep = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FOPEP -->
                                    <div class="col-12 tables-space" v-if="dataPlusFopep">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO PENSION:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.tp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR SALUD:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vsalud | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR DESCUENTOS:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.vdesc | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR CUPO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.cupo | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">VALOR EMBARGOS:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.venbargos | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-primary mb-3">
                                                    <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.clase }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.nomtercero }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.pagare }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">CUOTA:</b>
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ descapli.vaplicado | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text"
                                                                    >FECHA INICIO DEUDA:</b
                                                                >
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.fgrab }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD CEDIENTE:</b
                                                                >
                                                                <div v-for="(descapli, key) in descapli" :key="key">
                                                                    <p class="panel-value">{{ descapli.nonentant }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-primary mb-3">
                                                    <div class="panel-heading">
                                                        <b>OBLIGACIONES VIGENTES EN MORA</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD ACTUAL:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.nomtercero }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.pagare }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ descnoap.vfijo | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >FECHA INICIO DEUDA:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.fgrab }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text"
                                                                    >NOMBRE ENTIDAD CEDIENTE:</b
                                                                >
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.nonentant }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-2">
                                                                <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                                <div v-for="(descnoap, key) in descnoap" :key="key">
                                                                    <p class="panel-value">{{ descnoap.Incon }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 tables-space">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{ datamesfidu.vpension | currency }}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="dataPlusFidu = true">
                                        Ver mas
                                    </button>
                                    <button type="button" class="btn btn-secondary" v-on:click="dataPlusFidu = false">
                                        Cerrar
                                    </button>
                                    <!-- Mostrando respuesta nueva en btn Ver mas FIDU -->
                                    <div class="col-12 tables-space" v-if="dataPlusFidu">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>INFORMACIÃ“N PERSONAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.td }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.doc }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nomp }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">
                                                                        {{ datames.fecnacimient }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DIRECCIÃ“N:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dir }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">DEPARTAMENTO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.dpto }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">MUNICIPIO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.mnpio }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label"
                                                                    >NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b
                                                                >
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.nbanco }}</p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="col-md-6"
                                                                v-if="
                                                                    user.roles_id === 1 ||
                                                                    user.roles_id === '1' ||
                                                                    user.roles_id === 4 ||
                                                                    user.roles_id === '4' ||
                                                                    user.roles_id === 5 ||
                                                                    user.roles_id === '5'
                                                                "
                                                            >
                                                                <b class="panel-label">SUCURSAL BANCO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.sucursal }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">TELEFONO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.tel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CELULAR:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.cel }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b class="panel-label">CORREO:</b>
                                                                <div v-for="(datames, key) in datames" :key="key">
                                                                    <p class="panel-value">{{ datames.correo }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6" v-if="fechaVinc.length > 0">
                                                <div class="panel mb-3">
                                                    <div class="panel-heading">
                                                        <b>HISTORIAL LABORAL</b>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                                                    <p class="panel-value">{{ fechavinc.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR INGRESO:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.vpension | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">VALOR DESCUENTO:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.vdescbruto | currency }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <b class="panel-label">VINCULACION:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">{{ datamesfidu.vinc }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="col-6">
                                                                <b class="panel-label">FECHA DE PAGO PENSION:</b>
                                                                <div
                                                                    v-for="(datamesfidu, key) in datamesfidu"
                                                                    :key="key"
                                                                >
                                                                    <p class="panel-value">
                                                                        {{ datamesfidu.fechpago }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">CUOTA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-primary mb-3">
                                                <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-2">
                                                            <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">CUOTA DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text"
                                                                >NOMBRE ENTIDAD CEDIENTE:</b
                                                            >
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-2">
                                                            <b class="panel-label table-text">INCONSISTENCIA:</b>
                                                            <div>
                                                                <p class="panel-value">-</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.table-text {
    font-size: 12px;
}

.tables-space {
    margin-top: 15px !important;
}
</style>

<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css" />

<script>
import printJS from 'print-js';

export default {
    props: ['user'],
    data() {
        return {
            dataclient: {},
            plan: 'basico',
            enableFirstStep: false,
            enableSecondStep: false,
            enableThirdStep: false,
            enableFourStep: false,
            dataPlusFopep: false,
            dataPlusFidu: false,
            dataPlusFode: false,
            dataPlusSeca: false,
            consultaDescapli: [],
            actualDate: new Date().toLocaleString(),
            pagare: [],
            pagareSelected: [],
            nomterSelect: [],
            resultPagare: [],
            filter: '',
            type_consult: 'individual',

            datames: [],
            fechaVinc: [],
            descapli: [],
            descnoap: [],
            datamesfidu: [],
            datamessedvalle: [],
            datamessemcali: [],
            deduccionessemcali: [],
            descuentossedvalle: [],
            embargossemcali: [],
            embargossedvalle: [],
            id_consulta: null
        };
    },
    computed: {
        filteredRows() {
            if (!this.resultPagare.entidad) return false;

            return this.resultPagare.entidad.filter(row => {
                const pagare = row.toString().toLowerCase();
                const searchTerm = this.filter.toLowerCase();

                return pagare.includes(searchTerm);
            });
        }
    },
    methods: {
        getData() {
            this.getDatames();
            this.getFechaVinc();
            this.getDescapli();
            this.getDescnoap();
            this.getDatamesfidu();
            this.getDatamesSedValle();
            this.getDeduccionesSemCali();
            this.getDescuentosSedValle();
            this.getEmbargosSemCali();
            this.getEmbargosSedValle();
            this.getDatamesSemCali();
        },
        getDatames() {
            axios.get(`datames/${this.dataclient.doc}`).then(response => {
                this.datames = response.data;
            });
        },
        getDatamesfidu() {
            axios.post('/datamesfidu/consultaUnitaria', { doc: this.dataclient.doc }).then(response => {
                this.datamesfidu = response.data.data;
            });
        },
        getDatamesSedValle() {
            axios.post('/datamessedvalle/consultaUnitaria', { doc: this.dataclient.doc }).then(response => {
                this.datamessedvalle = response.data.data;
            });
        },
        getDatamesSemCali() {
            axios.post('/consultaDatamessemcali', { doc: this.dataclient.doc }).then(response => {
                console.log('Esto es datamessemcali', response.data);
                this.datamessemcali = response.data.data;
            });
        },
        getFechaVinc() {
            axios.get(`fechavinc/${this.dataclient.doc}`).then(response => {
                this.fechaVinc = response.data;
            });
        },
        getDescapli() {
            axios.get(`descapli/${this.dataclient.doc}`).then(response => {
                this.descapli = response.data;
            });
        },
        getDescnoap() {
            axios.get(`descnoap/${this.dataclient.doc}`).then(response => {
                this.descnoap = response.data;
            });
        },
        getDeduccionesSemCali() {
            axios.post('/consultaDeduccionessemcali', { doc: this.dataclient.doc }).then(response => {
                console.log('Esto es deduccionessemcali', response.data);
                this.deduccionessemcali = response.data.data;
            });
        },
        getEmbargosSemCali() {
            axios.post('/consultaEmbargossemcali', { doc: this.dataclient.doc }).then(response => {
                console.log('Esto es embargossemcali', response.data);
                this.embargossemcali = response.data.data;
            });
        },
        getEmbargosSedValle() {
            axios.post('/consultaEmbargossedvalle', { doc: this.dataclient.doc }).then(response => {
                console.log('Esto es embargossedvalle', response.data);
                this.embargossedvalle = response.data.data;
            });
        },
        getDescuentosSedValle() {
            axios.post('/consultaDescuentossedvalle', { doc: this.dataclient.doc }).then(response => {
                console.log('Esto es descuentossedvalle', response.data);
                this.descuentossedvalle = response.data.data;
            });
        },
        enableSteps(enable) {
            if (enable === true) {
                this.plan === 'premium';
                this.enableFirstStep = true;
                this.enableSecondStep = true;
                this.enableThirdStep = true;
                this.enableFourStep = true;
                this.sendPagare();
            } else {
            }
        },
        getDataClient() {
            axios
                .post('consultaDescnoap', { data: this.dataclient })
                .then(response => {
                    console.log(response, 'pagadura');
                    if (response.data.message === 'El cliente seleccionado tiene inconsistencias.') {
                        this.consultaDescapli = response.data.data;
                    } else {
                        axios
                            .post('consultaUnitaria', { data: this.dataclient })
                            .then(response => {
                                if (response.data.message === 'El cliente seleccionado tiene inconsistencias.') {
                                    toastr.success(response.data.message);
                                    this.consultaDescapli = response.data.data;
                                } else {
                                    this.consultaDescapli = response.data.data;
                                }
                            })
                            .catch(error => {
                                toastr.success(response.data.message);
                            });
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },

        vAplicado(value, data, pagareSelect, nomterSelected) {
            if (value === true) {
                this.pagareSelected.push(data);
            }

            this.dataclient.pagareSelected = this.pagareSelected;

            if (value === true) {
                this.pagare.push(data);
                this.nomterSelect.push(nomterSelected);
                this.dataclient.v_aplicado = this.pagare;
                this.dataclient.nomterSelect = this.nomterSelect;
            } else {
                let pagare = this.pagare.filter(function (item) {
                    return item !== nomterSelected;
                });
                this.dataclient.v_aplicado = pagare;

                let pagareSelected = this.pagareSelected.filter(function (item) {
                    return item.pagare !== pagareSelect;
                });
                this.dataclient.pagareSelected = pagareSelected;

                let nomterSelect = this.nomterSelect.filter(function (item) {
                    return item !== nomterSelected;
                });
                this.dataclient.nomterSelect =
                    nomterSelect.length === 0 ? nomterSelected : this.nomterSelect.push(nomterSelected);
            }
            //console.log(this.dataclient, "pagadurias");
        },

        sendPagare() {
            axios
                .post('resultadoAprobacion', { data: this.dataclient })
                .then(response => {
                    console.log(response, 'pagadurias');
                    toastr.success(response.data.message);
                    this.id_consulta = response.data.data.id_consulta;
                    this.resultPagare = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        print() {
            window.print();
        }
    }
};
</script>
