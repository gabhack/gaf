<template>
    <div>
      <b-card class="mb-3">
        <b-form @submit.prevent="submit">
          <b-form-file
            v-model="file"
            accept=".xlsx"
            :state="Boolean(file)"
            browse-text="Seleccionar Excel"
          />
  
          <b-row class="mt-3">
            <b-col md="4">
              <b-form-select v-model="mes" :options="meses" required/>
            </b-col>
            <b-col md="4">
              <b-form-select v-model="anio" :options="anios" required/>
            </b-col>
          </b-row>
  
          <b-button type="submit"
                    variant="primary"
                    class="mt-3"
                    :disabled="uploading || !file || !mes || !anio">
            Subir
          </b-button>
        </b-form>
  
        <b-progress class="mt-2"
                    :value="progress"
                    :max="100"
                    v-if="uploading"/>
      </b-card>
    </div>
  </template>
  
  <script>
  import axios from 'axios'
  export default {
    name: 'PendingDemographicUploadForm',
    data () {
      return {
        file: null,
        mes:  null,
        anio: null,
        uploading: false,
        progress: 0,
        meses: [
          { value: null, text: 'Mes' },
          ...[1,2,3,4,5,6,7,8,9,10,11,12].map(m => ({ value: m, text: m.toString().padStart(2, '0') }))
        ],
        anios: [
          { value: null, text: 'Año' },
          ...Array.from({ length: 6 }, (_, i) => new Date().getFullYear() - i)
                 .map(y => ({ value: y, text: y.toString() }))
        ]
      }
    },
    methods: {
      submit () {
        if (!this.file || !this.mes || !this.anio) return
        const data = new FormData()
        data.append('file', this.file)
        data.append('mes',  this.mes)
        data.append('anio', this.anio)
  
        this.uploading = true
        this.progress  = 0
  
        axios.post('/demografico/pending-uploads', data, {
          headers: { 'Content-Type': 'multipart/form-data' },
          onUploadProgress: e => {
            if (e.lengthComputable) this.progress = Math.round((e.loaded * 100) / e.total)
          }
        })
        .then(() => {
          this.$swal('Éxito', 'Archivo enviado para aprobación', 'success')
          this.file      = null
          this.mes = this.anio = null
        })
        .catch(() => {
          this.$swal('Error', 'No se pudo subir el archivo', 'error')
        })
        .finally(() => (this.uploading = false))
      }
    }
  }
  </script>
  