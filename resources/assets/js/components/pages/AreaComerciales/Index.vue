<template>
	<b-container fluid class="my-3">
		<b-row>
			<b-col cols="6">
				<h4>Listado de comerciales</h4>
			</b-col>
			<b-col cols="6" class="text-right">
				<CustomButton @click="crearComercial">
					<PlusIcon></PlusIcon>
					Crear comercial
				</CustomButton>
			</b-col>
		</b-row>
		<b-row>
			<b-col class="mt-4">
				<DataTable :items="comerciales" :columns="fields" @updateRows="updateRows" @pageChanged="pageChanged">
					<template #actions="data">
						<button class="action-button">
							<DocumentIcon />
						</button>
						<button class="action-button" @click="editAreaComercial(data.data.id)">
							<EditIcon />
						</button>
						<button class="action-button" @click="eliminarAreaComercial(data.data.id)">
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
		comerciales: {
			type: Object,
			required: true
		},
	},
	data() {
		return {
			fields: [
				{ key: 'id', label: 'ID' },
				{ key: 'nombre_completo', label: 'Nombre' },
				{ key: 'cargo', label: 'Cargo' },
				{ key: 'sede', label: 'Sede' },
				{ key: 'ciudad', label: 'Ciudad' },
				{ key: 'telefono', label: 'Teléfono' },
				{ key: 'actions', label: 'Acciones' }
			],
		}
	},
	mounted() {
		this.setBreadcumb();
	},
	methods: {
		updateRows(data) {
			window.location.replace('/area-comerciales?per_page=' + data);
		},
		pageChanged(path) {
			window.location.replace(path);
		},
		crearComercial() {
			window.location.replace('/area-comerciales/crear');
		},
		setBreadcumb() {
			let domBreadcumb = document.getElementById('dynamic-breadcumb');
			domBreadcumb.innerText = "> Area comercial";
		},
		editAreaComercial(id) {
			window.location.replace('/area-comerciales/edit/' + id)
		},
		eliminarAreaComercial(id) {
			axios.delete('/area-comerciales/' + id).then((res) => {
				window.location.replace('/area-comerciales');
			}).catch((error) => {
				console.log(error);
			})
		}
	}
}
</script>
<style scoped></style>