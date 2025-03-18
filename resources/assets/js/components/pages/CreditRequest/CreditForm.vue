<template>
  <form id="credit-form" @submit.prevent="submitForm" :class="collapsed ? 'collapsed' : null" enctype="multipart/form-data">
    <b-row style="width: 100%;">
      <b-col cols="12" md="8" class="pr-0">
        <b-card no-body class="card-main mt-5 mb-5 ml-5">
          <b-card-body style="padding-top: 3rem;">
            <h3 class="heading-title mb-3">Panel de Solicitudes de Consulta para Crédito</h3>
            
            <!-- Cédula -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Cédula" label-for="doc">
                  <b-form-input
                    id="doc"
                    class="form-control2"
                    v-model="form.doc"
                    type="text"
                    required
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Nombre -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Nombre" label-for="name">
                  <b-form-input
                    id="name"
                    class="form-control2"
                    v-model="form.name"
                    type="text"
                    required
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Tipo de Cliente -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Tipo de Cliente" label-for="client_type">
                  <b-form-select
                    id="client_type"
                    class="form-control2"
                    v-model="form.client_type"
                    :options="[
                      { value: '', text: 'Seleccione' },
                      { value: 'docente', text: 'Docente' },
                      { value: 'pensionado', text: 'Pensionado' }
                    ]"
                    required
                    @change="onChangeClientType"
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Buscador y Select (Docentes) -->
            <b-form-row v-if="showDocenteOptions">
              <b-col cols="12">
                <b-form-group label="Buscar Pagaduría (Docente)">
                  <b-form-input
                    v-model="docenteSearch"
                    placeholder="Escriba para filtrar..."
                  />
                </b-form-group>
              </b-col>
              <b-col cols="12">
                <b-form-group label="Pagaduría (Docente)">
                  <b-form-select
                    class="form-control2"
                    v-model.number="form.pagaduria_id"
                    :options="filteredDocentePagadurias"
                    required
                  >
                    <option disabled value="">Seleccione</option>
                  </b-form-select>
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Pensionados -->
            <b-form-row v-else-if="showPensionadoOptions">
              <b-col cols="12">
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
              </b-col>
            </b-form-row>

            <!-- Cuota -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Cuota" label-for="cuota">
                  <b-form-input
                    id="cuota"
                    class="form-control2"
                    v-model="form.cuota"
                    type="text"
                    required
                    placeholder="100.000"
                    @input="onInputCurrency('cuota')"
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Monto -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Monto" label-for="monto">
                  <b-form-input
                    id="monto"
                    class="form-control2"
                    v-model="form.monto"
                    type="text"
                    required
                    placeholder="1.000.000"
                    @input="onInputCurrency('monto')"
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Tasa Mensual -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Tasa (Mensual)" label-for="tasa">
                  <b-form-input
                    id="tasa"
                    class="form-control2"
                    v-model="form.tasa"
                    type="text"
                    required
                    placeholder="1.50%"
                    @input="onInputPercentage('tasa')"
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Plazo -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Plazo (meses)" label-for="plazo">
                  <b-form-input
                    id="plazo"
                    class="form-control2"
                    v-model.number="form.plazo"
                    type="number"
                    required
                    min="1"
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Documento (Volante de Pago) -->
            <b-form-row>
              <b-col cols="12">
                <b-form-group label="Volante de Pago" label-for="volante">
                  <b-form-file
                    id="volante"
                    v-model="form.volante"
                    :state="Boolean(form.volante)"
                    required
                    accept=".pdf, .jpg, .jpeg, .png"
                    class="form-control2"
                  />
                </b-form-group>
              </b-col>
            </b-form-row>

            <hr />
            <h4>Carteras a comprar</h4>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Tipo de Cartera</th>
                    <th>Nombre de la Entidad</th>
                    <th>Valor Cuota de Cartera</th>
                    <th>Saldo de Cartera</th>
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
                        @input="onInputCurrencyCartera(index, 'saldo')"
                      />
                    </td>
                    <td style="width: 90px;">
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
            <b-button class="btn-credit" type="submit" variant="green-table">
              Guardar
            </b-button>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </form>
</template>

<script>
import axios from "axios";
import {
  BRow,
  BCol,
  BCard,
  BCardBody,
  BFormRow,
  BFormGroup,
  BFormSelect,
  BFormInput,
  BFormFile,
  BButton
} from "bootstrap-vue";

