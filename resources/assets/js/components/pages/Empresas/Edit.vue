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
	props: {
		empresa: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			form: {
				tipo_empresa_id: this.empresa.tipo_empresa_id,
				consultas_diarias: this.empresa.consultas_diarias,
				empresa: {
					tipo_sociedad_id: this.empresa.tipo_sociedad_id,
					nombre: this.empresa.nombre,
					tipo_documento_id: this.empresa.tipo_documento_id,
					numero_documento: this.empresa.numero_documento,
					correo: this.empresa.correo,
					pagina_web: this.empresa.pagina_web,
					pais: this.empresa.pais,
					ciudad_id: this.empresa.ciudad_id,
					direccion: this.empresa.direccion
				},
				representante_legal: {
					nombres_completos: '',
					tipo_documento_id: '',
					numero_documento: '',
					nacionalidad: '',
					correo_contacto: '',
					numero_contacto: '',
				},
				documentacion: {
					responsable_iva: '',
					gran_contribuyente: '',
					autor_retenedor: '',
					archivo_representante_legal: '',
					archivo_camara_comercio: '',
					archivo_rut: '',
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