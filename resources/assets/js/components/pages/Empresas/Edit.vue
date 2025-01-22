<template>
	<b-container fluid class="my-3">
		<Form :form="form"></Form>
		<b-row align-h="end">
			<b-col cols="4">
				<CustomButton class="mt-4" @click="actualizarEmpresa">
					<PlusIcon></PlusIcon>
					Actualizar empresa
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
		},
		representanteLegal: {
			type: Object,
			required: true
		},
		documentoEmpresa: {
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
					nombres_completos: this.representanteLegal.nombres_completos,
					tipo_documento_id: this.representanteLegal.tipo_documento_id,
					numero_documento: this.representanteLegal.numero_documento,
					nacionalidad: this.representanteLegal.nacionalidad,
					correo: this.representanteLegal.correo,
					numero_contacto: this.representanteLegal.numero_contacto,
				},
				documentacion: {
					iva: this.documentoEmpresa.iva,
					contribuyente: this.documentoEmpresa.contribuyente,
					autoretenedor: this.documentoEmpresa.autoretenedor,
					src_representante_legal: '',
					src_camara_comercio: '',
					src_rut: '',
				},
				previewRepresentanteLegal: this.documentoEmpresa.src_representante_legal,
				previewCamaraComercio: this.documentoEmpresa.src_camara_comercio,
				previewRut: this.documentoEmpresa.src_rut,
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
		actualizarEmpresa() {
			let formData = new FormData();
			formData.append('tipo_empresa_id', this.form.tipo_empresa_id);
			formData.append('consultas_diarias', this.form.consultas_diarias);
			formData.append('empresa', JSON.stringify(this.form.empresa));
			formData.append('representante_legal', JSON.stringify(this.form.representante_legal));
			formData.append('documentacion', JSON.stringify(this.buildDocumentacion()));
			formData.append('src_representante_legal', this.form.documentacion.src_representante_legal);
			formData.append('src_camara_comercio', this.form.documentacion.src_camara_comercio);
			formData.append('src_rut', this.form.documentacion.src_rut);
			axios.post('/empresas/' + this.empresa.id, formData).then((response) => {
				window.location.replace('/empresas');
			}).catch((error) => {
				console.log(error);
			});
		},
		buildDocumentacion() {
			return {
				iva: this.form.documentacion.iva,
				contribuyente: this.form.documentacion.contribuyente,
				autoretenedor: this.form.documentacion.autoretenedor,
			};
		}
	}
}
</script>