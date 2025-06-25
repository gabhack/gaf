<template>
  <div class="d-flex justify-content-center w-100">
    <form
      id="credit-form"
      class="card-main mt-3 mb-5 px-4 py-4"
      style="max-width:860px;width:100%"
      @submit.prevent="submitForm"
    >
      <div v-if="isLoading" class="overlay">
        <b-spinner variant="success" style="width:3rem;height:3rem" />
      </div>

      <!-- acceso a pantalla masiva -->
     

      <!-- ░░ Información del Cliente ░░ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <ClientTypeIcon class="section-icon" />
          Información del Cliente
        </div>
        <b-card-body>
          <b-form-group label="Cédula">
            <b-form-input
              v-model="form.doc"
              class="form-control2"
              required
            />
          </b-form-group>

          <div class="row">
            <div class="col-md-6">
              <b-form-group label="Nombre completo">
                <b-form-input
                  v-model="form.name"
                  class="form-control2"
                  required
                />
              </b-form-group>
            </div>

            <div class="col-md-6">
              <b-form-group label="Tipo de cliente">
                <b-form-select
                  v-model="form.client_type"
                  class="form-control2"
                  :options="clientTypeOptions"
                  @change="onChangeClientType"
                  required
                />
              </b-form-group>
            </div>
          </div>

          <!-- pagadurías -->
          <div v-if="showDocenteOptions">
            <b-form-group label="Pagaduría (Docente)">
              <b-form-select
                v-model.number="form.pagaduria_id"
                :options="docentePagaduriasOptions"
                class="form-control2"
                required
              />
            </b-form-group>
          </div>

          <div v-else-if="showPensionadoOptions">
            <b-form-group label="Pagaduría (Pensionado)">
              <b-form-select
                v-model.number="form.pagaduria_id"
                class="form-control2"
                required
              >
                <option disabled value="">Seleccione</option>
                <option :value="200">COLPENSIONES</option>
                <option :value="201">FOPEP</option>
                <option :value="296">CASUR</option>
                <option :value="297">FIDUPREVISORA</option>
              </b-form-select>
            </b-form-group>

            <div v-if="showTipoPension">
              <b-form-group label="Tipo de Pensión">
                <b-form-select
                  v-model="form.tipo_pension"
                  :options="fixedPensionOptions"
                  class="form-control2"
                  required
                />
              </b-form-group>
              <b-form-group label="Resolución">
                <b-form-input
                  v-model="form.resolucion"
                  class="form-control2"
                  required
                />
              </b-form-group>
            </div>
          </div>
        </b-card-body>
      </b-card>

      <!-- ░░ Condiciones del Crédito ░░ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <RequisitosCumplidosIcon class="section-icon" /> Condiciones del Crédito
        </div>
        <b-card-body>
          <div class="row g-3">
            <div class="col-md-6">
              <b-form-group label="Valor cuota mensual">
                <b-form-input
                  v-model="form.cuota"
                  class="form-control2"
                  required
                  @keypress="onlyNumbers"
                  @input="onInputCurrency('cuota')"
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Monto solicitado">
                <b-form-input
                  v-model="form.monto"
                  class="form-control2"
                  required
                  @keypress="onlyNumbers"
                  @input="onInputCurrency('monto')"
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Tasa mensual (%)">
                <b-form-input
                  v-model="form.tasa"
                  class="form-control2"
                  required
                  @keypress="onlyNumbersAndDot"
                  @input="onInputPercentage('tasa')"
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Plazo (meses)">
                <b-form-input
                  v-model.number="form.plazo"
                  type="number"
                  min="1"
                  class="form-control2"
                  required
                  @keypress="onlyNumbers"
                />
              </b-form-group>
            </div>
          </div>

          <b-form-group label="Tipo de Crédito">
            <b-form-select
              v-model="form.tipo_credito"
              :options="tipoCreditoOptions"
              class="form-control2"
              required
            />
          </b-form-group>
        </b-card-body>
      </b-card>

      <!-- ░░ Documentos ░░ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <DocumentIcon class="section-icon" /> Documentos Requeridos
        </div>
        <b-card-body>
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-3 table-soft-head">
              <thead>
                <tr><th class="w-75">Archivo</th><th class="text-center w-25">Acción</th></tr>
              </thead>
              <tbody>
                <tr v-for="(d,i) in documentos" :key="'doc'+i">
                  <td>
                    <b-form-file
                      :state="!!d.file"
                      accept=".pdf,.jpg,.jpeg,.png"
                      @change="onFileChange($event,i)"
                    />
                  </td>
                  <td class="text-center">
                    <b-button size="sm" variant="outline-danger" @click="removeArchivo(i)">
                      Quitar
                    </b-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <button type="button" class="btn-outline-gray" @click="addArchivo">
            <PlusIcon class="me-1" /> Agregar documento
          </button>
        </b-card-body>
      </b-card>

      <!-- ░░ Carteras ░░ -->
      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <CarteraIcon class="section-icon" /> Información de Cartera a Comprar
        </div>
        <b-card-body>
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-3 table-soft-head">
              <thead>
                <tr>
                  <th>Tipo</th><th>Entidad</th><th>Valor cuota</th>
                  <th>Saldo</th><th>Desp.</th><th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(c,i) in form.carteras" :key="'car'+i">
                  <td>
                    <b-form-select
                      v-model="c.tipoCartera"
                      :options="tipoCarteraOptions"
                      class="form-control2"
                      :disabled="!requireCarteras"
                      required
                    />
                  </td>
                  <td>
                    <b-form-input
                      v-model="c.nombreEntidad"
                      class="form-control2"
                      :disabled="!requireCarteras"
                      required
                    />
                  </td>
                  <td>
                    <b-form-input
                      v-model="c.valorCuota"
                      class="form-control2"
                      :disabled="!requireCarteras"
                      required
                      @keypress="onlyNumbers"
                      @input="onInputCurrencyCartera(i,'valorCuota')"
                    />
                  </td>
                  <td>
                    <b-form-input
                      v-model="c.saldo"
                      class="form-control2"
                      :disabled="!requireCarteras"
                      required
                      @keypress="onlyNumbers"
                      @input="onInputCurrencyCartera(i,'saldo')"
                    />
                  </td>
                  <td class="text-center">
                    <b-form-checkbox
                      v-model="c.opera_x_desprendible"
                      :disabled="!requireCarteras"
                      switch
                    />
                  </td>
                  <td class="text-center">
                    <b-button
                      size="sm"
                      variant="outline-danger"
                      :disabled="!requireCarteras"
                      @click="removeCartera(i)"
                    >Quitar</b-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <button
            type="button"
            class="btn-green mb-3"
            :disabled="!requireCarteras"
            @click="addCartera"
          >
            <PlusIcon class="me-1" /> Agregar cartera
          </button>
        </b-card-body>
      </b-card>

      <b-button type="submit" class="btn-green-gradient w-100">
        Guardar solicitud
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
import axios from "axios"
import {
  BFormGroup,BFormInput,BFormSelect,BFormFile,BFormCheckbox,
  BButton,BAlert,BSpinner,BCard,BCardBody
} from "bootstrap-vue"
import PlusIcon               from "../../icons/PlusIcon.vue"
import ClientTypeIcon         from "../../icons/ClientTypeIcon.vue"
import RequisitosCumplidosIcon from "../../icons/RequisitosCumplidosIcon.vue"
import DocumentIcon           from "../../icons/DocumentIcon.vue"
import CarteraIcon            from "../../icons/CarteraIcon.vue"

