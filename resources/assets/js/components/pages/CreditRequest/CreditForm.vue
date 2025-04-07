<template>
  <div class="d-flex justify-content-center">
    <form
      id="credit-form"
      @submit.prevent="submitForm"
      class="card-main mt-5 mb-5 px-4 py-4"
      style="max-width: 800px; width: 100%;"
    >
      <h3 class="heading-title mb-4 text-center">
        Panel de Solicitudes de Consulta para Crédito
      </h3>

      <!-- Cédula -->
      <b-form-group label="Cédula" label-for="doc">
        <b-form-input
          id="doc"
          class="form-control2"
          v-model="form.doc"
          type="text"
          required
        />
      </b-form-group>

      <!-- Nombre -->
      <b-form-group label="Nombre" label-for="name">
        <b-form-input
          id="name"
          class="form-control2"
          v-model="form.name"
          type="text"
          required
        />
      </b-form-group>

      <!-- Tipo de Cliente -->
      <b-form-group label="Tipo de Cliente" label-for="client_type">
        <b-form-select
          id="client_type"
          class="form-control2"
          v-model="form.client_type"
          :options="clientTypeOptions"
          required
          @change="onChangeClientType"
        />
      </b-form-group>

      <!-- Pagaduría (Docente) -->
      <div v-if="showDocenteOptions">
        <b-form-group label="Pagaduría (Docente)">
          <b-form-select
            class="form-control2"
            v-model.number="form.pagaduria_id"
            :options="docentePagaduriasOptions"
            required
          >
            <option disabled value="">Seleccione</option>
          </b-form-select>
        </b-form-group>
      </div>

      <!-- Pagaduría (Pensionado) -->
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

      <!-- Tipo de Crédito -->
      <b-form-group label="Tipo de Crédito" label-for="tipo_credito">
        <b-form-select
          id="tipo_credito"
          class="form-control2"
          v-model="form.tipo_credito"
          :options="tipoCreditoOptions"
          required
        />
      </b-form-group>

      <!-- Cuota -->
      <b-form-group label="Cuota" label-for="cuota">
        <b-form-input
          id="cuota"
          class="form-control2"
          v-model="form.cuota"
          type="text"
          inputmode="numeric"
          required
          placeholder="100.000"
          @keypress="onlyNumbers"
          @input="onInputCurrency('cuota')"
        />
      </b-form-group>

      <!-- Monto -->
      <b-form-group label="Monto" label-for="monto">
        <b-form-input
          id="monto"
          class="form-control2"
          v-model="form.monto"
          type="text"
          inputmode="numeric"
          required
          placeholder="1.000.000"
          @keypress="onlyNumbers"
          @input="onInputCurrency('monto')"
        />
      </b-form-group>

      <!-- Tasa Mensual -->
      <b-form-group label="Tasa (Mensual)" label-for="tasa">
        <b-form-input
          id="tasa"
          class="form-control2"
          v-model="form.tasa"
          type="text"
          inputmode="decimal"
          required
          placeholder="1.50%"
          @keypress="onlyNumbersAndDot"
          @input="onInputPercentage('tasa')"
        />
      </b-form-group>

      <!-- Plazo -->
      <b-form-group label="Plazo (meses)" label-for="plazo">
        <b-form-input
          id="plazo"
          class="form-control2"
          v-model.number="form.plazo"
          type="number"
          inputmode="numeric"
          required
          min="1"
          @keypress="onlyNumbers"
        />
      </b-form-group>

    

      <!-- Documentos (múltiples) -->
      <div class="mb-3">
        <h5>Documentos</h5>
        <small class="text-muted">
          Estos archivos se subirán al finalizar el guardado del crédito. Puedes quitar filas antes de guardar.
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
                  <b-button variant="danger" @click="removeArchivo(idx)">
                    Quitar
                  </b-button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <b-button class="btn-credit" variant="primary" @click="addArchivo">
          Agregar fila para Archivo
        </b-button>
      </div>

      <hr />

      <!-- Carteras -->
      <h5>Carteras a comprar</h5>
      <div class="table-responsive mt-2">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tipo de Cartera</th>
              <th>Nombre de la Entidad</th>
              <th>Valor Cuota</th>
              <th>Saldo</th>
              <th>Opera X Desprendible</th> <!-- check -->
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(car, index) in form.carteras" :key="index">
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
                  type="text"
                  required
                />
              </td>
              <td>
                <b-form-input
                  v-model="car.valorCuota"
                  class="form-control2"
                  type="text"
                  placeholder="100.000"
                  required
                  @keypress="onlyNumbers"
                  @input="onInputCurrencyCartera(index, 'valorCuota')"
                />
              </td>
              <td>
                <b-form-input
                  v-model="car.saldo"
                  class="form-control2"
                  type="text"
                  placeholder="1.000.000"
                  required
                  @keypress="onlyNumbers"
                  @input="onInputCurrencyCartera(index, 'saldo')"
                />
              </td>
              <!-- Nuevo check => opera_x_desprendible -->
              <td class="text-center">
                <b-form-checkbox
                  v-model="car.opera_x_desprendible"
                  switch
                >
                  Sí
                </b-form-checkbox>
              </td>
              <td>
                <b-button variant="danger" @click="removeCartera(index)">
                  Quitar
                </b-button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <b-button class="btn-credit mb-3" @click="addCartera" variant="green-table">
        Agregar otra cartera
      </b-button>

      <hr />

      <!-- Botón final -->
      <b-button class="btn-credit" type="submit" variant="green-table">
        Guardar
      </b-button>
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
  BFormTextarea,
  BFormCheckbox,
  BButton,
  BTable,
  BRow,
  BCol
} from "bootstrap-vue";

