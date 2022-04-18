<template>
    <section class="container-fluid">
        <div class=" col-lg-12 col-sm-12 align-items-center justify-content-center">
            <div class="text-center">
                <div class="card">
                    <div class="card-header">
                        <p>Solicitud Validación</p>
                    </div>
                    <!-- <div class="card-body" v-if="token !== null"> -->
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Tipo de Validación</label>
                                    <select v-model="solicitudVal.TipoValidacion" class="form-control">
                                        <option :value="1">Directa</option>
                                        <option :value="2">Asesor</option>
                                        <option :value="3">AutoGestionada</option>
                                        <option :value="4">Ambas</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Asesor</label>
                                    <input v-model="solicitudVal.Asesor" class="form-control">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Sede</label>
                                    <input v-model="solicitudVal.Sede" class="form-control">
                                </div>
                            </div>                            
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>Tipo Documento</label>
                                    <input v-model="solicitudVal.TipoDoc" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label># Documento</label>
                                    <input v-model="solicitudVal.NumDoc" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input v-model="solicitudVal.Email" type="email" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Celular</label>
                                    <input v-model="solicitudVal.Celular" type="number" class="form-control">
                                </div>
                            </div>       

                            <div class="form-group">
                                <label>Identificador unico de Convenio</label>
                                <input class="form-control" v-model="solicitudVal.GuidConv"/>
                            </div>                       

                            <button v-if="solicitudVal.Asesor" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" v-on:click="getSolicValidacion">Solicitar Validación</button>
                        </form> 
                        <table class="mt-3 table table-striped table-bordered table hover">
                            <thead>
                                <tr>
                                    <th>Tipo Documento</th>
                                    <th>Numero de Documento</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Consultar Validación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(val,key) in validateData" :key="key">                                    
                                    <td>{{val.TipoDoc}}</td>
                                    <td>{{val.NumDoc}}</td>                                    
                                    <td>{{val.Celular}}</td>
                                    <td>{{val.Email}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalResultConsultVal" v-on:click="consultarValidacion(val)">Consultar Validación</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="width:740px">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Verificacion de Identidad</h5>                            
                        </div>
                        <div class="modal-body"> -->                            
                            <div v-if="resultSolicVal.url">
                                <iframe :src="resultSolicVal.url" allowusermedia='allowusermedia' allow="camera" title="Inline Frame Example" width="700" height="700"></iframe>
                                <!-- <vue-iframe
                                    style="visibility: visible; border: none;height: 700px;"
                                    :src="resultSolicVal.url"
                                    allow="camera *;"
                                    frame-id="my-ifram"                                
                                    name="my-frame"
                                    width="700px"
                                    height="700px"
                                /> -->
                            </div>                            
                        <!-- </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>                            
                        </div>
                    </div>
                </div>
            </div> -->


            <!-- Modal -->
            <div class="modal fade bd-example-modal-xl" id="modalResultConsultVal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Detalle Consulta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>aprobado</th>
                                        <th>asesor</th>
                                        <th>cancelado</th>
                                        
                                        <th>encontrado En Fuente</th>
                                        <th>estado Proceso</th>
                                        <th>fecha Finalizacion</th>
                                        <th>fecha Registro</th>
                                        <th>finalizado</th>
                                        <th>guidConv</th>
                                        <th>nombreSede</th>

                                        <th>Celular</th>
                                        <th>Tipo Doumentoc</th>
                                        <th># Documento</th>
                                        <th>primer Apellido</th>
                                        <th>segundo Apellido</th>
                                        <th>primer Nombre</th>                                        
                                        <th>segundo Nombre</th>

                                        <th>procesoConvenioGuid</th>
                                        <th>scoreRostroDocumento</th>
                                        <th>sede</th>                                        
                                        

                                        <th>codigoCliente</th>
                                        <th>estadoDescripcion</th>
                                        <th>fuentesAbiertas</th>
                                        <th>motivoCancelacion</th>
                                        <th>scoreProceso</th>

                                        <!-- <th>Servicios</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>                                        
                                        <td>{{resultConsultVal.aprobado === true ? 'Aprobado' : 'No Aprobado'}}</td>
                                        <td>{{resultConsultVal.asesor}}</td>
                                        <td>{{resultConsultVal.cancelado === true ? 'Cancelado' : 'Sin Cancelar'}}</td>
                                        
                                        <td>{{resultConsultVal.encontradoEnFuente === true ? 'Encontrado' : 'Sin Encontrar'}}</td>
                                        <td>{{resultConsultVal.estadoProceso}}</td>
                                        <td>{{resultConsultVal.fechaFinalizacion}}</td>
                                        <td>{{resultConsultVal.fechaRegistro}}</td>
                                        <td>{{resultConsultVal.finalizado === true ? 'Finalizado' : 'Sin Finalizar'}}</td>
                                        <td>{{resultConsultVal.guidConv}}</td>
                                        <td>{{resultConsultVal.nombreSede}}</td>

                                        <td>{{resultConsultVal.celular}}</td>
                                        <td>{{resultConsultVal.tipoDoc}}</td>
                                        <td>{{resultConsultVal.numDoc}}</td>
                                        <td>{{resultConsultVal.primerApellido}}</td>
                                        <td>{{resultConsultVal.segundoApellido}}</td>
                                        <td>{{resultConsultVal.primerNombre}}</td>                                        
                                        <td>{{resultConsultVal.segundoNombre}}</td>

                                        <td>{{resultConsultVal.procesoConvenioGuid}}</td>
                                        <td>{{resultConsultVal.scoreRostroDocumento}}</td>
                                        <td>{{resultConsultVal.sede}}</td>
                                                                        
                                        <td>{{resultConsultVal.codigoCliente}}</td>
                                        <td>{{resultConsultVal.estadoDescripcion}}</td>
                                        <td>{{resultConsultVal.fuentesAbiertas}}</td>
                                        <td>{{resultConsultVal.motivoCancelacion}}</td>
                                        <td>{{resultConsultVal.scoreProceso}}</td>

                                        <!-- <td>
                                            <div v-for="(serv, key) in resultConsultVal.servicios" :key="key">
                                                <p>Servicio: {{serv.servicio}}</p>
                                                <p>subTipos: {{serv.subTipos}}</p>
                                                <p>terminado: {{serv.terminado === true ? 'Si' : 'No'}}</p>
                                                <p>tipo: {{serv.tipo}}</p>
                                                <p>Documento Valido: {{serv.documentIsValid === true ? 'Si': 'No'}}</p>
                                            </div>
                                        </td> -->
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    
</template>
<script>
    export default {
        modules: ['vue-iframes/nuxt'],
        data(){
            return{
                token: null,
                solicitudVal:{
                    Usuario:'CKCOMERCIALIZADORA_2022',
                    Clave:'CKComercializadora.2022*',
                },
                resultSolicVal:{},
                validateData:[],
                resultConsultVal:{}
            }
        },
        mounted(){
            this.getToken();
            this.getData();
        },
        methods:{
            getData(){
                axios.get('validate').then((response)=>{
                    console.log(response.data);
                    this.validateData = response.data;
                })
            },
            getToken(){
                let data={
                    clientId: "CKCOMERCIALIZADORA",
                    clientSecret: "CKC0M3RP@$$w0rd",
                };

                axios.post('https://demorcs.olimpiait.com:6314/TraerToken',data).then((response)=>{
                    this.token = response.data.accessToken;
                }).catch((error)=>{
                    console.log(error);
                });
            },

            getSolicValidacion(){
                // let data = {
                //     GuidConv: '575650aa-b5ed-4797-844d-6ee965e41786',
                //     TipoValidacion: 4,
                //     Asesor:'pruevav',
                //     Sede:'000100',                    
                //     TipoDoc:'CC',
                //     NumDoc:'1026307251',
                //     Email:'brayantriana22@gmail.com',
                //     Celular:'3007819686',          
                //     PrefCelular : "57",          
                //     Usuario:'CKCOMERCIALIZADORA_2022',
                //     Clave:'CKComercializadora.2022*',
                // };

                axios.post('https://demorcs.olimpiait.com:6314/Validacion/SolicitudValidacion', this.solicitudVal, {headers:{
                    'Authorization':`Bearer ${this.token}`
                }}).then((response)=>{
                    this.resultSolicVal = response.data.data;
                    this.solicitudVal.ProcesoConvenioGuid = response.data.data.procesoConvenioGuid;
                    axios.post('validate',this.solicitudVal).then((response)=>{
                        console.log(response.data);
                    })
                }).catch((error)=>{
                    console.log(error);
                })
            },
            consultarValidacion(data){
                let dataDinamic = {
                    GuidConv: data.GuidConv,                    
                    ProcesoConvenioGuid: data.ProcesoConvenioGuid,
                    CodigoCliente:'',         
                    Usuario:'CKCOMERCIALIZADORA_2022',
                    Clave:'CKComercializadora.2022*',
                }

                axios.post('https://demorcs.olimpiait.com:6314/Validacion/ConsultarValidacion', dataDinamic, {headers:{
                    'Authorization':`Bearer ${this.token}`
                }}).then((response)=>{
                    this.resultConsultVal = response.data.data;
                }).catch((error)=>{
                    console.log(error);
                })
            }
        }
    }
</script>