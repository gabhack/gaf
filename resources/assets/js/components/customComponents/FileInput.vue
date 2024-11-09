<template>
	<b-col cols="12" class="file-input-content" @click="triggerFileInput" @dragover.prevent="handleDragOver"
		@dragleave.prevent="handleDragLeave" @drop.prevent="handleDrop">
		<div style="display: flex; flex-direction: column; align-items: center; justify-content: center">
			<UploadFile class="mb-2" />
			<p class="text-center" style="margin-bottom: 0.5rem">
				Arrastre o suelte el archivo <br />
				o
			</p>
			<CustomButton text="Seleccionar archivo" color="white" />
		</div>
		<input type="file" ref="fileInput" @change="handleFileUpload" style="display: none" />
	</b-col>
</template>
<script>
import UploadFile from '../icons/UploadFile.vue';
import CustomButton from './CustomButton.vue';
export default {
	components: {
		UploadFile,
		CustomButton
	},
	data() {
		return {
			isDragging: false,
		}
	},
	methods: {
		triggerFileInput() {
			this.$refs.fileInput.click();
		},
		handleDragOver(event) {
			this.isDragging = true;
		},
		handleDragLeave(event) {
			this.isDragging = false;
		},
		handleDrop(event) {
			const file = event.dataTransfer.files[0];
			if (file) {
				this.file = file;
				this.handleFileUpload({ target: { files: [file] } });
			}
			this.isDragging = false;
		},
		handleFileUpload(event) {
			this.$emit('handleFileInput', event.target.files[0]);
		},
	}
}
</script>
<style scoped>
.file-input-content {
	min-height: 150px;
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
}
</style>