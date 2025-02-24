<template>
    <b-container fluid class="my-3">
        <Form @submit="crearEmpresa" />
    </b-container>
</template>

<script>
import axios from 'axios';
import Form from './Form.vue';

export default {
    components: {
        Form
    },
    mounted() {
        this.setBreadcumb();
    },
    methods: {
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Empresas > Crear';
        },
        crearEmpresa(form) {
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
                    .post('/empresas', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then(response => {
                        console.log('Empresa creada con éxito:', response.data);
                        window.location.replace('/empresas');
                    })
                    .catch(error => {
                        console.error('Error al crear empresa:', error);
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
