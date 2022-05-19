<template>
    <div class="container-fluid">        
        <div v-if="type_consult === 'individual'">
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">                
                    <div class="d-flex align-items-end">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3">
                        <div v-for="(datames, key) in datames" :key="key">
                            <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{datames.nomp}}</h2>
                        </div>                        
                    </div>       
                    <button type="button" v-on:click="print" class="btn btn-black-pearl px-3">
                        <span>Descargar PDF</span>
                        <download-icon></download-icon>
                    </button>             
                </div>
            </div>

            <div id="consulta-container" class="row">
                <div class="panel mb-3 col-md-12">
                    <div class="panel-heading">
                        <b>REALIZAR CONSULTA</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">CEDULA:</b>                                
                                <input required class="form-control text-center" type="number" v-model="dataclient.doc">
                            </div>
                            <div class="col-6">
                                <b class="panel-label">NOMBRES Y APELLIDOS:</b>                                
                                <input required class="form-control text-center" type="text" v-model="dataclient.name">
                            </div>
                            <div class="col-6">
                                <b class="panel-label">PAGADURIA:</b>
                                <select required class="form-control" v-model="dataclient.pagaduria">
                                    <option value="FOPEP">FOPEP</option>
                                    <option value="FIDUPREVISORA">FIDUPREVISORA</option>
                                    <option value="FODE VALLE">FODE VALLE</option>                                        
                                </select>                                    
                            </div>                                
                            <div class="col-6 mt-4">
                            <button type="button" v-if="dataclient.pagaduria && dataclient.name && dataclient.doc" class="btn btn-primary" v-on:click="getData">CONSULTAR</button>
                        </div>                            
                    </div>
                </div>
            </div>
            <div class="col-6" v-if="datames.length > 0 && dataclient.pagaduria === 'FOPEP'">
                <div class="panel mb-3">
                    <div class="panel-heading">
                        <b>INFORMACIÓN PERSONAL</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">FONDO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.fondo}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.td}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.doc}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.nomp}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">FECHA DE NACIMIENTO:</b>      
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.fecnacimient}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">DIRECCIÓN:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.dir}}</p>
                                </div>                                
                            </div> <div class="col-6">
                                <b class="panel-label">DEPARTAMENTO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.dpto}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">MUNICIPIO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.mnpio}}</p>
                                </div>                                
                            </div>
                            <!-- <div class="col-6">
                                <b class="panel-label">TIPO PENSION:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.tp}}</p>
                                </div>                                
                            </div> -->
                            <div class="col-md-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.nbanco}}</p>
                                </div>                                
                            </div>
                            <div class="col-md-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">SUCURSAL BANCO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.sucursal}}</p>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <b class="panel-label">TELEFONO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.tel}}</p>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <b class="panel-label">CELULAR:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.cel}}</p>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <b class="panel-label">CORREO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.correo}}</p>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-6" v-if="datamesseceduc.length > 0 && dataclient.pagaduria === 'FODE VALLE' || user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5' || user.roles_id === 6 || user.roles_id === '6' "> -->
            <div class="col-md-6" v-if="datamesseceduc.length > 0 && dataclient.pagaduria === 'FODE VALLE'">    
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">DOCUMENTO IDENTIDAD:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.doc}}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.nomp}}</p>
                                </div>                                
                            </div>
                            <div class="col-6">
                                <b class="panel-label">FECHA INGRESO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.fechingr}}</p>
                                </div>                                
                            </div>
                            <!-- <div class="col-6">
                                <b class="panel-label">Antigüedad Vinculacion (B) :</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.antiguedad}}</p>
                                </div>                                
                            </div>  -->
                            <div class="col-6">
                                <b class="panel-label">FECHA NACIMIENTO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.fecnacimient}}</p>
                                </div>                                
                            </div>
                            <!-- <div class="col-6">
                                <b class="panel-label">EDAD PENSIONADO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.edad}}</p>
                                </div>                                
                            </div> -->

                            <div class="col-6">
                                <b class="panel-label">AREA DE DESEMPEÑO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.esquema}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">CARGO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.cargo}}</p>
                                </div>                                
                            </div>

                            <!-- <div class="col-6">
                                <b class="panel-label">VALOR INGRESO APOXIMADO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.vpension | currency}}</p>
                                </div>                                
                            </div> -->

                            <div class="col-6">
                                <b class="panel-label">FECHA VINCULACIÓN:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.fecnombr}}</p>
                                </div>                                
                            </div>

                            <!-- <div class="col-6">
                                <b class="panel-label">Fecha de posesion (B):</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.fecposesion}}</p>
                                </div>                                
                            </div>  -->

                            <div class="col-6">
                                <b class="panel-label">TIPO VINCULACIÓN:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.nivcontr}}</p>
                                </div>                                
                            </div>
                                
                            <div class="col-6">
                                <b class="panel-label">ESTADO LABORAL:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.estlaboral}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">CENTRO DE EDUCACIÓN:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.centrocosto}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">MUNICIPIO Y DEPARTAMENTO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.mnpioydep}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">TELEFONO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.tel}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">DIRECCIÓN:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.dir}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">CORREO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.correo}}</p>
                                </div>                                
                            </div>

                            <div class="col-6">
                                <b class="panel-label">SEDE EN LA QUE PRESTA EL SERVICIO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.sedecoleg}}</p>
                                </div>                                
                            </div>
                                
                            <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">FECHA CARGA DATA:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.fecdata}}</p>
                                </div>                                
                            </div>

                            <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">MES CARGA DATA:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.mesdata}}</p>
                                </div>                                
                            </div>

                            <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">AÑO CARGA DATA:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.anodata}}</p>
                                </div>                                
                            </div>                                
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" v-if="datamesfidu.length > 0 && dataclient.pagaduria === 'FIDUPREVISORA'">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>INFORMACIÓN PERSONAL</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">MUNICIPIO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.mnpio}}</p>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">ESTADO CIVIL:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.estcivil}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">FECHA DE NACIMIENTO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.fecnacimient}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">EDAD:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.edad}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Tipo de Vinculacion (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.tipvinc}}</p>
                                    </div>                                
                                </div>  -->

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">DESCRIPCIÓN:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.desctipvinc}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Codigo Fuente recurso (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.fuenrecurso}}</p>
                                    </div>                                
                                </div>  -->

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">RECURSOS:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.descfuenrecurso}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Codigo dane departamento (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.numdep}}</p>
                                    </div>                                
                                </div>  -->

                                <div class="col-6">
                                    <b class="panel-label">DEPARTAMENTO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.dpto}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Resolucion (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.resol}}</p>
                                    </div>                                
                                </div>  -->

                                <div class="col-6">
                                    <b class="panel-label">VINCULACION:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vinc}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Fecha expedicion resolucion (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.fechefect}}</p>
                                    </div>                                
                                </div>  -->
                        
                                <!-- <div class="col-6">
                                    <b class="panel-label">Estado Pension (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.estpens}}</p>
                                    </div>                                
                                </div> -->

                                <div class="col-6">
                                    <b class="panel-label">TIPO DOCUMENTO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.td}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">DOCUMENTO IDENTIDAD:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.docbenef}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">NOMBRE Y APELLIDO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.nombenef}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">TELEFONO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.tel}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">DIRECCIÓN:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.dir}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">CORREO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.correo}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Numero comprobante (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.nomcomprob}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">PERIODO DE DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.periodo}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">TIPO PENSION CODIGO:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.tipvinc}}</p>
                                    </div>                                
                                </div> -->

                                <div class="col-6">
                                    <b class="panel-label">FECHA DE PAGO PENSION:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.fechpago}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Sucursal Banco (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.sucursal}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Valor pension Con retroactivo (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vpension}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Valor descuentos en bruto Retroactivo (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vdescbruto}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Valor pago en bruto Retroactivo (B):</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.pagonetbruto}}</p>
                                    </div>                                
                                </div> -->

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">FECHA DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.fecdata}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">MES DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.mesdata}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">AÑO DE CARGA DATA:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.anodata}}</p>
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
                            <!--<div class="col-6">
                                <b class="panel-label">Documento de identidad Pensionado:</b>
                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                    <p class="panel-value">{{fechavinc.doc}}</p>
                                </div>                                
                            </div> -->
                            <div class="col-6">
                                <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                    <p class="panel-value">{{fechavinc.vinc}}</p>
                                </div>                                
                            </div>
                            <div class="col-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                <b class="panel-label">TIPO PENSION:</b>
                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                    <p class="panel-value">{{fechavinc.tp}}</p>
                                </div>                                
                            </div>
                            <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                <b class="panel-label">VALOR INGRESO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.vpension | currency}}</p>
                                </div>                                                                  
                            </div>

                            <div class="col-6" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                <b class="panel-label">VALOR INGRESO:</b>
                                <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                    <p class="panel-value">{{datamesfidu.vpension | currency}}</p>
                                </div>                                
                            </div>

                            <div class="col-6" v-if="dataclient.pagaduria === 'FODE VALLE'">
                                <b class="panel-label">VALOR INGRESO:</b>
                                <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                    <p class="panel-value">{{datamesseceduc.vpension | currency}}</p>
                                </div>
                            </div>
                
                            <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                <b class="panel-label">VALOR SALUD:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.vsalud | currency}}</p>
                                </div>                                
                            </div>
                            
                            <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                <b class="panel-label">VALOR DESCUENTOS:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.vdesc | currency}}</p>
                                </div>                                
                            </div>
                            <div class="col-6" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                <b class="panel-label">VALOR DESCUENTO:</b>
                                <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                    <p class="panel-value">{{datamesfidu.vdescbruto | currency}}</p>
                                </div>                                
                            </div>
                            
                            <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                <b class="panel-label">VALOR CUPO:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.cupo | currency}}</p>
                                </div>                                
                            </div>
                            <div class="col-md-6" v-if="dataclient.pagaduria === 'FOPEP'">
                                <b class="panel-label">VALOR EMBARGOS:</b>
                                <div v-for="(datames, key) in datames" :key="key">
                                    <p class="panel-value">{{datames.venbargos | currency}}</p>
                                </div>                                
                            </div>                            
                            <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">FECHA CARGA DATA:</b>
                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                    <p class="panel-value">{{fechavinc.fecdata}}</p>
                                </div>                                
                            </div>
                            <div class="col-6"  v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">MES CARGA DATA:</b>
                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                    <p class="panel-value">{{fechavinc.mesdata}}</p>
                                </div>                                
                            </div>
                            <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                <b class="panel-label">AÑO CARGA DATA:</b>
                                <div v-for="(fechavinc, key) in fechaVinc" :key="key">
                                    <p class="panel-value">{{fechavinc.anodata}}</p>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>                
            </div>

            <div class="col-md-12" v-if="descapli.length > 0">
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
                                <div class="col-md-2">
                                    <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.clase}}</p>
                                    </div> 
                                </div>
                                <div class="col-md-2">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.nomtercero}}</p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <b class="panel-label table-text">NUMERO PAGARE:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.pagare}}</p>
                                    </div>  
                                </div>  
                                <div class="col-md-2">
                                    <b class="panel-label table-text">CUOTA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.vaplicado | currency}}</p>
                                    </div>                                
                                </div>
                                 <div class="col-md-2">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.fgrab}}</p>
                                    </div>                                
                                </div>                                    
                                <!-- <div class="col-6">
                                    <b class="panel-label">Consecutivo (B):</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.concecutivo}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Entidad (B):</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.tercero}}</p>
                                    </div>                                
                                </div> -->

                               <!-- <div class="col-6">
                                    <b class="panel-label">TIPO DE DOCUMENTO:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.td}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">DOCUMENTO DE IDENTIDAD PENSIONADO:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.doc}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Nombre y apellido pensionado:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.nomp}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Porcentaje (B):</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.porcentaje}}</p>
                                    </div>                                
                                </div> -->
                            
                                <div class="col-md-2">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.nonentant}}</p>
                                    </div>                                
                                </div>

                                <div class="col-md-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label table-text">VALOR TOTAL DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.vtotal | currency}}</p>
                                    </div>                                
                                </div>

                                <div class="col-md-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label table-text">VALOR PAGADO DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.vpagado | currency}}</p>
                                    </div>                                
                                </div>                                         
                                
                                <!--<div class="col-6">
                                    <b class="panel-label">SALDO DEUDA:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.saldo}}</p>
                                    </div>                                
                                </div>-->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Forma (B):</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.forma}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Codigo entidad anterior (B):</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.codentiant}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Fecha de cesion a entidad actual:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.fechacesion}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Tipo de descuento:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.tdesc}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">PAGARE5DIG:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.p5d}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">PAGARE4DIGCON0:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.p4d}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Numero pagare:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.numpagopt}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Fecha carga data:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.fecdata}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Mes carga data:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.mesdata}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Año carga data:</b>
                                    <div v-for="(descapli, key) in descapli" :key="key">
                                        <p class="panel-value">{{descapli.anodata}}</p>
                                    </div>                                
                                </div> -->                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" v-if="descnoap.length > 0">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>OBLIGACIONES VIGENTES EN MORA</b></div>
                        <div class="panel-body">                                
                            <div class="row">
                                <div class="col-3" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">SELECCIONE PERIODO DE DATA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <input type="checkbox" v-on:click="(e)=>vAplicado(e.target.checked, descnoap, descnoap.pagare, descnoap.nomtercero)"/><p>{{descnoap.clase}}</p>
                                    </div>
                                </div>
                                <!-- <div class="col-6">
                                    <b class="panel-label">Entidad (B):</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.tercero}}</p>
                                    </div>                                
                                </div> -->
                                <div class="col-2">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD ACTUAL:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.nomtercero}}</p>
                                    </div>                                
                                </div>
                               <!-- <div class="col-6">
                                    <b class="panel-label">Tipo de documento:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.td}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Documento de identidad Pensionado:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.doc}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Nombre y apellido pensionado:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.nomp}}</p>
                                    </div>                                
                                </div> -->

                                <div class="col-md-2">
                                    <b class="panel-label table-text">NUMERO DE PAGARE:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.pagare}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Porcentaje (B):</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.porcentaje}}</p>
                                    </div>                                
                                </div> -->

                                <div class="col-2">
                                    <b class="panel-label table-text">CUOTA DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.vfijo | currency}}</p>
                                    </div>                                
                                </div>

                               <div class="col-2">
                                    <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.fgrab}}</p>
                                    </div>                                
                                </div>

                                <div class="col-2">
                                    <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.nonentant}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">VALOR TOTAL DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.vtotal | currency}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label">VALOR PAGADO DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.vpagado | currency}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                                    <b class="panel-label table-text">SALDO DEUDA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.saldo | currency}}</p>
                                    </div>                                
                                </div>

                                 <!--<div class="col-6">
                                    <b class="panel-">VALOR DESCUENTO NO APLICADO:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.vaplicado}}</p>
                                    </div>                                
                                </div>-->

                                

                                <!-- <div class="col-6">
                                    <b class="panel-label">Forma (B):</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.forma}}</p>
                                    </div>                                
                                </div> -->

                                <div class="col-2">
                                    <b class="panel-label table-text">INCONSISTENCIA:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.Incon}}</p>
                                    </div>                                
                                </div>

                                <!-- <div class="col-6">
                                    <b class="panel-label">Codigo entidad anterior (B):</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.codentiant}}</p>
                                    </div>                                
                                </div> -->

                                <!-- <div class="col-6">
                                    <b class="panel-label">Fecha de cesion a entidad actual:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.fechacesion}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Tipo de descuento:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.tdesc}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Fecha carga data:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.fecdata}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Mes carga data:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.mesdata}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Año carga data:</b>
                                    <div v-for="(descnoap, key) in descnoap" :key="key">
                                        <p class="panel-value">{{descnoap.anodata}}</p>
                                    </div>                                
                                </div> -->                            
                            </div> 
                        </div>
                    </div>                    
                </div>    

               <!-- <div class="col-12 mt-3 mb-3" v-if="descnoap.length > 0 || descapli.length > 0">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Consultar</button>
                </div> -->            

                <div class="col-md-12" v-if="datamesfidu.length > 0">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>OTROS POSIBLES INGRESOS Y DEDUCCIONES</b></div>
                        <div class="panel-body">                    
                            <div class="row" v-if="dataclient.pagaduria === 'FOPEP'">
                                <div class="col-6">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 3:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vpension | currency}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">OTRO POSIBLE DESCUENTO 3:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vdescbruto | currency}}</p>
                                    </div>                                
                                </div>
                                    
                                <div class="col-6">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                                    <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                        <p class="panel-value">{{datamesseceduc.vpension | currency}}</p>
                                    </div>
                                </div>
                            </div>   
                                
                            <div class="row" v-if="dataclient.pagaduria === 'FIDUPREVISORA'">
                                <div class="col-6" >
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{datames.vpension | currency}}</p>
                                    </div>
                                </div>

                                <div class="col-6" >
                                    <b class="panel-label">OTRO POSIBLE EGRESO SALUD:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{datames.vsalud | currency}}</p>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                                    <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                        <p class="panel-value">{{datamesseceduc.vpension | currency}}</p>
                                    </div>
                                </div>
                            </div>   
                                
                            <div class="row" v-if="dataclient.pagaduria === 'FODE VALLE'">
                                <div class="col-6" >
                                    <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{datames.vpension | currency}}</p>
                                    </div>
                                </div>

                                <div class="col-6" >
                                    <b class="panel-label">OTRO POSIBLE EGRESO SALUD:</b>
                                    <div v-for="(datames, key) in datames" :key="key">
                                        <p class="panel-value">{{datames.vsalud | currency}}</p>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">OTRO POSIBLE INGRESO 3:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vpension | currency}}</p>
                                    </div>                                
                                </div>  
                                
                                <div class="col-6">
                                    <b class="panel-label">OTRO POSIBLE DESCUENTO 3:</b>
                                    <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                        <p class="panel-value">{{datamesfidu.vdescbruto | currency}}</p>
                                    </div>                                
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
                
                <!-- <div class="col-md-12" v-if="resultPagare.cuota_compra && resultPagare.cuota_compra.length > 0">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>CALCULO DE COMPRA DE CARTERA</b></div>
                        <div class="panel-body">    
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">Consecutivo:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.consecutivo}}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Estado:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.estado}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Fecha consulta:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.fecha_consulta}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Cedula:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.doc}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Nombre:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.nombre}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Tipo de credito:</b>
                                    <div>
                                        <p class="panel-value">{{dataclient.tipo_credito}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Cupo Lib Inversion:</b>
                                    <div>
                                        <p class="panel-value">{{dataclient.clibinv}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Cuota Compra:</b>
                                    <div v-for="(libInv, key) in resultPagare.cuota_compra" :key="key">
                                        <p class="panel-value">{{libInv.vaplicado}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Entidad:</b>
                                    <div v-for="(row, index) in filteredRows" :key="index">
                                        <p class="panel-value">{{row}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Pagaduria:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.pagaduria}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Vr. Credito:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.vr_credito}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Vr. Desembolso:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.vr_desembolso}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Plazo:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.plazo}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Cuota Cred:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.cuota_cred}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Aprobado:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.aprobado}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">% de incorporacion:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.pct_incorporacion}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Cuota Maxima de incorporacion:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.max_incorporacion}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Fecha respuesta consulta:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.fec_rta_consulta}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Fecha Vinculacion:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.fecha_vinculacion}}</p>
                                    </div>                                
                                </div>

                                <div class="col-6">
                                    <b class="panel-label">Tipo Vinculacion:</b>
                                    <div>
                                        <p class="panel-value">{{resultPagare.tipo_vinculacion}}</p>
                                    </div>                                
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalle de Historial</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                        <b class="panel-label">Vr Credito:</b>
                            <input type="number" class="form-control text-center" v-model="dataclient.vr_credito">
                        </div>

                        <div class="col-12">
                            <b class="panel-label">Cupo Libre Inversión:</b>
                            <input type="number" class="form-control text-center" v-model="dataclient.clibinv">
                        </div>
                            
                        <div class="col-12">
                            <b class="panel-label">Vr Desembolso:</b>
                            <input type="number" class="form-control text-center" v-model="dataclient.vr_desembolso">
                        </div>

                        <div class="col-12">
                            <b class="panel-label">Plazo</b>
                            <input type="number" class="form-control text-center" v-model="dataclient.plazo">
                        </div>
                            
                        <div class="col-12">
                            <b class="panel-label">Cuota Credito</b>
                            <input type="number" class="form-control text-center" v-model="dataclient.cuota_cred">
                        </div>
            
                        <div class="col-12">
                            <label>Tipo de credito</label>
                            <select v-model="dataclient.tipo_credito" class="form-control">
                                <option value="Libre inversión">Libre inversión</option>
                                <option value="Compra cartera">Compra cartera</option>
                                <option value="Refinanciación">Refinanciación</option>
                            </select>
                        </div>
                    </div>
                        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>  
                        <button type="button" class="btn btn-primary" v-on:click="sendPagare" data-dismiss="modal" aria-label="Close">Cosultar</button>                      
                    </div>
                </div>
            </div>
        </div>       
    </div>
