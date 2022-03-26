<template>
    <section class="container-fluid">
        <div class=" col-lg-12 col-sm-12 align-items-center justify-content-center">
            <div class="text-center">
                <div class="card">
                    <div class="card-header">
                        <p>Integración</p>
                    </div>
                    <div class="card-body">
                        <p>{{token}}</p>
                        <button class="btn btn-primary" v-on:click="getSolicValidacion">Clic</button>
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
                token: 'Sin Token',
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

                let headers= {
                    'Authorization': `Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6InphSEVTNEh4LVRSNFBEcGxKUU1zREEiLCJ0eXAiOiJhdCtqd3QifQ.eyJuYmYiOjE2NDgwNTY1MTgsImV4cCI6MTY0ODA1ODMxOCwiaXNzIjoiaHR0cDovLzEwLjEzMC4xLjQwOjYzMjAiLCJhdWQiOlsiaWRlbnRpdHlBcGkiLCJtYWluQVBJIiwic2VydmljZXNBUEkiXSwiY2xpZW50X2lkIjoiQ0tDT01FUkNJQUxJWkFET1JBIiwic2NvcGUiOlsiaWRlbnRpdHlBcGkiLCJtYWluQVBJIiwic2VydmljZXNBUEkiXX0.F_1UJMGbfRmWPXPQJXXcZwT9gF2wjtkI5idtANtBlQWpcVsv0zlDmNErw89t1vGPIZ4tPpwGzQ5CvHy3000y70kf9JkaWIt0rtuLtcAFAvhXGWBnuZJV1S7Zu4xkDlBzvM3USK_ilOpyRby_OvGtS6RU-rbjPiqSTc_OnhXt32VkMtnxrdBXU9OHvLXwNx7WbZ6iRX0vBfTu5hIqc4oDujcbrkwh_I9QwCuvSdSUpyFlXRU_EyXXDh99Y7pgfXiApUOQxZpiPgu4WcNrT3tYhChXJnOr5zoACSrj8e55Df_o5VlZQj9ZU3Udm4Gm_CvHovGof1S4M3hcserfQgUKig`
                }                           

                axios.post('https://demorcs.olimpiait.com:6314/Validacion/SolicitudValidacion', data, headers).then((response)=>{
                    console.log(response.data);
                }).catch((error)=>{
                    console.log(error);
                })
            }
        }
    }
</script>