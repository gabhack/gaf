<template>
    <div>
        <h2 class="mb-5">Ver Usuario</h2>
        <BForm>
            <b-row>
                <b-col cols="4">
                    <h4>Datos de Empresa</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4" v-if="!isCompany">
                    <b-form-group label="Empresa" label-for="empresa_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="empresa_id"
                            v-model="form.empresa_id"
                            :options="empresas"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Departamento" label-for="empresa_departamento_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="empresa_departamento_id"
                            v-model="form.empresa.departamento_id"
                            :options="ubicacionesFiltradas.departamentos"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Ciudad" label-for="empresa_ciudad_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="empresa_ciudad_id"
                            v-model="form.empresa.ciudad_id"
                            :options="ubicacionesFiltradas.ciudades"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Sede" label-for="empresa_sede_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="empresa_sede_id"
                            v-model="form.empresa.sede_id"
                            :options="sedes"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Cargo" label-for="empresa_cargo">
                        <b-form-select
                            value-field="id"
                            text-field="cargo"
                            id="empresa_cargo"
                            v-model="form.empresa.cargo_id"
                            :options="cargos"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Límite de Consultas</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Consultas Diarias" label-for="consultas_diarias">
                        <b-form-input
                            id="consultas_diarias"
                            v-model="form.consultas_diarias"
                            type="number"
                            disabled
                        ></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Datos de Personal</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Nombre y Apellido" label-for="personal_nombre_apellido">
                        <b-form-input
                            id="personal_nombre_apellido"
                            v-model="form.personal.nombre_apellido"
                            type="text"
                            disabled
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Tipo de Documento" label-for="personal_tipo_documento_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="tipo_personal_id"
                            v-model="form.personal.tipo_documento_id"
                            :options="tiposDocumento"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número Documento" label-for="personal_numero_documento">
                        <b-form-input
                            id="personal_numero_documento"
                            v-model="form.personal.numero_documento"
                            type="number"
                            disabled
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Nacionalidad" label-for="personal_nacionalidad">
                        <b-form-input
                            id="personal_nacionalidad"
                            v-model="form.personal.nacionalidad"
                            type="text"
                            disabled
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Correo de Contacto" label-for="personal_correo_contacto">
                        <b-form-input
                            id="personal_correo_contacto"
                            v-model="form.personal.correo_contacto"
                            type="email"
                            disabled
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número de Contacto" label-for="personal_numero_contacto">
                        <b-form-input
                            id="personal_numero_contacto"
                            v-model="form.personal.numero_contacto"
                            type="number"
                            disabled
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <div class="mb-3">
                        <label for="personal_permisos">Permisos Adicionales</label>
                        <multiselect
                            id="personal_permisos"
                            v-model="form.personal.permisos"
                            :options="permisos"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true"
                            label="name"
                            track-by="id"
                            :taggable="true"
                            :searchable="false"
                            disabled
                        ></multiselect>
                    </div>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Documentación</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="6">
                    <b-form-group>
                        <CustomButton @click="showModal">
                            <PlusIcon></PlusIcon>
                            Documento de Identidad
                        </CustomButton>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Secciones en Plataforma</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="AMI (Análisis de mercado inteligente)" label-for="plataforma_ami">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="plataforma_ami"
                            v-model="form.plataforma.ami_id"
                            :options="amis"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="HEGO" label-for="plataforma_hego">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="plataforma_hego"
                            v-model="form.plataforma.hego_id"
                            :options="hegos"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
            </b-row>
        </BForm>
        <LiteModal
            id="documento-identidad-modal"
            title="Documento de identidad"
            :preview-document="form.previewDocumentoIdentidad"
        >
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en pdf con el documento por ambos lados.
                </div>
                <FileInput @handleFileInput="handleFileInput"></FileInput>
            </template>
        </LiteModal>
    </div>
</template>

<script>
import CustomButton from '../../customComponents/CustomButton.vue';
import PlusIcon from '../../icons/PlusIcon.vue';
import LiteModal from '../../customComponents/LiteModal.vue';
import InfoCircleIcon from '../../icons/InfoCircleIcon.vue';
import FileInput from '../../customComponents/FileInput.vue';
import Multiselect from 'vue-multiselect';

