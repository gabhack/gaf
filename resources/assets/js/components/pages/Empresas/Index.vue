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
		<b-row>
			<b-col class="mt-4">
				<DataTable :items="empresas" :columns="fields" @updateRows="updateRows">
					<template #id="data">
						<span class="font-bold" v-text="data.data.id"></span>
					</template>
					<template #actions="data">
						<button class="action-button">
							<DocumentIcon />
						</button>
						<button class="action-button" @click="editarEmpresa(data.data.id)">
							<EditIcon />
						</button>
						<button class="action-button">
							<TrashIcon />
						</button>
					</template>
				</DataTable>
			</b-col>
		</b-row>
	</b-container>
</template>
<script>
import CustomButton from '../../customComponents/CustomButton.vue'
import DataTable from '../../customComponents/DataTable.vue'
import DocumentIcon from './../../icons/DocumentIcon.vue'
import EditIcon from './../../icons/EditIcon.vue'
import TrashIcon from './../../icons/TrashIcon.vue'
import PlusIcon from '../../icons/PlusIcon.vue'
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
		},
	},
	data() {
		return {
			fields: [
				{ key: 'id', label: 'ID' },
				{ key: 'tipo_empresa', label: 'Tipo de empresa' },
				{ key: 'nombre', label: 'Nombre empresa' },
				{ key: 'documento', label: 'Documento' },
				{ key: 'ciudad', label: 'Ciudad' },
				{ key: 'actions', label: '' }
			],
			currentPage: 1,
			perPage: 5,
			totalRows: 10
		}
	},
	mounted() {
		this.setBreadcumb();
	},
	methods: {
		updateRows(data) {
			window.location.replace('/empresas?per_page=' + data);
		},
		crearEmpresa() {
			window.location.replace('/empresas/crear');
		},
		setBreadcumb() {
			let domBreadcumb = document.getElementById('dynamic-breadcumb');
			domBreadcumb.innerText = "> Empresas";
		},
		editarEmpresa(id) {
			window.location.replace('/empresas/edit/' + id);
		}
	}
}
</script>
<style scoped>
.font-bold {
	font-weight: bold;
}
</style>