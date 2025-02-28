<template>
    <b-container fluid class="my-3">
        <FormVer :initialData="form" :user="usuarioComercial" @update="actualizarAreaComercial" />
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
                    ciudad_id: this.comercial.ciudad_id,
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
        actualizarAreaComercial(form) {
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
                formData.append('empresa_id', this.form.empresa_id);
                formData.append('consultas_diarias', this.form.consultas_diarias);
                formData.append('empresa', JSON.stringify(this.form.empresa));
                formData.append('personal', JSON.stringify(this.form.personal));
                formData.append('plataforma', JSON.stringify(this.form.plataforma));
                formData.append('src_documento_identidad', this.form.documentacion.src_documento_identidad);

                axios
                    .post(`/area-comerciales/${this.comercial.id}`, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                    .then(response => {
                        console.log('Area comercial actualizada con éxito:', response.data);
                        window.location.replace('/area-comerciales');
                    })
                    .catch(error => {
                        console.error('Error al actualizar Area comercial:', error);
                        this.$bvToast.toast('Revisa la consola para más detalles.', {
                            title: 'Oops, algo salió mal',
                            variant: 'danger',
                            solid: true
                        });
                    });
            } catch (error) {
                console.error(error);
            }
        }
    }
};
</script>
