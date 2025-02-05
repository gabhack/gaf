<template>
    <div>
        <b-row>
            <b-col cols="6">
                <h2 class="mb-5">Panel de Creación Usuarios</h2>
            </b-col>
        </b-row>
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
                        text-field="ciudad"
                        class="custom-input"
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
                        class="custom-input"
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
                        class="custom-input"
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
                        class="custom-input"
                        id="consultas_diarias"
                        v-model="form.consultas_diarias"
                        type="text"
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
                        class="custom-input"
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
                        class="custom-input"
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
                        class="custom-input"
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
                        class="custom-input"
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
                        class="custom-input"
                        id="personal_numero_contacto"
                        v-model="form.personal.numero_contacto"
                        type="number"
                        placeholder="3214556756"
                        required
                    ></b-form-input>
                </b-form-group>
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
                        class="custom-input"
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
                        class="custom-input"
                        id="plataforma_hego"
                        v-model="form.plataforma.hego_id"
                        :options="hegos"
                    ></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>
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

export default {
    components: {
        CustomButton,
        PlusIcon,
        LiteModal,
        InfoCircleIcon,
        FileInput
    },
    props: {
        form: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            ciudades: [],
            sedes: [],
            cargos: [],
            tipoDocumentos: [],
            amis: [],
            hegos: []
        };
    },
    async mounted() {
        await this.listarCiudades();
        await this.listarSedesEdit();
        await this.listarCargos();
        await this.listarTiposDocumento();
        await this.listarAmis();
        await this.listarHegos();
    },
    methods: {
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
