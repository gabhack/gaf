<template>
    <div>
        <h2 class="mb-5">Panel de {{ !onUpdate ? 'Creación' : 'Edición' }} Sedes</h2>
        <BForm @submit.prevent="submitForm">
            <b-row v-if="!isCompany">
                <b-col cols="4">
                    <h4>Asignar Empresa</h4>
                    <b-form-group label="Empresa" label-for="empresa_id">
                        <b-form-select
                            :state="validateState('form.empresa_id')"
                            value-field="id"
                            text-field="nombre"
                            id="empresa_id"
                            v-model="form.empresa_id"
                            :options="empresas"
                        >
                            <template #first>
                                <b-form-select-option :value="null" disabled> Seleccione empresa </b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="6">
                    <h4>Datos Sede</h4>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4">
                    <b-form-group label="Nombre" label-for="nombre">
                        <b-form-input
                            :state="validateState('form.nombre')"
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            placeholder="Sede principal"
                        ></b-form-input>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Departamento" label-for="departamento">
                        <b-form-select
                            :state="validateState('form.departamento_id')"
                            value-field="id"
                            text-field="nombre"
                            id="empresa_departamento"
                            v-model="form.departamento_id"
                            :options="ubicaciones.departamentos"
                        >
                            <template #first>
                                <b-form-select-option :value="null" disabled>
                                    Seleccione un departamento
                                </b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
                <b-col cols="4">
                    <b-form-group label="Ciudad" label-for="ciudad">
                        <b-form-select
                            :state="validateState('form.ciudad_id')"
                            value-field="id"
                            text-field="nombre"
                            id="ciudad"
                            v-model="form.ciudad_id"
                            :options="ubicaciones.ciudades"
                        >
                            <template #first>
                                <b-form-select-option :value="null" disabled>
                                    Seleccione una ciudad
                                </b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row class="mt-4">
                <b-col cols="4" align-self="end">
                    <CustomButton class="mb-3" type="submit">
                        <template v-if="!onUpdate">
                            <PlusIcon></PlusIcon>
                            Crear Sede
                        </template>
                        <template v-else> Guardar Cambios </template>
                    </CustomButton>
                </b-col>
            </b-row>
        </BForm>
    </div>
</template>

<script>
import _ from 'lodash';
import { required, numeric } from 'vuelidate/lib/validators';

import CustomButton from '../../customComponents/CustomButton.vue';
import PlusIcon from '../../icons/PlusIcon.vue';

export default {
    props: ['initialData', 'empresas', 'user'],
    components: {
        CustomButton,
        PlusIcon
    },
    data() {
        return {
            form: {
                empresa_id: null,
                nombre: '',
                departamento_id: null,
                ciudad_id: null
            },
            ubicaciones: {
                departamentos: [],
                ciudades: []
            },
            municipiosData: [],
        };
    },
    computed: {
        onUpdate() {
            return !!this.initialData;
        },
        isCompany() {
            return this.user.role.name === 'EMPRESA';
        },
    },
    validations() {
        return {
            form: {
                empresa_id: { required, numeric },
                nombre: { required },
                departamento_id: { required, numeric },
                ciudad_id: { required, numeric }
            }
        };
    },
    mounted() {
        this.cargarUbicaciones();

        if (this.isCompany) {
            this.form.empresa_id = this.user.empresa.id;
        }
    },
    watch: {
        initialData: {
            immediate: true,
            handler(newData) {
                if (newData) {
                    Object.assign(this.form, newData);
                }
            }
        },
        'form.departamento_id'(newVal) {
            this.filtrarMunicipios();
            // this.form.ciudad_id = null;
        }
    },
    methods: {
        validateState(name) {
            const validation = _.get(this.$v, name);
            if (!validation) return null;
            const { $dirty, $error } = validation;
            return $dirty ? !$error : null;
        },
        async cargarUbicaciones() {
            try {
                const [depResponse, munResponse] = await Promise.all([
                    axios.get('/json/departamentos.json'),
                    axios.get('/json/municipios.json')
                ]);

                if (depResponse.data && munResponse.data) {
                    this.ubicaciones.departamentos = depResponse.data;
                    this.municipiosData = munResponse.data;

                    this.filtrarMunicipios();
                } else {
                    console.error('Error: Datos inválidos en las respuestas');
                }
            } catch (error) {
                console.error('Error al cargar las ubicaciones', error);
            }
        },
        filtrarMunicipios() {
            const departamentoId = this.form.departamento_id;

            this.ubicaciones.ciudades = departamentoId
                ? this.municipiosData.filter(municipio => municipio.departamento_id === departamentoId)
                : [];
        },
        submitForm() {
            this.$v.$touch();
            if (!this.$v.$invalid) {
                this.$emit(!this.onUpdate ? 'create' : 'update', this.form);
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.form-control {
    background-color: white;
    border-radius: 5px;
    color: black;
    font-weight: 100;
    &:not(.is-valid, .is-invalid) {
        border-color: #b8bec5;
    }
    &:placeholder {
        font-weight: 100;
    }
}

.form-group legend {
    font-size: 1rem;
}
</style>
