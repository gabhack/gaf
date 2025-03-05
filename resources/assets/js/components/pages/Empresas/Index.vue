<template>
    <b-container fluid class="my-3">
        <b-row align-v="center">
            <b-col>
                <h4>Listado de empresas</h4>
            </b-col>
            <b-col class="text-right">
                <CustomButton @click="crearEmpresa">
                    <PlusIcon />
                    Crear empresa
                </CustomButton>
            </b-col>
        </b-row>
        <b-row v-if="ciudades.length > 0">
            <b-col class="mt-4">
                <DataTable :items="empresas" :columns="fields" @updateRows="updateRows" @pageChanged="pageChanged">
                    <template #id="data">
                        <span class="font-bold" v-text="data.data.id"></span>
                    </template>
                    <template #actions="data">
                        <button class="action-button" @click="verEmpresa(data.data.id)">
                            <DocumentIcon />
                        </button>
                        <button class="action-button" @click="editarEmpresa(data.data.id)">
                            <EditIcon />
                        </button>
                        <button class="action-button" @click="eliminarEmpresa(data.data.id)">
                            <TrashIcon />
                        </button>
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
        empresas: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            fields: [
                { key: 'id', label: 'ID' },
                { key: 'tipo_empresa', label: 'Tipo de empresa' },
                { key: 'nombre', label: 'Nombre empresa' },
                { key: 'documento', label: 'Documento' },
                { key: 'ciudad', label: 'Ciudad' },
                { key: 'actions', label: 'Acciones' }
            ],
            ciudades: []
        };
    },
    mounted() {
        this.setBreadcumb();
        this.cargarCiudades();
    },
    methods: {
        updateRows(data) {
            window.location.replace('/empresas?per_page=' + data);
        },
        pageChanged(path) {
            window.location.replace(path);
        },
        crearEmpresa() {
            window.location.replace('/empresas/crear');
        },
        setBreadcumb() {
            let domBreadcumb = document.getElementById('dynamic-breadcumb');
            domBreadcumb.innerText = '> Empresas';
        },
        editarEmpresa(id) {
            window.location.replace('/empresas/edit/' + id);
        },
        verEmpresa(id) {
            window.location.replace('/empresas/ver/' + id);
        },
        eliminarEmpresa(id) {
            axios
                .delete('/empresas/' + id)
                .then(res => {
                    window.location.replace('/empresas');
                })
                .catch(error => {
                    console.log(error);
                });
        },
        async cargarCiudades() {
            try {
                const munResponse = await axios.get('/json/municipios.json');
                this.ciudades = munResponse.data || [];

                this.filtrarEmpresas();
            } catch (error) {
                console.log('Error al cargar las ciudades', error);
            }
        },
        filtrarEmpresas(){
            this.empresas.data = this.empresas.data.map(empresa => {
                const ciudad = this.ciudades.find(ciudad => ciudad.id === empresa.ciudad_id);

                return {
                    ...empresa,
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
