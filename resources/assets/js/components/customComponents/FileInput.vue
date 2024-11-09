<template>
	<b-row>
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
		<b-col cols="12">
			<div class="d-flex justify-content-center align-item-center mb-5" style="width: 100%" v-if="file">
				<div class="file-uploaded-content">
					<span style="font-size: 12px; font-weight: 400; line-height: 15.62px; color: black">{{
						file.name
					}}</span>
					<button style="padding: 0; margin: 0; border: none; background: none;" @click="deleteFile">
						<Trash />
					</button>
				</div>
			</div>
		</b-col>
		<b-col cols="12">
			<CustomButton @click="saveFile" text="Guardar" v-if="file" />
		</b-col>
	</b-row>
</template>
<script>
import UploadFile from '../icons/UploadFile.vue';
import CustomButton from './CustomButton.vue';
import Trash from '../icons/Trash.vue';
export default {
	components: {
		UploadFile,
		CustomButton,
		Trash
	},
	data() {
		return {
			file: '',
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
			this.file = event.target.files[0];
		},
		saveFile() {
			this.$emit('handleFileInput', this.file);
		},
		deleteFile() {
			this.file = '';
		}
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

.file-uploaded-content {
	display: flex;
	align-items: center;
	justify-content: space-between;
	width: 300px;
	border-bottom: 1px solid #babcbe;
	padding: 8px;
}
</style>