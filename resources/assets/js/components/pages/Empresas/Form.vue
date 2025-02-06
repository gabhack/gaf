<template>
    <div>
        <b-row>
            <b-col cols="6">
                <h2 class="mb-5">Panel de Creación Empresas</h2>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="6">
                <h4>Tipo de Empresa</h4>
            </b-col>
        </b-row>
        <b-row class="mt-4">
            <b-col cols="4">
                <b-form-group label="Tipo Empresa" label-for="tipo_empresa_id">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        class="custom-input"
                        id="tipo_empresa_id"
                        v-model="form.tipo_empresa_id"
                        :options="tiposEmpresa"
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
                <div class="mb-3">
                    <label for="consultas_diarias">Consultas Diarias</label>
                    <multiselect
                        id="consultas_diarias"
                        v-model="form.consultas_diarias"
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
                <h4>Datos Empresa</h4>
            </b-col>
        </b-row>
        <b-row class="mt-4">
            <b-col cols="4">
                <b-form-group label="Nombre Empresa" label-for="empresa_nombre">
                    <b-form-input
                        class="custom-input"
                        id="empresa_nombre"
                        v-model="form.empresa.nombre"
                        type="text"
                        placeholder="Empresa 1 SAS"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Tipo de Documento" label-for="empresa_tipo_documento_id">
                    <b-form-select
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
                        class="custom-input"
                        id="empresa_numero_documento"
                        v-model="form.empresa.numero_documento"
                        type="number"
                        placeholder="10253658596"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Tipo Sociedad" label-for="tipo_empresa_id">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        id="tipo_empresa_id"
                        v-model="form.empresa.tipo_sociedad_id"
                        :options="tiposSociedad"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="E-mail Registrado por la Compañía" label-for="empresa_correo">
                    <b-form-input
                        class="custom-input"
                        id="empresa_correo"
                        v-model="form.empresa.correo"
                        type="email"
                        placeholder="usuario1@gmail.com"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Página Web" label-for="empresa_pagina_web">
                    <b-form-input
                        class="custom-input"
                        id="empresa_pagina_web"
                        v-model="form.empresa.pagina_web"
                        type="text"
                        placeholder="empresa1.com"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="País" label-for="empresa_pais">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        id="empresa_pais"
                        v-model="form.empresa.pais_id"
                        :options="ubicaciones.paises"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Departamento" label-for="empresa_departamento">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        id="empresa_departamento"
                        v-model="form.empresa.departamento_id"
                        :options="ubicaciones.departamentos"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Ciudad" label-for="empresa_ciudad">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        id="empresa_ciudad"
                        v-model="form.empresa.ciudad_id"
                        :options="ubicaciones.ciudades"
                    ></b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Dirección" label-for="empresa_direccion">
                    <b-form-input
                        class="custom-input"
                        id="empresa_direccion"
                        v-model="form.empresa.direccion"
                        type="text"
                        placeholder="CL 8 BIS A #76-09"
                        required
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
                        class="custom-input"
                        id="representante_nombres_completos"
                        v-model="form.representante_legal.nombres_completos"
                        type="text"
                        placeholder="Danilo Perez"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Tipo de Documento" label-for="representante_tipo_documento_id">
                    <b-form-select
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
                        class="custom-input"
                        id="representante_legal_numero_documento"
                        v-model="form.representante_legal.numero_documento"
                        type="number"
                        placeholder="10253658596"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Nacionalidad" label-for="representante_legal_nacionalidad">
                    <b-form-input
                        class="custom-input"
                        id="representante_legal_nacionalidad"
                        v-model="form.representante_legal.nacionalidad"
                        type="text"
                        placeholder="Colombia"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Correo de Contacto" label-for="representante_legal_correo">
                    <b-form-input
                        class="custom-input"
                        id="representante_legal_correo"
                        v-model="form.representante_legal.correo"
                        type="email"
                        placeholder="representante1@gmail.com"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Número de Contacto" label-for="representante_legal_numero_contacto">
                    <b-form-input
                        class="custom-input"
                        id="representante_legal_numero_contacto"
                        v-model="form.representante_legal.numero_contacto"
                        type="number"
                        placeholder="3214567865"
                        required
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
                        value-field="id"
                        text-field="nombre"
                        id="documentacion_iva"
                        v-model="form.documentacion.iva"
                        :options="booleanValores"
                    >
                    </b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Gran Contribuyente" label-for="documentacion_contribuyente">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        id="documentacion_contribuyente"
                        v-model="form.documentacion.contribuyente"
                        :options="booleanValores"
                    >
                    </b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Auto Retenedor" label-for="documentacion_autoretenedor">
                    <b-form-select
                        value-field="id"
                        text-field="nombre"
                        id="documentacion_autoretenedor"
                        v-model="form.documentacion.autoretenedor"
                        :options="booleanValores"
                    >
                    </b-form-select>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Auto Retenedor" label-for="documentacion_autoretenedor">
                    <CustomButton @click="showModal('representante-legal-modal')">
                        <PlusIcon></PlusIcon>
                        Documento representante legal
                    </CustomButton>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Auto Retenedor" label-for="documentacion_autoretenedor">
                    <CustomButton @click="showModal('camara-comercio-modal')">
                        <PlusIcon></PlusIcon>
                        Camara de comercio
                    </CustomButton>
                </b-form-group>
            </b-col>
            <b-col cols="4">
                <b-form-group label="Auto Retenedor" label-for="documentacion_autoretenedor">
                    <CustomButton @click="showModal('rut-modal')">
                        <PlusIcon></PlusIcon>
                        RUT
                    </CustomButton>
                </b-form-group>
            </b-col>
        </b-row>
        <LiteModal
            id="representante-legal-modal"
            title="Documento de Representante Legal"
            :preview-document="form.previewRepresentanteLegal"
        >
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en pdf con el documento por ambos lados.
                </div>
                <FileInput @handleFileInput="handleFileRepresentanteLegal"></FileInput>
            </template>
        </LiteModal>
        <LiteModal id="camara-comercio-modal" title="Camara de Comercio" :preview-document="form.previewCamaraComercio">
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en pdf con la camara de comercio menor a 90 dias.
                </div>
                <FileInput @handleFileInput="handleFileCamaraComercio"></FileInput>
            </template>
        </LiteModal>
        <LiteModal id="rut-modal" title="RUT" :preview-document="form.previewRut">
            <template #modal-content>
                <div class="info-message">
                    <InfoCircleIcon></InfoCircleIcon>
                    Por favor, suba un archivo en pdf con el RUT menor a 90 dias.
                </div>
                <FileInput @handleFileInput="handleFileRut"></FileInput>
            </template>
        </LiteModal>
    </div>
