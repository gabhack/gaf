<template>
	<div>
		<b-row>
			<b-col cols="6">
				<h4>Datos de empresa</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Ciudad" label-for="empresa_ciudad">
					<b-form-select class="custom-input" id="empresa_ciudad" v-model="form.empresa.ciudad"
						:options="tiposEmpresa"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Sede" label-for="empresa_sede">
					<b-form-select class="custom-input" id="empresa_sede" v-model="form.empresa.sede"
						:options="tiposEmpresa"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Cargo" label-for="empresa_cargo">
					<b-form-select class="custom-input" id="empresa_cargo" v-model="form.empresa.cargo"
						:options="tiposEmpresa"></b-form-select>
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
				<h4>Datos de personal</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="Nombre y apellido" label-for="personal_nombre_apellido">
					<b-form-input class="custom-input" id="personal_nombre_apellido" v-model="form.personal.nombre_apellido"
						type="text" placeholder="Danilo perez" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Tipo de documento" label-for="personal_tipo_documento">
					<b-form-select id="tipo_personal_id" v-model="form.personal.tipo_documento"
						:options="tipoDocumentos"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Número documento" label-for="personal_numero_documento">
					<b-form-input class="custom-input" id="personal_numero_documento" v-model="form.personal.numero_documento"
						type="number" placeholder="10253658596" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Nacionalidad" label-for="personal_nacionalidad">
					<b-form-input class="custom-input" id="personal_nacionalidad" v-model="form.personal.nacionalidad" type="text"
						placeholder="Colombia" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Correo de contacto" label-for="personal_correo_contacto">
					<b-form-input class="custom-input" id="personal_correo_contacto" v-model="form.personal.correo_contacto"
						type="email" placeholder="usuario1@gmail.com" required></b-form-input>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="Número de contacto" label-for="personal_numero_contacto">
					<b-form-input class="custom-input" id="personal_numero_contacto" v-model="form.personal.numero_contacto"
						type="number" placeholder="3214556756" required></b-form-input>
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
				<b-form-group label="Documento de identidad de usuario" label-for="documentacion_autoretenedor">
					<CustomButton>Documento de identidad</CustomButton>
				</b-form-group>
			</b-col>
		</b-row>
		<b-row>
			<b-col cols="6">
				<h4>Secciones en plataforma</h4>
			</b-col>
		</b-row>
		<b-row class="mt-4">
			<b-col cols="4">
				<b-form-group label="AMI(Análisis de mercado inteligente)" label-for="plataforma_ami">
					<b-form-select class="custom-input" id="plataforma_ami" v-model="form.plataforma.ami"
						:options="tiposEmpresa"></b-form-select>
				</b-form-group>
			</b-col>
			<b-col cols="4">
				<b-form-group label="HEGO" label-for="plataforma_hego">
					<b-form-select class="custom-input" id="plataforma_hego" v-model="form.plataforma.hego"
						:options="tiposEmpresa"></b-form-select>
				</b-form-group>
			</b-col>
		</b-row>
	</div>
</template>
<script>
import CustomButton from '../../customComponents/CustomButton.vue';
export default {
	components: {
		CustomButton
	},
	data() {
		return {
			tiposEmpresa: [
				{ value: '', text: 'Elija un tipo de empresa' },
				{ value: 1, text: 'Fondo de inversion' },
				{ value: 2, text: 'Fiduciaria' },
				{ value: 3, text: 'Originadores' }
			],
			tipoDocumentos: [
				{ value: '', text: 'Seleccione un tipo' },
				{ value: 1, text: 'Cédula de ciudadanía' },
				{ value: 2, text: 'Cédula de extranjería' },
				{ value: 3, text: 'Pasaporte' },
				{ value: 4, text: 'NIT' }
			],
			booleanValores: [
				{ value: '', text: 'SI - NO' },
				{ value: 1, text: 'SI' },
				{ value: 0, text: 'NO' },
			],
			form: {
				empresa: {
					ciudad: '',
					sede: '',
					cargo: '',
				},
				personal: {
					nombre_apellido: '',
					tipo_documento: '',
					numero_documento: '',
					nacionalidad: '',
					correo_contacto: '',
					numero_contacto: '',
				},
				consultas_diarias: '',
				documentacion: {
					tipo_documento: '',
				},
				plataforma: {
					ami: '',
					hego: '',
				}
			}
		}
	}
}
</script>
<style scoped>
.custom-input {
	background-color: white;
	border-radius: 5px;
	border: 0.5px solid #B8BEC5;
}

.custom-input::placeholder {
	font-weight: 100;
}
</style>