export default {
  name: "CreditRequestForm",
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BFormRow,
    BFormGroup,
    BFormSelect,
    BFormInput,
    BFormFile,
    BButton
  },
  data() {
    return {
      collapsed: false,
      docenteSearch: "", // Para filtrar pagadurías docentes
      form: {
        doc: "",
        name: "",
        client_type: "",
        pagaduria_id: "",
        cuota: "",
        monto: "",
        tasa: "",
        plazo: 1,
        volante: null, // Documento (Volante de Pago)
        carteras: []
      },
      showDocenteOptions: false,
      showPensionadoOptions: false,
      docentePagaduriasMap: {
        "sed amazonas": 1,
        "sed antioquia": 130,
        "sed arauca": 109,
        "sed atlantico": 121,
        "sed bolivar": 5,
        "sed boyaca": 110,
        "sed caldas": 139,
        "sed caqueta": 140,
        "sed casanare": 104,
        "sed cauca": 177,
        "sed cesar": 11,
        "sed choco": 12,
        "sed cordoba": 182,
        "sed cundinamarca": 163,
        "sed guajira": 192,
        "sed guaviare": 173,
        "sed huila": 178,
        "sed magdalena": 145,
        "sed meta": 113,
        "sed narino": 143,
        "sed norte de santander": 154,
        "sed putumayo": 184,
        "sed quindio": 166,
        "sed risaralda": 114,
        "sed santander": 26,
        "sed sucre": 175,
        "sed tolima": 122,
        "sed valle": 165,
        "sed vaupes": 132,
        "sed vichada": 32,
        "sem sincelejo": 27,
        "sem armenia": 34,
        "sem barrancabermeja": 160,
        "sem barranquilla": 106,
        "sem bello": 111,
        "sem bucaramanga": 39,
        "sem buenaventura": 40,
        "sem buga": 157,
        "sem cali": 42,
        "sem cartagena": 43,
        "sem cartago": 136,
        "sem chia": 45,
        "sem cienaga": 103,
        "sem cucuta": 47,
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
        "sem giron": 61,
        "sem guainia": 116,
        "sem ibague": 147,
        "sem ipiales": 134,
        "sem itagui": 135,
        "sem jamundi": 146,
        "sem lorica": 67,
        "sem magangue": 133,
        "sem maicao": 69,
        "sem malambo": 161,
        "sem manizales": 174,
        "sem medellin": 180,
        "sem monteria": 176,
        "sem mosquera": 153,
        "sem neiva": 105,
        "sem palmira": 152,
        "sem pasto": 125,
        "sem pereira": 78,
        "sem piedecuesta": 79,
        "sem pitalito": 138,
        "sem popayan": 159,
        "sem quibdo": 162,
        "sem riohacha": 150,
        "sem rionegro": 129,
        "sem sabaneta": 108,
        "sem sahagun": 142,
        "sem san andres": 158,
        "sem santa marta": 126,
        "sem soacha": 119,
        "sem sogamoso": 172,
        "sem soledad": 123,
        "sem tulua": 120,
        "sem tumaco": 93,
        "sem tunja": 141,
        "sem turbo": 137,
        "sem uribia": 144,
        "sem valledupar": 171,
        "sem villavicencio": 124,
        "sem yopal": 100,
        "sem yumbo": 169,
        "sem zipaquira": 156
      },
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
    // Filtra las pagadurías según el texto de búsqueda
    filteredDocentePagadurias() {
      const searchLower = this.docenteSearch.toLowerCase();
      return Object.entries(this.docentePagaduriasMap)
        .filter(([label]) => label.toLowerCase().includes(searchLower))
        .map(([label, code]) => ({ value: code, text: label }));
    }
  },
  methods: {
    onChangeClientType() {
      this.showDocenteOptions = this.form.client_type === "docente";
      this.showPensionadoOptions = this.form.client_type === "pensionado";
      this.form.pagaduria_id = "";
    },
    onInputCurrency(field) {
      let raw = this.form[field].replace(/\D/g, "");
      if (!raw) {
        this.form[field] = "";
        return;
      }
      this.form[field] = this.addThousandDots(raw);
    },
    onInputCurrencyCartera(index, field) {
      let raw = this.form.carteras[index][field].replace(/\D/g, "");
      if (!raw) {
        this.form.carteras[index][field] = "";
        return;
      }
      this.form.carteras[index][field] = this.addThousandDots(raw);
    },
    addThousandDots(value) {
      let str = value.toString();
      return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    onInputPercentage(field) {
      let raw = this.form[field].replace(/[^\d.]/g, "");
      if (!raw) {
        this.form[field] = "";
        return;
      }
      const num = parseFloat(raw);
      if (isNaN(num)) {
        this.form[field] = "";
        return;
      }
      this.form[field] = num + "%";
    },
    addCartera() {
      this.form.carteras.push({
        tipoCartera: "",
        nombreEntidad: "",
        valorCuota: "",
        saldo: ""
      });
    },
    removeCartera(index) {
      this.form.carteras.splice(index, 1);
    },
    async submitForm() {
      try {
        // Construimos FormData para envío de archivo
        let formData = new FormData();

        formData.append("doc", this.form.doc);
        formData.append("name", this.form.name);
        formData.append("client_type", this.form.client_type);
        formData.append("pagaduria_id", this.form.pagaduria_id);
        formData.append("cuota", this.form.cuota.replace(/\./g, "") || 0);
        formData.append("monto", this.form.monto.replace(/\./g, "") || 0);
        formData.append(
          "tasa",
          this.form.tasa.replace(/[^\d.]/g, "") || 0
        );
        formData.append("plazo", this.form.plazo);
        // Agregar el volante de pago (documento) obligatorio
        formData.append("volante", this.form.volante);

        // Agregamos carteras
        this.form.carteras.forEach((car, index) => {
          formData.append(`carteras[${index}][tipo_cartera]`, car.tipoCartera);
          formData.append(`carteras[${index}][nombre_entidad]`, car.nombreEntidad);
          formData.append(
            `carteras[${index}][valor_cuota]`,
            car.valorCuota.replace(/\./g, "") || 0
          );
          formData.append(
            `carteras[${index}][saldo]`,
            car.saldo.replace(/\./g, "") || 0
          );
        });

        await axios.post("/credit-requests", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        });

        alert("Crédito guardado con éxito.");
        window.location.href = "/credit-requests";
      } catch (error) {
        console.error(error);
        alert("Error al guardar el crédito");
      }
    }
  }
};
</script>

<style scoped lang="scss">
.card-main {
  border-radius: 1rem;
  border: none;
  box-shadow: 7px 7px 18px 3px rgba(0, 0, 0, 0.1);
}
.btn-credit {
  color: white;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  &:hover {
  }
  &:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
  }
}
.form-control2 {
  /* Estilos que necesites */
}
</style>
