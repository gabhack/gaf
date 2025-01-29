<template>
  <form id="credit-form" @submit.prevent="submitForm" :class="collapsed ? 'collapsed' : null">
    <b-row style="width: 100%; padding: 5rem;">
      <b-col cols="12" md="6" class="pr-0">
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

            <!-- Pagaduría (Docente) -->
            <b-form-row v-if="showDocenteOptions">
              <b-col cols="12">
                <b-form-group label="Pagaduría (Docente)">
                  <b-form-select
                    class="form-control2"
                    v-model.number="form.pagaduria_id"
                    required
                  >
                    <option disabled value="">Seleccione</option>
                    <option
                      v-for="(code, name) in docentePagaduriasMap"
                      :key="name"
                      :value="code"
                    >
                      {{ name }}
                    </option>
                  </b-form-select>
                </b-form-group>
              </b-col>
            </b-form-row>

            <!-- Pagaduría (Pensionado) -->
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
                    <option :value="202">FIDUPREVISORA</option>
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

            <!-- Tasa (Mensual) -->
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

            <hr />

            <!-- Carteras a comprar -->
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
                      />
                    </td>
                    <td>
                      <b-form-input
                        v-model="car.nombreEntidad"
                        class="form-control2"
                        type="text"
                      />
                    </td>
                    <td>
                      <b-form-input
                        v-model="car.valorCuota"
                        class="form-control2"
                        type="text"
                        placeholder="100.000"
                        @input="onInputCurrencyCartera(index, 'valorCuota')"
                      />
                    </td>
                    <td>
                      <b-form-input
                        v-model="car.saldo"
                        class="form-control2"
                        type="text"
                        placeholder="1.000.000"
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

            <b-button class="btn-credit mb-3" @click="addCartera">
              Agregar otra cartera
            </b-button>

            <hr />

            <b-button class="btn-credit" type="submit">
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
  BButton
} from "bootstrap-vue";

export default {
  name: "CreditRequest",
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BFormRow,
    BFormGroup,
    BFormSelect,
    BFormInput,
    BButton
  },
  data() {
    return {
      collapsed: false,
      form: {
        doc: "",
        name: "",
        client_type: "",
        pagaduria_id: "",
        cuota: "",
        monto: "",
        tasa: "",
        plazo: 1,
        carteras: []
      },

      // Para mostrar selects de pagaduría
      showDocenteOptions: false,
      showPensionadoOptions: false,

      // Ejemplo parcial
      docentePagaduriasMap: {
        "sed antioquia": 130,
        // ... resto ...
      },

      // Opciones para tipo de Cartera
      tipoCarteraOptions: [
        { value: "Banco", text: "Banco" },
        { value: "Cooperativa", text: "Cooperativa" },
        { value: "CFC", text: "CFC" },
        { value: "Financiera", text: "Financiera" },
        { value: "Embargo", text: "Embargo" }
      ]
    };
  },
  methods: {
    // Cambio de tipo de cliente => pagaduría
    onChangeClientType() {
      this.showDocenteOptions = this.form.client_type === "docente";
      this.showPensionadoOptions = this.form.client_type === "pensionado";
      this.form.pagaduria_id = "";
    },

    /**
     * Inserta PUNTOS de miles a medida que se escribe (sin símbolo $).
     * Por ejemplo, si se digita "1234", se convierte en "1.234".
     *
     * Al final, "onInputCurrency(field)" => form[field] = "1.234"
     */
    onInputCurrency(field) {
      // Quitar todo lo que no sean dígitos
      let raw = this.form[field].replace(/\D/g, "");
      if (!raw) {
        this.form[field] = "";
        return;
      }
      // Insertar puntos cada 3 dígitos (empezando desde la derecha)
      this.form[field] = this.addThousandDots(raw);
    },

    // Misma lógica para la columna en carteras (índice + campo)
    onInputCurrencyCartera(index, field) {
      let raw = this.form.carteras[index][field].replace(/\D/g, "");
      if (!raw) {
        this.form.carteras[index][field] = "";
        return;
      }
      this.form.carteras[index][field] = this.addThousandDots(raw);
    },

    /**
     * Función auxiliar para insertar puntos de miles en un string numérico:
     * "1234" => "1.234"
     * "1234567" => "1.234.567"
     */
    addThousandDots(value) {
      // Convertir a string normal (por si se usó parseInt)
      let str = value.toString();
      // Usar regex para colocar un '.' cada 3 dígitos desde el final
      return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    /**
     * Formatea la Tasa con 2 decimales y "%" mientras se escribe.
     */
     onInputPercentage(field) {
  let raw = this.form[field].replace(/[^\d.]/g, ""); // Solo permite números y punto
  if (!raw) {
    this.form[field] = "";
    return;
  }
  const num = parseFloat(raw);
  if (isNaN(num)) {
    this.form[field] = "";
    return;
  }
  this.form[field] = num + "%"; // Mantiene el número tal cual, sin forzar decimales
},

    // Agregar fila de cartera
    addCartera() {
      this.form.carteras.push({
        tipoCartera: "",
        nombreEntidad: "",
        valorCuota: "",
        saldo: ""
      });
    },
    // Quitar fila de cartera
    removeCartera(index) {
      this.form.carteras.splice(index, 1);
    },

    /**
     * Al enviar, convertimos:
     * "1.234" => "1234" => número 1234
     * "1.234.567" => "1234567"
     */
    async submitForm() {
      try {
        const payload = {
          ...this.form,

          // Quitar puntos y parsear a int
          cuota: parseInt(this.form.cuota.replace(/\./g, ""), 10) || 0,
          monto: parseInt(this.form.monto.replace(/\./g, ""), 10) || 0,
          // Tasa: "1.50%" => "1.50" => 1.5
          tasa: parseFloat(this.form.tasa.replace(/[^\d.]/g, "")) || 0,

          // Carteras: limpiar los puntos
          carteras: this.form.carteras.map((c) => ({
            tipoCartera: c.tipoCartera,
            nombreEntidad: c.nombreEntidad,
            valorCuota: parseInt(c.valorCuota.replace(/\./g, ""), 10) || 0,
            saldo: parseInt(c.saldo.replace(/\./g, ""), 10) || 0
          }))
        };

        // Envía a tu endpoint real
        await axios.post("/credit-requests", payload);

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

<style lang="scss" scoped>
.card-main {
  border-radius: 1rem;
  border: none;
  box-shadow: 7px 7px 18px 3px rgba(0, 0, 0, 0.1);
}

.btn-credit {
  color: white;
  background-color: #28a745 !important;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;

  &:hover {
    background-color: #218838 !important;
  }

  &:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
  }
}
</style>