export default {
  name: "CreditRequestForm",
  components: {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BFormFile,
    BFormTextarea,
    BFormCheckbox,
    BButton,
    BTable
  },
  data() {
    return {
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
      documentos: [], // { file: null } por fila

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
    "sem cucuta": 47,
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
      let opts = [{ value: "", text: "Seleccione" }];
      for (const [key, val] of Object.entries(this.docentePagaduriasMap)) {
        let cleanText = key
          .replace(/(sed|sem)/gi, "")
          .replace(/\s+/g, "")
          .toUpperCase();
        opts.push({ value: val, text: cleanText });
      }
      return opts;
    }
  },
  methods: {
    /** Bloquean letras en campos numéricos */
    onlyNumbers(e) {
      if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
      }
    },
    onlyNumbersAndDot(e) {
      if (!/[0-9.]/.test(e.key)) {
        e.preventDefault();
      }
    },

    /** Manejo de formato miles */
    onInputCurrency(field) {
      let raw = this.form[field].replace(/\D/g, "");
      if (!raw) {
        this.form[field] = "";
        return;
      }
      this.form[field] = this.addThousandDots(raw);
    },
    addThousandDots(value) {
      // REGEX correcto => agrega '.' cada 3 dígitos
      return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    // Tasa => 2 decimales + '%'
    onInputPercentage(field) {
      let raw = this.form[field].replace(/[^0-9.]/g, "");
      const parts = raw.split(".");
      if (parts.length > 2) {
        raw = parts[0] + "." + parts.slice(1).join("");
      }
      if (parts[1]) {
        parts[1] = parts[1].slice(0, 2);
        raw = parts[0] + "." + parts[1];
      }
      this.form[field] = raw ? raw + "%" : "";
    },

    /** Documentos => agregamos/quitar filas */
    addArchivo() {
      this.documentos.push({ file: null });
    },
    removeArchivo(idx) {
      this.documentos.splice(idx, 1);
    },
    onFileChange(e, idx) {
      this.documentos[idx].file = e.target.files[0] || null;
    },

    // Sube cada archivo luego de crear
    async uploadArchivo(idx, creditID) {
      if (!this.documentos[idx].file) return;
      let formData = new FormData();
      formData.append("archivo", this.documentos[idx].file);
      await axios.post(
        `/credit-requests/${creditID}/documents`,
        formData,
        { headers: { "Content-Type": "multipart/form-data" } }
      );
    },

    /** Carteras => manipular array de {tipoCartera, nombreEntidad, valorCuota, saldo, opera_x_desprendible} */
    addCartera() {
      this.form.carteras.push({
        tipoCartera: "",
        nombreEntidad: "",
        valorCuota: "",
        saldo: "",
        opera_x_desprendible: false // NUEVO
      });
    },
    removeCartera(index) {
      this.form.carteras.splice(index, 1);
    },
    onInputCurrencyCartera(index, field) {
      let raw = this.form.carteras[index][field].replace(/[^0-9]/g, "");
      this.form.carteras[index][field] = raw ? this.addThousandDots(raw) : "";
    },

    async submitForm() {
      try {
        let payload = {
          doc: this.form.doc,
          name: this.form.name,
          client_type: this.form.client_type,
          pagaduria_id: this.form.pagaduria_id,
          tipo_credito: this.form.tipo_credito,
          cuota: parseInt(this.form.cuota.replace(/\./g, "")) || 0,
          monto: parseInt(this.form.monto.replace(/\./g, "")) || 0,
          tasa: parseFloat(this.form.tasa.replace(/[^\d.]/g, "")) || 0,
          plazo: parseInt(this.form.plazo, 10) || 1,
          carteras: this.form.carteras.map(c => ({
            tipo_cartera: c.tipoCartera,
            nombre_entidad: c.nombreEntidad,
            valor_cuota: parseInt(c.valorCuota.replace(/\./g, "")) || 0,
            saldo: parseInt(c.saldo.replace(/\./g, "")) || 0,
            opera_x_desprendible: c.opera_x_desprendible // NUEVO
          }))
        };

        let resp = await axios.post("/credit-requests", payload);
        if (!resp.data.data || !resp.data.data.id) {
          alert("No se recibió un ID válido al guardar crédito.");
          return;
        }

        let creditID = resp.data.data.id;
        alert(`Crédito guardado exitosamente (ID=${creditID}).`);

        // Sube documentos
        for (let i = 0; i < this.documentos.length; i++) {
          await this.uploadArchivo(i, creditID);
        }
        alert("Documentos subidos correctamente.");

        // Redirige a la lista
        window.location.href = "/credit-requests";
      } catch (err) {
        console.error("Error en submitForm =>", err);
        alert("Ocurrió un error al guardar el crédito.");
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
  border-radius: 1rem;
  border: none;
  box-shadow: 7px 7px 18px 3px rgba(0, 0, 0, 0.1);
  background-color: #fff;
}
.btn-credit {
  color: white;
  border: none;
  border-radius: 5px;
  padding: 8px 15px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
  &:hover {
    /* hover style */
  }
  &:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
  }
}
.form-control2 {
  background-color: #fff;
}
.heading-title {
  font-weight: 700;
}
</style>
