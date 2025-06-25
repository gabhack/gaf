<template>
    <div class="d-flex justify-content-center w-100">
      <div class="card-main mt-3 mb-5 px-4 py-4" style="max-width:1180px;width:100%">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0">
            <i class="fas fa-file-excel mr-2"/> Carga masiva de solicitudes
          </h4>
        </div>
  
        <div class="alert alert-info">
          <p class="mb-2">
            1. Descarga la plantilla (<a href="/templates/credit_requests.xlsx" target="_blank">aquí</a>).<br>
            2. Llénala con <b>Cédula, Nombre completo, Tipo de cliente, Pagaduría, Valor cuota mensual ($),
            Monto solicitado ($), Tasa mensual (%), Plazo (meses), Tipo de crédito</b>.<br>
            3. Sube el archivo y revisa la tabla.<br>
            4. Usa <b>Docs</b> o <b>Carteras</b> para adjuntar archivos o carteras por fila.<br>
            5. Haz clic en <b>Procesar</b> para registrar todo.
          </p>
        </div>
  
        <b-form-file accept=".xlsx,.xls" browse-text="Seleccionar archivo" @change="onMassFileChange" :key="fileInputKey" placeholder="Elige un archivo o arrástralo aquí..."></b-form-file>
  
        <div v-if="massErrors.length" class="alert alert-danger mt-3">
          <p class="mb-1"><b>Se encontraron errores al procesar el archivo:</b></p>
          <ul class="mb-0">
            <li v-for="(e,i) in massErrors" :key="'err'+i">
              Fila {{ e.row }} — <b>{{ e.field }}</b>: {{ e.msgs.join(', ') }}
            </li>
          </ul>
        </div>
  
        <div v-if="massPreview.length" class="table-responsive mt-3">
          <table class="table table-bordered table-sm">
            <thead class="thead-light">
              <tr>
                <th v-for="h in previewHeaders" :key="h">{{ h }}</th>
                <th style="min-width:130px">Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, idx) in massPreview" :key="'row'+idx">
                <td v-for="h in previewHeaders" :key="h+idx">
                  {{ formatCell(h, row[h]) }}
                </td>
                <td class="text-nowrap">
                  <b-button size="sm" variant="primary" @click="triggerDocInput(idx)">
                    Docs
                    <span v-if="row.__docs && row.__docs.length" class="badge-count">{{ row.__docs.length }}</span>
                  </b-button>
                  <b-button size="sm" variant="success" class="ml-1" @click="openCarteraModal(idx)">
                    Carteras
                    <span v-if="row.__carteras && row.__carteras.length" class="badge-count">{{ row.__carteras.length }}</span>
                  </b-button>
                  <input type="file" multiple class="d-none" :ref="'docs'+idx" @change="handleDocsChange(idx,$event)"/>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <div class="d-flex justify-content-end mt-3">
          <b-button variant="success" :disabled="!massPreview.length||sendingMass" @click="processMass">
            <b-spinner small v-if="sendingMass" class="mr-2"/> Procesar
          </b-button>
        </div>
      </div>
  
      <b-modal id="modal-cartera" size="lg" scrollable hide-footer>
        <template #modal-title>
          <span class="heading-title">Carteras de la fila {{ modalRowIndex !== null ? modalRowIndex + 1 : '' }}</span>
        </template>
  
        <div v-if="modalRowIndex!==null">
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-soft-head">
              <thead>
                <tr>
                  <th>Tipo</th><th>Entidad</th><th>Valor cuota</th><th>Saldo</th><th>Desp.</th><th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(c,i) in tempCarteras" :key="'car'+i">
                  <td><b-form-select v-model="c.tipoCartera" :options="tipoCarteraOptions"/></td>
                  <td><b-form-input v-model="c.nombreEntidad"/></td>
                  <td><b-form-input v-model="c.valorCuota" /></td>
                  <td><b-form-input v-model="c.saldo" /></td>
                  <td class="text-center"><b-form-checkbox v-model="c.opera_x_desprendible" switch/></td>
                  <td class="text-center">
                    <b-button size="sm" variant="outline-danger" @click="tempCarteras.splice(i,1)">Quitar</b-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <b-button size="sm" variant="success" @click="tempCarteras.push(emptyCartera())">Agregar cartera</b-button>
        </div>
  
        <div class="d-flex justify-content-end mt-3">
          <b-button variant="success" @click="saveCarteras">Aceptar</b-button>
          <b-button variant="secondary" class="ml-2" @click="$bvModal.hide('modal-cartera')">Cancelar</b-button>
        </div>
      </b-modal>
    </div>
