<template>
    <div>
        <div v-if="id_consult === null">
            <h2 class="title text-center">Historial de Consultas</h2>
            <div style="float: right;" class="form-group col-md-2">
                <label for="">Buscar</label>
                <input class="form-control" placeholder="Buscar" v-model="filter"/>
            </div>
            <table class="table table-hover table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Id Consulta</th>
                        <th scope="col" class="text-center">Cedula</th>
                        <th scope="col" class="text-center">Nombre Completo</th>
                        <th scope="col" class="text-center">Pagaduria</th>
                        <th scope="col" class="text-center">Tipo de Consulta</th>
                        <th scope="col" class="text-center">Score</th>
                        <th scope="col" class="text-center">Fecha de Consulta</th>
                        <th scope="col" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(history, key) in filteredRows" :key="key">
                        <td scope="row">{{history.id}}</td>                    
                        <td>{{history.ced}}</td>
                        <td>{{history.nombre}}</td>
                        <td>{{history.pagaduria}}</td>
                        <td>{{history.tipo_consulta}}</td>
                        <td></td>                    
                        <td>{{history.created_at}}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" v-on:click="getData(history)">Observar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Detalle de Historial</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input disabled class="form-control" :value="detailHistory.nombre"/>
                            </div>

                            <div class="form-group">
                                <label>Cedula</label>
                                <input disabled class="form-control" :value="detailHistory.ced"/>
                            </div>

                            <div class="form-group">
                                <label>Aprobado</label>
                                <input disabled class="form-control" :value="detailHistory.aprobado"/>
                            </div>

                            <div class="form-group">
                                <label>Cuota Compra</label>
                                <input disabled class="form-control" :value="detailHistory.ccompra"/>
                            </div>                        

                            <div class="form-group">
                                <label>Cantidad Libre Inversión</label>
                                <input disabled class="form-control" :value="detailHistory.clibinv"/>
                            </div>

                            <div class="form-group">
                                <label>Cantidad Maxima Incorporación</label>
                                <input disabled class="form-control" :value="detailHistory.cmaxincorp"/>
                            </div>

                            <div class="form-group">
                                <label>Consecutivo</label>
                                <input disabled class="form-control" :value="detailHistory.conc ? detailHistory.conc : detailHistory.id"/>
                            </div>

                            <div class="form-group">
                                <label>Cuota Credito</label>
                                <input disabled class="form-control" :value="detailHistory.cuotacredito"/>
                            </div>

                            <div class="form-group">
                                <label>Entidad</label>
                                <input disabled class="form-control" :value="detailHistory.entidad"/>
                            </div>

                            <div class="form-group">
                                <label>Estado</label>
                                <input disabled class="form-control" :value="detailHistory.estado"/>
                            </div>

                            <div class="form-group">
                                <label>Fecha Consulta AMI</label>
                                <input disabled class="form-control" :value="detailHistory.fconsultaami"/>
                            </div>

                            <div class="form-group">
                                <label>Fecha Respuesta</label>
                                <input disabled class="form-control" :value="detailHistory.frespuesta"/>
                            </div>

                            <div class="form-group">
                                <label>Fecha Vinculación</label>
                                <input disabled class="form-control" :value="detailHistory.fvinculacion"/>
                            </div>                        

                            <div class="form-group">
                                <label>Pagare</label>
                                <input disabled class="form-control" :value="detailHistory.pagare"/>
                            </div>

                            <div class="form-group">
                                <label>Plazo</label>
                                <input disabled class="form-control" :value="detailHistory.plazo"/>
                            </div>

                            <div class="form-group">
                                <label>Porcentaje Incorporación</label>
                                <input disabled class="form-control" :value="detailHistory.porcincorp"/>
                            </div>

                            <div class="form-group">
                                <label>Total Credito</label>
                                <input disabled class="form-control" :value="detailHistory.tcredito"/>
                            </div>

                            <div class="form-group">
                                <label>Tipo Consulta</label>
                                <input disabled class="form-control" :value="detailHistory.tipo_consulta"/>
                            </div>

                            <div class="form-group">
                                <label>Tipo Vinculación</label>
                                <input disabled class="form-control" :value="detailHistory.tvinculacion"/>
                            </div>

                            <div class="form-group">
                                <label>Valor Credito</label>
                                <input disabled class="form-control" :value="detailHistory.vcredito"/>
                            </div>

                            <div class="form-group">
                                <label>vdesembolso</label>
                                <input disabled class="form-control" :value="detailHistory.vdesembolso"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <button class="btn btn-primary mb-4" v-on:click="back">Volver</button>
            <detail-history-component :id="id_consult" :user="user"></detail-history-component>            
        </div>         
    </div>
</template>
<script>
export default {
    props:['user'],
    data(){
        return {
            HistoryConsult:[],
            detailHistory:{},
            filter:"",
            id_consult:null
        }
    },
    mounted(){
        this.getHistoryConsults();
    },
    computed: {
        filteredRows() {
            if(!this.HistoryConsult) return false;

            return this.HistoryConsult.filter((row) => {
                const name = row.nombre.toString().toLowerCase();
                const searchTerm = this.filter.toLowerCase();
                let data = name.includes(searchTerm); 
                if(data === true){
                    return name.includes(searchTerm); 
                }else{
                    const ced = row.ced.toString().toLowerCase();
                    const searchTerm = this.filter.toLowerCase();

                    let data1 = ced.includes(searchTerm); 
                    if(data1 === true){
                        return ced.includes(searchTerm); 
                    }else{
                        const pag = row.pagaduria.toString().toLowerCase();
                        const searchTerm = this.filter.toLowerCase();
                        let data2 = pag.includes(searchTerm);
                        if(data2 === true){
                            return pag.includes(searchTerm);
                        }else{
                            const date = row.created_at.toString().toLowerCase();
                            const searchTerm = this.filter.toLowerCase();    
                            let data3 = date.includes(searchTerm);
                            if(data3 === true){
                                return date.includes(searchTerm);
                            }else{
                                const id = row.id.toString().toLowerCase();
                                const searchTerm = this.filter.toLowerCase();
                                let data4 = id.includes(searchTerm);  
                                if(data4 === true){
                                    return id.includes(searchTerm);
                                }else{
                                    const type_consulta = row.tipo_consulta.toString().toLowerCase();
                                    const searchTerm = this.filter.toLowerCase();
                                    let data5 = type_consulta.includes(searchTerm);
                                    // if(data5 === true){
                                        return type_consulta.includes(searchTerm)
                                    // }
                                }
                                
                            }
                        }
                    }
                }         
                
            });
        },
    },
    methods:{
        getHistoryConsults(){
            axios.get('getHistoryConsults').then((response)=>{
                this.HistoryConsult = response.data.data
            })
        },
        getData(data){
            this.detailHistory = data;
            this.id_consult = data.id;
        },
        back(){
            this.id_consult = null;
        }
    }
}
</script>