<template>
  <form id="credit-form" class="d-flex align-items-center" :class="collapsed ? 'collapsed' : null">
    <b-card no-body class="card-main mt-5 mb-5 ml-5">
      <b-card-body>
        <!-- <div v-if="form.requestAmount >= 1000000">
          <p>Sí es tu primera vez, sólo puedes solicitar hasta $650,000.</p>
        </div>         -->

        <h2 class="title mb-3">Calcula tu crédito</h2>

        <b-form-row>
          <b-col cols="12">
            <b-form-group label="¿Cuánto dinero necesitas?" label-for="requestAmount" class="mb-0">
              <b-form-input
                id="requestAmount"
                v-model.number="form.requestAmount"
                type="number"
                trim
              />
            </b-form-group>
            <b-form-group>
              <b-input-group class="amount">
                <b-input-group-prepend>
                  <b-input-group-text> {{ valuesRange.min | currency }} </b-input-group-text>
                </b-input-group-prepend>
                <b-form-input
                  type="range"
                  :min="valuesRange.min"
                  :max="valuesRange.max"
                  id="amountRange"
                  v-model.number="form.requestAmount"
                  :step="10000"
                />
                <b-input-group-append>
                  <b-input-group-text> {{ valuesRange.max | currency }} </b-input-group-text>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="12">
            <b-form-group label-for="payDate" class="mb-0">
              <template v-slot:label>
                <span>¿Cuándo puedes pagar?</span>
                <small>Una cuota máx de <b>180 meses</b></small>
              </template>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group class="form-group-icon">
              <b-form-datepicker
                id="payDate"
                v-model="form.payDate"                
                locale="es"
                :initial-date="minPayDate"
                :min="minPayDate"
                :max="maxPayDate"
                label-no-date-selected=""
                :date-format-options="{
                  year: 'numeric',
                  month: 'numeric',
                  day: 'numeric'
                }"
                :hide-header="true"
                label-help=""
              >
                <template #button-content>
                  <CalendarIcon class="icon" />
                </template>
              </b-form-datepicker>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group>
              <!-- <b-form-select v-model="form.due" :options="duesOptions" /> -->
              <b-input-group class="dues">
                <b-input-group-prepend>
                  <b-input-group-text>Cuotas</b-input-group-text>
                  <b-button variant="lavender" @click="minusDue">-</b-button>
                </b-input-group-prepend>
                <b-form-input type="text" @change="validateDue" v-model.number="form.due" trim />
                <b-input-group-append>
                  <b-button variant="lavender" @click="plusDue">+</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="12">
            <b-form-group label-for="clientType" class="form-group-icon">
              <ClientTypeIcon class="icon" />
              <b-form-select
                id="clientType"
                v-model="form.client"
                :options="clientType"
                @change="setEntidades()"
              />
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row v-if="form.client">
          <b-col cols="12">
            <b-form-group label-for="clientType" class="form-group-icon">
              <ClientTypeIcon class="icon" />
              <b-form-select id="clientType" v-model="form.entidad" :options="entidades" />
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="12">
            <b-form-group class="form-group-icon">
              <CreditTypeIcon class="icon" />
              <b-form-select v-model="form.credit" :options="creditType" />
            </b-form-group>
          </b-col>
        </b-form-row>
        <div class="btn-credit-wrap">
          <b-button
            class="btn-credit rounded-pill py-3 px-5"
            variant="slate-blue"
            @click="onSubmit"
          >
            Solicita tu crédito
          </b-button>
        </div>
      </b-card-body>
    </b-card>
    <div id="credit-detail">
      <b-list-group>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="font-weight-bold">Valor solicitado</span>
            </b-col>
            <b-col cols="5">
              <span class="font-weight-bold">{{ form.requestAmount | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Interés (25%)</span>
            </b-col>
            <b-col cols="5">
              <span>{{ (form.requestAmount * 0.025) | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Seguro</span>
            </b-col>
            <b-col cols="5">
              <span>2900</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Administración</span>
            </b-col>
            <b-col cols="5">
              <span>25500</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="font-weight-bold">Subtotal</span>
            </b-col>
            <b-col cols="5">
              <span class="font-weight-bold">{{ subTotal | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="font-weight-bold">Tecnología</span>
            </b-col>
            <b-col cols="5">
              <span>0</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>IVA</span>
            </b-col>
            <b-col cols="5">
              <span>4845</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="h5 font-weight-bold">Total a pagar</span>
            </b-col>
            <b-col cols="5">
              <span class="h5 font-weight-bold total-val">
                {{ total | currency }}
              </span>
            </b-col>
          </b-row>
        </b-list-group-item>
      </b-list-group>
    </div>
    <div class="btn-wrap">
      <button type="button" class="btn btn-slate-blue btn-collapse" @click="setCollapsed">
        <LeftArrowIcon />
      </button>
    </div>
  </form>
</template>

<script>
import CalendarIcon from '../../icons/CalendarIcon.vue';
import ClientTypeIcon from '../../icons/ClientTypeIcon.vue';
import CreditTypeIcon from '../../icons/CreditTypeIcon.vue';
import LeftArrowIcon from '../../icons/LeftArrowIcon.vue';

export default {
  props: {
    // currentUser: {
    //   type: Object,
    //   validator: function (value) {
    //     return JSON.parse(value);
    //   }
    // },
    userFirstname: String
  },
  components: {
    CalendarIcon,
    ClientTypeIcon,
    CreditTypeIcon,
    LeftArrowIcon
  },
  data() {
    return {
      form: {
        requestAmount: 1000000,
        payDate: '',
        due: 1,
        client: null,
        credit: null,
        entidad: null
      },
      entidades: [],
      collapsed: false,
      subTotal: 0,
      total: 0,
      duesOptions: [{ value: null, text: 'Cuotas' }],
      clientType: [
        {
          value: null,
          text: 'Tipo de Cliente'
        },
        {
          value: 'docente-sector-publico',
          text: 'Docente - Sector Publico',
          entidades: [
            { value: null, text: 'Entidad' },
            { value: 'SED-CALDAS', text: '(SED) CALDAS' },
            { value: 'SED-CAUCA', text: '(SED) CAUCA' },
            { value: 'SED-CHOCO', text: '(SED) CHOCO' },
            { value: 'SED-SAN-ANDRES', text: '(SED) SAN ANDRES' },
            { value: 'SED-VALLE', text: '(SED) VALLE' },
            { value: 'SEM-BUGA', text: '(SEM) BUGA' },
            { value: 'SEM-CALI', text: '(SEM) CALI' },
            { value: 'SEM-IBAGUE', text: '(SEM) IBAGUE' },
            { value: 'SEM-JAMUNDI', text: '(SEM) JAMUNDI' },
            { value: 'SEM-PASTO', text: '(SEM) PASTO' },
            { value: 'SEM-POPAYÁN', text: '(SEM) POPAYÁN' },
            { value: 'SEM-QUIBDO', text: '(SEM) QUIBDO' }
          ]
        },
        {
          value: 'pensionado',
          text: 'Pensionado',
          entidades: [
            { value: null, text: 'Entidad' },
            { value: 'FIDUPREVISORA', text: 'Fiduprevisora' },
            { value: 'COLPENSIONES', text: 'Colpensiones' },
            { value: 'FOPEP', text: 'Fopep' },
            { value: 'CASUR', text: 'Casur' },
            { value: 'CAGEN', text: 'Cagen' },
            { value: 'CREMIL', text: 'Cremil' },
            { value: 'MINDEFENSA-PENSIONADOS ', text: 'Mindefensa pensionados' },
            { value: 'ALCALDIA-CALI-PENSIONADOS', text: 'Alcaldía de Cali pensionados' }
          ]
        },
      ],

      creditType: [
        { value: null, text: 'Tipo de Crédito' },
        {
          value: 'libre-inversion',
          text: 'Libranza, Libre Inversión',
          min: 1000000, max: 200000000
        },
        {
          value: 'compra-cartera',
          text: 'Libranza, Compra de Cartera',
          min: 1000000, max: 200000000
        }
      ]
    };
  },
  computed: {    
    firstName() {
      return this.userFirstname != '' ? this.userFirstname : 'Usuario';
    },

    items() {
      return {
        amount: { key: 'Valor Solicitado', value: this.form.requestAmount },
        interestRate: { key: 'Interes', value: this.form.requestAmount * 0.025 },
        insurance: { key: 'Seguro', value: 2900 },
        administration: { key: 'Administración', value: 25500 },
        subTotal: { key: 'Sub Total', value: this.subTotal },
        technology: { key: 'Tecnología', value: 0 },
        iva: { key: 'IVA', value: 4845 },
        total: { key: 'Total a Pagar', value: this.total, class: 'total-val' }
      };
    },
    minPayDate() {
      const minDate = new Date();
      minDate.setDate(minDate.getDate() + 4);
      return minDate;
    },
    maxPayDate() {
      const maxDate = new Date();
      // maxDate.setMonth(maxDate.getMonth() + 1);
      maxDate.setDate(maxDate.getDate() + 30);
      return maxDate;
    },
    valuesRange() {
      const value = this.form.credit;
      if (!value) {
        return this.creditType[1];
      } else {
        return this.creditType.find(val => val.value === value);
      }
    }
  },
  created() {
    this.setSubTotal();
    this.setTotal();
    this.generateDues(30);
    this.form.payDate = this.minPayDate;
  },
  methods: {    
    generateDues(count) {
      for (let i = 1; i <= count; i++) {
        this.duesOptions.push({ value: i, text: `Cuotas: ${i}` });
      }
    },
    setEntidades() {
      this.form.entidad = null;

      const selectType = this.clientType.find(type => {
        console.log(type.value, this.form.client);
        return type.value === this.form.client;
      });

      this.entidades = selectType.entidades || [{ value: null, text: 'No hay entidades' }];
    },
    setSubTotal() {
      const subTotal =
        this.items.amount.value +
        this.items.interestRate.value +
        this.items.insurance.value +
        this.items.administration.value;
      this.subTotal = subTotal;
    },
    setTotal() {
      const total = this.items.subTotal.value + this.items.technology.value + this.items.iva.value;
      this.total = total;
    },
    setCollapsed() {
      this.collapsed = !this.collapsed;
      // document.querySelector('#text-fancy').style.display = this.collapsed ? 'flex' : 'none';
    },
    onSubmit() {
      // let items = this.items;
      let tasa = 0.014;

      let val1 = 10/100;
      let val2 = 19/100;
      let val3 = 5/100;
      let val5 = 3.93/100;
      let val6 = 2.500;

      let amount = this.form.requestAmount;
      let dues = this.form.due;

      let aval = amount * val1;
      let ivaAval = aval*val2;
      let comision = amount * val3;
      let val1TR = amount + aval + ivaAval + comision;
       console.log('Calculos TR',val1TR);

      let val2t = (amount + aval + ivaAval + comision)* val3;
      let ivaCK =  val2t * val2;
      let totalCredit = val1TR + val2t + ivaCK;
      
      let interesInicial = totalCredit * val5;
      let gmf = (val1TR*4)/1000;

      let totalCredit2 = totalCredit + interesInicial + gmf;      
      let seguro = (totalCredit2 / 1000000) * val6;
      
      let cuota =  totalCredit2 * dues * tasa
      

      console.log('Calculo vlr 2: ', val2t);
      console.log('Calculo ivaCK: ', ivaCK);
      console.log('Calculo totalCredit: ', totalCredit);
      console.log('Calculo interesInicial: ', interesInicial);
      console.log('Calculo gmf: ', gmf);
      console.log('Calculo totalCredit2: ', totalCredit2);      
      console.log('seguro: ', seguro);
      console.log('Calculo Cuota: '+cuota);

      const params = {
        amount: this.form.requestAmount,
        dues: this.form.due,
        total: this.items.total.value,
        iva: this.items.iva.value,
        technology: this.items.technology.value,
        administration: this.items.administration.value,
        insurance: this.items.insurance.value,
        interestRate: this.items.interestRate.value
      };
      console.log('Parametros: ',params);
      // // this.setCreditInfo(params);
      // window.localStorage.setItem('creditInfo', JSON.stringify(params));

      // this.$swal({
      //   icon: 'success',
      //   title: '¡ Vamos bien !'
      // }).then(() => {
      //   window.location.href = 'RegisterCredit';
      // });
    },
    minusDue() {
      const dueValue = this.form.due;
      this.form.due = dueValue > 1 ? dueValue - 1 : dueValue;
    },
    plusDue() {
      const dueValue = this.form.due;
      this.form.due = dueValue < 180 ? dueValue + 1 : dueValue;
    },
    validateDue() {
      const dueValue = this.form.due;

      if (dueValue < 1) {
        this.form.due = 1;
      } else if (dueValue > 180) {
        this.form.due = 180;
      } else {
        this.form.due = dueValue;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
@import '../../../../../scss/_colors.scss';

.input-group .custom-range {
  border: none;
  margin: initial;
  background: unset;

  &::-webkit-slider-runnable-track {
    height: 3px;
    background: $lavender;
    box-shadow: none;
  }

  &::-webkit-slider-thumb {
    background: $slate-blue;
    height: 8px;
    width: 8px;
    border: none;
    margin-top: -3px;
  }

  &:focus {
    box-shadow: none;

    &::-webkit-slider-runnable-track {
      background: $lavender;
    }
  }
}

input.form-control {
  background-color: $lavender;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  border: none;
}

.b-form-datepicker.form-control {
  background-color: $lavender;
  border-radius: 25px;
  border: none;
  padding: 0;

  & > .btn {
    .icon {
      position: relative;
      left: 6px;
      top: -1px;
    }
  }

  & > .form-control {
    padding: 0.5rem 1.5rem;
    padding-left: 3.25rem;
  }
}
.custom-select {
  background-color: $lavender;
  border-radius: 25px;
  padding: 0.5rem 1.5rem;
  border: none;
  height: auto;
  cursor: pointer;
}

.form-group {
  &-icon {
    .icon {
      cursor: pointer;
      width: 20px;
      height: 20px;
      position: absolute;
      top: 9px;
      left: 22px;
      fill: $cobalt;
      z-index: 1;
    }

    .custom-select {
      padding-left: 3.25rem;
    }
  }
}

$card-width: 570px;

#credit-detail {
  max-width: $card-width;
  width: 100%;
  transition: transform 500ms ease;
  z-index: 2;
}

.btn-wrap {
  display: flex;
  align-items: center;
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100%;
  max-width: $card-width;
  width: 100%;
  justify-content: flex-end;
  transition: transform 500ms ease;
  transform: translateX(100%);
  z-index: 1;

  .btn-collapse {
    position: relative;
    right: -45px;
    border-radius: 100%;
    height: 85px;
    width: 80px;
    border: none;

    &:hover,
    &:focus {
      background: $slate-blue;
      box-shadow: none;
    }

    svg {
      width: 30px;
      height: 30px;
      fill: #fff;
      margin-left: 25px;
      transform: scaleX(-1);
      transition: transform 500ms ease;
    }
  }
}

#credit-detail {
  padding: 3rem;
  background: #021b1e;
  background: linear-gradient(90deg, #021b1e 0%, #021b1e 80%);
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;

  position: absolute;
  transform: translateX(100%);

  .list-group-item {
    background: #021b1e;
    background: linear-gradient(90deg, #021b1e 0%, #021b1e 100%);
    color: #fff;

    padding: 0.5rem 1.25rem;
    border: none;

    &:first-child {
      border-top-right-radius: 15px;
      border-top-left-radius: 15px;
      padding-top: 1rem;
    }

    &:last-child {
      border-bottom-right-radius: 15px;
      border-bottom-left-radius: 15px;
      padding-bottom: 1rem;
    }

    span {
      padding: 5px 8px;
    }

    .total-val {
      background: #021b1e;
      background: linear-gradient(90deg, #021b1e 50%, #021b1e 100%);
      border-radius: 6px;
      padding-top: 5px;
      padding-bottom: 3px;
      display: block;
    }
  }
}

#credit-form {
  &.collapsed {
    #credit-detail {
      transform: translateX(0);
    }

    .btn-wrap {
      transform: translateX(0);

      svg {
        transform: scaleX(1);
      }
    }
  }

  .card-main {
    align-items: center;
    flex-direction: row;
    justify-content: flex-end;

    z-index: 3;

    border-radius: 1rem;
    border: none;
    box-shadow: 7px 7px 18px 3px rgba(0, 0, 0, 0.1);
    max-width: $card-width;

    .card-body {
      padding: 4.25rem 2.8rem 3.25rem 2.8rem;
    }

    .welcome {
      &-wrap {
        text-align: center;
        position: absolute;
        top: -40px;
        left: 0;
        right: 0;
      }

      &-name {
        background-color: $cobalt;
        display: inline-block;
        padding: 1rem 3.75rem;
        font-size: 1.8rem;
        color: white;
        border-radius: 3rem;
        box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.15);
        font-weight: 300;

        b {
          font-weight: 600;
        }
      }
    }
  }

  .title {
    color: $slate-blue;
    font-weight: 700;
    font-size: 1.25rem;
  }
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type='number'] {
  -moz-appearance: textfield;
}

.input-group {
  .input-group-text {
    border: none;
    background: none;
  }

  &.dues {
    background-color: $lavender;
    border-radius: 25px;

    .input-group-text {
      padding-left: 1.3rem;
      padding-right: 0.325rem;
    }

    .form-control {
      padding: 0.5rem 0;
      border-radius: 0;
      text-align: center;

      &:focus {
        background-color: $lavender;
        box-shadow: none;
      }
    }

    .btn {
      background-color: transparent;
      border: none;
      color: $cobalt;
      padding: 0.325rem 0.75rem;
      font-size: 1.3rem;

      &:focus,
      &:active,
      &:active:focus {
        background-color: transparent !important;
        border: none;
        box-shadow: none;
      }
    }
  }

  &.amount {
    .input-group-text {
      color: $slate-blue;
      font-size: 0.9rem;
    }
  }
}

.btn-credit-wrap {
  position: absolute;
  right: 0;
  left: 0;
  text-align: center;
  bottom: -25px;

  .btn-credit {
    color:white;
    background-image: linear-gradient(to right, #0cedb0 0%, #0cedb0 55%, #0cedb0 100%);
    transition: 0.5s;
    background-size: 200% auto;

    &:hover {
      background-position: right center;
    }
  }
}
</style>
