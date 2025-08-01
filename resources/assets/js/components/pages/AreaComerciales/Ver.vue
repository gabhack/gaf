<template>
    <b-container fluid class="my-3">
        <FormVer :initialData="form" :user="usuarioComercial"/>
    </b-container>
</template>

<script>
import FormVer from './FormVer.vue';

export default {
    components: {
        FormVer
    },
    props: {
        comercial: {
            type: Object,
            required: true
        },
        usuarioComercial: {
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
            domBreadcumb.innerText = '> Area comercial > Editar';
        },
        inicializarDatos() {
            this.form = {
                empresa_id: this.comercial.empresa_id,
                empresa: {
                    departamento_id: this.comercial.sede.departamento_id,
                    ciudad_id: this.comercial.sede.ciudad_id,
                    sede_id: this.comercial.sede_id,
                    cargo_id: this.comercial.cargo_id,
                },
                consultas_diarias: this.comercial.consultas_diarias,
                personal: {
                    nombre_apellido: this.comercial.nombre_completo,
                    tipo_documento_id: this.comercial.tipo_documento_id,
                    numero_documento: this.comercial.numero_documento,
                    nacionalidad: this.comercial.nacionalidad,
                    correo_contacto: this.comercial.correo,
                    numero_contacto: this.comercial.numero_contacto,
                    permisos: this.usuarioComercial.direct_permissions
                },
                documentacion: {
                    src_documento_identidad: ''
                },
                previewDocumentoIdentidad: this.comercial.src_documento_identidad,
                plataforma: {
                    ami_id: this.comercial.ami_id,
                    hego_id: this.comercial.hego_id,
                }
            };
        },
    }
};
</script>
