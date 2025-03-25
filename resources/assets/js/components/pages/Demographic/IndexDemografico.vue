<template>
    <div>
      <div v-if="isLoading" class="loading-overlay">
        <div class="spinner"></div>
      </div>
      <div class="panel mb-3 col-md-12" style="margin-left: 20px">
        <div class="panel-heading" style="background-color: white">
          <b style="color: black">Datos Demográficos</b>
        </div>
        <div class="panel-body">
          <div style="background-color: white">
            <span style="color: black">
              Asegúrese de que el Excel tiene la columna
              <strong>cedulas</strong> con los números de cédula.
            </span>
            <br /><br />
          </div>
          <div class="form-group d-flex align-items-center">
            <div class="custom-file-upload">
              <input type="file" @change="handleFileUpload" id="file-upload" class="file-input" />
              <label for="file-upload">Elegir archivo</label>
              <span v-if="fileName" class="file-name">{{ fileName }}</span>
            </div>
          </div>
          <CustomButton
            v-if="file"
            @click="uploadFile"
            class="btn btn-primary"
            style="white-space: nowrap; background-color: #2c8c73"
          >
            Subir Archivo
          </CustomButton>
        </div>
      </div>
      <div v-if="results.length" class="panel mb-3 col-md-12">
        <div class="panel-heading" style="background-color: white">
          <div class="float-right">
            <button
              @click="exportToPDF"
              class="btn btn-danger mr-2"
              style="background-color: #2c8c73"
            >
              Exportar a PDF
            </button>
            <button @click="exportToExcel" class="btn btn-success" style="background-color: #2c8c73">
              Exportar a Excel
            </button>
          </div>
          <b style="color: black; margin-left: 20px">Resultado:</b>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Buscar por documento"
              class="form-control mb-3"
            />
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Documento</th>
                  <th>Nombre</th>
                  <th>Celular</th>
                  <th>Teléfono Fijo</th>
                  <th>Email</th>
                  <th>Ciudad</th>
                  <th>Dirección</th>
                  <th>Centro de Costo</th>
                  <th>Tipo de Contrato</th>
                  <th>Edad</th>
                  <th>Fecha de Nacimiento</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="result in filteredResults" :key="result.doc">
                  <td>{{ capitalize(result.doc) }}</td>
                  <td>{{ capitalize(result.nombre_usuario) }}</td>
                  <td>{{ result.cel }}</td>
                  <td>{{ result.tel }}</td>
                  <td>{{ result.correo_electronico }}</td>
                  <td>{{ capitalize(result.ciudad) }}</td>
                  <td>{{ capitalize(result.direccion_residencial) }}</td>
                  <td>{{ capitalize(result.centro_costo) }}</td>
                  <td>{{ capitalize(result.tipo_contrato) }}</td>
                  <td>{{ result.edad }}</td>
                  <td>{{ result.fecha_nacimiento }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="pagination">
            <button
              v-if="page > 1"
              @click="fetchPaginatedResults(page - 1)"
              class="btn btn-primary"
              style="background-color: #2c8c73"
            >
              Anterior
            </button>
            <button
              @click="fetchPaginatedResults(page + 1)"
              :disabled="(page * perPage) >= total"
              class="btn btn-primary"
              style="background-color: #2c8c73"
            >
              Siguiente
            </button>
          </div>
        </div>
      </div>
      <div v-if="error" class="alert alert-danger mt-3">
        {{ error }}
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios'
  import CustomButton from '../../customComponents/CustomButton.vue'
  import * as XLSX from 'xlsx'
  import jsPDF from 'jspdf'
  import 'jspdf-autotable'
  
  export default {
    name: 'DemographicIndex',
    components: { CustomButton },
    data() {
      return {
        file: null,
        fileName: '',
        isLoading: false,
        results: [],
        searchQuery: '',
        error: null,
        page: 1,
        perPage: 3000,
        total: 0
      }
    },
    computed: {
      filteredResults() {
        if (this.searchQuery.length < 3) return this.results
        return this.results.filter(r => r.doc.toString().includes(this.searchQuery))
      }
    },
    methods: {
      capitalize(text) {
        if (!text) return ''
        return text.toLowerCase().replace(/\b\w/g, c => c.toUpperCase())
      },
      handleFileUpload(e) {
        this.file = e.target.files[0]
        this.fileName = this.file ? this.file.name : ''
      },
      async uploadFile() {
        if (!this.file) {
          alert('Seleccione un archivo')
          return
        }
        this.isLoading = true
        let formData = new FormData()
        formData.append('file', this.file)
        try {
          let response = await axios.post('/demografico/upload', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          })
          if (response.data.uploaded) {
            this.page = 1
            await this.fetchPaginatedResults(this.page)
          } else {
            this.error = 'Error al subir el archivo'
          }
        } catch (err) {
          this.error = 'Error al subir el archivo'
        } finally {
          this.isLoading = false
        }
      },
      async fetchPaginatedResults(page) {
        this.isLoading = true
        try {
          let res = await axios.get(
            `/demografico/fetch-paginated-results-demografico?page=${page}&perPage=${this.perPage}`
          )
          this.results = res.data.data
          this.total = res.data.total
          this.page = res.data.page
          this.perPage = res.data.perPage
        } catch (err) {
          this.error = 'Error al obtener resultados paginados'
        } finally {
          this.isLoading = false
        }
      },
      exportToPDF() {
        const doc = new jsPDF()
        const columns = [
          'Documento','Nombre','Celular','Teléfono','Email','Ciudad',
          'Dirección','Centro Costo','Tipo Contrato','Edad','Fecha Nac.'
        ]
        const rows = this.results.map(i => [
          i.doc, i.nombre_usuario, i.cel, i.tel, i.correo_electronico,
          i.ciudad, i.direccion_residencial, i.centro_costo,
          i.tipo_contrato, i.edad, i.fecha_nacimiento
        ])
        doc.autoTable(columns, rows)
        doc.save('resultados.pdf')
      },
      exportToExcel() {
        const columns = [[
          'Documento','Nombre','Celular','Teléfono','Email','Ciudad',
          'Dirección','Centro Costo','Tipo Contrato','Edad','Fecha Nac.'
        ]]
        const rows = this.results.map(i => [
          i.doc, i.nombre_usuario, i.cel, i.tel, i.correo_electronico,
          i.ciudad, i.direccion_residencial, i.centro_costo,
          i.tipo_contrato, i.edad, i.fecha_nacimiento
        ])
        const worksheet = XLSX.utils.aoa_to_sheet([...columns, ...rows])
        const workbook = XLSX.utils.book_new()
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Resultados')
        XLSX.writeFile(workbook, 'resultados.xlsx')
      }
    }
  }
  </script>
  
  <style scoped>
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .spinner {
    border: 16px solid #f3f3f3;
    border-top: 16px solid #3498db;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  .custom-file-upload .file-input {
    display: none;
  }
  .custom-file-upload label {
    display: inline-block;
    padding: 10px 18px;
    color: white;
    background-color: #2c8c73;
    border-radius: 20px;
    cursor: pointer;
  }
  .file-name {
    margin-left: 10px;
    font-size: 14px;
    color: black;
  }
  .panel-body {
    padding: 15px;
  }
  .table-striped > tbody > tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,0.05);
  }
  .float-right {
    float: right;
  }
  </style>
  