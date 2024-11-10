<template>
	<b-container fluid class="my-3">
		<Form :form="form"></Form>
		<b-row align-h="end">
			<b-col cols="4">
				<CustomButton class="mt-4" @click="actualizarEmpresa">
					<PlusIcon></PlusIcon>
					Actualizar usuario
				</CustomButton>
			</b-col>
		</b-row>
	</b-container>
</template>
<script>
import Form from './Form.vue';
import CustomButton from '../../customComponents/CustomButton.vue'
import PlusIcon from '../../icons/PlusIcon.vue';
import axios from 'axios';
export default {
	components: {
		Form,
		CustomButton,
		PlusIcon
	},
	props: {
		comercial: {
			type: Object,
			required: true
		},
	},
	data() {
		return {
			form: {
				empresa: {
					ciudad_id: this.comercial.ciudad_id,
					sede_id: this.comercial.sede_id,
					cargo_id: this.comercial.cargo_id,
				},
				personal: {
					nombre_apellido: this.comercial.nombre_completo,
					tipo_documento_id: this.comercial.tipo_documento_id,
					numero_documento: this.comercial.numero_documento,
					nacionalidad: this.comercial.nacionalidad,
					correo_contacto: this.comercial.correo,
					numero_contacto: this.comercial.numero_contacto,
				},
				consultas_diarias: this.comercial.consultas_diarias,
				documentacion: {
					tipo_documento: '',
				},
				plataforma: {
					ami_id: this.comercial.ami_id,
					hego_id: this.comercial.hego_id,
				}
			}
		}
	},
	mounted() {
		this.setBreadcumb();
	},
	methods: {
		setBreadcumb() {
			let domBreadcumb = document.getElementById('dynamic-breadcumb');
			domBreadcumb.innerText = "> Empresas > Editar";
		},
		actualizarEmpresa() {
			let formData = JSON.parse(JSON.stringify(this.form));
			formData.representante_legal = JSON.stringify(formData.representante_legal);
			formData.empresa = JSON.stringify(formData.empresa);
			formData.documentacion = JSON.stringify(formData.documentacion);
			axios.post('/empresas', formData).then((response) => {
				window.location.replace('/empresas');
			}).catch((error) => {
				console.log(error);
			});
		}
	}
}
</script>