export default {
    props: ['initialData', 'user'],
    components: {
        CustomButton,
        PlusIcon,
        LiteModal,
        InfoCircleIcon,
        FileInput,
        Multiselect
    },
    data() {
        return {
            form: {
                empresa_id: null,
                empresa: {
                    departamento_id: null,
                    ciudad_id: null,
                    sede_id: null,
                    cargo_id: null
                },
                consultas_diarias: null,
                personal: {
                    nombre_apellido: '',
                    tipo_documento_id: null,
                    numero_documento: '',
                    nacionalidad: '',
                    correo_contacto: '',
                    numero_contacto: '',
                    contrasena: null,
                    confirmarContrasena: null,
                    permisos: []
                },
                documentacion: {
                    src_documento_identidad: ''
                },
                previewDocumentoIdentidad: '',
                plataforma: {
                    ami_id: null,
                    hego_id: null
                }
            },
            permisos: [],
            ciudades: [],
            sedes: [],
            cargos: [],
            tiposDocumento: [],
            amis: [],
            hegos: [],
            empresas: [],
            ubicaciones: {
                departamentos: [],
                ciudades: []
            }
        };
    },
    computed: {
        isCompany() {
            return this.user.role.name === "EMPRESA";
        },
        sedesEmpresa() {
            const empresa = this.empresas.find(empresa => empresa.id === this.form.empresa_id);
            return empresa ? empresa.sedes : [];
        },
        sedesDepartamentosIds() {
            return this.sedesEmpresa.map(sede => sede.departamento_id);
        },
        sedesCiudadesIds() {
            return this.sedesEmpresa.map(sede => sede.ciudad_id);
        },
        ubicacionesFiltradas() {
            return {
                departamentos: this.ubicaciones.departamentos.filter(dep =>
                    this.sedesDepartamentosIds.includes(dep.id)
                ),
                ciudades: this.form.empresa.departamento_id
                    ? this.ubicaciones.ciudades
                          .filter(mun => mun.departamento_id === this.form.empresa.departamento_id)
                          .filter(mun => this.sedesCiudadesIds.includes(mun.id))
                    : []
            };
        }
    },
    async mounted() {
        await this.listarPermisos();
        await this.listarCargos();
        await this.listarTiposDocumento();
        await this.listarAmis();
        await this.listarHegos();
        await this.listarEmpresas();
        await this.cargarUbicaciones();
        await this.listarSedes();

        if (this.isCompany) {
            this.form.empresa_id = this.user.empresa.id;
        }
    },
    watch: {
        initialData: {
            immediate: true,
            handler(newData) {
                if (newData) {
                    Object.assign(this.form, newData);
                }
            }
        },
    },
    methods: {
        async listarPermisos() {
            let response = await axios.get('/listas/permisos');
            this.permisos = response.data;
        },
        async listarCargos() {
            let response = await axios.get('/listas/cargos');
            this.cargos = response.data;
        },
        async listarTiposDocumento() {
            let response = await axios.get('/listas/tipo-documentos');
            this.tiposDocumento = response.data;
        },
        async listarAmis() {
            let response = await axios.get('/listas/amis');
            this.amis = response.data;
        },
        async listarHegos() {
            let response = await axios.get('/listas/hegos');
            this.hegos = response.data;
        },
        async listarEmpresas() {
            let response = await axios.get('/listas/empresas');
            this.empresas = response.data;
        },
        async listarSedes() {
            let response = await axios.get('/listas/ciudades/' + this.form.empresa.ciudad_id + '/sedes');
            this.sedes = response.data;
        },
        async cargarUbicaciones() {
            try {
                const [depResponse, munResponse] = await Promise.all([
                    axios.get('/json/departamentos.json'),
                    axios.get('/json/municipios.json')
                ]);

                if (depResponse.data && munResponse.data) {
                    this.ubicaciones.departamentos = depResponse.data;
                    this.ubicaciones.ciudades = munResponse.data;
                } else {
                    console.error('Error: Datos inválidos en las respuestas');
                }
            } catch (error) {
                console.error('Error al cargar las ubicaciones', error);
            }
        },
        handleFileInput(file) {
            this.form.documentacion.src_documento_identidad = file;
            this.$bvModal.hide('documento-identidad-modal');
        },
        showModal() {
            this.$bvModal.show('documento-identidad-modal');
        },
    }
};
</script>

<style lang="scss" scoped>
.form-control {
    border-radius: 5px;
    color: black;
    font-weight: 100;

    &:not(.is-valid, .is-invalid) {
        border-color: #b8bec5;
    }

    &:not(:disabled) {
        background-color: white;
    }

    &:placeholder {
        font-weight: 100;
    }
}

.form-group legend {
    font-size: 1rem;
}

.info-message {
    background-color: #f9fafb;
    padding: 15px 10px 15px 10px;
    font-size: 13px;
    border-radius: 5px;
    border-left-width: 50px;
    border-left: 4px solid #20a0e9;
}
</style>
