<template>
  <div class="d-flex justify-content-center">
    <form
      id="credit-form"
      @submit.prevent="submitForm"
      class="card-main mt-5 mb-5 px-4 py-4"
      style="max-width: 800px; width: 100%;"
    >
      <div v-if="isLoading" class="overlay">
        <b-spinner variant="success" style="width: 3rem; height: 3rem;" />
      </div>

      <h3 class="heading-title mb-4 text-center">
        Panel de Solicitudes de Consulta para Crédito
      </h3>

      <b-form-group label="Cédula">
        <b-form-input class="form-control2" v-model="form.doc" required />
      </b-form-group>

      <b-form-group label="Nombre">
        <b-form-input class="form-control2" v-model="form.name" required />
      </b-form-group>

      <b-form-group label="Tipo de Cliente">
        <b-form-select
          class="form-control2"
          v-model="form.client_type"
          :options="clientTypeOptions"
          required
          @change="onChangeClientType"
        />
      </b-form-group>

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
            <option :value="297">FIDUPREVISORA</option>
            <option :value="296">CASUR</option>
          </b-form-select>
        </b-form-group>
      </div>

      <b-form-group label="Tipo de Crédito">
        <b-form-select
          class="form-control2"
          v-model="form.tipo_credito"
          :options="tipoCreditoOptions"
          required
        />
      </b-form-group>

      <b-form-group label="Cuota">
        <b-form-input
          class="form-control2"
          v-model="form.cuota"
          inputmode="numeric"
          required
          placeholder="100.000"
          @keypress="onlyNumbers"
          @input="onInputCurrency('cuota')"
        />
      </b-form-group>

      <b-form-group label="Monto">
        <b-form-input
          class="form-control2"
          v-model="form.monto"
          inputmode="numeric"
          required
          placeholder="1.000.000"
          @keypress="onlyNumbers"
          @input="onInputCurrency('monto')"
        />
      </b-form-group>

      <b-form-group label="Tasa (Mensual)">
        <b-form-input
          class="form-control2"
          v-model="form.tasa"
          inputmode="decimal"
          required
          placeholder="1.50%"
          @keypress="onlyNumbersAndDot"
          @input="onInputPercentage('tasa')"
        />
      </b-form-group>

      <b-form-group label="Plazo (meses)">
        <b-form-input
          class="form-control2"
          v-model.number="form.plazo"
          type="number"
          required
          min="1"
          @keypress="onlyNumbers"
        />
      </b-form-group>

      <div class="mb-3">
        <h5>Documentos (al menos 1 obligatorio)</h5>
        <small class="text-muted">
          Estos archivos se subirán al finalizar el guardado.
        </small>
        <div class="table-responsive mt-2">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Archivo</th>
                <th>Acción</th>
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
                <td>
                  <b-button variant="outline-danger" @click="removeArchivo(idx)">
                    Quitar
                  </b-button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <b-button variant="outline-primary" @click="addArchivo">
          Agregar archivo
        </b-button>
      </div>

      <hr />

      <h5>Carteras a comprar</h5>
      <div class="table-responsive mt-2">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Entidad</th>
              <th>Valor Cuota</th>
              <th>Saldo</th>
              <th>Desprendible</th>
              <th></th>
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
              <td>
                <b-button variant="outline-danger" @click="removeCartera(i)">
                  Quitar
                </b-button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <b-button variant="outline-success" class="mb-3" @click="addCartera">
        Agregar cartera
      </b-button>

      <hr />

      <b-button class="btn-credit w-100" type="submit">
        Guardar
      </b-button>

      <b-alert
        v-if="alertMessage"
        :variant="alertVariant"
        show
        dismissible
        class="mt-3 text-center"
        @dismissed="alertMessage=''"
      >
        {{ alertMessage }}
      </b-alert>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import {
  BFormGroup,
  BFormInput,
  BFormSelect,
  BFormFile,
  BFormCheckbox,
  BButton,
  BAlert,
  BSpinner
} from "bootstrap-vue";

