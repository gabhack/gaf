<template>
	<div>
		<b-row>
			<b-col cols="6">
				<h4>Tipo de empresa</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Tipo empresa" label-for="tipo_empresa_id">
					<b-form-select value-field="id" text-field="nombre" class="custom-input" id="tipo_empresa_id"
						v-model="form.tipo_empresa_id" :options="tiposEmpresa"></b-form-select>
				</b-form-group>
			</b-col>
		</b-row>
		<b-row>
			<b-col cols="6">
				<h4>Límite de consultas</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Consultas diarias" label-for="consultas_diarias">
					<b-form-input class="custom-input" id="consultas_diarias" v-model="form.consultas_diarias" type="text"
						placeholder="50" required></b-form-input>
				</b-form-group>
			</b-col>
		</b-row>
		<b-row>
			<b-col cols="6">
				<h4>Datos empresa</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Nombre empresa" label-for="empresa_nombre">
					<b-form-input class="custom-input" id="empresa_nombre" v-model="form.empresa.nombre" type="text"
						placeholder="Empresa 1 SAS" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Tipo de documento" label-for="empresa_tipo_documento_id">
					<b-form-select value-field="id" text-field="nombre" id="empresa_tipo_documento_id"
						v-model="form.empresa.tipo_documento_id" :options="tiposDocumento"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Número documento" label-for="empresa_numero_documento">
					<b-form-input class="custom-input" id="empresa_numero_documento" v-model="form.empresa.numero_documento"
						type="number" placeholder="10253658596" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Tipo empresa" label-for="tipo_empresa_id">
					<b-form-select value-field="id" text-field="nombre" id="tipo_empresa_id"
						v-model="form.empresa.tipo_sociedad_id" :options="tiposSociedad"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Correo de usuario plataforma" label-for="empresa_correo">
					<b-form-input class="custom-input" id="empresa_correo" v-model="form.empresa.correo" type="email"
						placeholder="usuario1@gmail.com" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Página web" label-for="empresa_pagina_web">
					<b-form-input class="custom-input" id="empresa_pagina_web" v-model="form.empresa.pagina_web" type="text"
						placeholder="empresa1.com" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="País" label-for="empresa_pais">
					<b-form-input class="custom-input" id="empresa_pais" v-model="form.empresa.pais" type="text"
						placeholder="Colombia" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Ciudad" label-for="empresa_ciudad">
					<b-form-select value-field="id" text-field="ciudad" id="empresa_ciudad" v-model="form.empresa.ciudad_id"
						:options="ciudades"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Dirección" label-for="empresa_direccion">
					<b-form-input class="custom-input" id="empresa_direccion" v-model="form.empresa.direccion" type="text"
						placeholder="CL 8 BIS A #76-09" required></b-form-input>
				</b-form-group>
			</b-col>
		</b-row>
		<b-row>
			<b-col cols="6">
				<h4>Datos representante legal</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Nombre y apellido" label-for="representante_nombres_completos">
					<b-form-input class="custom-input" id="representante_nombres_completos"
						v-model="form.representante_legal.nombres_completos" type="text" placeholder="Danilo perez"
						required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Tipo de documento" label-for="representante_tipo_documento_id">
					<b-form-select value-field="id" text-field="nombre" id="representante_tipo_documento_id"
						v-model="form.representante_legal.tipo_documento_id" :options="tiposDocumento"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Número de documento" label-for="representante_legal_numero_documento">
					<b-form-input class="custom-input" id="representante_legal_numero_documento"
						v-model="form.representante_legal.numero_documento" type="number" placeholder="10253658596"
						required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Nacionalidad" label-for="representante_legal_nacionalidad">
					<b-form-input class="custom-input" id="representante_legal_nacionalidad"
						v-model="form.representante_legal.nacionalidad" type="text" placeholder="Colombia" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Correo de contacto" label-for="representante_legal_correo">
					<b-form-input class="custom-input" id="representante_legal_correo" v-model="form.representante_legal.correo"
						type="email" placeholder="representante1@gmail.com" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Número de contacto" label-for="representante_legal_numero_contacto">
					<b-form-input class="custom-input" id="representante_legal_numero_contacto"
						v-model="form.representante_legal.numero_contacto" type="number" placeholder="3214567865"
						required></b-form-input>
				</b-form-group>
			</b-col>
		</b-row>
		<b-row>
			<b-col cols="6">
				<h4>Documentación</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Responsable IVA" label-for="documentacion_iva">
					<b-form-select value-field="id" text-field="nombre" id="documentacion_iva" v-model="form.documentacion.iva"
						:options="booleanValores">
					</b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Gran contribuyente" label-for="documentacion_contribuyente">
					<b-form-select value-field="id" text-field="nombre" id="documentacion_contribuyente"
						v-model="form.documentacion.contribuyente" :options="booleanValores">
					</b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Auto retenedor" label-for="documentacion_autoretenedor">
					<b-form-select value-field="id" text-field="nombre" id="documentacion_autoretenedor"
						v-model="form.documentacion.autoretenedor" :options="booleanValores">
					</b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Auto retenedor" label-for="documentacion_autoretenedor">
					<CustomButton @click="showModal">
						<PlusIcon></PlusIcon>
						Documento representante legal
					</CustomButton>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Auto retenedor" label-for="documentacion_autoretenedor">
					<CustomButton>
						<PlusIcon></PlusIcon>
						Camara de comercio
					</CustomButton>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Auto retenedor" label-for="documentacion_autoretenedor">
					<CustomButton>
						<PlusIcon></PlusIcon>
						RUT
					</CustomButton>
				</b-form-group>
			</b-col>
		</b-row>
		<b-modal id="my-modal" title="RUT" @hide="resetModal" @ok="handleOk" ok-title="Aceptar" cancel-title="Cancelar">
			<div class="info-message">
				<InfoCircleIcon></InfoCircleIcon>
				Por favor, suba un archivo en pdf con el RUT menor a 90 dias.
			</div>
		</b-modal>
	</div>
</template>
<script>
import axios from 'axios';
import CustomButton from '../../customComponents/CustomButton.vue';
import InfoCircleIcon from '../../icons/InfoCircleIcon.vue';
import PlusIcon from '../../icons/PlusIcon.vue';
export default {
	components: {
		CustomButton,
		InfoCircleIcon,
		PlusIcon
	},
	props: {
		form: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			tiposEmpresa: [],
			tiposSociedad: [],
			tiposDocumento: [],
			ciudades: [],
			booleanValores: [
				{ id: '', nombre: 'SI - NO' },
				{ id: 1, nombre: 'SI' },
				{ id: 0, nombre: 'NO' },
			]
		}
	},
	async mounted() {
		await this.listarTipoEmpresas();
		await this.listarTipoDocumentos();
		await this.listarTipoSociedades();
		await this.listarTipoCiudades();
	},
	methods: {
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
		async listarTipoCiudades() {
			let response = await axios.get('/listas/ciudades');
			this.ciudades = response.data;
		},
		showModal() {
			this.$bvModal.show('my-modal');
		},
		resetModal() {

		},
		handleOk() {

		}
	}
}
</script>
<style scoped>
.custom-input {
	background-color: white;
	border-radius: 5px;
	border: 0.5px solid #B8BEC5;
	color: black;
	font-weight: 100;
}

.custom-input::placeholder {
	font-weight: 100;
}

.info-message {
	background-color: #F9FAFB;
	padding: 15px 10px 15px 10px;
	font-size: 13px;
	border-radius: 5px;
	border-left-width: 50px;
	border-left: 4px solid #20A0E9;
}
</style>