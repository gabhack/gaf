<template>
    <div class="container-fluid">        
        <div v-if="type_consult === 'individual'">
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">                
                    <div class="d-flex align-items-end">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3">
                        <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{dataclient.name}}</h2>
                    </div>       
                    <button v-if="id_consulta !== null" type="button" v-on:click="getPdf" class="btn btn-black-pearl px-3">
                        <span>Descargar PDF</span>
                        <download-icon></download-icon>
                    </button>             
                </div>
            </div>
            <div id="consulta-container" class="row">
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <b>Información básica</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-6">
                                    <b class="panel-label">Cedula:</b>                                
                                    <input class="form-control text-center" type="number" v-model="dataclient.doc">
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">Nombre:</b>                                
                                    <input class="form-control text-center" type="text" v-model="dataclient.name">
                                </div>
                                <div class="col-6">
                                    <b class="panel-label">PAGADURIA:</b>
                                    <select class="form-control" v-model="dataclient.pagaduria">
                                        <option value="FOPEP">FOPEP</option>
                                        <option value="FIDUPREVISORA">FIDUPREVISORA</option>
                                        <option value="FODE VALLE">FODE VALLE</option>                                        
                                    </select>                                    
                                </div>                                
                                <div class="col-6 mt-4">
                                    <button type="button" class="btn btn-primary" v-on:click="getData">Consultar</button>
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Información Pesonal (DATAMES)</b></div>
                        <div class="panel-body">      
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Fondo</th>
                                        <th>Tipo de documento</th>
                                        <th>Documento de identidad Pensionado</th>
                                        <th>Tipo y Cedula concatenadas (B)</th>
                                        <th>Nombre y apellido pensionado</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th>Dirección</th>
                                        <th>Departamento</th>
                                        <th>Municipio</th>
                                        <th>Tipo Pension</th>
                                        <th>Nombre Banco donde le cosignan</th>
                                        <th>Sucursal Banco</th>
                                        <th>Telefono</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Valor pension</th>
                                        <th>Valor salud</th>
                                        <th>Valor embargos Aproximado</th>
                                        <th>Valor descuentos</th>
                                        <th>Valor Cupo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(datames, key) in datames" :key="key">
                                        <td>{{datames.fondo}}</td>
                                        <td>{{datames.td}}</td>
                                        <td>{{datames.x}}</td>
                                        <td>{{datames.doc}}</td>
                                        <td>{{datames.nomp}}</td>
                                        <td>{{datames.fecnacimient}}</td>
                                        <td>{{datames.dir}}</td>
                                        <td>{{datames.dpto}}</td>
                                        <td>{{datames.mnpio}}</td>
                                        <td>{{datames.tp}}</td>
                                        <td>{{datames.nbanco}}</td>
                                        <td>{{datames.sucursal}}</td>
                                        <td>{{datames.tel}}</td>
                                        <td>{{datames.cel}}</td>
                                        <td>{{datames.correo}}</td>
                                        <td>{{datames.vpension}}</td>
                                        <td>{{datames.vsalud}}</td>
                                        <td>{{datames.venbargos}}</td>
                                        <td>{{datames.vdesc}}</td>
                                        <td>{{datames.cupo}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <b>(FECHAVINC)</b>
                        </div>
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Documento de identidad Pensionado</th>
                                        <th>Fecha de Vinculacion</th>
                                        <th>Tipo pension</th>
                                        <th>Fecha carga data</th>
                                        <th>Mes carga data</th>
                                        <th>Año carga data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <td>{{fechavinc.doc}}</td>
                                        <td>{{fechavinc.vinc}}</td>
                                        <td>{{fechavinc.tp}}</td>
                                        <td>{{fechavinc.fecdata}}</td>
                                        <td>{{fechavinc.mesdata}}</td>
                                        <td>{{fechavinc.anodata}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>            

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Obligaciones Aplicadas (DESCAPLI)</b></div>
                        <div class="panel-body">      
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Seleccionar</th>
                                        <th>Periodo de data</th>
                                        <th>Consecutivo (B)</th>
                                        <th>Tipo entidad</th>
                                        <th>Entidad (B)</th>
                                        <th>Nombre entidad actual</th>
                                        <th>Tipo Documento</th>
                                        <th>Documento de identidad Pensionado</th>
                                        <th>Nombre y apellido pensionado</th>
                                        <th>Numero de pagare</th>
                                        <th>Porcentaje (B)</th>
                                        <th>Valor total descuentos Aplicados</th>
                                        <th>Valor total deuda</th>
                                        <th>Valor pagado deuda</th>
                                        <th>Saldo deuda</th>
                                        <th>Fecha inicio deuda</th>
                                        <th>Forma (B)</th>
                                        <th>Codigo entidad anterior (B)</th>
                                        <th>Nombre entidad cediente</th>
                                        <th>Fecha de cesion a entidad actual</th>
                                        <th>Tipo de descuento</th>
                                        <th>PAGARE5DIG</th>
                                        <th>PAGARE4DIGCON0</th>
                                        <th>Numero pagare</th>
                                        <th>Fecha carga data</th>
                                        <th>Mes carga data</th>
                                        <th>Año carga data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(descapli, key) in descapli" :key="key">
                                        <td>
                                            <input type="checkbox" v-on:click="(e)=>vAplicado(e.target.checked, descapli, descapli.pagare, descapli.nomtercero)"/>
                                        </td>
                                        <td>{{descapli.periodo}}</td>
                                        <td>{{descapli.concecutivo}}</td>
                                        <td>{{descapli.clase}}</td>
                                        <td>{{descapli.tercero}}</td>
                                        <td>{{descapli.nomtercero}}</td>
                                        <td>{{descapli.td}}</td>
                                        <td>{{descapli.doc}}</td>
                                        <td>{{descapli.nomp}}</td>
                                        <td>{{descapli.pagare}}</td>
                                        <td>{{descapli.porcentaje}}</td>
                                        <td>{{descapli.vaplicado}}</td>
                                        <td>{{descapli.vtotal}}</td>
                                        <td>{{descapli.vpagado}}</td>
                                        <td>{{descapli.saldo}}</td>
                                        <td>{{descapli.fgrab}}</td>
                                        <td>{{descapli.forma}}</td>
                                        <td>{{descapli.codentiant}}</td>
                                        <td>{{descapli.nonentant}}</td>
                                        <td>{{descapli.fechacesion}}</td>
                                        <td>{{descapli.tdesc}}</td>
                                        <td>{{descapli.p5d}}</td>
                                        <td>{{descapli.p4d}}</td>
                                        <td>{{descapli.numpagopt}}</td>
                                        <td>{{descapli.fecdata}}</td>
                                        <td>{{descapli.mesdata}}</td>
                                        <td>{{descapli.anodata}}</td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Obligaciones no Aplicadas (DESCNOAPL)</b></div>
                        <div class="panel-body">      
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr> 
                                        <th>Seleccionar</th>                                       
                                        <th>Tipo entidad</th>
                                        <th>Entidad (B)</th>
                                        <th>Nombre entidad actual</th>
                                        <th>Tipo de documento</th>
                                        <th>Documento de identidad Pensionado</th>
                                        <th>Nombre y apellido pensionado</th>
                                        <th>Numero de pagare</th>
                                        <th>Porcentaje (B)</th>
                                        <th>Cuota deuda</th>
                                        <th>Valor descuento no aplicado</th>
                                        <th>Valor total deuda</th>
                                        <th>Valor pagado deuda</th>
                                        <th>Saldo deuda</th>
                                        <th>Fecha inicio deuda</th>
                                        <th>Forma (B)</th>
                                        <th>Inconsistencia</th>
                                        <th>Codigo entidad anterior (B)</th>
                                        <th>Nombre entidad cediente</th>
                                        <th>Fecha de cesion a entidad actual</th>
                                        <th>Tipo de descuento</th>     
                                        <th>Fecha carga data</th>
                                        <th>Mes carga data</th>
                                        <th>Año carga data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(descnoap, key) in descnoap" :key="key">
                                        <td>
                                            <input type="checkbox" v-on:click="(e)=>vAplicado(e.target.checked, descnoap, descnoap.pagare, descnoap.nomtercero)"/>
                                        </td>
                                        <td>{{descnoap.clase}}</td>
                                        <td>{{descnoap.tercero}}</td>
                                        <td>{{descnoap.nomercero}}</td>
                                        <td>{{descnoap.td}}</td>
                                        <td>{{descnoap.doc}}</td>
                                        <td>{{descnoap.nomp}}</td>
                                        <td>{{descnoap.pagare}}</td>
                                        <td>{{descnoap.porcentaje}}</td>
                                        <td>{{descnoap.vfijo}}</td>
                                        <td>{{descnoap.vaplicado}}</td>
                                        <td>{{descnoap.vtotal}}</td>
                                        <td>{{descnoap.vpagado}}</td>
                                        <td>{{descnoap.saldo}}</td>
                                        <td>{{descnoap.fgrab}}</td>
                                        <td>{{descnoap.forma}}</td>
                                        <td>{{descnoap.Incon}}</td>
                                        <td>{{descnoap.codentiant}}</td>
                                        <td>{{descnoap.nonentant}}</td>
                                        <td>{{descnoap.fechacesion}}</td>
                                        <td>{{descnoap.tdesc}}</td>
                                        <td>{{descnoap.fecdata}}</td>
                                        <td>{{descnoap.mesdata}}</td>
                                        <td>{{descnoap.anodata}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Consultar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel-heading">
                        <b>DATAMESFIDU</b>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive table-striped table-hover">
                            <thead>
                                <tr> 
                                    <th>Municipio</th>
                                    <th>Documento de identidad Pensionado</th>
                                    <th>Tipo de documento</th>
                                    <th>Descripcion tipo documento (B)</th>
                                    <th>Nombre(s) pensionado</th>
                                    <th>Apellido(s) pensionado</th>
                                    <th>Genero</th>
                                    <th>Estado civil</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Edad Pensionado</th>
                                    <th>Tipo de Vinculacion (B)</th>
                                    <th>Descripcion tipo de vinculacion</th>
                                    <th>Codigo Fuente recurso (B)</th>
                                    <th>Fuente de recurso</th>
                                    <th>Codigo dane departamento (B)</th>
                                    <th>Departamento</th>
                                    <th>Resolucion (B)</th>
                                    <th>Vinculacion</th>
                                    <th>Fecha expedicion resolucion (B)</th>
                                    <th>Valor pension</th>
                                    <th>Estado Pension (B)</th>
                                    <th>Documento de identidad beneficiario</th>
                                    <th>Tipo de documento Beneficiario</th>
                                    <th>Nombre beneficiario</th>
                                    <th>Telefono</th>
                                    <th>Direccion</th>
                                    <th>Correo</th>
                                    <th>Numero comprobante (B)</th>
                                    <th>Periodo de data</th>
                                    <th>Tipo pension codigo</th>
                                    <th>Fecha pago pension</th>
                                    <th>Sucursal Banco (B)</th>
                                    <th>Valor pension Con retroactivo (B)</th>
                                    <th>Valor descuentos en bruto Retroactivo (B)</th>
                                    <th>Valor pago en bruto Retroactivo (B)</th>
                                    <th>Fecha carga data</th>
                                    <th>Mes carga data</th>
                                    <th>Año carga data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(datamesfidu, key) in datamesfidu" :key="key">
                                    <td>{{datamesfidu.mnpio}}</td>
                                    <td>{{datamesfidu.doc}}</td>
                                    <td>{{datamesfidu.td}}</td>
                                    <td>{{datamesfidu.tdd}}</td>
                                    <td>{{datamesfidu.solonomp}}</td>
                                    <td>{{datamesfidu.soloapellp}}</td>
                                    <td>{{datamesfidu.genero}}</td>
                                    <td>{{datamesfidu.estcivil}}</td>
                                    <td>{{datamesfidu.fecnacimient}}</td>
                                    <td>{{datamesfidu.edad}}</td>
                                    <td>{{datamesfidu.tipvinc}}</td>
                                    <td>{{datamesfidu.desctipvinc}}</td>
                                    <td>{{datamesfidu.fuenrecurso}}</td>
                                    <td>{{datamesfidu.descfuenrecurso}}</td>
                                    <td>{{datamesfidu.numdep}}</td>
                                    <td>{{datamesfidu.dpto}}</td>
                                    <td>{{datamesfidu.resol}}</td>
                                    <td>{{datamesfidu.vinc}}</td>
                                    <td>{{datamesfidu.fechefect}}</td>
                                    <td>{{datamesfidu.vpension}}</td>
                                    <td>{{datamesfidu.estpens}}</td>
                                    <td>{{datamesfidu.docbenef}}</td>
                                    <td>{{datamesfidu.td}}</td>
                                    <td>{{datamesfidu.nombenef}}</td>
                                    <td>{{datamesfidu.tel}}</td>
                                    <td>{{datamesfidu.dir}}</td>
                                    <td>{{datamesfidu.correo}}</td>
                                    <td>{{datamesfidu.nomcomprob}}</td>
                                    <td>{{datamesfidu.periodo}}</td>
                                    <td>{{datamesfidu.tipvinc}}</td>
                                    <td>{{datamesfidu.fechpago}}</td>
                                    <td>{{datamesfidu.sucursal}}</td>
                                    <td>{{datamesfidu.vpension}}</td>
                                    <td>{{datamesfidu.vdescbruto}}</td>
                                    <td>{{datamesfidu.pagonetbruto}}</td>
                                    <td>{{datamesfidu.fecdata}}</td>
                                    <td>{{datamesfidu.mesdata}}</td>
                                    <td>{{datamesfidu.anodata}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <b>DATAMESSECEDUC</b>
                        </div>
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr> 
                                        <th>Documento de identidad docente </th>
                                        <th>Nombre y apellido docente </th>
                                        <th>Fecha Vinculacion </th>
                                        <th>Antigüedad Vinculacion (B) </th>
                                        <th>Fecha de nacimiento </th>
                                        <th>Edad Pensionado </th>
                                        <th>Area de desempeño </th>
                                        <th>Cargo </th>
                                        <th>Valor ingreso Aproximado </th>
                                        <th>Fecha Vinculacion </th>
                                        <th>Fecha de posesion (B) </th>
                                        <th>Tipo Vinculacion </th>
                                        <th>Estado laboral </th>
                                        <th>Centro de educacion </th>
                                        <th>Municipio y Departamento </th>
                                        <th>Telefono </th>
                                        <th>Direccion </th>
                                        <th>Correo </th>
                                        <th>Sede en que presta el servicio </th>
                                        <th>Fecha carga data</th>
                                        <th>Mes carga data</th>
                                        <th>Año carga data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(datamesseceduc, key) in datamesseceduc" :key="key">
                                        <td>{{datamesseceduc.doc}}</td>
                                        <td>{{datamesseceduc.nomp}}</td>
                                        <td>{{datamesseceduc.fechingr}}</td>
                                        <td>{{datamesseceduc.antiguedad}}</td>
                                        <td>{{datamesseceduc.fecnacimient}}</td>
                                        <td>{{datamesseceduc.edad}}</td>
                                        <td>{{datamesseceduc.esquema}}</td>
                                        <td>{{datamesseceduc.cargo}}</td>
                                        <td>{{datamesseceduc.vpension}}</td>
                                        <td>{{datamesseceduc.fecnombr}}</td>
                                        <td>{{datamesseceduc.fecposesion}}</td>
                                        <td>{{datamesseceduc.nivcontr}}</td>
                                        <td>{{datamesseceduc.estlaboral}}</td>
                                        <td>{{datamesseceduc.centrocosto}}</td>
                                        <td>{{datamesseceduc.mnpioydep}}</td>
                                        <td>{{datamesseceduc.tel}}</td>
                                        <td>{{datamesseceduc.dir}}</td>
                                        <td>{{datamesseceduc.correo}}</td>
                                        <td>{{datamesseceduc.sedecoleg}}</td>
                                        <td>{{datamesseceduc.fecdata}}</td>
                                        <td>{{datamesseceduc.mesdata}}</td>
                                        <td>{{datamesseceduc.anodata}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" v-if="resultPagare.cuota_compra && resultPagare.cuota_compra.length > 0">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <b>CALCULO DE COMPRA DE CARTERA</b>
                        </div>
                        <div class="panel-body">
                            <table id="consulta" class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>                            
                                        <th>Consecutivo</th>	
                                        <th>Estado</th>	
                                        <th>Fecha consulta</th>	
                                        <th>Cedula</th>	
                                        <th>Nombre</th>	
                                        <th>Tipo de credito</th>	
                                        <th>Cupo Lib Inversion</th>	
                                        
                                        <th>Cuota Compra</th>	                            
                                        <th>Entidad</th>	
                                        <th>Pagaduria</th>	
                                        
                                        <th>Vr. Credito</th>	
                                        <th>Vr. Desembolso</th>	
                                        <th>Plazo</th>	
                                        <th>Cuota Cred</th>
                                        <th>Aprobado</th>	
                                        <th>% de incorporacion</th>	
                                        <th>Cuota Maxima de incorporacion</th>	
                                        <th>Fecha respuesta consulta</th>	
                                        <th>Fecha Vinculacion</th>	
                                        <th>Tipo Vinculacion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{resultPagare.consecutivo}}</td>
                                        <td>{{resultPagare.estado}}</td>
                                        <td>{{resultPagare.fecha_consulta}}</td>
                                        <td>{{resultPagare.doc}}</td>
                                        <td>{{resultPagare.nombre}}</td>
                                        <td>{{dataclient.tipo_credito}}</td>
                                        <td>{{dataclient.clibinv}}</td>
                                        <td>        
                                            <div v-for="(libInv, key) in resultPagare.cuota_compra" :key="key">
                                                <p>{{libInv.vaplicado}}</p><br/>
                                            </div>                                
                                        </td>                            
                                        <td>
                                            <div v-for="(row, index) in filteredRows" :key="index">
                                                <p>{{row}}</p><br/>
                                            </div>
                                        </td>
                                        <td>                                                
                                            <p>{{resultPagare.pagaduria}}</p><br/>                                            
                                        </td>
                                        
                                        <td>{{resultPagare.vr_credito}}</td>
                                        <td>{{resultPagare.vr_desembolso}}</td>
                                        <td>{{resultPagare.plazo}}</td>
                                        <td>{{resultPagare.cuota_cred}}</td>
                                        

                                        <td>{{resultPagare.aprobado}}</td>
                                        <td>{{resultPagare.pct_incorporacion}}</td>
                                        <td>{{resultPagare.max_incorporacion}}</td>
                                        <td>{{resultPagare.fec_rta_consulta}}</td>
                                        <td>{{resultPagare.fecha_vinculacion}}</td>
                                        <td>{{resultPagare.tipo_vinculacion}}</td>                            
                                    </tr>
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>
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
<script>
import { jsPDF } from "jspdf";
export default {
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

        getPdf(){
            // consulta
            axios.post('pdfDetalle',{id_consulta:this.id_consulta}).then((response)=>{
                var doc = new jsPDF();
                doc.html(response.data, {
                    callback: function (doc) {
                        doc.save("Informe");
                    },
                    x: 15,
                    y: 15,
                    width: 170,
                    weight:12,
                });
            });                        
        }
    }
}
</script>