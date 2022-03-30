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
                        <div class="form-group">
                            <label>Identificador unico de Convenio</label>
                            <input class="form-control" v-model="solicitudVal.GuidConv"/>
                        </div>

                        <div class='form-group'>
                            <label>Tipo de Validación</label>
                            <select v-model="solicitudVal.TipoValidacion" class="form-control">
                                <option :value="1">Directa</option>
                                <option :value="2">Asesor</option>
                                <option :value="3">AutoGestionada</option>
                                <option :value="4">Ambas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Asesor</label>
                            <input v-model="solicitudVal.Asesor" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Sede</label>
                            <input v-model="solicitudVal.Sede" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Tipo Documento</label>
                            <input v-model="solicitudVal.TipoDoc" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Numero de Documento</label>
                            <input v-model="solicitudVal.NumDoc" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="solicitudVal.Email" type="email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Celular</label>
                            <input v-model="solicitudVal.Celular" type="number" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Usuario</label>
                            <input v-model="solicitudVal.Usuario" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Clave</label>
                            <input v-model="solicitudVal.Clave" type="password" class="form-control">
                        </div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" v-on:click="getSolicValidacion">Solicitar Validación</button>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="width:740px">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Verificacion de Identidad</h5>                            
                        </div>
                        <div class="modal-body">
                            <iframe :src="resultSolicVal.url" allow="camera" title="Inline Frame Example" width="700" height="700"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    
</template>
<script>
    export default {
        data(){
            return{
                token: null,
                solicitudVal:{},
                resultSolicVal:{}
            }
        },
        mounted(){
            this.getToken();
        },
        methods:{
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
                console.log(this.solicitudVal);
                let data = {
                    GuidConv: '575650aa-b5ed-4797-844d-6ee965e41786',
                    TipoValidacion: 4,
                    Asesor:'pruevav',
                    Sede:'000100',                    
                    TipoDoc:'CC',
                    NumDoc:'1026307251',
                    Email:'brayantriana22@gmail.com',
                    Celular:'3007819686',          
                    PrefCelular : "57",          
                    Usuario:'CKCOMERCIALIZADORA_2022',
                    Clave:'CKComercializadora.2022*',
                };

                axios.post('https://demorcs.olimpiait.com:6314/Validacion/SolicitudValidacion', data, {headers:{
                    'Authorization':`Bearer ${this.token}`
                }}).then((response)=>{
                    this.resultSolicVal = response.data.data
                }).catch((error)=>{
                    console.log(error);
                })
            }
        }
    }
</script>