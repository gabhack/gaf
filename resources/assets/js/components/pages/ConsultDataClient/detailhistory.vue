<template>
    <div class="container-fluid">        
        <div>
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">                
                    <div class="d-flex align-items-end">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3">
                        <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{detalleConsulta.nombre}}</h2>
                    </div>       
                    <button type="button" v-on:click="print" class="btn btn-black-pearl px-3">
                        <span>Descargar PDF</span>
                        <download-icon></download-icon>
                    </button>             
                </div>
            </div>

            <div id="consulta-container" class="row">
                <div class="panel mb-3">
                    <div class="panel-heading">
                        <b>Información básica</b>
                    </div>
                    <div class="panel-body" v-if="detalleConsulta.nombre">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Cedula:</b>                                
                                <input disabled class="form-control text-center" type="number" v-model="detalleConsulta.ced">
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Nombre:</b>                                
                                <input disabled class="form-control text-center" type="text" v-model="detalleConsulta.nombre">
                            </div>
                            <div class="col-6">
                                <b class="panel-label">PAGADURIA:</b>
                                <select disabled class="form-control" v-model="detalleConsulta.pagaduria">
                                    <option value="FOPEP">FOPEP</option>
                                    <option value="FIDUPREVISORA">FIDUPREVISORA</option>
                                    <option value="FODE VALLE">FODE VALLE</option>                                        
                                </select>                                    
                            </div>                                                                                                            
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="panel mb-3">
                        <div class="panel-heading">
                            <b>Información Personal</b>
                        </div>
                        <div class="panel-body" v-if="datames.fondo">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">Fondo:</b>
                                    <div>
                                        <p class="panel-value">{{datames.fondo}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Tipo de documento:</b>
                                    <div>
                                        <p class="panel-value">{{datames.td}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Documento de identidad Pensionado:</b>
                                    <div>
                                        <p class="panel-value">{{datames.doc}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Tipo y Cedula concatenadas:</b>
                                    <div>
                                        <p class="panel-value">{{datames.x}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Nombre y apellido pensionado:</b>
                                    <div>
                                        <p class="panel-value">{{datames.nomp}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Fecha de Nacimiento:</b>      
                                    <div>
                                        <p class="panel-value">{{datames.fecnacimient}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Dirección:</b>
                                    <div>
                                        <p class="panel-value">{{datames.dir}}</p>
                                    </div>                                
                                </div> <div class="col-6">
                                    <b class="panel-label">Departamento:</b>
                                    <div>
                                        <p class="panel-value">{{datames.dpto}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Municipio:</b>
                                    <div>
                                        <p class="panel-value">{{datames.mnpio}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Tipo Pension:</b>
                                    <div>
                                        <p class="panel-value">{{datames.tp}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Nombre Banco donde le cosignan:</b>
                                    <div>
                                        <p class="panel-value">{{datames.nbanco}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Sucursal Banco:</b>
                                    <div>
                                        <p class="panel-value">{{datames.sucursal}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Telefono:</b>
                                    <div>
                                        <p class="panel-value">{{datames.tel}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Celular:</b>
                                    <div>
                                        <p class="panel-value">{{datames.cel}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Correo:</b>
                                    <div>
                                        <p class="panel-value">{{datames.correo}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Valor pension:</b>
                                    <div>
                                        <p class="panel-value">{{datames.vpension}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Valor salud:</b>
                                    <div>
                                        <p class="panel-value">{{datames.vsalud}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Valor embargos Aproximado:</b>
                                    <div>
                                        <p class="panel-value">{{datames.venbargos}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Valor descuentos:</b>
                                    <div>
                                        <p class="panel-value">{{datames.vdesc}}</p>
                                    </div>                                
                                </div>
                                <div class="col-md-6">
                                    <b class="panel-label">Valor Cupo:</b>
                                    <div>
                                        <p class="panel-value">{{datames.cupo}}</p>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="panel mb-3">
                        <div class="panel-heading">
                            <b>FECHAVINC</b>
                        </div>
                        <div class="panel-body" v-if="fechaVinc.doc">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">Documento de identidad Pensionado:</b>
                                    <div>
                                        <p class="panel-value">{{fechaVinc.doc}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Fecha de Vinculacion:</b>
                                    <div>
                                        <p class="panel-value">{{fechaVinc.vinc}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Tipo pension:</b>
                                    <div>
                                        <p class="panel-value">{{fechaVinc.tp}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Fecha carga data:</b>
                                    <div>
                                        <p class="panel-value">{{fechaVinc.fecdata}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Mes carga data:</b>
                                    <div>
                                        <p class="panel-value">{{fechaVinc.mesdata}}</p>
                                    </div>                                
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Año carga data:</b>
                                    <div>
                                        <p class="panel-value">{{fechaVinc.anodata}}</p>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>

                <div class="col-md-6">
                    <div class="panel panel-primary mb-3">
                        <div class="panel-heading"><b>Obligaciones Seleccionadas</b></div>
                            <div class="panel-body" v-if="obligacionSelected !== null">
                                <div class="row">
                                    <div class="col-6">
                                        <b class="panel-label">Seeccionar Periodo de data:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <input type="checkbox" class="mr-2" v-on:click="(e)=>vAplicado(e.target.checked, obligacionSelected, obligacionSelected.pagare, obligacionSelected.nomtercero)"/><p class="panel-value">{{obligacionSelected.periodo}}</p>                                        
                                        </div>
                                    </div>
                                    <!-- <div class="col-6">
                                        <b class="panel-label">Consecutivo (B):</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.concecutivo}}</p>
                                        </div>                                
                                    </div> -->
                                    <div class="col-6">
                                        <b class="panel-label">Tipo entidad:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.clase}}</p>
                                        </div>                                
                                    </div>
                                    <!-- <div class="col-6">
                                        <b class="panel-label">Entidad (B):</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.tercero}}</p>
                                        </div>                                
                                    </div> -->
                                    <div class="col-6">
                                        <b class="panel-label">Nombre entidad actual:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.nomtercero}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Tipo Documento:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.td}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Documento de identidad Pensionado:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.doc}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Nombre y apellido pensionado:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.nomp}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Numero de pagare:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.pagare}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Porcentaje (B):</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.porcentaje}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Valor total descuentos Aplicados:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.vaplicado}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Valor total deuda:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.vtotal}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Valor pagado deuda:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.vpagado}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Saldo deuda:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.saldo}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha inicio deuda:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.fgrab}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Forma (B):</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.forma}}</p>
                                        </div>                                
                                    </div> -->

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Codigo entidad anterior (B):</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.codentiant}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Nombre entidad cediente:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.nonentant}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha de cesion a entidad actual:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.fechacesion}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Tipo de descuento:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.tdesc}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">PAGARE5DIG:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.p5d}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">PAGARE4DIGCON0:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.p4d}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Numero pagare:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.numpagopt}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha carga data:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.fecdata}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Mes carga data:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.mesdata}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Año carga data:</b>
                                        <div v-for="(obligacionSelected, key) in obligacionSelected" :key="key">
                                            <p class="panel-value">{{obligacionSelected.anodata}}</p>
                                        </div>                                
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-primary mb-3">
                            <div class="panel-heading"><b>DATAMESFIDU</b></div>
                            <div class="panel-body" v-if="datamesfidu.length > 0">
                                <div class="row">
                                    <div class="col-6">
                                        <b class="panel-label">Municipio:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.mnpio}}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <b class="panel-label">Documento de identidad Pensionado:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.doc}}</p>
                                        </div>                                
                                    </div>
                                    <div class="col-6">
                                        <b class="panel-label">Tipo de documento:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.td}}</p>
                                        </div>                                
                                    </div>
                                    <!-- <div class="col-6">
                                        <b class="panel-label">Descripcion tipo documento (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.tdd}}</p>
                                        </div>                                
                                    </div> -->
                                    <div class="col-6">
                                        <b class="panel-label">Nombre(s) pensionado:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.solonomp}}</p>
                                        </div>                                
                                    </div>
                                    <div class="col-6">
                                        <b class="panel-label">Apellido(s) pensionado:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.soloapellp}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Genero:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.genero}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Estado civil:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.estcivil}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha de nacimiento:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.fecnacimient}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Edad Pensionado:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.edad}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Tipo de Vinculacion (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.tipvinc}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Descripcion tipo de vinculacion:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.desctipvinc}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Codigo Fuente recurso (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.fuenrecurso}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Fuente de recurso:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.descfuenrecurso}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Codigo dane departamento (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.numdep}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Departamento:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.dpto}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Resolucion (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.resol}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Vinculacion:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.vinc}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Fecha expedicion resolucion (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.fechefect}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Valor pension:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.vpension}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Estado Pension (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.estpens}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Documento de identidad beneficiario:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.docbenef}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Tipo de documento Beneficiario:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.td}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Nombre beneficiario:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.nombenef}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Telefono:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.tel}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Direccion:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.dir}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Correo:</b>
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

                                    <div class="col-6">
                                        <b class="panel-label">Periodo de data:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.periodo}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Tipo pension codigo:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.tipvinc}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha pago pension:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.fechpago}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Sucursal Banco (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.sucursal}}</p>
                                        </div>                                
                                    </div> -->

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Valor pension Con retroactivo (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.vpension}}</p>
                                        </div>                                
                                    </div> -->

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Valor descuentos en bruto Retroactivo (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.vdescbruto}}</p>
                                        </div>                                
                                    </div> -->

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Valor pago en bruto Retroactivo (B):</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.pagonetbruto}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Fecha carga data:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.fecdata}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Mes carga data:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.mesdata}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Año carga data:</b>
                                        <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                            <p class="panel-value">{{datamesfidu.anodata}}</p>
                                        </div>                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="panel panel-primary mb-3">
                            <div class="panel-heading"><b>DATAMESSECEDUC</b></div>
                            <div class="panel-body" v-if="datamesseceduc.length > 0">
                                <div class="row">
                                    <div class="col-6">
                                        <b class="panel-label">Documento de identidad docente:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.doc}}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <b class="panel-label">Nombre y apellido docente:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.nomp}}</p>
                                        </div>                                
                                    </div>
                                    <div class="col-6">
                                        <b class="panel-label">Fecha Ingreso:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.fechingr}}</p>
                                        </div>                                
                                    </div>
                                    <!-- <div class="col-6">
                                        <b class="panel-label">Antigüedad Vinculacion (B) :</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.antiguedad}}</p>
                                        </div>                                
                                    </div> -->
                                    <div class="col-6">
                                        <b class="panel-label">Fecha de nacimiento:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.fecnacimient}}</p>
                                        </div>                                
                                    </div>
                                    <div class="col-6">
                                        <b class="panel-label">Edad Pensionado:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.edad}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Area de desempeño:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.esquema}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Cargo:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.cargo}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Valor ingreso Aproximado:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.vpension}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha Vinculacion:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.fecnombr}}</p>
                                        </div>                                
                                    </div>

                                    <!-- <div class="col-6">
                                        <b class="panel-label">Fecha de posesion (B):</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.fecposesion}}</p>
                                        </div>                                
                                    </div> -->

                                    <div class="col-6">
                                        <b class="panel-label">Tipo Vinculacion:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.nivcontr}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Estado laboral:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.estlaboral}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Centro de educacion:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.centrocosto}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Municipio y Departamento:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.mnpioydep}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Telefono:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.tel}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Direccion:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.dir}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Correo:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.correo}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Sede en que presta el servicio:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.sedecoleg}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Fecha carga data:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.fecdata}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Mes carga data:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.mesdata}}</p>
                                        </div>                                
                                    </div>

                                    <div class="col-6">
                                        <b class="panel-label">Año carga data:</b>
                                        <div v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                            <p class="panel-value">{{datamesseceduc.anodata}}</p>
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
<script>
export default {
    props: ["id"],
    data() {
        return {
            detalleConsulta:{},
            datames:[],
            fechaVinc:[],
            obligacionSelected:null,
            datamesfidu:[],
            datamesseceduc:[],   
        }
    },
    mounted(){
        this.getData();
    },
    methods:{  
        getData(){
            axios.post('detalleConsulta',{id:this.id}).then((result)=>{
                console.log(result.data.data);

                this.detalleConsulta = result.data.data.detalle_consulta;
                this.datames = result.data.data.info_datames;
                this.fechaVinc = result.data.data.info_fechavinc;
                this.obligacionSelected = result.data.data.info_obligaciones;

                this.datamesfidu = result.data.data.datamesfidu;
                this.datamesseceduc = result.data.data.datamesseceduc;

            })
        },
        print() {
            window.print();
        }
    }
}
</script>