</template>
  
<script>
  import axios from "axios";
  import * as XLSX from "xlsx";
  import { BFormFile, BFormSelect, BFormInput, BFormCheckbox, BButton, BSpinner, BModal } from "bootstrap-vue";
  
  export default {
    name: "CreditRequestBulk",
    components: { BFormFile, BFormSelect, BFormInput, BFormCheckbox, BButton, BSpinner, BModal },
    data() {
      return {
        massPreview: [],
        previewHeaders: [],
        massErrors: [],
        sendingMass: false,
        modalRowIndex: null,
        tempCarteras: [],
        fileInputKey: 0,
        tipoCarteraOptions: [
          { value: "Banco", text: "Banco" },
          { value: "Cooperativa", text: "Cooperativa" },
          { value: "CFC", text: "CFC" },
          { value: "Financiera", text: "Financiera" },
          { value: "Embargo", text: "Embargo" },
          { value: "Afiliaciones", text: "Afiliaciones" }
        ],
        moneyHeaders: [
          "Valor cuota mensual ($)",
          "Monto solicitado ($)"
        ],
        percentHeader: "Tasa mensual (%)",
        pagMap: {
          "sed valle": 165,
          "casur": 296,
          "fopep": 201,
          "colpensiones": 200,
          "fiduprevisora": 297
        }
      };
    },
    methods: {
      parseCurrency(value) {
        if (typeof value === 'number') {
          return value;
        }
        if (typeof value !== 'string') {
          return 0;
        }
        const cleaned = String(value)
          .trim()
          .replace(/\$\s*/g, '') 
          .replace(/\./g, '');   
        
        const normalized = cleaned.replace(',', '.');
        const num = parseFloat(normalized);
        return isNaN(num) ? 0 : num;
      },
      parsePercent(value) {
          if(typeof value === 'number') {
              return value >= 1 ? value / 100 : value;
          }
          if(typeof value !== 'string') return 0;

          const cleaned = String(value).trim().replace('%','');
          const normalized = cleaned.replace(',', '.');
          let num = parseFloat(normalized);

          if(isNaN(num)) return 0;
          
          if(num >= 1) {
              num = num / 100;
          }
          return num;
      },
      formatCell(header, value) {
        if (this.moneyHeaders.includes(header)) {
          const num = this.parseCurrency(value);
          return new Intl.NumberFormat("es-CO", {
            style: "currency",
            currency: "COP",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          }).format(num);
        }
        if (header === this.percentHeader) {
          const num = this.parsePercent(value);
          return new Intl.NumberFormat("es-CO", {
            style: 'percent',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          }).format(num);
        }
        return value;
      },
      emptyCartera() {
        return {
          tipoCartera: "Banco",
          nombreEntidad: "",
          valorCuota: "",
          saldo: "",
          opera_x_desprendible: false
        };
      },
      onMassFileChange(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
          try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: "array" });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const json = XLSX.utils.sheet_to_json(worksheet, { defval: "" });
            
            const cleanedJson = json.map(row => {
              const cleanedRow = {};
              for (const key in row) {
                if (Object.prototype.hasOwnProperty.call(row, key)) {
                  cleanedRow[key.trim()] = row[key];
                }
              }
              return cleanedRow;
            });
            
            this.massPreview = cleanedJson.reverse();
            this.previewHeaders = cleanedJson.length > 0 ? Object.keys(cleanedJson[0]) : [];
            this.massErrors = [];
          } catch (error) {
            this.massErrors.push({ row: 'N/A', field: 'Archivo', msgs: ['Error al leer el archivo de Excel. Verifique el formato.'] });
            this.massPreview = [];
            this.previewHeaders = [];
          }
        };
        reader.readAsArrayBuffer(file);
      },
      triggerDocInput(index) {
        this.$refs["docs" + index][0].click();
      },
      handleDocsChange(index, event) {
        const files = [...event.target.files];
        if (!this.massPreview[index].__docs) {
          this.$set(this.massPreview[index], "__docs", []);
        }
        this.massPreview[index].__docs.push(...files);
        event.target.value = null;
      },
      openCarteraModal(index) {
        this.modalRowIndex = index;
        this.tempCarteras = JSON.parse(JSON.stringify(this.massPreview[index].__carteras || []));
        this.$bvModal.show("modal-cartera");
      },
      saveCarteras() {
        if (this.modalRowIndex !== null) {
          this.$set(this.massPreview[this.modalRowIndex], "__carteras", JSON.parse(JSON.stringify(this.tempCarteras)));
        }
        this.$bvModal.hide("modal-cartera");
      },
      getPagaduriaId(value) {
        if (!isNaN(parseInt(value, 10))) {
          return parseInt(value, 10);
        }
        const key = String(value).trim().toLowerCase();
        return this.pagMap[key] || 0;
      },
      async processMass() {
        if (!this.massPreview.length) return;
        
        this.sendingMass = true;
        this.massErrors = [];
        
        for (let i = 0; i < this.massPreview.length; i++) {
            const row = this.massPreview[i];
            const originalIndex = this.massPreview.length - i;

            const payload = {
                doc: String(row["Cedula"] || "").trim(),
                name: String(row["Nombre completo"] || "").trim(),
                client_type: String(row["Tipo de cliente"] || "").trim(),
                pagaduria_id: this.getPagaduriaId(row["Pagaduria"]),
                cuota: this.parseCurrency(row["Valor cuota mensual ($)"]),
                monto: this.parseCurrency(row["Monto solicitado ($)"]),
                tasa: this.parsePercent(row["Tasa mensual (%)"]),
                plazo: Math.max(1, parseInt(row["Plazo (meses)"], 10) || 1),
                tipo_credito: String(row["Tipo de credito"] || "").trim(),
                carteras: (row.__carteras || []).map(c => ({
                    tipo_cartera: c.tipoCartera,
                    nombre_entidad: c.nombreEntidad,
                    valor_cuota: this.parseCurrency(c.valorCuota),
                    saldo: this.parseCurrency(c.saldo),
                    opera_x_desprendible: c.opera_x_desprendible || false
                }))
            };

            try {
                const res = await axios.post("/credit-requests", payload);
                const id = res.data?.data?.id;

                if (id && row.__docs?.length) {
                    for (const file of row.__docs) {
                        const formData = new FormData();
                        formData.append("archivo", file);
                        await axios.post(`/credit-requests/${id}/documents`, formData, {
                           headers: { "Content-Type": "multipart/form-data" }
                        });
                    }
                }
            } catch (error) {
                const errorData = error.response?.data;
                const errorMsgs = [errorData?.message || "Ocurrió un error inesperado."];
                if(errorData?.errors){
                    Object.values(errorData.errors).forEach(errArray => {
                        errorMsgs.push(...errArray);
                    });
                }
                this.massErrors.push({
                    row: originalIndex,
                    field: "Registro",
                    msgs: errorMsgs
                });
            }
        }
        
        this.sendingMass = false;
        
        if (this.massErrors.length === 0) {
            this.$bvToast.toast("Carga masiva completada con éxito.", {
                title: "Éxito",
                variant: "success",
                solid: true,
                toaster: "b-toaster-top-center",
                autoHideDelay: 4000
            });
            this.massPreview = [];
            this.previewHeaders = [];
            this.fileInputKey++;
        } else {
             this.$bvToast.toast("Algunos registros no se pudieron procesar. Revise los errores.", {
                title: "Error en la carga",
                variant: "danger",
                solid: true,
                toaster: "b-toaster-top-center",
                autoHideDelay: 6000
            });
        }
      }
    }
  };
</script>
  
<style scoped lang="scss">
  .card-main {
    position: relative;
    border-radius: .75rem;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
  }
  .table-soft-head thead th {
    background: #FFFBE6;
    color: #2d2d2d;
    font-weight: 600;
  }
  .badge-count {
    margin-left: 4px;
    background: #fff;
    color: #000;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: .75rem;
  }
</style>
