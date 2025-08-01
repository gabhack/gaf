<template>
    <b-container fluid class="my-3">
        <FormVer :initialData="form" />
    </b-container>
</template>

<script>
import FormVer from './FormVer.vue';

export default {
    components: {
        FormVer
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
        },
        usuarioEmpresa: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: {}
        };
    },
    mounted() {
        this.setBreadcumb();
        this.inicializarDatos();
    },
    methods: {
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Empresas > Ver';
        },
        inicializarDatos() {
            this.form = {
                tipo_empresa_id: this.empresa.tipo_empresa_id,
                consultas_diarias: this.empresa.consultas_diarias,
                empresa: {
                    tipo_sociedad_id: this.empresa.tipo_sociedad_id,
                    nombre: this.empresa.nombre,
                    tipo_documento_id: this.empresa.tipo_documento_id,
                    numero_documento: this.empresa.numero_documento,
                    correo: this.empresa.correo,
                    pagina_web: this.empresa.pagina_web,
                    pais_id: this.empresa.pais_id,
                    departamento_id: this.empresa.departamento_id,
                    ciudad_id: this.empresa.ciudad_id,
                    direccion: this.empresa.direccion
                },
                representante_legal: {
                    nombres_completos: this.representanteLegal.nombres_completos,
                    tipo_documento_id: this.representanteLegal.tipo_documento_id,
                    numero_documento: this.representanteLegal.numero_documento,
                    nacionalidad: this.representanteLegal.nacionalidad,
                    correo: this.representanteLegal.correo,
                    numero_contacto: this.representanteLegal.numero_contacto
                },
                documentacion: {
                    iva: this.documentoEmpresa.iva,
                    contribuyente: this.documentoEmpresa.contribuyente,
                    autoretenedor: this.documentoEmpresa.autoretenedor,
                    src_representante_legal: '',
                    src_camara_comercio: '',
                    src_rut: ''
                },
                previewRepresentanteLegal: this.documentoEmpresa.src_representante_legal,
                previewCamaraComercio: this.documentoEmpresa.src_camara_comercio,
                previewRut: this.documentoEmpresa.src_rut,
                usuario: {
                    nombre: this.usuarioEmpresa.name,
                    correo: this.usuarioEmpresa.email,
                    permisos: this.usuarioEmpresa.direct_permissions
                }
            };
        },
    }
};
</script>