export default {
  name : "CreditRequestForm",
  components:{
    BFormGroup,BFormInput,BFormSelect,BFormFile,BFormCheckbox,
    BButton,BAlert,BSpinner,BCard,BCardBody,
    PlusIcon,ClientTypeIcon,RequisitosCumplidosIcon,DocumentIcon,CarteraIcon
  },
  data () {
    return {
      isLoading     : false,
      alertMessage  : "",
      alertVariant  : "warning",
      form : {
        doc:"",name:"",client_type:"",pagaduria_id:"",
        tipo_credito:"",cuota:"",monto:"",tasa:"",
        plazo:1,tipo_pension:"",resolucion:"",
        carteras:[]
      },
      documentos            : [],
      clientTypeOptions     : [
        { value:"",text:"Seleccione" },
        { value:"docente",text:"Docente" },
        { value:"pensionado",text:"Pensionado" }
      ],
      tipoCreditoOptions    : [
        {value:"",text:"Seleccione"},
        {value:"Libre Inversión",text:"Libre Inversión"},
        {value:"Compra de Cartera",text:"Compra de Cartera"},
        {value:"Refinanciación",text:"Refinanciación"},
        {value:"Refinanciación + Libre inversión",text:"Refinanciación + Libre inversión"},
        {value:"Refinanciación + Compra Cartera",text:"Refinanciación + Compra Cartera"}
      ],
      tipoCarteraOptions    : [
        {value:"Banco",text:"Banco"},
        {value:"Cooperativa",text:"Cooperativa"},
        {value:"CFC",text:"CFC"},
        {value:"Financiera",text:"Financiera"},
        {value:"Embargo",text:"Embargo"},
        {value:"Afiliaciones",text:"Afiliaciones"}
      ],
      fixedPensionOptions   : [
        {value:"JUBILACIÓN O VEJEZ",text:"JUBILACIÓN O VEJEZ"},
        {value:"INVALIDEZ",text:"INVALIDEZ"},
        {value:"PENSIÓN DE GRACIA",text:"PENSIÓN DE GRACIA"},
        {value:"SUSTITUCIÓN",text:"SUSTITUCIÓN"}
      ],
      docentePagaduriasMap  : {
        "sed amazonas":1,"sed antioquia":130,"sem armenia":34,"sed arauca":109,
        "sed atlantico":121,"sem barrancabermeja":160,"sem barranquilla":106,
        "sem bello":111,"sed bolivar":293,"sed boyaca":110,"sem bucaramanga":39,
        "sem buenaventura":40,"sem buga":157,"sed caldas":139,"sem cali":42,
        "sed caqueta":140,"sed casanare":104,"sem cartagena":189,"sem cartago":136,
        "sed cauca":177,"sem chia":45,"sem cienaga":103,"sed cesar":11,
        "sem cucuta":286,"sed choco":294,"sed cordoba":182,"sed cundinamarca":163,
        "sem dosquebradas":112,"sem duitama":49,"sem envigado":115,
        "sem estrella":168,"sem facatativa":164,"sem florencia":55,
        "sem floridablanca":170,"sem funza":117,"sem fusagasuga":151,
        "sem girardot":179,"sem giron":287,"sem guainia":116,"sed guajira":192,
        "sed guaviare":173,"sed huila":178,"sem ibague":147,"sem ipiales":134,
        "sem itagui":135,"sem jamundi":146,"sem lorica":67,"sed magdalena":145,
        "sem magangue":133,"sem maicao":69,"sem malambo":161,"sed meta":113,
        "sem manizales":174,"sem medellin":180,"sem monteria":176,"sem mosquera":153,
        "sem neiva":105,"sed narino":143,"sed norte de santander":154,
        "sem palmira":152,"sem pasto":125,"sem pereira":78,"sem piedecuesta":79,
        "sem pitalito":138,"sed putumayo":184,"sed quindio":166,"sem quibdo":162,
        "sem riohacha":150,"sem rionegro":129,"sed risaralda":114,
        "sed santander":26,"sem sabaneta":108,"sem sahagun":142,"sem san andres":158,
        "sem santa marta":126,"sed sucre":175,"sem soacha":119,"sem sogamoso":172,
        "sem soledad":123,"sed tolima":122,"sem tulua":120,"sem tunja":141,
        "sem turbo":137,"sem tumaco":93,"sem uribia":144,"sed valle":165,
        "sem valledupar":171,"sed vaupes":132,"sed vichada":32,"sem villavicencio":124,
        "sed sincelejo":27,"sem yopal":289,"sem yumbo":169,"sem zipaquira":156,
        "colpensiones":200,"fopep":201,"casur":296,"fiduprevisora":297
      }
    };
  },
  computed:{
    showDocenteOptions:function(){ return this.form.client_type==="docente"; },
    showPensionadoOptions:function(){ return this.form.client_type==="pensionado"; },
    showTipoPension:function(){ return this.showPensionadoOptions; },
    docentePagaduriasOptions:function(){
      var o=[{value:"",text:"Seleccione"}];
      for (var k in this.docentePagaduriasMap) {
        o.push({ value:this.docentePagaduriasMap[k], text:k.replace(/(sed|sem)/gi,"").trim().toUpperCase() });
      }
      return o;
    },
    requireCarteras:function(){
      return this.form.tipo_credito==="Compra de Cartera"
          || this.form.tipo_credito==="Refinanciación + Libre inversión";
    }
  },
  watch:{
    'form.client_type':function(){ this.form.pagaduria_id=this.form.tipo_pension=this.form.resolucion=""; },
    'form.pagaduria_id':function(){ this.form.tipo_pension=this.form.resolucion=""; }
  },
  methods:{
    onlyNumbers:function(e){ if(!/[0-9]/.test(e.key)) e.preventDefault(); },
    onlyNumbersAndDot:function(e){ if(!/[0-9.]/.test(e.key)) e.preventDefault(); },
    onInputCurrency:function(f){
      var raw=this.form[f].replace(/\D/g,"");
      this.form[f]=raw?raw.replace(/\B(?=(\d{3})+(?!\d))/g,"."):"";
    },
    onInputPercentage:function(f){
      var raw=this.form[f].replace(/[^0-9.]/g,"");
      var p=raw.split(".");
      if(p.length>2) raw=p[0]+"."+p.slice(1).join("");
      if(p[1]) raw=p[0]+"."+p[1].slice(0,2);
      this.form[f]=raw?raw+"%":"";
    },
    addArchivo:function(){ this.documentos.push({file:null}); },
    removeArchivo:function(i){ this.documentos.splice(i,1); },
    onFileChange:function(e,i){ this.documentos[i].file=e.target.files[0]||null; },
    emptyCartera:function(){
      return { tipoCartera:"Banco",nombreEntidad:"",valorCuota:"",saldo:"",opera_x_desprendible:false };
    },
    addCartera:function(){
      if(this.requireCarteras) this.form.carteras.push(this.emptyCartera());
    },
    removeCartera:function(i){ this.form.carteras.splice(i,1); },
    onInputCurrencyCartera:function(i,f){
      var raw=this.form.carteras[i][f].replace(/\D/g,"");
      this.form.carteras[i][f]=raw?raw.replace(/\B(?=(\d{3})+(?!\d))/g,"."):"";
    },
    onChangeClientType:function(){
      this.form.pagaduria_id=this.form.tipo_pension=this.form.resolucion="";
    },
    alert:function(v,m){ this.alertVariant=v; this.alertMessage=m; },
    submitForm:async function(){
      if(this.documentos.length<1){ this.alert("warning","Debes añadir al menos un documento."); return; }
      if(!this.documentos.some(function(d){ return d.file; })){ this.alert("warning","Selecciona un archivo antes de guardar."); return; }
      if(this.requireCarteras&&!this.form.carteras.length){ this.alert("warning","Debes agregar al menos una cartera."); return; }
      if(this.showTipoPension&&(!this.form.tipo_pension||!this.form.resolucion)){
        this.alert("warning","Selecciona tipo de pensión y resolución."); return;
      }

      this.isLoading=true;
      try{
        var payload={
          doc:this.form.doc,
          name:this.form.name,
          client_type:this.form.client_type,
          pagaduria_id:this.form.pagaduria_id,
          tipo_credito:this.form.tipo_credito,
          cuota:parseInt(String(this.form.cuota).replace(/\./g,""))||0,
          monto:parseInt(String(this.form.monto).replace(/\./g,""))||0,
          tasa:parseFloat(String(this.form.tasa).replace(/[^\d.]/g,""))||0,
          plazo:this.form.plazo,
          tipo_pension:this.form.tipo_pension,
          resolucion:this.form.resolucion,
          carteras:this.form.carteras.map(function(c){
            return {
              tipo_cartera:c.tipoCartera,
              nombre_entidad:c.nombreEntidad,
              valor_cuota:parseInt(String(c.valorCuota).replace(/\./g,""))||0,
              saldo:parseInt(String(c.saldo).replace(/\./g,""))||0,
              opera_x_desprendible:c.opera_x_desprendible
            };
          })
        };

        var res=await axios.post("/credit-requests",payload);
        var id=res.data&&res.data.data&&res.data.data.id;

        if(id){
          for(var j=0;j<this.documentos.length;j++){
            if(this.documentos[j].file){
              var fd=new FormData();
              fd.append("archivo",this.documentos[j].file);
              await axios.post("/credit-requests/"+id+"/documents",fd,{headers:{"Content-Type":"multipart/form-data"}});
            }
          }
          this.$bvToast.toast(
            "Crédito y documentos guardados con éxito.",
            { title:"Éxito",variant:"success",solid:true,toaster:"b-toaster-top-center",autoHideDelay:4000 }
          );
          setTimeout(function(){ location.reload(); },1200);
        }
      }catch(e){
        this.alert("danger","Ocurrió un error al guardar.");
      }finally{
        this.isLoading=false;
      }
    }
  }
};
</script>

