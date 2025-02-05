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
import CustomButton from '../../customComponents/CustomButton.vue';
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
                tipo_empresa_id: null,
                consultas_diarias: '',
                empresa: {
                    tipo_sociedad_id: null,
                    nombre: '',
                    tipo_documento_id: null,
                    numero_documento: '',
                    correo: '',
                    pagina_web: '',
                    pais_id: 1,
                    departamento_id: null,
                    ciudad_id: null,
                    direccion: ''
                },
                representante_legal: {
                    nombres_completos: '',
                    tipo_documento_id: null,
                    numero_documento: '',
                    nacionalidad: '',
                    correo: '',
                    numero_contacto: ''
                },
                documentacion: {
                    iva: '',
                    contribuyente: '',
                    autoretenedor: '',
                    src_representante_legal: '',
                    src_camara_comercio: '',
                    src_rut: ''
                }
            }
        };
    },
    mounted() {
        this.setBreadcumb();
    },
    methods: {
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Empresas > Crear';
        },
        crearEmpresa() {
            let formData = new FormData();
            formData.append('tipo_empresa_id', this.form.tipo_empresa_id);
            formData.append('consultas_diarias', this.form.consultas_diarias);
            formData.append('empresa', JSON.stringify(this.form.empresa));
            formData.append('representante_legal', JSON.stringify(this.form.representante_legal));
            formData.append('documentacion', JSON.stringify(this.buildDocumentacion()));
            formData.append('src_representante_legal', this.form.documentacion.src_representante_legal);
            formData.append('src_camara_comercio', this.form.documentacion.src_camara_comercio);
            formData.append('src_rut', this.form.documentacion.src_rut);

            axios
                .post('/empresas', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(response => {
                    window.location.replace('/empresas');
                })
                .catch(error => {
                    console.log(error);
                });
        },
        buildDocumentacion() {
            return {
                iva: this.form.documentacion.iva,
                contribuyente: this.form.documentacion.contribuyente,
                autoretenedor: this.form.documentacion.autoretenedor
            };
        }
    }
};
</script>
