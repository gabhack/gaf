<template>
  <div class="d-flex justify-content-center">
    <form
      id="credit-form"
      @submit.prevent="submitForm"
      class="card-main mt-5 mb-5 px-4 py-4"
      style="max-width: 860px; width: 100%"
    >
      <!-- Spinner -->
      <div v-if="isLoading" class="overlay">
        <b-spinner variant="success" style="width: 3rem; height: 3rem" />
      </div>

      <!-- ═══════════════════ 1. Información del Cliente ═══════════════════ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <ClientTypeIcon class="section-icon" />
          <span>Información del Cliente</span>
        </div>
        <b-card-body>
          <!-- Cédula -->
          <b-form-group label="Cédula">
            <b-form-input class="form-control2" v-model="form.doc" required />
          </b-form-group>

          <!-- Nombre + Tipo Cliente -->
          <b-form-group label="Nombre completo">
            <div class="row g-3">
              <div class="col-md-6">
                <b-form-input
                  class="form-control2"
                  v-model="form.name"
                  placeholder="Nombre y apellido"
                  required
                />
              </div>
              <div class="col-md-6">
                <b-form-select
                  class="form-control2"
                  v-model="form.client_type"
                  :options="clientTypeOptions"
                  required
                  @change="onChangeClientType"
                />
              </div>
            </div>
          </b-form-group>

          <!-- Pagaduría según tipo -->
          <div v-if="showDocenteOptions">
            <b-form-group label="Pagaduría (Docente)">
              <b-form-select
                class="form-control2"
                v-model.number="form.pagaduria_id"
                :options="docentePagaduriasOptions"
                required
              />
            </b-form-group>
          </div>

          <div v-else-if="showPensionadoOptions">
            <b-form-group label="Pagaduría (Pensionado)">
              <b-form-select
                class="form-control2"
                v-model.number="form.pagaduria_id"
                required
              >
                <option disabled value="">Seleccione</option>
                <option :value="200">COLPENSIONES</option>
                <option :value="201">FOPEP</option>
                <option :value="296">CASUR</option>
              </b-form-select>
            </b-form-group>
          </div>
        </b-card-body>
      </b-card>

      <!-- ═══════════════════ 2. Condiciones del Crédito ═══════════════════ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <RequisitosCumplidosIcon class="section-icon" />
          <span>Condiciones del Crédito</span>
        </div>
        <b-card-body>
          <div class="row g-3">
            <div class="col-md-6">
              <b-form-group label="Valor de la cuota mensual">
                <b-form-input
                  class="form-control2"
                  v-model="form.cuota"
                  placeholder="1.000.000"
                  required
                  @keypress="onlyNumbers"
                  @input="onInputCurrency('cuota')"
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Monto solicitado">
                <b-form-input
                  class="form-control2"
                  v-model="form.monto"
                  placeholder="1.000.000"
                  required
                  @keypress="onlyNumbers"
                  @input="onInputCurrency('monto')"
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Tasa mensual (%)">
                <b-form-input
                  class="form-control2"
                  v-model="form.tasa"
                  placeholder="1.50 %"
                  required
                  @keypress="onlyNumbersAndDot"
                  @input="onInputPercentage('tasa')"
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Plazo (en meses)">
                <b-form-input
                  class="form-control2"
                  v-model.number="form.plazo"
                  type="number"
                  min="1"
                  required
                  @keypress="onlyNumbers"
                />
              </b-form-group>
            </div>
          </div>

          <!-- Tipo Crédito -->
          <b-form-group label="Tipo de Crédito">
            <b-form-select
              class="form-control2"
              v-model="form.tipo_credito"
              :options="tipoCreditoOptions"
              required
            />
          </b-form-group>
        </b-card-body>
      </b-card>

      <!-- ═══════════════════ 3. Documentos Requeridos ═══════════════════ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <DocumentIcon class="section-icon" />
          <span>Documentos Requeridos</span>
        </div>
        <b-card-body>
          <p class="mb-2">
            Sube al menos un documento de identidad o soporte laboral.<br />
            <small class="text-muted"
              >Los archivos se adjuntan al finalizar el proceso.</small
            >
          </p>

          <!-- Tabla documentos -->
          <div class="table-responsive">
            <table
              class="table table-bordered align-middle mb-3 table-soft-head"
            >
              <thead>
                <tr>
                  <th class="w-75">Archivo</th>
                  <th class="text-center w-25">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(doc, idx) in documentos" :key="idx">
                  <td>
                    <b-form-file
                      :state="Boolean(doc.file)"
                      accept=".pdf, .jpg, .jpeg, .png"
                      @change="onFileChange($event, idx)"
                    />
                  </td>
                  <td class="text-center">
                    <b-button
                      size="sm"
                      variant="outline-danger"
                      @click="removeArchivo(idx)"
                    >
                      Quitar
                    </b-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Botón nuevo documento -->
          <button
            type="button"
            class="btn-outline-gray"
            @click="addArchivo"
          >
            <PlusIcon class="me-1" /> Agregar nuevo documento
          </button>
        </b-card-body>
      </b-card>

      <!-- ═══════════════ 4. Información de Cartera a Comprar ═══════════════ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <CarteraIcon class="section-icon" />
          <span>Información de Cartera a Comprar</span>
        </div>
        <b-card-body>
          <!-- Tabla carteras -->
          <div class="table-responsive">
            <table
              class="table table-bordered align-middle mb-3 table-soft-head"
            >
              <thead>
                <tr>
                  <th>Tipo</th>
                  <th>Entidad</th>
                  <th>Valor Cuota</th>
                  <th>Saldo</th>
                  <th>Desprendible</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(car, i) in form.carteras" :key="i">
                  <td>
                    <b-form-select
                      v-model="car.tipoCartera"
                      :options="tipoCarteraOptions"
                      class="form-control2"
                      required
                    />
                  </td>
                  <td>
                    <b-form-input
                      v-model="car.nombreEntidad"
                      class="form-control2"
                      required
                    />
                  </td>
                  <td>
                    <b-form-input
                      v-model="car.valorCuota"
                      class="form-control2"
                      placeholder="100.000"
                      required
                      @keypress="onlyNumbers"
                      @input="onInputCurrencyCartera(i, 'valorCuota')"
                    />
                  </td>
                  <td>
                    <b-form-input
                      v-model="car.saldo"
                      class="form-control2"
                      placeholder="1.000.000"
                      required
                      @keypress="onlyNumbers"
                      @input="onInputCurrencyCartera(i, 'saldo')"
                    />
                  </td>
                  <td class="text-center">
                    <b-form-checkbox v-model="car.opera_x_desprendible" switch />
                  </td>
                  <td class="text-center">
                    <b-button
                      size="sm"
                      variant="outline-danger"
                      @click="removeCartera(i)"
                    >
                      Quitar
                    </b-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Botón agregar cartera -->
          <button
            type="button"
            class="btn-green mb-3"
            @click="addCartera"
          >
            <PlusIcon class="me-1" /> Agregar cartera
          </button>
        </b-card-body>
      </b-card>

      <!-- ═══════════════════ Botón Guardar ═══════════════════ -->
      <b-button class="btn-green-gradient w-100" type="submit">
        Guardar solicitud
      </b-button>

      <!-- Alertas -->
      <b-alert
        v-if="alertMessage"
        :variant="alertVariant"
        show
        dismissible
        class="mt-3 text-center"
        @dismissed="alertMessage = ''"
      >
        {{ alertMessage }}
      </b-alert>
    </form>
  </div>
