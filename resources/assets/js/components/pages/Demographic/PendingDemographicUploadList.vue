<template>
  <b-card>
    <b-table
      :items="uploads.filter(u => u.status === 'pending')"
      :fields="computedFields"
      bordered
      responsive
    >
      <!-- sólo renderizamos la celda de acciones si el usuario puede aprobar -->
      <template #cell(actions)="row" v-if="canApprove">
        <b-button
          size="sm"
          variant="primary"
          @click="analyze(row.item.id)"
        >
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

  // recibimos desde Blade si este usuario tiene permiso para aprobar
  props: {
    canApprove: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      uploads: [],

      // campos sin la columna de acciones
      fieldsNoActions: [
        { key: 'id',            label: 'ID' },
        { key: 'original_name', label: 'Archivo' },
        { key: 'mes',           label: 'Mes' },
        { key: 'anio',          label: 'Año' },
        { key: 'user_id',       label: 'Usuario' },
        { key: 'created_at',    label: 'Fecha' },
        { key: 'status',        label: 'Estado' }
      ],

      // mismos campos más la columna de acciones
      fieldsWithActions: [
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

  created () {
    this.fetch()
  },

  computed: {
    // elegimos el set de campos según el permiso
    computedFields () {
      return this.canApprove
        ? this.fieldsWithActions
        : this.fieldsNoActions
    }
  },

  methods: {
    fetch () {
      axios.get('/demografico/pending-uploads')
        .then(({ data }) => {
          this.uploads = data
        })
        .catch(err => {
          console.error('[PendingList] fetch error', err)
          this.uploads = []
        })
    },

    analyze (id) {
      if (!window.confirm('¿Deseas procesar este archivo?')) return

      axios.post(`/demografico/pending-uploads/${id}/approve`)
        .then(({ data }) => {
          // eliminamos el registro localmente
          this.uploads = this.uploads.filter(u => u.id !== id)

          const url = `/analisis-de-cartera?mes=${String(data.mes).trim()}&${encodeURIComponent('año')}=${data.año}`
          window.location.href = url
        })
        .catch(err => {
          console.error('[PendingList] analyze error', err)
          alert('Error al aprobar el archivo')
        })
    }
  }
}
</script>
