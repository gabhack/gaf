<template>
    <b-container fluid class="my-3">
        <b-row align-v="center">
            <b-col>
                <h4>Listado de Sedes</h4>
            </b-col>
            <b-col class="text-right">
                <CustomButton href="/sedes/crear">
                    <PlusIcon />
                    Crear Sede
                </CustomButton>
            </b-col>
        </b-row>
        <b-row v-if="ciudades.length > 0">
            <b-col class="mt-4">
                <DataTable :items="sedes" :columns="isAdmin ? fieldsAdmin : fields" @updateRows="updateRows" @pageChanged="pageChanged">
                    <template #id="data">
                        <span class="font-bold" v-text="data.data.id"></span>
                    </template>
                    <template #actions="data">
                        <b-button variant="outline-light" size="sm" :href="`/sedes/editar/${data.data.id}`">
                            <EditIcon />
                        </b-button>
                        <b-button variant="outline-light" size="sm" @click="eliminarSede(data.data.id)">
                            <TrashIcon />
                        </b-button>
                    </template>
                </DataTable>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import CustomButton from '../../customComponents/CustomButton.vue';
import DataTable from '../../customComponents/DataTable.vue';
import DocumentIcon from './../../icons/DocumentIcon.vue';
import EditIcon from './../../icons/EditIcon.vue';
import TrashIcon from './../../icons/TrashIcon.vue';
import PlusIcon from '../../icons/PlusIcon.vue';

export default {
    components: {
        CustomButton,
        DataTable,
        DocumentIcon,
        EditIcon,
        TrashIcon,
        PlusIcon
    },
    props: {
        sedes: {
            type: Object,
            required: true
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            fieldsAdmin: [
                { key: 'id', label: 'ID' },
                { key: 'empresa', label: 'Empresa' },
                { key: 'nombre', label: 'Nombre' },
                { key: 'ciudad', label: 'Ciudad' },
                { key: 'actions', label: 'Acciones' }
            ],
            fields: [
                { key: 'id', label: 'ID' },
                { key: 'nombre', label: 'Nombre' },
                { key: 'ciudad', label: 'Ciudad' },
                { key: 'actions', label: 'Acciones' }
            ],
            ciudades: []
        };
    },
    computed: {
        isAdmin() {
            return this.user.role.name === 'ADMIN_SISTEMA';
        }
    },
    mounted() {
        this.setBreadcumb();
        this.cargarCiudades();
    },
    methods: {
        updateRows(data) {
            window.location.replace('/sedes?per_page=' + data);
        },
        pageChanged(path) {
            window.location.replace(path);
        },
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Sedes';
        },
        eliminarSede(id) {
            axios
                .delete(`/sedes/${id}`)
                .then(res => {
                    window.location.replace('/sedes');
                })
                .catch(error => {
                    console.log(error);
                });
        },
        async cargarCiudades() {
            try {
                const munResponse = await axios.get('/json/municipios.json');
                this.ciudades = munResponse.data || [];

                this.filtrarSede();
            } catch (error) {
                console.log('Error al cargar las ciudades', error);
            }
        },
        filtrarSede(){
            this.sedes.data = this.sedes.data.map(sede => {
                const ciudad = this.ciudades.find(ciudad => ciudad.id === sede.ciudad_id);

                return {
                    ...sede,
                    ciudad: ciudad ? ciudad.nombre : 'N/A'
                };
            });
        }
    }
};
</script>

<style scoped>
.font-bold {
    font-weight: bold;
}
</style>
