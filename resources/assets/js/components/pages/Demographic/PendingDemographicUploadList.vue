<template>
    <b-card>
        <b-table :items="uploads.filter(u=>u.status==='pending')"  :fields="fields" bordered responsive>
        <template #cell(actions)="row">
          <b-button size="sm"
                    variant="primary"
                    @click="analyze(row.item.id)">
            Analizar
          </b-button>
        </template>
      </b-table>
    </b-card>
  </template>
  
  <script>
  import axios from 'axios'
  
  export default {
    name: 'PendingDemographicUploadList',
    data () {
      return {
        uploads: [],
        fields: [
          { key: 'id',            label: 'ID' },
          { key: 'original_name', label: 'Archivo' },
          { key: 'mes',           label: 'Mes' },
          { key: 'anio',          label: 'Año' },
          { key: 'user_id',       label: 'Usuario' },
          { key: 'created_at',    label: 'Fecha' },
          { key: 'status',        label: 'Estado' },
          { key: 'actions',       label: 'Acciones' }
        ]
      }
    },
    created () { this.fetch() },
    methods: {
      fetch () {
        axios.get('/demografico/pending-uploads')
          .then(({ data }) => { this.uploads = data })          // ya llegan sólo “pending”
      },
  
      analyze (id) {
        if (!window.confirm('¿Deseas procesar este archivo?')) return
  
        axios.post(`/demografico/pending-uploads/${id}/approve`)
          .then(({ data }) => {
            /* quitamos el registro de la tabla local antes de irnos */
            this.uploads = this.uploads.filter(u => u.id !== id)
  
            const url = `/analisis-de-cartera?mes=${String(data.mes).trim()}&${encodeURIComponent('año')}=${data.año}`
            window.location.href = url
          })
          .catch(err => {
            console.error('[PendingList] error', err)
            alert('Error al aprobar el archivo')
          })
      }
    }
  }
  </script>
  