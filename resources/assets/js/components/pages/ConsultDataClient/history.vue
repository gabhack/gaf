<template>
    <div class="panel container-fluid">
      <div class="row">
        <div class="col-12">
          <template v-if="id_consult === null">
            <div class="mb-4 mt-5">
              <h2 class="heading-title">Listado de Consultas</h2>
            </div>
            <div
              class="d-flex mt-3 mb-3"
              style="color:#000; border: 1px solid #b9bdc3; border-radius: 10px"
            >
              <div class="form-group col-md-3">
                <label for="" style="color: black;">Desde</label>
                <b-form-input
                  placeholder="Desde"
                  v-model="queryParams.desde"
                  type="date"
                  class="small-input form-control2"
                />
              </div>
              <div class="form-group col-md-3">
                <label for="" style="color: black;">Hasta</label>
                <b-form-input
                  placeholder="Hasta"
                  v-model="queryParams.hasta"
                  type="date"
                  class="small-input form-control2"
                />
              </div>
              <div class="form-group col-md-3">
                <label for="" style="color: black;">Documento</label>
                <input
                  class="form-control2"
                  placeholder="Documento"
                  v-model="filter"
                />
              </div>
              <div class="form-group col-md-3 mt-4">
                <b-button
                  type="submit"
                  class="mr-2 align-self-end mb-3"
                  variant="success"
                  id="filtrarButton"
                  @click="getHistoryConsults"
                >
                  <i class="fa fa-filter" aria-hidden="true"></i>
                  Filtrar
                </b-button>
              </div>
            </div>
            <div class="table-responsive">
              <b-table
                head-variant="dark"
                style="border: 1px solid #b9bdc3; border-radius: 10px"
                responsive
                bordered
                striped
                hover
                :fields="fields"
                :items="HistoryConsult.data"
                :busy="isBusy"
              >
                <template #table-busy>
                  <div class="text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                  </div>
                </template>
                <template #cell(actions)="data">
                  <b-button
                    variant="primary"
                    class="mb-2"
                    @click="getData(data.item)"
                  >
                    Observar
                  </b-button>
                  <b-button variant="secondary" href="/solicitud">
                    Proceso HEGO
                  </b-button>
                </template>
              </b-table>
            </div>
            <b-pagination
              v-if="HistoryConsult.total > 0"
              v-model="currentPage"
              :per-page="perPage"
              :total-rows="totalRows"
              @change="getHistoryConsults"
            />
          </template>
          <template v-else>
            <button class="btn btn-primary mb-4" @click="back">Volver</button>
            <detail-history-component-draft
              :id="id_consult"
              :user="user"
              :pagaduriaType="pagaduriaType"
            />
          </template>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    props: ["user"],
    data() {
      return {
        HistoryConsult: { data: [], per_page: 15, total: 0 },
        detailHistory: {},
        filter: "",
        id_consult: null,
        pagaduriaType: "",
        fields: [
          { key: "created_at", label: "Fecha y Hora", sortable: true },
          { key: "tipo_consulta", label: "Tipo de Consulta", sortable: true },
          { key: "pagaduria", label: "Pagaduria", sortable: true },
          { key: "ced", label: "Cedula", sortable: true },
          { key: "estado", label: "Estado", sortable: true },
          { key: "nombre", label: "Nombre Completo", sortable: true },
          { key: "score", label: "Score", sortable: true },
          { key: "cuota", label: "Cuota", sortable: true },
          { key: "monto", label: "Monto", sortable: true },
          { key: "plazo", label: "Plazo", sortable: true },
          { key: "actions", label: "Acciones" },
        ],
        isBusy: false,
        perPage: 15,
        totalRows: 0,
        currentPage: 1,
        queryParams: {
          desde: null,
          hasta: null,
        },
      };
    },
    mounted() {
      console.log("Componente montado, iniciando carga de datos...");
      this.getHistoryConsults();
    },
    methods: {
      getHistoryConsults() {
        console.log("Iniciando solicitud de datos...");
        this.isBusy = true;
  
        const url = `/getHistoryConsults?page=${this.currentPage}`;
        console.log("URL de la solicitud:", url);
  
        axios
          .get(url)
          .then((response) => {
            console.log("Respuesta de la API recibida:", response);
  
            const dataWrapper = response.data.data || {};
            console.log("Datos envueltos recibidos:", dataWrapper);
  
            if (Array.isArray(dataWrapper.data)) {
              console.log("Datos válidos, actualizando historial...");
              this.HistoryConsult.data = dataWrapper.data;
              this.HistoryConsult.per_page = dataWrapper.per_page || 15;
              this.HistoryConsult.total = dataWrapper.total || 0;
              console.log("Historial actualizado:", this.HistoryConsult.data);
            } else {
              console.error("Los datos no son un arreglo:", dataWrapper.data);
              this.HistoryConsult.data = [];
            }
  
            this.perPage = this.HistoryConsult.per_page;
            this.totalRows = this.HistoryConsult.total;
          })
          .catch((error) => {
            console.error("Error al obtener consultas:", error);
            this.HistoryConsult = { data: [], per_page: 15, total: 0 };
          })
          .finally(() => {
            console.log("Finalización de la solicitud de datos");
            this.isBusy = false;
          });
      },
      getData(data) {
        console.log("Obteniendo detalles para:", data);
        this.pagaduriaType = data.pagaduria;
        this.detailHistory = data;
        this.id_consult = data.id;
      },
      back() {
        console.log("Regresando al listado principal");
        this.id_consult = null;
      },
    },
  };
  </script>
  
  <style scoped>
  .small-input {
    width: 100px;
  }
  .form-control2 {
    border: 1px solid #b9bdc3;
    background-color: white;
    border-radius: 10px;
  }
  </style>
  