</template>

<script>
/* ------------- IMPORTS ------------- */
import axios from "axios";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BFormFile,
  BFormCheckbox,
  BButton,
  BAlert,
  BSpinner,
  BCard,
  BCardBody
} from "bootstrap-vue";

import PlusIcon from "../../icons/PlusIcon.vue";
import ClientTypeIcon from "../../icons/ClientTypeIcon.vue";
import RequisitosCumplidosIcon from "../../icons/RequisitosCumplidosIcon.vue";
import DocumentIcon from "../../icons/DocumentIcon.vue";
import CarteraIcon from "../../icons/CarteraIcon.vue";

export default {
  /* ------------- COMPONENTS ------------- */
  components: {
    BFormGroup,
    BFormInput,
    BFormSelect,
    BFormFile,
    BFormCheckbox,
    BButton,
    BAlert,
    BSpinner,
    BCard,
    BCardBody,
    PlusIcon,
    ClientTypeIcon,
    DocumentIcon,
    CarteraIcon,
    RequisitosCumplidosIcon
  },

  /* ------------- DATA ------------- */
  data() {
    return {
      isLoading: false,
      alertMessage: "",
      alertVariant: "warning",
      form: {
        doc: "",
        name: "",
        client_type: "",
        pagaduria_id: "",
        tipo_credito: "",
        cuota: "",
        monto: "",
        tasa: "",
        plazo: 1,
        carteras: []
      },
      documentos: [],

      /* Selects */
      clientTypeOptions: [
        { value: "", text: "Seleccione" },
        { value: "docente", text: "Docente" },
        { value: "pensionado", text: "Pensionado" }
      ],

      /* Mapa Pagadurías */
      docentePagaduriasMap: {
        "sed amazonas": 1,
        "sed antioquia": 130,
        "sem armenia": 34,
        "sed arauca": 109,
        "sed atlantico": 121,
        "sem barrancabermeja": 160,
        "sem barranquilla": 106,
        "sem bello": 111,
        "sed bolivar": 293,
        "sed boyaca": 110,
        "sem bucaramanga": 39,
        "sem buenaventura": 40,
        "sem buga": 157,
        "sed caldas": 139,
        "sem cali": 191,
        "sed caqueta": 140,
        "sed casanare": 104,
        "sem cartagena": 189,
        "sem cartago": 136,
        "sed cauca": 177,
        "sem chia": 45,
        "sem cienaga": 103,
        "sed cesar": 11,
        "sem cucuta": 286,
        "sed choco": 294,
        "sed cordoba": 182,
        "sed cundinamarca": 163,
        "sem dosquebradas": 112,
        "sem duitama": 49,
        "sem envigado": 115,
        "sem estrella": 168,
        "sem facatativa": 164,
        "sem florencia": 55,
        "sem floridablanca": 170,
        "sem funza": 117,
        "sem fusagasuga": 151,
        "sem girardot": 179,
        "sem giron": 287,
        "sem guainia": 116,
        "sed guajira": 192,
        "sed guaviare": 173,
        "sed huila": 178,
        "sem ibague": 147,
        "sem ipiales": 134,
        "sem itagui": 135,
        "sem jamundi": 146,
        "sem lorica": 67,
        "sed magdalena": 145,
        "sem magangue": 133,
        "sem maicao": 69,
        "sem malambo": 161,
        "sed meta": 113,
        "sem manizales": 174,
        "sem medellin": 180,
        "sem monteria": 176,
        "sem mosquera": 153,
        "sem neiva": 105,
        "sed narino": 143,
        "sed norte de santander": 154,
        "sem palmira": 152,
        "sem pasto": 125,
        "sem pereira": 78,
        "sem piedecuesta": 79,
        "sem pitalito": 138,
        "sed putumayo": 184,
        "sed quindio": 166,
        "sem quibdo": 162,
        "sem riohacha": 150,
        "sem rionegro": 129,
        "sed risaralda": 114,
        "sed santander": 26,
        "sem sabaneta": 108,
        "sem sahagun": 142,
        "sem san andres": 158,
        "sem santa marta": 126,
        "sed sucre": 175,
        "sem soacha": 119,
        "sem sogamoso": 172,
        "sem soledad": 123,
        "sed tolima": 122,
        "sem tulua": 120,
        "sem tunja": 141,
        "sem turbo": 137,
        "sem tumaco": 93,
        "sem uribia": 144,
        "sed valle": 165,
        "sem valledupar": 171,
        "sed vaupes": 132,
        "sed vichada": 32,
        "sem villavicencio": 124,
        "sed sincelejo": 27,
        "sem yopal": 289,
        "sem yumbo": 169,
        "sem zipaquira": 156,
        "casur": 296,
        "fiduprevisora": 297
      },

      /* Opciones Crédito */
      tipoCreditoOptions: [
        { value: "", text: "Seleccione" },
        { value: "Libre Inversión", text: "Libre Inversión" },
        { value: "Compra de Cartera", text: "Compra de Cartera" },
        { value: "Refinanciación", text: "Refinanciación" },
        {
          value: "Refinanciaciónmas + Libre inversión",
          text: "Refinanciación + Libre inversión"
        },
        {
          value: "Refinanciación + Libre Inversión",
          text: "Refinanciación + Compra Cartera"
        }
      ],

      /* Opciones Cartera */
      tipoCarteraOptions: [
        { value: "Banco", text: "Banco" },
        { value: "Cooperativa", text: "Cooperativa" },
        { value: "CFC", text: "CFC" },
        { value: "Financiera", text: "Financiera" },
        { value: "Embargo", text: "Embargo" },
        { value: "Afiliaciones", text: "Afiliaciones" }
      ]
    };
  },

  /* ------------- COMPUTED ------------- */
  computed: {
    showDocenteOptions() {
      return this.form.client_type === "docente";
    },
    showPensionadoOptions() {
      return this.form.client_type === "pensionado";
    },
    docentePagaduriasOptions() {
      const opts = [{ value: "", text: "Seleccione" }];
      for (const [k, v] of Object.entries(this.docentePagaduriasMap)) {
        const t = k.replace(/(sed|sem)/gi, "").trim().toUpperCase();
        opts.push({ value: v, text: t });
      }
      return opts;
    }
  },

  /* ------------- METHODS ------------- */
  methods: {
    /* Sólo números */
    onlyNumbers(e) {
      if (!/[0-9]/.test(e.key)) e.preventDefault();
    },
    /* Números y punto */
    onlyNumbersAndDot(e) {
      if (!/[0-9.]/.test(e.key)) e.preventDefault();
    },

    /* Formatear moneda */
    onInputCurrency(f) {
      const raw = this.form[f].replace(/\D/g, "");
      this.form[f] = raw ? raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "";
    },

    /* Formatear porcentaje */
    onInputPercentage(f) {
      let raw = this.form[f].replace(/[^0-9.]/g, "");
      const p = raw.split(".");
      if (p.length > 2) raw = p[0] + "." + p.slice(1).join("");
      if (p[1]) raw = p[0] + "." + p[1].slice(0, 2);
      this.form[f] = raw ? `${raw}%` : "";
    },

    /* ---------------- Documentos ---------------- */
    addArchivo() {
      this.documentos.push({ file: null });
    },
    removeArchivo(i) {
      this.documentos.splice(i, 1);
    },
    onFileChange(e, i) {
      this.documentos[i].file = e.target.files[0] || null;
    },
    async uploadArchivo(i, id) {
      if (!this.documentos[i].file) return;
      const fd = new FormData();
      fd.append("archivo", this.documentos[i].file);
      await axios.post(`/credit-requests/${id}/documents`, fd, {
        headers: { "Content-Type": "multipart/form-data" }
      });
    },

    /* ---------------- Carteras ---------------- */
    addCartera() {
      this.form.carteras.push({
        tipoCartera: "",
        nombreEntidad: "",
        valorCuota: "",
        saldo: "",
        opera_x_desprendible: false
      });
    },
    removeCartera(i) {
      this.form.carteras.splice(i, 1);
    },
    onInputCurrencyCartera(i, f) {
      const raw = this.form.carteras[i][f].replace(/\D/g, "");
      this.form.carteras[i][f] = raw
        ? raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        : "";
    },

    /* ---------------- Envío formulario ---------------- */
    async submitForm() {
      /* Validaciones */
      if (this.documentos.length < 1) {
        this.alertVariant = "warning";
        this.alertMessage = "Debes añadir al menos un documento.";
        return;
      }
      if (!this.documentos.some((d) => d.file)) {
        this.alertVariant = "warning";
        this.alertMessage = "Selecciona un archivo antes de guardar.";
        return;
      }

      this.isLoading = true;
      try {
        /* Payload */
        const payload = {
          doc: this.form.doc,
          name: this.form.name,
          client_type: this.form.client_type,
          pagaduria_id: this.form.pagaduria_id,
          tipo_credito: this.form.tipo_credito,
          cuota: parseInt(this.form.cuota.replace(/\./g, "")) || 0,
          monto: parseInt(this.form.monto.replace(/\./g, "")) || 0,
          tasa: parseFloat(this.form.tasa.replace(/[^\d.]/g, "")) || 0,
          plazo: parseInt(this.form.plazo) || 1,
          carteras: this.form.carteras.map((c) => ({
            tipo_cartera: c.tipoCartera,
            nombre_entidad: c.nombreEntidad,
            valor_cuota: parseInt(c.valorCuota.replace(/\./g, "")) || 0,
            saldo: parseInt(c.saldo.replace(/\./g, "")) || 0,
            opera_x_desprendible: c.opera_x_desprendible
          }))
        };

        /* Guardar solicitud */
        const { data } = await axios.post("/credit-requests", payload);
        const id = data?.data?.id;
        if (!id) {
          this.alertVariant = "danger";
          this.alertMessage = "Error inesperado. Intenta de nuevo.";
          this.isLoading = false;
          return;
        }

        /* Subir documentos */
        for (let i = 0; i < this.documentos.length; i++) {
          await this.uploadArchivo(i, id);
        }

        /* Toast éxito */
        this.$bvToast.toast("Crédito y documentos guardados con éxito.", {
          title: "Éxito",
          variant: "success",
          solid: true,
          toaster: "b-toaster-top-center",
          autoHideDelay: 4000
        });

        setTimeout(() => (window.location.href = "/credit-requests"), 1500);
      } catch (e) {
        this.alertVariant = "danger";
        this.alertMessage = "Ocurrió un error al guardar el crédito.";
      } finally {
        this.isLoading = false;
      }
    },

    /* Reset pagaduría al cambiar tipo */
    onChangeClientType() {
      this.form.pagaduria_id = "";
    }
  }
};
</script>

