<template>
	<b-modal :id="id" hide-footer hide-header modal-header-close>
		<div id="modal-header">
			<div v-text="title"></div>
			<div id="modal-close-icon" @click="hideModal">
				<XIcon></XIcon>
			</div>
		</div>
		<slot name="modal-content"></slot>
		<a :href="base64Url" v-if="previewDocument" target="_blank" download="archivo.pdf">Descargar archivo</a>
	</b-modal>
</template>
<script>
import XIcon from '../icons/XIcon.vue';
export default {
	components: {
		XIcon
	},
	props: {
		id: {
			type: String,
			required: true
		},
		title: {
			type: String,
			required: true,
		},
		previewDocument: {
			type: String,
			required: false,
		}
	},
	computed: {
		base64Url() {
			return `data:application/pdf;base64,${this.previewDocument}`;
		},
	},
	methods: {
		hideModal() {
			this.$bvModal.hide(this.id);
		}
	}
}
</script>
<style scoped>
#modal-header {
	display: flex;
	justify-content: space-between;
	margin-bottom: 1rem;
	align-items: center
}

#modal-close-icon {
	cursor: pointer;
}
</style>