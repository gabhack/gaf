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
                                        <th>FONDO</th>
                                        <th>TD DOCUMENTO</th>
                                        <th>DOCUMENTO</th>
                                        <th>X</th>
                                        <th>PENSIONADO (APELLIDOS Y NOMBRES)</th>
                                        <th>FECHA DE NACIMIENTO</th>
                                        <th>DIRECCION</th>
                                        <th>DPTO (NOMBRE DEPARTAMENTO de residencia del pensionado)</th>
                                        <th>MNPIO (NOMBRE MUNICIPIO de residencia del pensionado)</th>
                                        <th>TIPO_PENSION (NOMBRE_PENSION)</th>
                                        <th>NOMBRE_BANCO (NOMBRE DEL BANCO)</th>
                                        <th>SUCURSAL (NOMBRE DE LA SUCURSAL)</th>
                                        <th>TELÉFONO</th>
                                        <th>CELULAR</th>
                                        <th>CORREO ELECTRÓNICO</th>
                                        <th>*VALOR PENSIONES</th>
                                        <th>*VALOR SALUD</th>
                                        <th>*VALOR EMBARGOS</th>
                                        <th>*VALOR DESCUENTOS</th>
                                        <th>*CUPO</th>
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
                                        <th>DOCUMENTO</th>
                                        <th>VINCULACIÓN</th>
                                        <th>TIPO_PENSION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(fechavinc, key) in fechaVinc" :key="key">
                                        <td>{{fechavinc.doc}}</td>
                                        <td>{{fechavinc.vinc}}</td>
                                        <td>{{fechavinc.tp}}</td>
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
                                        <th>Periodo</th>
                                        <th>Consecutivo</th>
                                        <th>Clase Tercero</th>
                                        <th>Tercero</th>
                                        <th>Nombre del Tercero</th>
                                        <th>Tipo Documento</th>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Pagare</th>
                                        <th>Porcentaje</th>
                                        <th>Valor Aplicado</th>
                                        <th>Valor Total</th>
                                        <th>Valor Pagado</th>
                                        <th>Saldo</th>
                                        <th>Fecha Grabación</th>
                                        <th>Forma</th>
                                        <th>Código entidad anterior</th>
                                        <th>Nombre Entidad Anterior</th>
                                        <th>Fecha de Cesión</th>
                                        <th>Tipo Descuento</th>
                                        <th>PAGARE5DIG</th>
                                        <th>PAGARE4DIGCON0</th>
                                        <th>Numero pagare</th>
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
                                        <th>Clase Tercero</th>
                                        <th>Tercero</th>
                                        <th>Nombre del Tercero</th>
                                        <th>Tipo Documento</th>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Pagare</th>
                                        <th>Porcentaje</th>
                                        <th>Valor Fijo</th>
                                        <th>Valor Aplicado</th>
                                        <th>Valor Total</th>
                                        <th>Valor Pagado</th>
                                        <th>Saldo</th>
                                        <th>Fecha Grabación</th>
                                        <th>Forma</th>
                                        <th>Inconsistencia</th>
                                        <th>Código entidad anterior</th>
                                        <th>Nombre Entidad Anterior</th>
                                        <th>Fecha de Cesión</th>
                                        <th>Tipo Descuento</th>                                        
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
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Consultar</button>
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