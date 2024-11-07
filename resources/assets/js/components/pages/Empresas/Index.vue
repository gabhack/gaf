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
				<DataTable :items="items" :columns="fields" @updateRows="updateRows">
					<template #id="data">
						<span class="font-bold" v-text="data.data.id"></span>
					</template>
					<template #actions="data">
						<button class="action-button">
							<DocumentIcon />
						</button>
						<button class="action-button">
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
	data() {
		return {
			items: [
				{ id: 1, name: 'Fondo de inversión', nombre_empresa: 'EMPRESA 1 SAS', document: 123456, city: 'Bogota' },
				{ id: 2, name: 'Fondo de inversión', nombre_empresa: 'EMPRESA 1 SAS', document: 123456, city: 'Bogota' },
				{ id: 3, name: 'Fondo de inversión', nombre_empresa: 'EMPRESA 2 SAS', document: 123456, city: 'Bogota' }
			],
			fields: [
				{ key: 'id', label: 'ID' },
				{ key: 'name', label: 'Tipo de empresa' },
				{ key: 'nombre_empresa', label: 'Nombre empresa' },
				{ key: 'document', label: 'Documento' },
				{ key: 'city', label: 'Ciudad' },
				{ key: 'actions', label: '' }
			],
			currentPage: 1,
			perPage: 5,
			totalRows: 10
		}
	},
	methods: {
		updateRows(data) {
			alert(data)
		},
		crearEmpresa() {
			window.location.replace('/empresas/crear');
		}
	}
}
</script>
<style scoped>
.font-bold {
	font-weight: bold;
}
</style>