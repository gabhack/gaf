<template>
    <div>
        <h2 class="mb-5">Panel de {{ !onUpdate ? 'Creación' : 'Edición' }} Empresas</h2>
        <BForm @submit.prevent="submitForm">
            <b-row>
                <b-col cols="4">
                    <h4>Tipo de Empresa</h4>
                    <b-form-group label="Tipo Empresa" label-for="tipo_empresa_id">
                        <b-form-select
                            :state="validateState('form.tipo_empresa_id')"
                            value-field="id"
                            text-field="nombre"
                            id="tipo_empresa_id"
                            v-model="form.tipo_empresa_id"
                            :options="tiposEmpresa"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <h4>Límite de Consultas</h4>
                    <b-form-group label="Consultas Diarias" label-for="consultas_diarias">
                        <b-form-input
                            :state="validateState('form.consultas_diarias')"
                            id="consultas_diarias"
                            v-model="form.consultas_diarias"
                            type="number"
                            placeholder="50"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Datos Empresa</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Nombre Empresa" label-for="empresa_nombre">
                        <b-form-input
                            :state="validateState('form.empresa.nombre')"
                            id="empresa_nombre"
                            v-model="form.empresa.nombre"
                            type="text"
                            placeholder="Empresa 1 SAS"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Tipo de Documento" label-for="empresa_tipo_documento_id">
                        <b-form-select
                            :state="validateState('form.empresa.tipo_documento_id')"
                            value-field="id"
                            text-field="nombre"
                            id="empresa_tipo_documento_id"
                            v-model="form.empresa.tipo_documento_id"
                            :options="tiposDocumento"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número de Documento" label-for="empresa_numero_documento">
                        <b-form-input
                            :state="validateState('form.empresa.numero_documento')"
                            id="empresa_numero_documento"
                            v-model="form.empresa.numero_documento"
                            type="number"
                            placeholder="10253658596"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Tipo Sociedad" label-for="tipo_sociedad_id">
                        <b-form-select
                            :state="validateState('form.empresa.tipo_sociedad_id')"
                            value-field="id"
                            text-field="nombre"
                            id="tipo_sociedad_id"
                            v-model="form.empresa.tipo_sociedad_id"
                            :options="tiposSociedad"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="E-mail Registrado por la Compañía" label-for="empresa_correo">
                        <b-form-input
                            :state="validateState('form.empresa.correo')"
                            id="empresa_correo"
                            v-model="form.empresa.correo"
                            type="email"
                            placeholder="usuario1@gmail.com"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Página Web" label-for="empresa_pagina_web">
                        <b-form-input
                            :state="validateState('form.empresa.pagina_web')"
                            id="empresa_pagina_web"
                            v-model="form.empresa.pagina_web"
                            type="text"
                            placeholder="empresa1.com"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <!-- País fijado a Colombia -->
                <b-col cols="4">
                    <b-form-group label="País" label-for="empresa_pais">
                        <b-form-select
                            :state="validateState('form.empresa.pais_id')"
                            value-field="id"
                            text-field="nombre"
                            id="empresa_pais"
                            v-model="form.empresa.pais_id"
                            :options="ubicaciones.paises"
                            disabled
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <!-- Departamento y Municipio cargados desde JSON -->
                <b-col cols="4">
                    <b-form-group label="Departamento" label-for="empresa_departamento">
                        <b-form-select
                            :state="validateState('form.empresa.departamento_id')"
                            value-field="id"
                            text-field="nombre"
                            id="empresa_departamento"
                            v-model="form.empresa.departamento_id"
                            :options="ubicaciones.departamentos"
                        >
                            <template #first>
                                <b-form-select-option :value="null" disabled>
                                    Seleccione un departamento
                                </b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Ciudad" label-for="empresa_ciudad">
                        <b-form-select
                            :state="validateState('form.empresa.ciudad_id')"
                            value-field="id"
                            text-field="nombre"
                            id="empresa_ciudad"
                            v-model="form.empresa.ciudad_id"
                            :options="ubicaciones.ciudades"
                        >
                            <template #first>
                                <b-form-select-option :value="null" disabled>
                                    Seleccione una ciudad
                                </b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Dirección" label-for="empresa_direccion">
                        <b-form-input
                            :state="validateState('form.empresa.direccion')"
                            id="empresa_direccion"
                            v-model="form.empresa.direccion"
                            type="text"
                            placeholder="CL 8 BIS A #76-09"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Datos Representante Legal</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Nombre y Apellido" label-for="representante_nombres_completos">
                        <b-form-input
                            :state="validateState('form.representante_legal.nombres_completos')"
                            id="representante_nombres_completos"
                            v-model="form.representante_legal.nombres_completos"
                            type="text"
                            placeholder="Danilo Perez"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Tipo de Documento" label-for="representante_tipo_documento_id">
                        <b-form-select
                            :state="validateState('form.representante_legal.tipo_documento_id')"
                            value-field="id"
                            text-field="nombre"
                            id="representante_tipo_documento_id"
                            v-model="form.representante_legal.tipo_documento_id"
                            :options="tiposDocumento"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número de Documento" label-for="representante_legal_numero_documento">
                        <b-form-input
                            :state="validateState('form.representante_legal.numero_documento')"
                            id="representante_legal_numero_documento"
                            v-model="form.representante_legal.numero_documento"
                            type="number"
                            placeholder="10253658596"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Nacionalidad" label-for="representante_legal_nacionalidad">
                        <b-form-input
                            :state="validateState('form.representante_legal.nacionalidad')"
                            id="representante_legal_nacionalidad"
                            v-model="form.representante_legal.nacionalidad"
                            type="text"
                            placeholder="Colombia"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Correo de Contacto" label-for="representante_legal_correo">
                        <b-form-input
                            :state="validateState('form.representante_legal.correo')"
                            id="representante_legal_correo"
                            v-model="form.representante_legal.correo"
                            type="email"
                            placeholder="representante1@gmail.com"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Número de Contacto" label-for="representante_legal_numero_contacto">
                        <b-form-input
                            :state="validateState('form.representante_legal.numero_contacto')"
                            id="representante_legal_numero_contacto"
                            v-model="form.representante_legal.numero_contacto"
                            type="number"
                            placeholder="3214567865"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Datos de Facturación</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Responsable IVA" label-for="documentacion_iva">
                        <b-form-select
                            :state="validateState('form.documentacion.iva')"
                            value-field="id"
                            text-field="nombre"
                            id="documentacion_iva"
                            v-model="form.documentacion.iva"
                            :options="booleanValores"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Gran Contribuyente" label-for="documentacion_contribuyente">
                        <b-form-select
                            :state="validateState('form.documentacion.contribuyente')"
                            value-field="id"
                            text-field="nombre"
                            id="documentacion_contribuyente"
                            v-model="form.documentacion.contribuyente"
                            :options="booleanValores"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Auto Retenedor" label-for="documentacion_autoretenedor">
                        <b-form-select
                            :state="validateState('form.documentacion.autoretenedor')"
                            value-field="id"
                            text-field="nombre"
                            id="documentacion_autoretenedor"
                            v-model="form.documentacion.autoretenedor"
                            :options="booleanValores"
                        ></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group>
                        <CustomButton @click="showModal('representante-legal-modal')">
                            <PlusIcon></PlusIcon>
                            Documento representante legal
                        </CustomButton>
                        <b-form-invalid-feedback :state="validateState('form.documentacion.src_representante_legal')">
                            <template v-if="!$v.form.documentacion.src_representante_legal.required">
                                Debes añadir un archivo
                            </template>
                            <template v-else-if="!$v.form.documentacion.src_representante_legal.isPDF">
                                Debes añadir un archivo en formato PDF
                            </template>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group>
                        <CustomButton @click="showModal('camara-comercio-modal')">
                            <PlusIcon></PlusIcon>
                            Cámara de Comercio
                        </CustomButton>
                        <b-form-invalid-feedback :state="validateState('form.documentacion.src_camara_comercio')">
                            <template v-if="!$v.form.documentacion.src_camara_comercio.required">
                                Debes añadir un archivo
                            </template>
                            <template v-else-if="!$v.form.documentacion.src_camara_comercio.isPDF">
                                Debes añadir un archivo en formato PDF
                            </template>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group>
                        <CustomButton @click="showModal('rut-modal')">
                            <PlusIcon></PlusIcon>
                            RUT
                        </CustomButton>
                        <b-form-invalid-feedback :state="validateState('form.documentacion.src_rut')">
                            <template v-if="!$v.form.documentacion.src_rut.required">
                                Debes añadir un archivo
                            </template>
                            <template v-else-if="!$v.form.documentacion.src_rut.isPDF">
                                Debes añadir un archivo en formato PDF
                            </template>
                        </b-form-invalid-feedback>
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
                    <b-form-group label="Permisos Plataforma" label-for="usuario_permisos">
                        <multiselect
                            id="usuario_permisos"
                            v-model="form.usuario.permisos"
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
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Usuario Plataforma</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Nombres" label-for="usuario_nombre">
                        <b-form-input
                            :state="validateState('form.usuario.nombre')"
                            id="usuario_nombre"
                            v-model="form.usuario.nombre"
                            type="text"
                            placeholder="John Doe"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Correo" label-for="usuario_correo">
                        <b-form-input
                            :state="validateState('form.usuario.correo')"
                            id="usuario_correo"
                            v-model="form.usuario.correo"
                            type="email"
                            placeholder="email@example.com"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4" v-if="!onUpdate">
                    <b-form-group label="Contraseña" label-for="usuario_contrasena">
                        <b-form-input
                            :state="validateState('form.usuario.contrasena')"
                            id="usuario_contrasena"
                            v-model="form.usuario.contrasena"
                            type="password"
                            placeholder="********"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4" v-if="!onUpdate">
                    <b-form-group label="Confirmar Contraseña" label-for="usuario_confirmar_contrasena">
                        <b-form-input
                            :state="validateState('form.usuario.confirmarContrasena')"
                            id="usuario_confirmar_contrasena"
                            v-model="form.usuario.confirmarContrasena"
                            type="password"
                            placeholder="********"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="12" align-self="end" class="mt-4">
                    <CustomButton class="mb-3" type="submit">
                        <template v-if="!onUpdate">
                            <PlusIcon></PlusIcon>
                            Crear Empresa
                        </template>
                        <template v-else> Guardar Cambios </template>
                    </CustomButton>
                </b-col>
            </b-row>
        </BForm>
        <!-- Modales para carga de archivos -->
        <LiteModal
            id="representante-legal-modal"
            title="Documento de Representante Legal"
            :preview-document="form.previewRepresentanteLegal"
        >
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en PDF con el documento por ambos lados.
                </div>
                <FileInput @handleFileInput="handleFileRepresentanteLegal"></FileInput>
            </template>
        </LiteModal>
        <LiteModal id="camara-comercio-modal" title="Cámara de Comercio" :preview-document="form.previewCamaraComercio">
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en PDF con la cámara de comercio menor a 90 días.
                </div>
                <FileInput @handleFileInput="handleFileCamaraComercio"></FileInput>
            </template>
        </LiteModal>
        <LiteModal id="rut-modal" title="RUT" :preview-document="form.previewRut">
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en PDF con el RUT menor a 90 días.
                </div>
                <FileInput @handleFileInput="handleFileRut"></FileInput>
            </template>
        </LiteModal>
    </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash';
