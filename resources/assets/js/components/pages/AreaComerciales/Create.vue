<template>
    <b-container fluid class="my-3">
        <Form ref="formComponent"></Form>
        <b-row align-h="end">
            <b-col cols="4">
                <CustomButton class="mt-4" @click="crearAreaComercial">
                    <PlusIcon></PlusIcon>
                    Crear usuario
                </CustomButton>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import Form from './Form.vue';
import CustomButton from '../../customComponents/CustomButton.vue';
import PlusIcon from '../../icons/PlusIcon.vue';

export default {
    components: {
        Form,
        CustomButton,
        PlusIcon
    },
    mounted() {
        this.setBreadcumb();
    },
    methods: {
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Area comercial > Crear';
        },
        crearAreaComercial() {
            const formComponent = this.$refs.formComponent;
            if (!formComponent) {
                console.error('No se encontró el componente de formulario.');
                return;
            }

            const form = formComponent.submitForm();

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
                formData.append('consultas_diarias', form.consultas_diarias);
                formData.append('empresa', JSON.stringify(form.empresa));
                formData.append('personal', JSON.stringify(form.personal));
                formData.append('plataforma', JSON.stringify(form.plataforma));
                formData.append('src_documento_identidad', form.documentacion.tipo_documento);

                axios
                    .post('/area-comerciales', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then(response => {
                        console.log('Area comercial creada con éxito:', response.data);
                        window.location.replace('/area-comerciales');
                    })
                    .catch(error => {
                        console.log('Error al crear el área comercial:', error);
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
