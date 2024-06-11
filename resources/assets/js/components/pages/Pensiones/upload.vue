<template>
    <div class="container">
        <h1>Upload Excel Files</h1>
        <form @submit.prevent="uploadFile">
            <div class="form-group">
                <label for="file">Choose Excel File</label>
                <input type="file" class="form-control" id="file" ref="file" required />
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <div v-if="progressVisible">
            <h3>Upload Progress</h3>
            <div class="progress">
                <div
                    class="progress-bar"
                    role="progressbar"
                    :style="{ width: progress + '%' }"
                    :aria-valuenow="progress"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>
            </div>
            <div v-if="status === 'completed'" class="alert alert-success mt-3">Upload completed successfully!</div>
            <div v-if="status === 'pending'" class="alert alert-info mt-3">Upload in progress...</div>
            <div v-if="status === 'failed'" class="alert alert-danger mt-3">Upload failed. Please try again.</div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            progress: 0,
            progressVisible: false,
            status: 'pending',
            progressId: null
        };
    },
    methods: {
        async uploadFile() {
            const file = this.$refs.file.files[0];
            let formData = new FormData();
            formData.append('file', file);

            try {
                console.log('Starting upload...');
                const response = await axios.post('/colpensiones/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: progressEvent => {
                        let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        this.progress = percentCompleted;
                        console.log(`Upload progress: ${percentCompleted}%`);
                    }
                });

                console.log('Upload response:', response.data);
                if (response.data.success) {
                    this.progressVisible = true;
                    this.progressId = response.data.progressId;
                    console.log(`Progress ID: ${this.progressId}`);

                    let interval = setInterval(async () => {
                        try {
                            const progressResponse = await axios.get(`/colpensiones/progress/${this.progressId}`);
                            console.log('Progress response:', progressResponse.data);

                            this.progress = progressResponse.data.progress;
                            this.status = progressResponse.data.status;
                            if (this.status === 'completed' || this.status === 'failed') {
                                clearInterval(interval);
                                console.log('Upload finished.');
                            }
                        } catch (error) {
                            console.error('Error fetching progress:', error);
                            clearInterval(interval);
                        }
                    }, 1000);
                } else {
                    alert('Error: ' + response.data.message);
                    console.error('Error response:', response.data);
                }
            } catch (error) {
                console.error('Error during upload:', error);
                if (error.response) {
                    alert('Error: ' + error.response.data.message);
                    console.error('Error response data:', error.response.data);
                }
            }
        }
    }
};
</script>

<style scoped>
.progress-bar {
    transition: width 0.4s ease;
}
</style>
