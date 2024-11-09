<template>
	<b-container fluid class="my-3">
		<Form :form="form"></Form>
		<b-row align-h="end">
			<b-col cols="4">
				<CustomButton class="mt-4" @click="crearEmpresa">
					<PlusIcon></PlusIcon>
					Crear empresa
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
	data() {
		return {
			form: {
				tipo_empresa_id: '',
				consultas_diarias: '',
				empresa: {
					tipo_sociedad_id: '',
					nombre: '',
					tipo_documento_id: '',
					numero_documento: '',
					correo: '',
					pagina_web: '',
					pais: '',
					ciudad_id: '',
					direccion: '',
				},
				representante_legal: {
					nombres_completos: '',
					tipo_documento_id: '',
					numero_documento: '',
					nacionalidad: '',
					correo: '',
					numero_contacto: '',
				},
				documentacion: {
					iva: '',
					contribuyente: '',
					autoretenedor: '',
					src_representante_legal: '/doc',
					src_camara_comercio: '/doc',
					src_rut: '/doc',
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
			domBreadcumb.innerText = "> Empresas > Crear";
		},
		crearEmpresa() {
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