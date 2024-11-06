<template>
	<b-container fluid class="my-3">
		<b-row>
			<b-col cols="6">
				<h4>Listado de empresas</h4>
			</b-col>
			<b-col cols="6" class="text-right">
				<CustomButton>Crear empresa</CustomButton>
			</b-col>
		</b-row>
		<b-row>
			<b-col>
				<DataTable :items="items" :columns="fields" @updateRows="updateRows">
					<template #actions="data">
						<button class="action-button">
							📄
						</button>
						<button class="action-button">
							✏️
						</button>
						<button class="action-button">
							🗑️
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
export default {
	components: {
		CustomButton,
		DataTable
	},
	mounted() {
		console.log('running..')
	},
	data() {
		return {
			items: [
				{ id: 1, name: 'Juan Pérez', email: 'juan@example.com', document: 123456, city: 'Bogota' },
				{ id: 2, name: 'Ana Gómez', email: 'ana@example.com', document: 123456, city: 'Bogota' },
				{ id: 3, name: 'Carlos Ruiz', email: 'carlos@example.com', document: 123456, city: 'Bogota' }
			],
			fields: [
				// Definición de las columnas de la tabla
				{ key: 'id', label: 'ID' },
				{ key: 'name', label: 'Tipo de empresa' },
				{ key: 'email', label: 'Nombre empresa' },
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
		}
	}
}
</script>
<style scoped>
.header {
	background-color: green;
}

.columns-header {
	display: flex;
	gap: 10px;
}
</style>