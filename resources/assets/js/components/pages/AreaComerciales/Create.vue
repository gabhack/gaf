<template>
	<b-container fluid class="my-3">
		<Form :form="form"></Form>
		<b-row align-h="end">
			<b-col cols="4">
				<CustomButton class="mt-4" @click="crearAreaComercial">
					<PlusIcon></PlusIcon>
					Crear usuario
				</CustomButton>
			</b-col>
		</b-row>
	</b-container>
</template>
<script>
import Form from './Form.vue';
import CustomButton from '../../customComponents/CustomButton.vue'
import PlusIcon from '../../icons/PlusIcon.vue';
export default {
	components: {
		Form,
		CustomButton,
		PlusIcon
	},
	data() {
		return {
			form: {
				empresa: {
					ciudad_id: '',
					sede_id: '',
					cargo_id: '',
				},
				personal: {
					nombre_apellido: '',
					tipo_documento_id: '',
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
					ami_id: '',
					hego_id: '',
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
			domBreadcumb.innerText = "> Area comercial > Crear";
		},
		crearAreaComercial() {
			let formData = new FormData();
			formData.append('consultas_diarias', this.form.consultas_diarias);
			formData.append('empresa', JSON.stringify(this.form.empresa));
			formData.append('personal', JSON.stringify(this.form.personal));
			formData.append('plataforma', JSON.stringify(this.form.plataforma));
			formData.append('src_documento_identidad', this.form.documentacion.tipo_documento);
			axios.post('/area-comerciales', formData, { headers: { 'Content-Type': 'multipart/form-data' } }).then((response) => {
				window.location.replace('/area-comerciales');
			}).catch((error) => {
				console.log(error);
			});
		},
	}
}
</script>