</template>
<style>
.table-text{
    font-size:12px;
}
</style>
<script src="print.js"></script>
<script rel="stylesheet" type="text/css" href="print.css"/>
<script>
import printJS from 'print-js';
export default {
    props:['user'],
    data() {
        return {
            dataclient:{},
            plan: 'basico',
            enableFirstStep:false,
            enableSecondStep:false,
            enableThirdStep:false,
            enableFourStep:false,
            consultaDescapli:[],
            actualDate: new Date().toLocaleString(),
            pagare:[],
            pagareSelected:[],
            nomterSelect:[],
            resultPagare:[],
            filter: '',
            type_consult:'individual',

            datames:[],
            fechaVinc:[],
            descapli:[],
            descnoap:[],
            datamesfidu:[],
            datamesseceduc:[],
            id_consulta:null
        }
    },
    computed: {
            filteredRows() {
                if(!this.resultPagare.entidad) return false;

                return this.resultPagare.entidad.filter((row) => {
                    const pagare = row.toString().toLowerCase();
                    const searchTerm = this.filter.toLowerCase();

                    return pagare.includes(searchTerm);
                });
            },
        },
    methods:{  
        getData(){
            this.getDatames();
            this.getFechaVinc();
            this.getDescapli();
            this.getDescnoap();
            this.getDatamesfidu();
            this.getDatamesseceduc();
        },
        getDatames(){
            axios.get(`datames/${this.dataclient.doc}`).then((response)=>{
                this.datames = response.data;
            });
        },
        getFechaVinc(){
            axios.get(`fechavinc/${this.dataclient.doc}`).then((response)=>{
                this.fechaVinc = response.data;
            });
        },
        getDescapli(){
            axios.get(`descapli/${this.dataclient.doc}`).then((response)=>{
                this.descapli = response.data;
            });
        },
        getDescnoap(){
            axios.get(`descnoap/${this.dataclient.doc}`).then((response)=>{
                console.log(response.data);
                this.descnoap = response.data;
            });
        },
        getDatamesfidu(){
            axios.post('/datamesfidu/consultaUnitaria',{doc: this.dataclient.doc}).then((response)=>{
                this.datamesfidu = response.data.data;                
            });
        },
        getDatamesseceduc(){
            axios.post('/datamesseceduc/consultaUnitaria',{doc: this.dataclient.doc}).then((response)=>{
                console.log(response.data);
                this.datamesseceduc = response.data.data;
            });
        },
        enableSteps(enable){
            if(enable === true){
                this.plan === 'premium';
                this.enableFirstStep=true;
                this.enableSecondStep=true;
                this.enableThirdStep=true;
                this.enableFourStep=true;
                this.sendPagare();
            }else{
                
            }
        },      
        getDataClient(){
            axios.post('consultaDescnoap',{data:this.dataclient}).then((response)=>{  
                if(response.data.message === 'El cliente seleccionado tiene inconsistencias.'){                    
                    this.consultaDescapli = response.data.data;                    
                }else{                      
                    axios.post('consultaUnitaria',{data:this.dataclient}).then((response)=>{
                        if(response.data.message === 'El cliente seleccionado tiene inconsistencias.'){
                            toastr.success(response.data.message);
                            this.consultaDescapli = response.data.data;                            
                        }else{                            
                            this.consultaDescapli = response.data.data;                            
                        }                        
                    }).catch((error)=>{
                        toastr.success(response.data.message);
                    });
                }
            }).catch((error)=>{
                console.log(error);
            });            
        },

        vAplicado(value, data, pagareSelect, nomterSelected){            
            if(value === true){
                this.pagareSelected.push(data)
            }
            
            this.dataclient.pagareSelected = this.pagareSelected;

            if(value === true){
                this.pagare.push(data);                
                this.nomterSelect.push(nomterSelected);
                this.dataclient.v_aplicado = this.pagare;                
                this.dataclient.nomterSelect = this.nomterSelect;                
            }else{                
                let pagare = this.pagare.filter(function(item) {
                    return item !== nomterSelected
                });
                this.dataclient.v_aplicado = pagare;
                
                let pagareSelected = this.pagareSelected.filter(function(item) {
                    return item.pagare !== pagareSelect
                });
                this.dataclient.pagareSelected = pagareSelected;

                let nomterSelect = this.nomterSelect.filter(function(item) {                
                    return item !== nomterSelected
                });
                this.dataclient.nomterSelect = nomterSelect.length === 0 ? nomterSelected : this.nomterSelect.push(nomterSelected);                                
            }
            console.log(this.dataclient);            
        },

        sendPagare(){            
            axios.post('resultadoAprobacion',{data:this.dataclient}).then((response)=>{
                toastr.success(response.data.message);                
                this.id_consulta = response.data.data.id_consulta;              
                this.resultPagare = response.data.data;
            }).catch((error)=>{
                console.log(error);
            })
        },

        print() {
            window.print();
        }
    }
}
</script>