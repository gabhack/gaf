<template>
    <b-container fluid class="my-3">
        <Form :initialData="form" @update="actualizarEmpresa" />
    </b-container>
</template>

<script>
import Form from './Form.vue';

export default {
    components: {
        Form
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
            domBreadcumb.innerText = '> Empresas > Editar';
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
        actualizarEmpresa(form) {
            // Invalid form
            if (!form) {
                this.$bvToast.toast('Corrige los campos marcados para continuar.', {
                    title: 'Formulario con errores',
                    variant: 'danger',
                    solid: true
                });

                return;
            }

            try {
                let formData = new FormData();
                formData.append('tipo_empresa_id', form.tipo_empresa_id);
                formData.append('consultas_diarias', form.consultas_diarias);
                formData.append('empresa', JSON.stringify(form.empresa));
                formData.append('representante_legal', JSON.stringify(form.representante_legal));
                formData.append('documentacion', JSON.stringify(this.buildDocumentacion(form)));
                formData.append('usuario', JSON.stringify(form.usuario));
                formData.append('src_representante_legal', form.documentacion.src_representante_legal);
                formData.append('src_camara_comercio', form.documentacion.src_camara_comercio);
                formData.append('src_rut', form.documentacion.src_rut);

                axios
                    .post(`/empresas/${this.empresa.id}`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                    .then(response => {
                        console.log('Empresa actualizada con éxito:', response.data);
                        window.location.replace('/empresas');
                    })
                    .catch(error => {
                        console.error('Error al actualizar empresa:', error);
                        this.$bvToast.toast('Revisa la consola para más detalles.', {
                            title: 'Oops, algo salió mal',
                            variant: 'danger',
                            solid: true
                        });
                    });
            } catch (error) {
                console.error(error);
            }
        },
        buildDocumentacion(form) {
            return {
                iva: form.documentacion.iva,
                contribuyente: form.documentacion.contribuyente,
                autoretenedor: form.documentacion.autoretenedor
            };
        }
    }
};
</script>