import { email, required, requiredIf, sameAs, minLength, numeric } from 'vuelidate/lib/validators';

import CustomButton from '../../customComponents/CustomButton.vue';
import InfoCircleIcon from '../../icons/InfoCircleIcon.vue';
import PlusIcon from '../../icons/PlusIcon.vue';
import FileInput from '../../customComponents/FileInput.vue';
import LiteModal from '../../customComponents/LiteModal.vue';
import Multiselect from 'vue-multiselect';

const isPDF = value => {
    if (!value) return true;
    return value.type === 'application/pdf';
};

export default {
    props: ['initialData'],
    components: {
        CustomButton,
        InfoCircleIcon,
        PlusIcon,
        FileInput,
        LiteModal,
        Multiselect
    },
    data() {
        return {
            form: {
                tipo_empresa_id: null,
                consultas_diarias: null,
                empresa: {
                    tipo_sociedad_id: null,
                    nombre: '',
                    tipo_documento_id: null,
                    numero_documento: '',
                    correo: '',
                    pagina_web: '',
                    pais_id: 1, // Solo Colombia
                    departamento_id: null,
                    ciudad_id: null,
                    direccion: ''
                },
                representante_legal: {
                    nombres_completos: '',
                    tipo_documento_id: null,
                    numero_documento: '',
                    nacionalidad: '',
                    correo: '',
                    numero_contacto: ''
                },
                documentacion: {
                    iva: 0,
                    contribuyente: 0,
                    autoretenedor: 0,
                    src_representante_legal: '',
                    src_camara_comercio: '',
                    src_rut: ''
                },
                previewRepresentanteLegal: '',
                previewCamaraComercio: '',
                previewRut: '',
                usuario: {
                    nombre: '',
                    correo: '',
                    contrasena: null,
                    confirmarContrasena: null,
                    permisos: []
                }
            },
            permisos: [],
            tiposEmpresa: [],
            tiposSociedad: [],
            tiposDocumento: [],
            ubicaciones: {
                paises: [{ id: 1, nombre: 'COLOMBIA' }],
                departamentos: [],
                ciudades: []
            },
            // Almacenamos los municipios cargados desde JSON
            municipiosData: [],
            booleanValores: [
                { id: 1, nombre: 'SI' },
                { id: 0, nombre: 'NO' }
            ]
        };
    },
    computed: {
        onUpdate() {
            return !!this.initialData;
        }
    },
    validations() {
        return {
            form: {
                tipo_empresa_id: { required },
                consultas_diarias: { required, numeric },
                empresa: {
                    tipo_sociedad_id: { required },
                    nombre: { required },
                    tipo_documento_id: { required },
                    numero_documento: { required, numeric },
                    correo: { required, email },
                    pagina_web: { required },
                    pais_id: { required },
                    departamento_id: { required },
                    ciudad_id: { required },
                    direccion: { required }
                },
                representante_legal: {
                    nombres_completos: { required },
                    tipo_documento_id: { required },
                    numero_documento: { required, numeric },
                    nacionalidad: { required },
                    correo: { required, email },
                    numero_contacto: { required, numeric }
                },
                documentacion: {
                    iva: { required },
                    contribuyente: { required },
                    autoretenedor: { required },
                    src_representante_legal: {
                        required: requiredIf(() => {
                            return !this.form.previewRepresentanteLegal;
                        }),
                        isPDF
                    },
                    src_camara_comercio: {
                        required: requiredIf(() => {
                            return !this.form.previewCamaraComercio;
                        }),
                        isPDF
                    },
                    src_rut: {
                        required: requiredIf(() => {
                            return !this.form.previewRut;
                        }),
                        isPDF
                    }
                },
                usuario: {
                    nombre: { required },
                    correo: { required, email },
                    contrasena: {
                        required: requiredIf(() => !this.onUpdate),
                        minLength: minLength(6)
                    },
                    confirmarContrasena: {
                        required: requiredIf(() => !this.onUpdate),
                        sameAsContrasena: sameAs('contrasena')
                    }
                }
            }
        };
    },
    async mounted() {
        await this.listarPermisos();
        await this.listarTipoEmpresas();
        await this.listarTipoDocumentos();
        await this.listarTipoSociedades();
        await this.cargarUbicaciones();
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
        // Cada vez que cambie el departamento se filtran los municipios
        'form.empresa.departamento_id'(newVal) {
            this.filtrarMunicipios();
            this.form.empresa.ciudad_id = null;
        }
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
        async listarTipoEmpresas() {
            let response = await axios.get('/listas/tipo-empresas');
            this.tiposEmpresa = response.data;
        },
        async listarTipoDocumentos() {
            let response = await axios.get('/listas/tipo-documentos');
            this.tiposDocumento = response.data;
        },
        async listarTipoSociedades() {
            let response = await axios.get('/listas/tipo-sociedades');
            this.tiposSociedad = response.data;
        },
        async cargarUbicaciones() {
            // Cargar municipios desde el JSON
            try {
                const [depResponse, munResponse] = await Promise.all([
                    axios.get('/json/departamentos.json'),
                    axios.get('/json/municipios.json')
                ]);

                if (depResponse.data && munResponse.data) {
                    this.ubicaciones.departamentos = depResponse.data;
                    this.municipiosData = munResponse.data;

                    this.filtrarMunicipios();
                } else {
                    console.error("Error: Datos inválidos en las respuestas");
                }
            } catch (error) {
                console.error('Error al cargar las ubicaciones', error);
            }
        },
        filtrarMunicipios() {
            const departamentoId = this.form.empresa.departamento_id;

            this.ubicaciones.ciudades = departamentoId
                ? this.municipiosData.filter(municipio => municipio.departamento_id === departamentoId)
                : [];
        },
        showModal(id) {
            this.$bvModal.show(id);
        },
        handleFileRepresentanteLegal(file) {
            this.form.documentacion.src_representante_legal = file;
            this.$bvModal.hide('representante-legal-modal');
        },
        handleFileCamaraComercio(file) {
            this.form.documentacion.src_camara_comercio = file;
            this.$bvModal.hide('camara-comercio-modal');
        },
        handleFileRut(file) {
            this.form.documentacion.src_rut = file;
            this.$bvModal.hide('rut-modal');
        },
        submitForm() {
            this.$v.$touch();
            if (!this.$v.$invalid) {
                this.$emit(!this.onUpdate ? 'create' : 'update', this.form);
            }
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

.form-group legend {
    font-size: 1rem;
}

.info-message {
    background-color: #f9fafb;
    padding: 15px 10px;
    font-size: 13px;
    border-radius: 5px;
    border-left: 4px solid #20a0e9;
}
</style>
