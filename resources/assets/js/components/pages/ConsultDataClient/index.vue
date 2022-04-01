<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12 d-flex align-items-center justify-content-between">                
                <button type="button" onclick="print();" class="btn btn-black-pearl px-3">
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
                                <b class="panel-label">Nombre Completo:</b>                                
                                <input class="form-control text-center" type="text" v-model="dataclient.nombre">
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Pagaduría:</b>
                                <input class="form-control text-center" type="number" v-model="dataclient.pagaduria">
                            </div>
                            <div class="col-6 mt-4">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"  v-on:click="getDataClient">Consultar</button>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6" v-if="consultaDescapli.length > 0">
                <div class="panel">
                    <div class="panel-heading">
                        <b>Resultado</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Fecha de Consulta:</b>
                                <td><input class="text-center" type="text" disabled :placeholder="actualDate"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Correo:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Cedula:</b>
                                <td><input class="text-center" type="text" disabled :placeholder="consultaDescapli[0].doc"></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Nombre:</b>
                                <td><input class="text-center" type="text" disabled :placeholder="consultaDescapli[0].nomp"></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Pagaduria:</b>
                                <td>
                                    <tr v-for="(consultaD,key) in consultaDescapli" :key="key">
                                        <td>
                                            <input type="checkbox" v-on:click="(e)=>vAplicado(e.target.checked, consultaD.vaplicado, consultaD.pagare, consultaD.nomtercero)"/><input class="text-center" type="text" disabled :placeholder="consultaD.pagare">
                                        </td>                                        
                                    </tr>                                    
                                </td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Inconsistencia:</b>
                                <td>
                                    <tr v-for="(consultaD,key) in consultaDescapli" :key="key">
                                        <td>
                                            <input class="text-center" type="text" disabled :placeholder="consultaD.incon ? consultaD.incon : ''">
                                        </td>                                        
                                    </tr>                                    
                                </td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Score:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>                                

                            <div class="col-6">
                                <b class="panel-label">Vr Credito:</b>
                                <td><input type="number" v-model="dataclient.vr_credito" class="text-center"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Vr Desembolso:</b>
                                <td><input type="number" v-model="dataclient.vr_desembolso" class="text-center"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Plazo</b>
                                <td><input type="number" v-model="dataclient.plazo" class="text-center"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Cuota Credito</b>
                                <td><input type="number" v-model="dataclient.cuota_cred" class="text-center"></td>
                            </div>

                            <div class="col-6 mt-4" v-if="plan === 'basico'">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1">Tenemos Mas Información para tí</button>
                            </div>                      
                        </div>
                    </div>
                </div>
            </div>            

            <div class="col-md-6" v-if="plan === 'premium'  || enableFirstStep === true">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Información General</b></div>
                    <div class="panel-body">      
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Cedula:</b>
                                <td><input class="text-center" type="text" disabled v-model="consultaDescapli[0].doc"></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Nombre Completo:</b>
                                <td><input class="text-center" type="text" disabled v-model="consultaDescapli[0].nomp"></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Telefono Celular:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.celphone"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Telefono Fijo:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.phone"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Edad:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.age"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Cargo:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.charge"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Tipo de Pension:</b>
                                <td><input class="text-center" type="text" disabled v-model="resultPagare[0].tipo_vinculacion"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Antiguedad:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.antiguedad"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Correo Electronico:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.email"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Dirección :</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.address"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Ciudad:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.city"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Barrio:</b>
                                <td><input class="text-center" type="text" disabled v-model="dataclient.barrio"></td>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" v-if="plan === 'premium'  || enableSecondStep === true">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Obligaciones Aplicadas</b></div>
                    <div class="panel-body">      
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Entidad:</b>
                                <td><input class="text-center" v-for="(result, key) in resultPagare.entidad" :key="key" type="text" disabled :placeholder="result"></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Numero Obligación:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Valor de Cuota:</b>
                                <td><input class="text-center" type="text" disabled :placeholder="resultPagare.cuota_cred"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Valor de Credito:</b>
                                <td><input class="text-center" type="text" disabled: :placeholder="resultPagare.vr_credito"></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Valor Pagado:</b>
                                <td><input class="text-center" type="text" disabled :placeholder="resultPagare.vr_desembolso"></td>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" v-if="plan === 'premium'  || enableThirdStep === true">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Obligaciones no Descontadas</b></div>
                    <div class="panel-body">      
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Entidad:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Numero Obligación:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Valor de Cuota:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Valor de Credito:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>

                            <div class="col-6">
                                <b class="panel-label">Valor Pagado:</b>
                                <td><input class="text-center" type="text" disabled></td>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" v-if="plan === 'premium' || enableFourStep === true">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <b>INFORMACION FINANCIERA INGRESOS PROBABLES DEL CLIENTE</b>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive table-striped table-hover">
                            <thead>
                                <tr>                            
                                    <th>Consecutivo</th>	
                                    <th>Estado</th>	
                                    <th>Fecha consulta</th>	
                                    <th>Cedula</th>	
                                    <th>Nombre</th>	
                                    <th>Pagaduria</th>	
                                    <th>Tipo de credito</th>	
                                    <th>Cupo Lib Inversion</th>	
                                    
                                    <th>Cuota Compra</th>	                            
                                    <th>Entidad</th>	
                                    <th>Pagare</th>	
                                    
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
                                    <td>{{resultPagare.pagaduria}}</td>
                                    <td>{{resultPagare.tipo_de_credito}}</td>
                                    <td>{{resultPagare.cupo_lib_inversion}}</td>
                                    <td>        
                                        <div v-for="(libInv, key) in resultPagare.cuota_compra" :key="key">
                                            <p>{{libInv}}</p><br/>
                                        </div>                                
                                    </td>                            
                                    <td>
                                        <div v-for="(libInv, key) in resultPagare.entidad" :key="key">
                                            <p>{{libInv}}</p><br/>
                                        </div>
                                    </td>
                                    <td>
                                        <div v-for="(libInv, key) in resultPagare.pagare" :key="key">
                                            <p>{{libInv}}</p><br/>
                                        </div>
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

        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModal1Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">   
                        <h5 class="modal-title" id="exampleModal1Label">Todo va bien</h5>                     
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <b>¡queremos preguntarte si deseas conocer mas detalles de tu consulta!</b>
                        <div class="row text-center">
                            <div class="col-6">
                                <button class="btn btn-primary btn-block" v-on:click="enableSteps(true)">Si</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary btn-block" v-on:click="enableSteps(false)">No</button>
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
        }
    },
    methods:{  
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
            let pagares=[]      
            
            this.consultaDescapli.forEach(element => {
                if(element.pagare === pagareSelect && value === true){
                    pagares.push({
                        pagare: element.pagare,
                        selected: true
                    })
                }else{
                    pagares.push({
                        pagare: element.pagare,
                        selected: false
                    })
                }
            });
            this.dataclient.pagareSelected = pagares;

            if(value === true){
                this.pagare.push(data);
                
                this.nomterSelect.push(nomterSelected);

                this.dataclient.v_aplicado = this.pagare;
                
                this.dataclient.nomterSelect = this.nomterSelect;                
            }else{                
                let pagare = this.pagare.filter(function(item) {
                    return item !== data
                });
                this.dataclient.v_aplicado = pagare;
                
                let nomterSelect = this.nomterSelect.filter(function(item) {                
                    return item !== nomterSelected
                });
                this.dataclient.nomterSelect = nomterSelect.length === 0 ? nomterSelected : this.nomterSelect.push(nomterSelected);                
            }
        },

        sendPagare(){            
            axios.post('resultadoAprobacion',{data:this.dataclient}).then((response)=>{
                toastr.success(response.data.message);
                this.resultPagare = response.data.data;
            }).catch((error)=>{
                console.log(error);
            })
        }
    }
}
</script>