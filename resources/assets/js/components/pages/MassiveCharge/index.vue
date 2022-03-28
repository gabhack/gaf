<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Administración de Datos</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Selecciona el tipo de archivo a importar</label>
                    <select v-model="optionSelected" class="form-control">
                        <option value="fechavinc">FECHAVINC</option>
                        <option value="datames">DATAMES</option>
                        <option value="descapli">DESCAPLI</option>
                        <option value="descnoap">DESCNOAP</option>
                    </select>
                </div>

                <div class="form-group" v-if="optionSelected === 'fechavinc'">
                    <label for="">Selecciona el archivo a importar (FECHAVINC)</label>
                    <input type="file" name="fechavinc" v-on:change="(e)=>getFile(e.target.files)" class="form-control">
                </div>

                <div class="form-group" v-if="optionSelected === 'datames'">
                    <label for="">Selecciona el archivo a importar (DATAMES)</label>
                    <input type="file" ref="file" name="datames" v-on:change="getFile" class="form-control">                    
                </div>

                <div class="form-group" v-if="optionSelected === 'descapli'">
                    <label for="">Selecciona el archivo a importar (DESCAPLI)</label>
                    <input type="file" name="descapli" v-on:change="(e)=>getFile(e.target.files)" class="form-control">
                </div>

                <div class="form-group" v-if="optionSelected === 'descnoap'">
                    <label for="">Selecciona el archivo a importar (DESCNOAP)</label>
                    <input type="file" name="descnoap" v-on:change="(e)=>getFile(e.target.files)" class="form-control">
                </div>

                <div class="form-group" v-if="optionSelected !==null">
                    <button class="btn btn-primary" v-on:click="importFile">Importar</button>
                </div> 

                <div class="form-group mt-2" v-if="optionSelected === 'datames'">
                    <label for="">Vaciar Tabla</label>
                    <button class="btn btn-primary" v-on:click="dumpDataMes">Vaciar tabla DATAMES</button>
                </div>               
            </div>
        </div>
    </div>
</template>
<script>
export default ({
    data() {
        return{
            optionSelected:null,
            file:[],

        }
    },

    mounted(){
        
    },

    methods:{
        getFile(e){
            var files = e.target.files || e.dataTransfer.files;            
            if (!files.length)
            return;
            this.file = files[0];
        },
        dumpDataMes(){
            axios.get('dumpDataMes').then((response)=>{
                this.$bvToast.toast(response.data.message, {
                    title: 'Datos de la tabla Eliminados',
                    variant: 'success',
                    solid: true
                });
                console.log(response.data);
            })
        },
        importFile(){        
            if(this.optionSelected === 'fechavinc'){
                const formData = new FormData();
                formData.append("file", this.file);
                axios.post('fechaVincImport',formData,{headers:{'Content-Type':'multipart/form-data','mime-type':'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}}).then((response)=>{
                    this.$bvToast.toast(response.data.message, {
                        title: response.data.message,
                        variant: 'success',
                        solid: true
                    });
                    console.log(response.data);
                }).catch((error)=>{
                    console.log(error);
                })
            }else if(this.optionSelected === 'datames'){                   
                const formData = new FormData();
                formData.append("file", this.file);
                axios.post('datamesImport',formData,{headers:{'Content-Type':'multipart/form-data','mime-type':'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}}).then((response)=>{
                    this.$bvToast.toast(response.data.message, {
                        title: response.data.message,
                        variant: 'success',
                        solid: true
                    });
                    console.log(response.data);
                }).catch((error)=>{
                    console.log(error);
                })
            }else if(this.optionSelected === 'descapli'){
                const formData = new FormData();
                formData.append("file", this.file);
                axios.post('descapliImport',formData,{headers:{'Content-Type':'multipart/form-data','mime-type':'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}}).then((response)=>{
                    this.$bvToast.toast(response.data.message, {
                        title: response.data.message,
                        variant: 'success',
                        solid: true
                    });
                    console.log(response.data);
                }).catch((error)=>{
                    console.log(error);
                })
            }else if(this.optionSelected === 'descnoap'){
                const formData = new FormData();
                formData.append("file", this.file);
                axios.post('descnoapController',formData,{headers:{'Content-Type':'multipart/form-data','mime-type':'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}}).then((response)=>{
                    this.$bvToast.toast(response.data.message, {
                        title: response.data.message,
                        variant: 'success',
                        solid: true
                    });
                    console.log(response.data);
                }).catch((error)=>{
                    console.log(error);
                })
            }
        }
    }
})
</script>
