<template>
	<b-container fluid class="my-3">
		<b-row>
			<b-col cols="6">
				<h4>Listado de comerciales</h4>
			</b-col>
			<b-col cols="6" class="text-right">
				<CustomButton @click="crearComercial">Crear comercial</CustomButton>
			</b-col>
		</b-row>
		<b-row>
			<b-col class="mt-4">
				<DataTable :items="items" :columns="fields" @updateRows="updateRows">
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
export default {
	components: {
		CustomButton,
		DataTable,
		DocumentIcon,
		EditIcon,
		TrashIcon
	},
	mounted() {
		console.log('running..')
	},
	data() {
		return {
			items: [
				{ id: 1, name: 'Juan Pérez', cargo: 'Gerente comercial', sede: "Pitalito", city: 'Bogota', phone: 321453234 },
				{ id: 2, name: 'Ana Gómez', cargo: 'Gerente comercial', sede: "Pitalito", city: 'Bogota', phone: 321453234 },
				{ id: 3, name: 'Carlos Ruiz', cargo: 'Gerente comercial', sede: "Pitalito", city: 'Bogota', phone: 321453234 }
			],
			fields: [
				{ key: 'id', label: 'ID' },
				{ key: 'name', label: 'Nombre' },
				{ key: 'cargo', label: 'Cargo' },
				{ key: 'sede', label: 'Sede' },
				{ key: 'city', label: 'Ciudad' },
				{ key: 'phone', label: 'Teléfono' },
				{ key: 'actions', label: 'Acciones' }
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
		crearComercial() {
			window.location.replace('/area-comerciales/crear');
		}
	}
}
</script>
<style scoped></style>