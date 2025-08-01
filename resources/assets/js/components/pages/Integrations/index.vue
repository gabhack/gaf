<template>
    <section class="container-fluid">
        <div class="col-lg-12 col-sm-12 align-items-center justify-content-center">
            <div class="text-center">
                <div class="card">
                    <div class="card-header">
                        <p>Solicitud Validación</p>
                    </div>
                    <!-- <div class="card-body" v-if="token !== null"> -->
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>Tipo Documento</label>
                                    <input v-model="solicitudVal.TipoDoc" disabled class="form-control" />
                                </div>
                                <div class="form-group col-md-2">
                                    <label># Documento</label>
                                    <input v-model="solicitudVal.NumDoc" class="form-control" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input v-model="solicitudVal.Email" type="email" class="form-control" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Celular</label>
                                    <input v-model="solicitudVal.Celular" type="number" class="form-control" />
                                </div>
                            </div>
                            <button
                                v-if="solicitudVal.TipoDoc"
                                type="button"
                                class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#exampleModal"
                                v-on:click="getSolicValidacion"
                            >
                                Solicitar Validación
                            </button>
                        </form>
                        <table class="mt-3 table table-striped table-bordered table hover">
                            <thead>
                                <tr>
                                    <th>Tipo Documento</th>
                                    <th>Numero de Documento</th>
                                    <th>Correo</th>
                                    <th>Celular</th>
                                    <th>Consultar Validación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(val, key) in validateData" :key="key">
                                    <td>{{ val.TipoDoc }}</td>
                                    <td>{{ val.NumDoc }}</td>
                                    <td>{{ val.Email }}</td>
                                    <td>{{ val.Celular }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-primary"
                                            data-toggle="modal"
                                            data-target="#modalResultConsultVal"
                                            v-on:click="consultarValidacion(val)"
                                        >
                                            Consultar Validación
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div
                class="modal fade"
                id="exampleModal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 740px">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Verificacion de Identidad</h5>
                        </div>
                        <div class="modal-body">
                            <iframe
                                :src="resultSolicVal.url"
                                allow="geolocation *; camera *;"
                                frameborder="0"
                                border="0"
                                cellspacing="0"
                                style="border-style: none; width: 100%; height: 770px"
                            ></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="getData" data-dismiss="modal">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div
                class="modal fade bd-example-modal-xl"
                id="modalResultConsultVal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
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
                                        <th>motivoCancelacion</th>
                                        <th>scoreProceso</th>

                                        <!-- <th>Servicios</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ resultConsultVal.aprobado === true ? 'Aprobado' : 'No Aprobado' }}</td>
                                        <td>{{ resultConsultVal.asesor }}</td>
                                        <td>
                                            {{ resultConsultVal.cancelado === true ? 'Cancelado' : 'Sin Cancelar' }}
                                        </td>

                                        <td>
                                            {{
                                                resultConsultVal.encontradoEnFuente === true
                                                    ? 'Encontrado'
                                                    : 'Sin Encontrar'
                                            }}
                                        </td>
                                        <td>{{ resultConsultVal.estadoProceso }}</td>
                                        <td>{{ resultConsultVal.fechaFinalizacion }}</td>
                                        <td>{{ resultConsultVal.fechaRegistro }}</td>
                                        <td>
                                            {{ resultConsultVal.finalizado === true ? 'Finalizado' : 'Sin Finalizar' }}
                                        </td>
                                        <td>{{ resultConsultVal.guidConv }}</td>
                                        <td>{{ resultConsultVal.nombreSede }}</td>

                                        <td>{{ resultConsultVal.celular }}</td>
                                        <td>{{ resultConsultVal.tipoDoc }}</td>
                                        <td>{{ resultConsultVal.numDoc }}</td>
                                        <td>{{ resultConsultVal.primerApellido }}</td>
                                        <td>{{ resultConsultVal.segundoApellido }}</td>
                                        <td>{{ resultConsultVal.primerNombre }}</td>
                                        <td>{{ resultConsultVal.segundoNombre }}</td>

                                        <td>{{ resultConsultVal.procesoConvenioGuid }}</td>
                                        <td>{{ resultConsultVal.scoreRostroDocumento }}</td>
                                        <td>{{ resultConsultVal.sede }}</td>

                                        <td>{{ resultConsultVal.codigoCliente }}</td>
                                        <td>{{ resultConsultVal.estadoDescripcion }}</td>
                                        <td>{{ resultConsultVal.motivoCancelacion }}</td>
                                        <td>{{ resultConsultVal.scoreProceso }}</td>

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
                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripcion</th>
                                        <th>Mensaje</th>
                                        <th>Nombre</th>
                                        <th>Riesgo</th>
                                        <th>Puntaje</th>
                                        <th>Riesgo de la Fuente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(fuente, key) in fuentes" :key="key">
                                        <td>{{ fuente.codigo }}</td>
                                        <td>{{ fuente.descripcion }}</td>
                                        <td>{{ fuente.mensaje }}</td>
                                        <td>{{ fuente.nombre }}</td>
                                        <td>{{ fuente.riesgo === true ? 'Riesgo Encontrado' : 'Sin Riesgo' }}</td>
                                        <td>{{ fuente.score }}</td>
                                        <td>{{ fuente.isSourceRisk === true ? 'Riesgo Encontrado' : 'Sin Riesgo' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Texto</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(fuentesR, key) in fuentesRaw" :key="key">
                                        <td>{{ fuentesR.codigo }}</td>
                                        <td>{{ fuentesR.nombre }}</td>
                                        <td>
                                            {{
                                                fuentesR.texto.resultado
                                                    ? fuentesR.data.texto.boletin_acuerdos
                                                    : fuentesR.data.texto.resultado
                                                    ? fuentesR.data.texto.respuesta
                                                    : fuentesR.data.texto.main_respuesta
                                                    ? fuentesR.data.texto
                                                    : fuentesR.texto
                                            }}
                                        </td>
                                        <td>{{ fuentesR.tipo }}</td>
                                        <td>{{ fuentesR.estado }}</td>
                                        <td>{{ fuentesR.error === true ? 'Error Encontrado' : 'Sin Error' }}</td>
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
    data() {
        return {
            token: null,
            solicitudVal: {
                TipoValidacion: 4,
                Asesor: 'pruevav',
                Sede: '000100',
                TipoDoc: 'CC',
                GuidConv: process.env.MIX_OLIMPIAIT_GUID,
                Usuario: process.env.MIX_OLIMPIAIT_USER,
                Clave: process.env.MIX_OLIMPIAIT_PASSWORD
            },
            resultSolicVal: {},
            validateData: [],
            resultConsultVal: {},
            fuentes: [],
            fuentesRaw: []
        };
    },
    mounted() {
        this.getToken();
        this.getData();
    },
    methods: {
        onLoad(data) {
            console.log('datos', data);
        },
        getData() {
            axios.get('validate').then(response => {
                this.validateData = response.data;
            });
        },
        getToken() {
            const data = {
                clientId: process.env.MIX_OLIMPIAIT_CLIENT_ID,
                clientSecret: process.env.MIX_OLIMPIAIT_CLIENT_SECRET
            };

            axios
                .post(`${process.env.MIX_OLIMPIAIT_URL}/TraerToken`, data)
                .then(response => {
                    this.token = response.data.accessToken;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        getSolicValidacion() {
            axios
                .post(`${process.env.MIX_OLIMPIAIT_URL}/Validacion/SolicitudValidacion`, this.solicitudVal, {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                })
                .then(response => {
                    this.resultSolicVal = response.data.data;
                    navigator.permissions.query({ name: 'camera' }).then(res => {
                        console.log(res);
                    });

                    this.solicitudVal.ProcesoConvenioGuid = response.data.data.procesoConvenioGuid;

                    axios.post('validate', this.solicitudVal).then(response => {
                        // console.log(response.data);
                    });
                })
                .catch(error => {
                    console.log(error);
                });
        },
        consultarValidacion(data) {
            const dataDinamic = {
                GuidConv: data.GuidConv,
                ProcesoConvenioGuid: data.ProcesoConvenioGuid,
                CodigoCliente: '',
                Usuario: process.env.MIX_OLIMPIAIT_USER,
                Clave: process.env.MIX_OLIMPIAIT_PASSWORD
            };

            axios
                .post(`${process.env.MIX_OLIMPIAIT_URL}/Validacion/ConsultarValidacion`, dataDinamic, {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                })
                .then(response => {
                    this.fuentes = response.data.data.fuentesAbiertas.fuentes;
                    this.fuentesRaw = response.data.data.fuentesAbiertas.fuentesRaw;
                    this.resultConsultVal = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>
