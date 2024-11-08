<template>
	<div class="table-container">
		<table class="custom-table">
			<thead>
				<tr>
					<th v-for="(element, index) in columns" v-text="element.label" v-bind:class="thCustomStyle(index)"></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="item in items.data" :key="item.id">
					<td v-for="(element, index) in columns" v-bind:class="tdCustomStyle(index)">
						<slot :name="element.key" v-bind:data="item">{{ item[element.key] }}</slot>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="pagination">
			<label for="rowsPerPage" class="label-per-page">Cantidad de filas:</label>
			<select id="rowsPerPage" v-model="rowsPerPage" @change="updateRows">
				<option v-for="option in [5, 10, 15]" :key="option" :value="option">{{ option }}</option>
			</select>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		columns: {
			type: Array,
			required: true,
		},
		items: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			rowsPerPage: this.items.per_page
		}
	},
	methods: {
		updateRows() {
			this.$emit('updateRows', this.rowsPerPage);
		},
		thCustomStyle(index) {
			return {
				'rounded-l': index == 0,
				'rounded-r': index == this.columns.length - 1
			}
		},
		tdCustomStyle(index) {
			return {
				'border-l': index == 0,
				'border-r': index == this.columns.length - 1
			}
		}
	}
};
</script>

<style scoped>
.table-container {
	max-width: 100%;
	margin: 0 auto;
}

.custom-table {
	width: 100%;
	border-collapse: collapse;
	border: none;
}

.custom-table thead {
	background-color: #3a5a58;
	color: #fff;
}

.custom-table th,
.custom-table td {
	padding: 0.75rem;
	text-align: left;
	border-bottom: 1px solid #e0e0e0;
}

.border-l {
	border-left: 1px solid #e0e0e0;
}

.border-r {
	border-right: 1px solid #e0e0e0;
}

.rounded-l {
	border-top-left-radius: 10px;
}

.rounded-r {
	border-top-right-radius: 10px;
}

.custom-table th {
	font-weight: 300;
}

.custom-table tbody tr:hover {
	background-color: #f5f5f5;
}

.actions {
	display: flex;
	gap: 0.5rem;
}

.action-button {
	background: none;
	border: none;
	cursor: pointer;
	font-size: 1.2rem;
	color: #3a5a58;
}

.action-button:hover {
	color: #0056b3;
}

.pagination {
	display: flex;
	align-items: center;
	align-content: center;
	gap: 0.5rem;
	background-color: white;
	padding: 0.75rem;
	border-left: 0.5px solid #B8BEC5;
	border-bottom: 0.5px solid #B8BEC5;
	border-right: 0.5px solid #B8BEC5;
	border-top-left-radius: 0px;
	border-top-right-radius: 0px;
}

.pagination select {
	padding: 0.50rem;
	background-color: white;
	border-radius: 5px;
	border: 0.5px solid #B8BEC5;
}

.label-per-page {
	font-size: 13px;
}
</style>
