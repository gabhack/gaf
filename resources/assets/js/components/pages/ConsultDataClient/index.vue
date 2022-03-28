<template>
    <div class="container-fluid">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'form' ? 'nav-link active' : 'nav-link'" id="formClient-tab" data-toggle="tab" href="#formClient" :v-on:click="()=>changeTab('form')" role="tab" aria-controls="formClient" aria-selected="true">Formulario Cliente</a>
            </li>
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu1' && menu1Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu1-tab" data-toggle="tab" href="#menu1" :v-on:click="()=>changeTab('menu1')" role="tab" aria-controls="menu1" aria-selected="true">Fecha Vinculación</a>
            </li>
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu2' && menu2Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu2-tab" data-toggle="tab" href="#menu2" :v-on:click="()=>changeTab('menu2')" role="tab" aria-controls="menu2" aria-selected="false">Data Mes</a>
            </li>
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu3' && menu3Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu3-tab" data-toggle="tab" href="#menu3" :v-on:click="()=>changeTab('menu3')" role="tab" aria-controls="menu3" aria-selected="false">Descapli</a>
            </li>
            <li class="nav-item" role="presentation">
                <a :class="tabSelect === 'menu4' && menu4Disabled === false ? 'nav-link active' : 'nav-link disabled'" id="menu4-tab" data-toggle="tab" href="#menu4" :v-on:click="()=>changeTab('menu4')" role="tab" aria-controls="menu4" aria-selected="false">Descanoap</a>
            </li>

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
                            <input v-model="dataclient.cc" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <button v-on:click="getDescnoap" class="btn btn-primary">Consultar</button>
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
            <div :class="tabSelect === 'menu1' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
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
            </div>
            
            <div :class="tabSelect === 'menu2' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fondo</th>
                            <th>Tipo de Documento</th>
                            <th>x</th>
                            <th>Pensionado</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Dirección</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Tipo Pensión</th>
                            <th>Nombre Banco</th>
                            <th>Sucursal</th>
                            <th>Telefono</th>
                            <th>Celular</th>
                            <th>Correo</th>
                            <th>Valor Pensiones</th>
                            <th>Valor Salud</th>
                            <th>Valor Embargos</th>
                            <th>Valor Descuento</th>
                            <th>Cupo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1026303251</td>
                            <td>13095b33b23</td>
                            <td>Vinculacion</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div :class="tabSelect === 'menu3' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu3" role="tabpanel" aria-labelledby="menu3-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Periodo</th>
                            <th>Consecutivo</th>
                            <th>Clase Tercero</th>
                            <th>Tercero</th>
                            <th>Nombre del Tercero</th>
                            <th>Tipo de Documento</th>
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
                            <th>Codigo Entidad Anterior</th>
                            <th>Nombre Entidad Anterior</th>
                            <th>Fecha de Cesión</th>
                            <th>Tipo Descuento</th>
                            <th>Pagare 5 Dig</th>
                            <th>PAGARE4DIGCON0</th>
                            <th>Numero pagare</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1026303251</td>
                            <td>13095b33b23</td>
                            <td>Vinculacion</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>
                                <input type="checkbox"/>
                                1026303251
                            </td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>1026303251</td>
                            <td>13095b33b23</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div :class="tabSelect === 'menu4' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu4" role="tabpanel" aria-labelledby="menu4-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Clase Tercero</th>
                            <th>Tercero</th>
                            <th>Nombre Tercero</th>
                            <th>Tipo de Documento</th>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Pagare</th>
                            <th>Porcentaje</th>
                            <th>Valor Fijo</th>
                            <th>Valor Aplicado</th>
                            <th>Valor Total</th>
                            <th>Valor Pagad0</th>
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
                        <tr>
                            <td>1026303251</td>
                            <td>13095b33b23</td>
                            <td>Vinculacion</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>
                                <input type="checkbox" v-on:click="() => getPagare()"/>13095b33b23                                
                            </td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                            <td>13095b33b23</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div :class="tabSelect === 'menu5' ? 'tab-pane fade show active' : 'tab-pane fade'" id="menu5" role="tabpanel" aria-labelledby="menu5-tab">
                <table class="table table-responsive table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Aprobado</th>
                            <th>% de incorporacion</th>
                            <th>Cuota Maxima de incorporacion</th>
                            <th>Fecha resp.</th>
                            <th>Fecha Vinculacion</th>
                            <th>Tipo Vinculacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Si</td>
                            <td>2</td>
                            <td>40</td>
                            <td>10-10-2020</td>
                            <td>11-11-2021</td>
                            <td>Directa</td>
                        </tr>
                    </tbody>
                </table>
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
            menu4Disabled: true         
        }
    },
    mounted(){
        
    },
    methods:{
        changeTab(data){
            console.log(data);
            this.tabSelect = data;
        },
        getDataClient(){
            console.log(this.dataclient);        
            this.tabSelect='menu1';
            this.menu1Disabled = false
            
            axios.post('').then((response)=>{
            
            }).catch((error)=>{

            })            
        },
        getFechaVinc(){
            this.tabSelect='menu2';
            this.menu2Disabled = false
            axios.get('').then((response)=>{

            }).catch((error)=>{
                console.log(error);
            })
        },

        getDataMes(){
            this.tabSelect='menu3';
            this.menu3Disabled = false
            axios.get('').then((response)=>{

            }).catch((error)=>{
                console.log(error);
            })
        },

        getDescapli(){
            this.tabSelect='menu4';
            this.menu4Disabled = false
            axios.get('').then((response)=>{

            }).catch((error)=>{
                console.log(error);
            })
        },

        getDescnoap(){
            this.tabSelect='menu5';
            this.menu5Disabled = false
            axios.post('consultaDescnoap',{doc:this.dataclient.cc}).then((response)=>{
                console.log(response.data)
            }).catch((error)=>{
                console.log(error);
            })
        },
        getPagare(data){
            
        }
    }
}
</script>