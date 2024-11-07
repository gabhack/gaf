<template>
	<b-container fluid class="my-3">
		<Form></Form>
		<b-row align-h="end">
			<b-col cols="4">
				<CustomButton class="mt-4">Crear usuario</CustomButton>
			</b-col>
		</b-row>
	</b-container>
</template>
<script>
import Form from './Form.vue';
import CustomButton from '../../customComponents/CustomButton.vue'
export default {
	components: {
		Form,
		CustomButton
	}
}
</script>