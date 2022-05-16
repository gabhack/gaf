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
                <span>¿En cuántas cuotas?</span>
                <small>Maximo <b>180 meses</b></small>
              </template>
            </b-form-group>
          </b-col>
        </b-form-row>    
        <b-form-row>
          <b-col cols="12">
            <b-form-group>
              <!-- <b-form-select v-model="form.due" :options="duesOptions" /> -->
              <b-input-group class="dues">
                <b-input-group-prepend>
                  <b-input-group-text>Cuotas</b-input-group-text>                
                </b-input-group-prepend>
                <b-form-select
                  id="gender"
                  v-model="form.due"
                  :options="selectDues"
                />
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-form-row>

        <b-form-row>
          <b-col cols="12">
            <b-form-group label-for="payDate" class="mb-0">
              <template v-slot:label>
                <span>Seleccione su Genero</span>                
              </template>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="12">
            <b-form-group>
              <!-- <b-form-select v-model="form.due" :options="duesOptions" /> -->
              <b-input-group class="dues">                
                <b-form-select
                  id="gender"
                  v-model="form.gender"
                  :options="gender"
                />
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-form-row>

        <b-form-row>
          <b-col cols="12">
            <b-form-group label-for="payDate" class="mb-0">
              <template v-slot:label>
                <span>Edad</span>                
              </template>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>    
          <b-col cols="12">
            <b-form-group> 
              <b-input-group class="dues">                
                <b-form-input                
                  id="age"
                  v-model.number="form.age"
                  type="number"                
                />
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-form-row>

        <b-form-row>
          <b-col cols="12">
            <b-form-group label-for="payDate" class="mb-0">
              <template v-slot:label>
                <span>Seleccione Tipo de Cliente</span>                
              </template>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="12">
            <b-form-group>
              <b-input-group class="dues">                
                <b-form-select
                  id="clientType"
                  v-model="form.client"
                  :options="clientType"
                  @change="setEntidades()"
                />
              </b-input-group>              
            </b-form-group>
          </b-col>
        </b-form-row>

        <b-form-row v-if="form.client">
          <b-col cols="12">
            <b-form-group label-for="payDate" class="mb-0">
              <template v-slot:label>
                <span>Seleccione la Entidad</span>                
              </template>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row v-if="form.client">
          <b-col cols="12">
            <b-form-group>
              <b-input-group class="dues">                
                <b-form-select id="clientType" v-model="form.entidad" :options="entidades" />
              </b-input-group>              
            </b-form-group>
          </b-col>
        </b-form-row>

        <b-form-row>
          <b-col cols="12">
            <b-form-group label-for="payDate" class="mb-0">
              <template v-slot:label>
                <span>Seleccione el tipo de credito</span>                
              </template>
            </b-form-group>
          </b-col>
        </b-form-row>
        <b-form-row>
          <b-col cols="12">
            <b-form-group>
              <b-input-group class="dues">                
                <b-form-select v-model="form.credit" :options="creditType" />
              </b-input-group>              
            </b-form-group>
          </b-col>
        </b-form-row>
        <div class="btn-credit-wrap" v-if="showButton">
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
              <span class="font-weight-bold">{{ amount | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Cuotas</span>
            </b-col>
            <b-col cols="5">
              <span>{{ dues }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Aval</span>
            </b-col>
            <b-col cols="5">
              <span>{{ aval | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>IVA Aval</span>
            </b-col>
            <b-col cols="5">
              <span>{{ivaAval | currency}}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="font-weight-bold">Comisión</span>
            </b-col>
            <b-col cols="5">
              <span class="font-weight-bold">{{ comision | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="font-weight-bold">Valor 1</span>
            </b-col>
            <b-col cols="5">
              <span>{{ val1TR | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>
        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Valor 2</span>
            </b-col>
            <b-col cols="5">
              <span>{{ val2t | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>IVA CK</span>
            </b-col>
            <b-col cols="5">
              <span>{{ ivaCK | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Credito Total</span>
            </b-col>
            <b-col cols="5">
              <span>{{ totalCredit | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Interes Inicial</span>
            </b-col>
            <b-col cols="5">
              <span>{{ interesInicial | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>GMF</span>
            </b-col>
            <b-col cols="5">
              <span>{{ gmf | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Seguro</span>
            </b-col>
            <b-col cols="5">
              <span>{{ seguro | currency }}</span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span>Total a Pagar</span>
            </b-col>
            <b-col cols="5">
              <span>
                {{ totalCredit2 | currency }}
              </span>
            </b-col>
          </b-row>
        </b-list-group-item>

        <b-list-group-item>
          <b-row>
            <b-col cols="7">
              <span class="h5 font-weight-bold">
                Cuota
              </span>
            </b-col>
            <b-col cols="5">
              <span class="h5 font-weight-bold total-val">
                {{ cuota | currency }}
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
      showButton:true,
      amount:null,
      dues:null,
      aval:null,
      ivaAval:null,
      comision:null,
      val1TR:null,
      val2t:null,
      ivaCK:null,
      totalCredit:null,
      interesInicial:null,
      gmf:null,
      totalCredit2:null,
      seguro:null,
      cuota:null,
      form: {
        requestAmount: 1000000,
        payDate: '',
        due: 1,
        client: null,
        credit: null,
        entidad: null,
        gender: null,
        age:null
      },
      entidades: [],
      collapsed: false,
      subTotal: 0,
      total: 0,
      duesOptions: [{ value: null, text: 'Cuotas' }],
      clientType: [ {
          value:null,
          text:'Seleccione el Tipo de Cliente'
        },{
          value: 'docente-sector-publico',
          text: 'Docente - Sector Publico',
          entidades: [            
            { value: null, text: 'Seleccione la entidad' },
            { value: 'SED-VALLE', text: '(SED) VALLE' },
            { value: 'SED-CAUCA', text: '(SED) CAUCA' },
            { value: 'SED-NARIÑO', text: '(SED) SAN NARIÑO' },
            { value: 'SED-CHOCO', text: '(SED) CHOCO' },            
            { value: 'SED-CALI', text: '(SED) CALI' },
            { value: 'SEM-YUMBO', text: '(SEM) YUMBO' },
            { value: 'SEM-BUGA', text: '(SEM) BUGA' },
            { value: 'SEM-POPAYAN', text: '(SEM) POPAYAN' },
            { value: 'SEM-QUIBDÓ', text: '(SEM) QUIBDÓ' }
          ]
        },
        {
          value: 'pensionado',
          text: 'Pensionado',
          entidades: [
            { value: null, text: 'Seleccione la entidad' },
            { value: 'COLPENSIONES', text: 'Colpensiones' },
            { value: 'FIDUPREVISORA', text: 'Fiduprevisora' },            
            { value: 'FOPEP', text: 'Fopep' }
          ]
        },
      ],

      gender: [{
          value:null,
          text:'Seleccione el Genero'
        },{
          value: 'Masculino',
          text: 'Masculino',        
        },{
          value: 'Femenino',
          text: 'Femenino',        
        },
      ],

      creditType: [{
          value:null,
          text:'Seleccione el tipo de Credito'
        },{
          value: 'libre-inversion',
          text: 'Libranza, Libre Inversión',
          min: 1000000, max: 200000000
        },
        {
          value: 'compra-cartera',
          text: 'Libranza, Compra de Cartera',
          min: 1000000, max: 200000000
        }
      ],

      selectDues: [{
          value: 12,
          text: '12 Meses'
        },{
          value: 24,
          text: '24 Meses'
        },{
          value: 36,
          text: '36 Meses'
        },{
          value: 48,
          text: '48 Meses'
        },{
          value: 60,
          text: '60 Meses'
        },{
          value: 72,
          text: '72 Meses'
        },{
          value: 84,
          text: '84 Meses'
        },{
          value: 96,
          text: '96 Meses'
        },{
          value: 108,
          text: '108 Meses'
        },{
          value: 120,
          text: '120 Meses'
        },{
          value: 132,
          text: '132 Meses'
        },{
          value: 144,
          text: '144 Meses'
        },{
          value: 156,
          text: '156 Meses'
        },{
          value: 168,
          text: '168 Meses'
        },{
          value: 180,
          text: '180 Meses'
        }
      ]
    };
  },
  watch:{
    'form.requestAmount': function(newVal,oldVal){
      if(newVal!==oldVal){
        this.simulator();
      }
    },
    'form.due': function(newVal,oldVal){
      if(newVal!==oldVal){
        this.simulator();
      }
    },
    'form.client': function(newVal){
      if( (newVal === 'pensionado' && this.form.age > 75) || (newVal === 'docente-sector-publico' && this.form.gender === 'Femenino' && this.form.age > 57) || (newVal === 'docente-sector-publico' && this.form.gender === 'Masculino' && this.form.age > 62) ){
        this.showButton = false;
        this.$swal({
          icon: 'warning',
          title: 'Tu solicitud de credito no ha sido aprobada, revisa el campo de edad ingresado o cambia tu tipo de cliente'
        });
      }
    },
    'form.age': function(newVal){
      if(newVal){
        this.showButton = true;
        if( (this.form.client === 'pensionado' && newVal > 75) || (this.form.client === 'docente-sector-publico' && this.form.gender === 'Femenino' && newVal > 57) || (this.form.client === 'docente-sector-publico' && this.form.gender === 'Masculino' && newVal > 62) ){
          this.showButton = false;
          this.$swal({
            icon: 'warning',
            title: 'Tu solicitud de credito no ha sido aprobada, revisa el campo de edad ingresado o cambia tu tipo de cliente'
          });
        }
      }
    }
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
    this.generateDues(30);
    this.simulator();
    this.form.payDate = this.minPayDate;
  },
  methods: {  
    simulator(){
      let tasa = 1.40;

      let val1 = 10/100;
      let val2 = 19/100;
      let val3 = 5/100;
      let val5 = 3.93/100;
      let val6 = 2500;

      this.amount = this.form.requestAmount;
      this.dues = this.form.due;

      this.aval = this.amount * val1;
      this.ivaAval = this.aval*val2;
      this.comision = this.amount * val3;
      this.val1TR = this.amount + this.aval + this.ivaAval + this.comision;

      this.val2t = (this.amount + this.aval + this.ivaAval + this.comision)* val3;
      this.ivaCK =  this.val2t * val2;
      this.totalCredit = this.val1TR + this.val2t + this.ivaCK;
          
      this.interesInicial = this.totalCredit * val5;
      this.gmf = (this.val1TR*4)/1000;

      this.totalCredit2 = this.totalCredit + this.interesInicial + this.gmf;      
      this.seguro = (this.totalCredit2 / 1000000) * val6;
        
      this.cuota = this.totalCredit2 * (Math.pow(1+tasa/100, this.dues)*tasa/100) / (Math.pow(1+tasa/100,this.dues)-1);
    },  
    generateDues(count) {
      for (let i = 1; i <= count; i++) {
        this.duesOptions.push({ value: i, text: `Cuotas: ${i}` });
      }
    },
    setEntidades() {
      this.form.entidad = null;

      const selectType = this.clientType.find(type => {        
        return type.value === this.form.client;
      });

      this.entidades = selectType.entidades || [{ value: null, text: 'No hay entidades' }];
    },
    setCollapsed() {
      this.collapsed = !this.collapsed;
      // document.querySelector('#text-fancy').style.display = this.collapsed ? 'flex' : 'none';
    },
    onSubmit() {
      const params = {
        amount: this.amount,
        dues: this.dues,
        aval: this.aval,
        ivaAval: this.ivaAval,
        comision: this.comision,
        val1TR: this.val1TR,
        val2t: this.val2t,
        ivaCK: this.ivaCK,
        totalCredit: this.totalCredit,
        interesInicial: this.interesInicial,
        gmf: this.gmf,
        totalCredit2: this.totalCredit2,
        seguro: this.seguro,
        cuota: this.cuota,
      }; 

      window.localStorage.setItem('creditInfo', JSON.stringify(params));

      this.$swal({
        icon: 'success',
        title: '¡ Vamos bien !'
      }).then(() => {
        window.location.href = 'RegisterCredit';
      });
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
