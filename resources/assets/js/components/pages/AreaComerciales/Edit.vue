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
				},
				previewDocumentoIdentidad: this.comercial.src_documento_identidad,
			},
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
			let formData = new FormData();
			formData.append('consultas_diarias', this.form.consultas_diarias);
			formData.append('empresa', JSON.stringify(this.form.empresa));
			formData.append('personal', JSON.stringify(this.form.personal));
			formData.append('plataforma', JSON.stringify(this.form.plataforma));
			formData.append('src_documento_identidad', this.form.documentacion.tipo_documento);
			axios.post('/area-comerciales/' + this.comercial.id, formData, { headers: { 'Content-Type': 'multipart/form-data' } }).then((response) => {
				window.location.replace('/area-comerciales');
			}).catch((error) => {
				console.log(error);
			});
		}
	}
}
</script>