</template>

<script>
import axios from 'axios';
import CustomButton from '../../customComponents/CustomButton.vue';
import InfoCircleIcon from '../../icons/InfoCircleIcon.vue';
import PlusIcon from '../../icons/PlusIcon.vue';
import FileInput from '../../customComponents/FileInput.vue';
import LiteModal from '../../customComponents/LiteModal.vue';
import Multiselect from 'vue-multiselect';

export default {
    components: {
        CustomButton,
        InfoCircleIcon,
        PlusIcon,
        FileInput,
        LiteModal,
        Multiselect
    },
    props: {
        form: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            permisos: [],
            tiposEmpresa: [],
            tiposSociedad: [],
            tiposDocumento: [],
            ubicaciones: [],
            booleanValores: [
                { id: 1, nombre: 'SI' },
                { id: 0, nombre: 'NO' }
            ]
        };
    },
    async mounted() {
        await this.listarPermisos();
        await this.listarTipoEmpresas();
        await this.listarTipoDocumentos();
        await this.listarTipoSociedades();
        await this.listarUbicaciones();
    },
    watch: {
        'form.empresa.pais_id': async function () {
            this.form.empresa.departamento_id = null;
            await this.listarUbicaciones();
        },
        'form.empresa.departamento_id': async function () {
            this.form.empresa.ciudad_id = null;
            await this.listarUbicaciones();
        }
    },
    methods: {
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
        async listarUbicaciones() {
            const response = await axios.get('/listas/ubicaciones', {
                params: {
                    pais: this.form.empresa.pais_id,
                    departamento: this.form.empresa.departamento_id
                }
            });

            this.ubicaciones = response.data;
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
        }
    }
};
</script>

<style scoped>
.custom-input {
    background-color: white;
    border-radius: 5px;
    border: 0.5px solid #b8bec5;
    color: black;
    font-weight: 100;
}

.custom-input::placeholder {
    font-weight: 100;
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
