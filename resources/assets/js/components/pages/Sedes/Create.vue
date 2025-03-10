<template>
    <b-container fluid class="my-3">
        <Form @create="crearSede" :empresas="empresas" :user="user" />
    </b-container>
</template>

<script>
import Form from './Form.vue';

export default {
    props: ['empresas', 'user'],
    components: {
        Form
    },
    mounted() {
        this.setBreadcumb();
    },
    methods: {
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Sedes > Crear';
        },
        crearSede(form) {
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
                    .post('/sedes', form)
                    .then(response => {
                        console.log('Empresa creada con éxito:', response.data);
                        window.location.replace('/sedes');
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
        }
    }
};
</script>
