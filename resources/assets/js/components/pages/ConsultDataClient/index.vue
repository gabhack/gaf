<template>
    <div class="container-fluid">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'form' ? 'nav-link active' : 'nav-link'" id="formClient-tab" data-toggle="tab" href="#formClient" :v-on:click="()=>changeTab('form')" role="tab" aria-controls="formClient" aria-selected="true">Formulario Cliente</a>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu1' && menu1Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu1-tab" data-toggle="tab" href="#menu1" :v-on:click="()=>changeTab('menu1')" role="tab" aria-controls="menu1" aria-selected="true">Fecha Vinculación</a>
            </li> -->
            <!-- <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu2' && menu2Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu2-tab" data-toggle="tab" href="#menu2" :v-on:click="()=>changeTab('menu2')" role="tab" aria-controls="menu2" aria-selected="false">Data Mes</a>
            </li> -->
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu3' && menu3Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu3-tab" data-toggle="tab" href="#menu3" :v-on:click="()=>changeTab('menu3')" role="tab" aria-controls="menu3" aria-selected="false">Descapli</a>
            </li>
            <!-- <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu4' && menu4Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu4-tab" data-toggle="tab" href="#menu4" :v-on:click="()=>changeTab('menu4')" role="tab" aria-controls="menu4" aria-selected="false">Descanoap</a>
            </li> -->

            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu5' && menu5Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu5-tab" data-toggle="tab" href="#menu5" :v-on:click="()=>changeTab('menu5')" role="tab" aria-controls="menu5" aria-selected="false">Resultado</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div :class="tabSelect === 'form' ? 'tab-pane fade show active' : 'tab-pane fade'" id="formClient" role="tabpanel" aria-labelledby="formClient-tab">
                <div class="form-group">
                    <label>Seleccione</label>
                    <select v-model="type" class="form-control">
                        <option value="individual">Individual</option>
                        <option value="bloque">Bloque</option>
                    </select>
                </div>
                <div class="card text-center" v-if="type === 'individual'">
                    <div class="card-header">
                        <h2>Individual</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Cedula</label>
                            <input v-model="dataclient.doc" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Nombre</label>
                            <input v-model="dataclient.nombre" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Pagaduria</label>
                            <input v-model="dataclient.pagaduria" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Tipo de Credito</label>
                            <input v-model="dataclient.tipo_de_credito" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Cupo Lib Inversión</label>
                            <input v-model="dataclient.cupo_lib_inversion" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Compras</label>
                            <input v-model="dataclient.compras" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>pagaré</label>
                            <input v-model="dataclient.pagare" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Vr Credito</label>
                            <input v-model="dataclient.vr_credito" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Vr Desembolso</label>
                            <input v-model="dataclient.vr_desembolso" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Plazo</label>
                            <input v-model="dataclient.plazo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Cuota Credito</label>
                            <input v-model="dataclient.cuota_cred" class="form-control">
                        </div>

                        <div class="form-group">
                            <button v-on:click="getDataClient" class="btn btn-primary">Consultar</button>
                        </div>

                        <div class="form-group">
                            <table class="table table-responsive table-striped table-hover" v-if="constultaDescnoap.length > 0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Nombre Tercero</th>
                                        <th>Pagare</th>
                                        <th>Inconveniente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(descnoap, key) in constultaDescnoap" :key="key">
                                        <td>{{descnoap.nomp}}</td>
                                        <td>{{descnoap.nomtercero}}</td>
                                        <td>{{descnoap.pagare}}</td>
                                        <td>{{descnoap.incon}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card text-center" v-else-if="type === 'bloque'">
                    <div class="card-header">
                        <h2>Bloque</h2>
                    </div>
                    <div class="card-body">
                        <p>Proximamente</p>
                    </div>
                </div>                
            </div>
            <!-- <div :class="tabSelect === 'menu1' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Vinculación</th>
                            <th>Tipo Pensión</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1026303251</td>
                            <td>13095b33b23</td>
                            <td>Vinculacion</td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            
            <!-- <div :class="tabSelect === 'menu2' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fondo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1026303251</td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            <div :class="tabSelect === 'menu3' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu3" role="tabpanel" aria-labelledby="menu3-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Entidad Anterior</th>
                            <th>Nombre Tercero</th>
                            <th>Pagare</th>
                            <th>Valor Aplicado</th>
                            <th>Selecciona</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(consultaD,key) in consultaDescapli" :key="key">
                            <td>{{consultaD.nonentant}}</td>
                            <td>{{consultaD.nomtercero}}</td>
                            <td>{{consultaD.pagare}}</td>
                            <td>{{consultaD.vaplicado}}</td>
                            <td>                            
                                <input type="checkbox" v-on:click="(e)=>vAplicado(e.target.checked, consultaD.vaplicado)"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button v-on:click="sendPagare" class="btn btn-primary">Continuar</button>
            </div>
            <!-- <div :class="tabSelect === 'menu4' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu4" role="tabpanel" aria-labelledby="menu4-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" v-on:click="() => vAplicado()"/>13095b33b23                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

            <div :class="tabSelect === 'menu5' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu5" role="tabpanel" aria-labelledby="menu5-tab">
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
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            
                            <td>
                                <ul>
                                    <li>12</li>
                                    <li>10</li>
                                    <li>12</li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li>15</li>
                                    <li>24</li>
                                    <li>12</li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li>Bancolombia</li>
                                    <li>AvVillas</li>
                                    <li>Banco Bogotá</li>
                                </ul>
                            </td>
                            
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                            <td>|</td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn-primary" v-on:click="newConsult">Nueva Consulta</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            dataclient:{},
            type:'',
            tabSelect:'form',
            menu1Disabled: true,  
            menu2Disabled: true,
            menu3Disabled: true,
            menu4Disabled: true,
            menu4Disabled: true,
            constultaDescnoap:[],
            consultaDescnoapli:[],
            consultaDescapli:[],
            pagare:[],
        }
    },
    methods:{
        changeTab(data){
            this.tabSelect = data;
        },
        getDataClient(){
            axios.post('consultaDescnoap',{data:this.dataclient}).then((response)=>{  
                if(response.data.message === 'El cliente seleccionado tiene inconsistencias.'){
                    toastr.success(response.data.message);               
                    this.constultaDescnoap = response.data.data;
                }else{  
                    toastr.success(response.data.message);
                    axios.post('consultaUnitaria',{data:this.dataclient}).then((response)=>{
                        if(response.data.message === 'El cliente seleccionado tiene inconsistencias.'){
                            toastr.success(response.data.message);
                            this.constultaDescnoap = response.data.data;
                        }else{
                            toastr.success(response.data.message);    
                            console.log(response.data.data);                        
                            this.consultaDescapli = response.data.data;
                            this.tabSelect = 'menu3';
                            this.menu3Disabled= false;
                        }                        
                    }).catch((error)=>{
                        toastr.success(response.data.message);
                    });
                }
            }).catch((error)=>{
                console.log(error);
            })            
        },
        vAplicado(value, data){
            if(value === true){
                this.pagare.push(data);
                this.dataclient.v_aplicado = this.pagare;
                console.log(this.dataclient);  
            }else{                
                let pagare = this.pagare.filter(function(item) {
                    return item !== data
                });
                this.dataclient.v_aplicado = pagare;
                console.log(this.dataclient);
            }
        },
        sendPagare(){
            axios.post('resultadoAprobacion',{data:this.dataclient}).then((response)=>{
                toastr.success(response.data.message);
                this.result = response.data.data;
                this.tabSelect = 'menu5';
                this.menu5Disabled = false;
            }).catch((error)=>{
                console.log(error);
            })
        },
        newConsult(){
            this.dataclient = {};
            this.type='';
            this.tabSelect='form';
            this.menu1Disabled= true;  
            this.menu2Disabled= true;
            this.menu3Disabled= true;
            this.menu4Disabled= true;
            this.menu4Disabled= true;
            this.constultaDescnoap=[];
            this.consultaDescnoapli=[];
            this.consultaDescapli=[];
            this.pagare=[];
        }
    }
}
</script>