<style scoped lang="scss">
.card-main{position:relative;border-radius:.75rem;background:#fff;box-shadow:0 4px 12px rgba(0,0,0,.08)}
.overlay{position:absolute;inset:0;background:rgba(255,255,255,.8);display:flex;align-items:center;justify-content:center;z-index:10}
.section-card{border:1px solid #e2e8f0;border-radius:.75rem}
.section-header{display:flex;align-items:center;gap:.5rem;padding:.75rem 1.25rem;font-weight:600;font-size:1.125rem;border-bottom:1px solid #e2e8f0;background:#fafafa}
.section-icon{width:20px;height:20px;color:#35a15a}
.form-control2{background:#fafafa}
.table-soft-head thead th{background:#FFFBE6;color:#2d2d2d;font-weight:600}
.btn-outline-gray{display:inline-flex;align-items:center;gap:.35rem;background:#fff;color:#1f2937;border:1px solid #d1d5db;border-radius:.5rem;padding:.55rem 1rem;font-weight:600}
.btn-outline-gray:hover{background:#f3f4f6}
.btn-green{display:inline-flex;align-items:center;gap:.35rem;background:#35a15a;color:#fff;border:none;border-radius:.5rem;padding:.55rem 1rem;font-weight:600;transition:background .2s}
.btn-green:hover{background:#2e8d4d}
.btn-green-gradient{background-image:linear-gradient(90deg,#35a15a,#4cc36a);border:none;border-radius:.5rem;padding:.7rem 1rem;font-weight:600;color:#fff;transition:opacity .3s}
.btn-green-gradient:hover{opacity:.9}
.badge-count{margin-left:4px;background:#fff;color:#000;font-weight:700;padding:1px 6px;border-radius:8px;font-size:.75rem}
</style>
