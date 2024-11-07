<template>
	<b-container fluid class="my-3">
		<Form></Form>
		<b-row align-h="end">
			<b-col cols="4">
				<CustomButton class="mt-4">
					<PlusIcon></PlusIcon>
					Crear empresa
				</CustomButton>
			</b-col>
		</b-row>
	</b-container>
</template>
<script>
import Form from './Form.vue';
import CustomButton from '../../customComponents/CustomButton.vue'
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
			domBreadcumb.innerText = "> Empresas > Crear";
		}
	}
}
</script>