export default {
  components: {
    BFormGroup,
    BFormInput,
    BFormSelect,
    BFormFile,
    BFormCheckbox,
    BButton,
    BAlert,
    BSpinner
  },
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
      clientTypeOptions: [
        { value: "", text: "Seleccione" },
        { value: "docente", text: "Docente" },
        { value: "pensionado", text: "Pensionado" }
      ],
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
        "sed choco": 12,
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
        "sem sincelejo": 27,
        "sem yopal": 289,
        "sem yumbo": 169,
        "sem zipaquira": 156,
        "casur": 296,
        "fiduprevisora": 297
      },
      tipoCreditoOptions: [
        { value: "", text: "Seleccione" },
        { value: "Libre Inversión", text: "Libre Inversión" },
        { value: "Compra de Cartera", text: "Compra de Cartera" },
        { value: "Recuperación de Cartera", text: "Recuperación de Cartera" },
        { value: "Refinanciación", text: "Refinanciación" },
        { value: "Refinanciaciónmas + libre inversión", text: "Refinanciación + libre inversión" },
        { value: "Refinanciación + Libre Inversión", text: "Refinanciación + Compra cartera" }
      ],
      tipoCarteraOptions: [
        { value: "Banco", text: "Banco" },
        { value: "Cooperativa", text: "Cooperativa" },
        { value: "CFC", text: "CFC" },
        { value: "Financiera", text: "Financiera" },
        { value: "Embargo", text: "Embargo" }
      ]
    };
  },
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
        const t = k.replace(/(sed|sem)/gi, "").replace(/\s+/g, "").toUpperCase();
        opts.push({ value: v, text: t });
      }
      return opts;
    }
  },
  methods: {
    onlyNumbers(e) {
      if (!/[0-9]/.test(e.key)) e.preventDefault();
    },
    onlyNumbersAndDot(e) {
      if (!/[0-9.]/.test(e.key)) e.preventDefault();
    },
    onInputCurrency(f) {
      const raw = this.form[f].replace(/\D/g, "");
      this.form[f] = raw ? raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "";
    },
    onInputPercentage(f) {
      let raw = this.form[f].replace(/[^0-9.]/g, "");
      const p = raw.split(".");
      if (p.length > 2) raw = p[0] + "." + p.slice(1).join("");
      if (p[1]) raw = p[0] + "." + p[1].slice(0, 2);
      this.form[f] = raw ? raw + "%" : "";
    },
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
      this.form.carteras[i][f] = raw ? raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "";
    },
    async submitForm() {
      if (this.documentos.length < 1) {
        this.alertVariant = "warning";
        this.alertMessage = "Debes añadir al menos un documento.";
        return;
      }
      if (!this.documentos.some(d => d.file)) {
        this.alertVariant = "warning";
        this.alertMessage = "Selecciona un archivo antes de guardar.";
        return;
      }
      this.isLoading = true;
      try {
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
          carteras: this.form.carteras.map(c => ({
            tipo_cartera: c.tipoCartera,
            nombre_entidad: c.nombreEntidad,
            valor_cuota: parseInt(c.valorCuota.replace(/\./g, "")) || 0,
            saldo: parseInt(c.saldo.replace(/\./g, "")) || 0,
            opera_x_desprendible: c.opera_x_desprendible
          }))
        };
        const { data } = await axios.post("/credit-requests", payload);
        const id = data?.data?.id;
        if (!id) {
          this.alertVariant = "danger";
          this.alertMessage = "Error inesperado. Intenta de nuevo.";
          this.isLoading = false;
          return;
        }
        for (let i = 0; i < this.documentos.length; i++) await this.uploadArchivo(i, id);
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
    onChangeClientType() {
      this.form.pagaduria_id = "";
    }
  }
};
</script>

<style scoped lang="scss">
.card-main {
  position: relative;
  border-radius: 1rem;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  background-color: #ffffff;
}
.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}
.btn-credit {
  background-color: #28a745;
  border: none;
  border-radius: 0.5rem;
  padding: 0.6rem 1rem;
  font-size: 1rem;
  color: #fff;
  transition: background 0.3s ease;
}
.btn-credit:hover {
  background-color: #218838;
}
.form-control2 {
  background-color: #fafafa;
}
.heading-title {
  font-weight: 700;
}
</style>
