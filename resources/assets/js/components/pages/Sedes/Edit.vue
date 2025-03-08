<template>
    <b-container fluid class="my-3">
        <Form :initialData="form" :empresas="empresas" @update="actualizarSede" />
    </b-container>
</template>

<script>
import Form from './Form.vue';

export default {
    props: {
        sede: {
            type: Object,
            required: true
        },
        empresas: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            form: {}
        };
    },
    components: {
        Form
    },
    mounted() {
        this.setBreadcumb();
        this.inicializarDatos();
    },
    methods: {
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Sedes > Editar';
        },
        async inicializarDatos() {
            this.form = {
                empresa_id: this.sede.empresa_id,
                nombre: this.sede.nombre,
                departamento_id: this.sede.departamento_id,
                ciudad_id: this.sede.ciudad_id,
            };
        },
        actualizarSede(form) {
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
                axios
                    .put(`/sedes/${this.sede.id}`, form)
                    .then(response => {
                        console.log('Sede actualizada con éxito:', response.data);
                        window.location.replace('/sedes');
                    })
                    .catch(error => {
                        console.error('Error al actualizar sede:', error);
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
    }
};
</script>