<style scoped lang="scss">
/* ---- Tarjeta principal ---- */
.card-main {
  position: relative;
  border-radius: 0.75rem;
  background-color: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* sombra más marcada */
}

/* ---- Overlay (spinner) ---- */
.overlay {
  position: absolute;
  inset: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}

/* ---- Secciones ---- */
.section-card {
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
}

.section-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  font-weight: 600;
  font-size: 1.125rem;
  border-bottom: 1px solid #e2e8f0;
  background: #fafafa;
}

.section-icon {
  width: 20px;
  height: 20px;
  color: #35a15a; /* verde claro */
}

/* ---- Inputs ---- */
.form-control2 {
  background: #fafafa;
}

/* ---- Encabezados tablas amarillo clarito ---- */
.table-soft-head thead th {
  background: #FFFBE6; /* amarillo muy claro */
  color: #2d2d2d;
  font-weight: 600;
}

/* ---- Botón blanco ---- */
.btn-outline-gray {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: #ffffff;
  color: #1f2937;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  padding: 0.55rem 1rem;
  font-weight: 600;
}

.btn-outline-gray:hover {
  background: #f3f4f6;
}

/* ---- Botón verde claro ---- */
.btn-green {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: #35a15a;
  color: #ffffff;
  border: none;
  border-radius: 0.5rem;
  padding: 0.55rem 1rem;
  font-weight: 600;
  transition: background 0.2s ease;
}

.btn-green:hover {
  background: #2e8d4d;
}

/* ---- Botón gradiente ---- */
.btn-green-gradient {
  background-image: linear-gradient(90deg, #35a15a, #4cc36a);
  border: none;
  border-radius: 0.5rem;
  padding: 0.7rem 1rem;
  font-weight: 600;
  color: #fff;
  transition: opacity 0.3s ease;
}

.btn-green-gradient:hover {
  opacity: 0.9;
}
</style>
