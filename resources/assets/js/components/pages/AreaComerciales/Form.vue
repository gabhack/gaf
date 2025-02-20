<template>
    <div>
        <h2 class="mb-5">Panel de Creación Usuarios</h2>
        <BForm @submit.prevent="submitForm">
            <b-row>
                <b-col cols="6">
                    <h4>Datos de Empresa</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Ciudad" label-for="empresa_ciudad_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="empresa_ciudad_id"
                            v-model="form.empresa.ciudad_id"
                            :options="ciudades"
                            @change="listarSedes"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Sede" label-for="empresa_sede_id">
                        <b-form-select
                            value-field="id"
                            text-field="nombre"
                            id="empresa_sede_id"
                            v-model="form.empresa.sede_id"
                            :options="sedes"
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
                            placeholder="50"
                            required
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
                            :state="validateState('form.personal.nombre_apellido')"
                            id="personal_nombre_apellido"
                            v-model="form.personal.nombre_apellido"
                            type="text"
                            placeholder="Danilo perez"
                            required
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
                            :options="tipoDocumentos"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número Documento" label-for="personal_numero_documento">
                        <b-form-input
                            id="personal_numero_documento"
                            v-model="form.personal.numero_documento"
                            type="number"
                            placeholder="10253658596"
                            required
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Nacionalidad" label-for="personal_nacionalidad">
                        <b-form-input
                            id="personal_nacionalidad"
                            v-model="form.personal.nacionalidad"
                            type="text"
                            placeholder="Colombia"
                            required
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Correo de Contacto" label-for="personal_correo_contacto">
                        <b-form-input
                            :state="validateState('form.personal.correo_contacto')"
                            id="personal_correo_contacto"
                            v-model="form.personal.correo_contacto"
                            type="email"
                            placeholder="usuario1@gmail.com"
                            required
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número de Contacto" label-for="personal_numero_contacto">
                        <b-form-input
                            id="personal_numero_contacto"
                            v-model="form.personal.numero_contacto"
                            type="number"
                            placeholder="3214556756"
                            required
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Contraseña" label-for="personal_contrasena">
                        <b-form-input
                            :state="validateState('form.personal.contrasena')"
                            id="personal_contrasena"
                            v-model="form.personal.contrasena"
                            type="password"
                            placeholder="********"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Confirmar Contraseña" label-for="personal_confirmar_contrasena">
                        <b-form-input
                            :state="validateState('form.personal.confirmarContrasena')"
                            id="personal_confirmar_contrasena"
                            v-model="form.personal.confirmarContrasena"
                            type="password"
                            placeholder="********"
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
                            placeholder="Busca y selecciona"
                            label="name"
                            track-by="id"
                            :taggable="true"
                            :searchable="false"
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
                    <b-form-group label="Documento de Identidad de Usuario" label-for="documentacion_identidad_file">
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
import { email, required, sameAs, minLength } from 'vuelidate/lib/validators';

import CustomButton from '../../customComponents/CustomButton.vue';
import PlusIcon from '../../icons/PlusIcon.vue';
import LiteModal from '../../customComponents/LiteModal.vue';
import InfoCircleIcon from '../../icons/InfoCircleIcon.vue';
import FileInput from '../../customComponents/FileInput.vue';
import Multiselect from 'vue-multiselect';

export default {
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
                empresa: {
                    ciudad_id: '',
                    sede_id: '',
                    cargo_id: ''
                },
                personal: {
                    nombre_apellido: '',
                    tipo_documento_id: '',
                    numero_documento: '',
                    nacionalidad: '',
                    correo_contacto: '',
                    numero_contacto: '',
                    contrasena: null,
                    confirmarContrasena: null,
                    permisos: []
                },
                consultas_diarias: 0,
                documentacion: {
                    tipo_documento: ''
                },
                plataforma: {
                    ami_id: '',
                    hego_id: ''
                }
            },
            permisos: [],
            ciudades: [],
            sedes: [],
            cargos: [],
            tipoDocumentos: [],
            amis: [],
            hegos: []
        };
    },
    validations: {
        form: {
            personal: {
                nombre_apellido: {
                    required
                },
                correo_contacto: {
                    required,
                    email
                },
                contrasena: {
                    required,
                    minLength: minLength(6)
                },
                confirmarContrasena: {
                    required,
                    sameAsContrasena: sameAs('contrasena')
                }
            }
        }
    },
    async mounted() {
        await this.listarPermisos();
        await this.listarCiudades();
        await this.listarSedesEdit();
        await this.listarCargos();
        await this.listarTiposDocumento();
        await this.listarAmis();
        await this.listarHegos();
    },
    methods: {
        validateState(name) {
            const validation = _.get(this.$v, name);

            if (!validation) return null;

            const { $dirty, $error } = validation;
            return $dirty ? !$error : null;
        },
        async listarPermisos() {
            let response = await axios.get('/listas/permisos');
            this.permisos = response.data;
        },
        async listarCiudades() {
            let response = await axios.get('/listas/ciudades');
            this.ciudades = response.data;
        },
        async listarCargos() {
            let response = await axios.get('/listas/cargos');
            this.cargos = response.data;
        },
        async listarTiposDocumento() {
            let response = await axios.get('/listas/tipo-documentos');
            this.tipoDocumentos = response.data;
        },
        async listarAmis() {
            let response = await axios.get('/listas/amis');
            this.amis = response.data;
        },
        async listarHegos() {
            let response = await axios.get('/listas/hegos');
            this.hegos = response.data;
        },
        async listarSedes() {
            let response = await axios.get('/listas/ciudades/' + this.form.empresa.ciudad_id + '/sedes');
            this.sedes = response.data;
        },
        async listarSedesEdit() {
            if (this.form.empresa.ciudad_id) {
                let response = await axios.get('/listas/ciudades/' + this.form.empresa.ciudad_id + '/sedes');
                this.sedes = response.data;
            }
        },
        handleFileInput(file) {
            this.form.documentacion.tipo_documento = file;
            this.$bvModal.hide('documento-identidad-modal');
        },
        showModal() {
            this.$bvModal.show('documento-identidad-modal');
        },
        submitForm() {
            this.$v.$touch();
            return !this.$v.$invalid ? this.form : null;
        }
    }
};
</script>

<style lang="scss" scoped>
.form-control {
    background-color: white;
    border-radius: 5px;
    color: black;
    font-weight: 100;

    &:not(.is-valid, .is-invalid) {
        border-color: #b8bec5;
    }

    &:placeholder {
        font-weight: 100;
    }
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
