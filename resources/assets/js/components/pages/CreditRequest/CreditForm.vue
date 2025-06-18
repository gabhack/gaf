<template>
  <div class="d-flex justify-content-center w-100">
    

    <b-modal id="modal-masivo" size="xl" scrollable hide-footer>
      <template #modal-title><span class="heading-title">Carga masiva de solicitudes</span></template>

      <div class="alert alert-info">
        <p class="mb-2">
          1. Descarga la plantilla (<a href="/templates/credit_requests.xlsx" target="_blank">aquí</a>).<br>
          2. Llénala con <b>Cedula, Nombre completo, Tipo de cliente, Pagaduria,
          Valor cuota mensual ($), Monto solicitado ($), Tasa mensual (%), Plazo (meses), Tipo de credito</b>.<br>
          3. Carga el archivo y revisa la tabla.<br>
          4. Usa <b>Docs</b> o <b>Carteras</b> para anexar archivos / carteras por fila.<br>
          5. Pulsa <b>Procesar</b> para registrar todo.
        </p>
      </div>

      <b-form-file accept=".xlsx,.xls" browse-text="Seleccionar archivo" @change="onMassFileChange" />

      <div v-if="massErrors.length" class="alert alert-danger mt-3">
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
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row,idx) in massPreview" :key="'row'+idx">
              <td v-for="h in previewHeaders" :key="h+idx">{{ row[h] }}</td>
              <td class="text-nowrap">
                <b-button size="sm" variant="primary" @click="triggerDocInput(idx)">
                  Docs<span v-if="row.__docs && row.__docs.length" class="badge-count">{{ row.__docs.length }}</span>
                </b-button>
                <b-button size="sm" variant="success" class="ml-1" @click="openCarteraModal(idx)">
                  Carteras<span v-if="row.__carteras && row.__carteras.length" class="badge-count">{{ row.__carteras.length }}</span>
                </b-button>
                <input type="file" multiple class="d-none" :ref="'docs'+idx" @change="handleDocsChange(idx,$event)" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-end mt-3">
        <b-button variant="success" :disabled="!massPreview.length||sendingMass" @click="processMass">
          <b-spinner small v-if="sendingMass" class="mr-2" /> Procesar
        </b-button>
        <b-button variant="secondary" class="ml-2" @click="$bvModal.hide('modal-masivo')">Cerrar</b-button>
      </div>
    </b-modal>

    <!-- ░░░░░░░░░░  MODAL CARTERAS POR FILA  ░░░░░░░░░░ -->
    <b-modal id="modal-cartera" size="lg" scrollable hide-footer>
      <template #modal-title><span class="heading-title">Carteras de la fila {{ modalRowIndex + 1 }}</span></template>

      <div v-if="modalRowIndex !== null">
        <div class="table-responsive">
          <table class="table table-bordered table-sm table-soft-head">
            <thead><tr><th>Tipo</th><th>Entidad</th><th>Valor cuota</th><th>Saldo</th><th>Desp.</th><th></th></tr></thead>
            <tbody>
              <tr v-for="(c,i) in tempCarteras" :key="'tmp'+i">
                <td><b-form-select v-model="c.tipoCartera" :options="tipoCarteraOptions" /></td>
                <td><b-form-input  v-model="c.nombreEntidad" /></td>
                <td><b-form-input  v-model="c.valorCuota" @keypress="onlyNumbers" /></td>
                <td><b-form-input  v-model="c.saldo"      @keypress="onlyNumbers" /></td>
                <td class="text-center"><b-form-checkbox v-model="c.opera_x_desprendible" switch /></td>
                <td class="text-center"><b-button size="sm" variant="outline-danger" @click="tempCarteras.splice(i,1)">Quitar</b-button></td>
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

    <!-- ░░░░░░░░░░  FORMULARIO INDIVIDUAL  ░░░░░░░░░░ -->
     <!-- ░░░░░░░░░░  BOTÓN & MODAL CARGA MASIVA  ░░░░░░░░░░ -->
    
    <form id="credit-form" @submit.prevent="submitForm" class="card-main mt-3 mb-5 px-4 py-4" style="max-width:860px;width:100%">
    
      <b-button size="sm" variant="outline-success"  @click="$bvModal.show('modal-masivo')">
      <i class="fas fa-file-excel mr-1" /> Carga masiva (Excel)
    </b-button>

      <div v-if="isLoading" class="overlay"><b-spinner variant="success" style="width:3rem;height:3rem" /></div>

      <b-card no-body class="section-card mb-4">
        <div class="section-header">
          <ClientTypeIcon class="section-icon" />
          Información del Cliente
        </div>
        <b-card-body>
          <!-- Cédula -->
          <b-form-group label="Cédula">
            <b-form-input
              class="form-control2"
              v-model="form.doc"
              required
            />
          </b-form-group>

          <!-- Nombre completo + Tipo de cliente en la misma fila -->
          <div class="row">
            <div class="col-md-6">
              <b-form-group label="Nombre completo">
                <b-form-input
                  class="form-control2"
                  v-model="form.name"
                  required
                />
              </b-form-group>
            </div>
            <div class="col-md-6">
              <b-form-group label="Tipo de cliente">
                <b-form-select
                  class="form-control2"
                  v-model="form.client_type"
                  :options="clientTypeOptions"
                  required
                  @change="onChangeClientType"
                />
              </b-form-group>
            </div>
          </div>

          <!-- Pagaduría según tipo de cliente -->
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
                <option :value="297">FIDUPREVISORA</option>
              </b-form-select>
            </b-form-group>
            <div v-if="showTipoPension">
              <b-form-group label="Tipo de Pensión">
                <b-form-select
                  class="form-control2"
                  v-model="form.tipo_pension"
                  :options="fixedPensionOptions"
                  required
                />
              </b-form-group>
              <b-form-group label="Resolución">
                <b-form-input
                  class="form-control2"
                  v-model="form.resolucion"
                  required
                />
              </b-form-group>
            </div>
          </div>
        </b-card-body>
      </b-card>

      <b-card no-body class="section-card mb-4">
        <div class="section-header"><RequisitosCumplidosIcon class="section-icon" /> Condiciones del Crédito</div>
        <b-card-body>
          <div class="row g-3">
            <div class="col-md-6"><b-form-group label="Valor cuota mensual"><b-form-input class="form-control2" v-model="form.cuota" required @keypress="onlyNumbers" @input="onInputCurrency('cuota')" /></b-form-group></div>
            <div class="col-md-6"><b-form-group label="Monto solicitado"><b-form-input class="form-control2" v-model="form.monto" required @keypress="onlyNumbers" @input="onInputCurrency('monto')" /></b-form-group></div>
            <div class="col-md-6"><b-form-group label="Tasa mensual (%)"><b-form-input class="form-control2" v-model="form.tasa" required @keypress="onlyNumbersAndDot" @input="onInputPercentage('tasa')" /></b-form-group></div>
            <div class="col-md-6"><b-form-group label="Plazo (meses)"><b-form-input type="number" class="form-control2" v-model.number="form.plazo" min="1" required @keypress="onlyNumbers" /></b-form-group></div>
          </div>
          <b-form-group label="Tipo de Crédito"><b-form-select class="form-control2" v-model="form.tipo_credito" :options="tipoCreditoOptions" required /></b-form-group>
        </b-card-body>
      </b-card>

      <b-card no-body class="section-card mb-4">
        <div class="section-header"><DocumentIcon class="section-icon" /> Documentos Requeridos</div>
        <b-card-body>
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-3 table-soft-head">
              <thead><tr><th class="w-75">Archivo</th><th class="text-center w-25">Acción</th></tr></thead>
              <tbody>
                <tr v-for="(d,i) in documentos" :key="'d'+i">
                  <td><b-form-file :state="!!d.file" accept=".pdf,.jpg,.jpeg,.png" @change="onFileChange($event,i)" /></td>
                  <td class="text-center"><b-button size="sm" variant="outline-danger" @click="removeArchivo(i)">Quitar</b-button></td>
                </tr>
              </tbody>
            </table>
          </div>
          <button type="button" class="btn-outline-gray" @click="addArchivo"><PlusIcon class="me-1" />Agregar documento</button>
        </b-card-body>
      </b-card>

      <b-card no-body class="section-card mb-4">
        <div class="section-header"><CarteraIcon class="section-icon" /> Información de Cartera a Comprar</div>
        <b-card-body>
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-3 table-soft-head">
              <thead><tr><th>Tipo</th><th>Entidad</th><th>Valor cuota</th><th>Saldo</th><th>Desp.</th><th class="text-center">Acción</th></tr></thead>
              <tbody>
                <tr v-for="(c,i) in form.carteras" :key="'c'+i">
                  <td><b-form-select v-model="c.tipoCartera" :options="tipoCarteraOptions" class="form-control2" :disabled="!requireCarteras" required /></td>
                  <td><b-form-input  v-model="c.nombreEntidad" class="form-control2" :disabled="!requireCarteras" required /></td>
                  <td><b-form-input  v-model="c.valorCuota" class="form-control2" :disabled="!requireCarteras" required @keypress="onlyNumbers" @input="onInputCurrencyCartera(i,'valorCuota')" /></td>
                  <td><b-form-input  v-model="c.saldo"      class="form-control2" :disabled="!requireCarteras" required @keypress="onlyNumbers" @input="onInputCurrencyCartera(i,'saldo')" /></td>
                  <td class="text-center"><b-form-checkbox v-model="c.opera_x_desprendible" :disabled="!requireCarteras" switch /></td>
                  <td class="text-center"><b-button size="sm" variant="outline-danger" @click="removeCartera(i)" :disabled="!requireCarteras">Quitar</b-button></td>
                </tr>
              </tbody>
            </table>
          </div>
          <button type="button" class="btn-green mb-3" @click="addCartera" :disabled="!requireCarteras"><PlusIcon class="me-1" />Agregar cartera</button>
        </b-card-body>
      </b-card>

      <b-button class="btn-green-gradient w-100" type="submit">Guardar solicitud</b-button>
      <b-alert v-if="alertMessage" :variant="alertVariant" show dismissible class="mt-3 text-center" @dismissed="alertMessage=''">{{alertMessage}}</b-alert>
    </form>
  </div>
</template>

<script>
import axios from "axios"
import * as XLSX from "xlsx"
import {
  BFormGroup,BFormInput,BFormSelect,BFormFile,BFormCheckbox,
  BButton,BAlert,BSpinner,BCard,BCardBody,BModal
} from "bootstrap-vue"
import PlusIcon from "../../icons/PlusIcon.vue"
import ClientTypeIcon from "../../icons/ClientTypeIcon.vue"
import RequisitosCumplidosIcon from "../../icons/RequisitosCumplidosIcon.vue"
import DocumentIcon from "../../icons/DocumentIcon.vue"
import CarteraIcon from "../../icons/CarteraIcon.vue"

export default{
name:"CreditForm",
components:{BFormGroup,BFormInput,BFormSelect,BFormFile,BFormCheckbox,BButton,BAlert,BSpinner,BCard,BCardBody,BModal,PlusIcon,ClientTypeIcon,RequisitosCumplidosIcon,DocumentIcon,CarteraIcon},
data(){
return{
isLoading:false,alertMessage:"",alertVariant:"warning",
form:{doc:"",name:"",client_type:"",pagaduria_id:"",tipo_credito:"",cuota:"",monto:"",tasa:"",plazo:1,tipo_pension:"",resolucion:"",carteras:[]},
documentos:[],
clientTypeOptions:[{value:"",text:"Seleccione"},{value:"docente",text:"Docente"},{value:"pensionado",text:"Pensionado"}],
tipoCreditoOptions:[{value:"",text:"Seleccione"},{value:"Libre Inversión",text:"Libre Inversión"},{value:"Compra de Cartera",text:"Compra de Cartera"},{value:"Refinanciación",text:"Refinanciación"},{value:"Refinanciación + Libre inversión",text:"Refinanciación + Libre inversión"},{value:"Refinanciación + Compra Cartera",text:"Refinanciación + Compra Cartera"}],
tipoCarteraOptions:[{value:"Banco",text:"Banco"},{value:"Cooperativa",text:"Cooperativa"},{value:"CFC",text:"CFC"},{value:"Financiera",text:"Financiera"},{value:"Embargo",text:"Embargo"},{value:"Afiliaciones",text:"Afiliaciones"}],
fixedPensionOptions:[{value:"JUBILACIÓN O VEJEZ",text:"JUBILACIÓN O VEJEZ"},{value:"INVALIDEZ",text:"INVALIDEZ"},{value:"PENSIÓN DE GRACIA",text:"PENSIÓN DE GRACIA"},{value:"SUSTITUCIÓN",text:"SUSTITUCIÓN"}],
massPreview:[],previewHeaders:[],massErrors:[],sendingMass:false,modalRowIndex:null,tempCarteras:[],
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
  "sem cali": 42,
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
  "sem san": 158,
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
  "colpensiones": 200,
  "fopep": 201,
  "casur": 296,
  "fiduprevisora": 297
}
}},
computed:{
showDocenteOptions(){return this.form.client_type==="docente"},
showPensionadoOptions(){return this.form.client_type==="pensionado"},
showTipoPension(){return this.showPensionadoOptions},
docentePagaduriasOptions(){
const o=[{value:"",text:"Seleccione"}]
for(const[k,v]of Object.entries(this.docentePagaduriasMap)){o.push({value:v,text:k.replace(/(sed|sem)/gi,"").trim().toUpperCase()})}
return o},
requireCarteras(){return this.form.tipo_credito==="Compra de Cartera"||this.form.tipo_credito==="Refinanciación + Libre inversión"}
},
watch:{
"form.client_type"(){this.form.pagaduria_id=this.form.tipo_pension=this.form.resolucion=""},
"form.pagaduria_id"(){this.form.tipo_pension=this.form.resolucion=""}
},
methods:{
onlyNumbers(e){if(!/[0-9]/.test(e.key))e.preventDefault()},
onlyNumbersAndDot(e){if(!/[0-9.]/.test(e.key))e.preventDefault()},
onInputCurrency(f){const r=this.form[f].replace(/\D/g,"");this.form[f]=r?r.replace(/\B(?=(\d{3})+(?!\d))/g,"."):""},
onInputPercentage(f){let r=this.form[f].replace(/[^0-9.]/g,"");const p=r.split(".");if(p.length>2)r=p[0]+"."+p.slice(1).join("");if(p[1])r=p[0]+"."+p[1].slice(0,2);this.form[f]=r?`${r}%`:""},
addArchivo(){this.documentos.push({file:null})},removeArchivo(i){this.documentos.splice(i,1)},onFileChange(e,i){this.documentos[i].file=e.target.files[0]||null},
emptyCartera(){return{tipoCartera:"Banco",nombreEntidad:"",valorCuota:"",saldo:"",opera_x_desprendible:false}},
addCartera(){if(this.requireCarteras)this.form.carteras.push(this.emptyCartera())},removeCartera(i){this.form.carteras.splice(i,1)},
onInputCurrencyCartera(i,f){const r=this.form.carteras[i][f].replace(/\D/g,"");this.form.carteras[i][f]=r?r.replace(/\B(?=(\d{3})+(?!\d))/g,"."):""},
onChangeClientType(){this.form.pagaduria_id=this.form.tipo_pension=this.form.resolucion=""},

triggerDocInput(i){this.$refs["docs"+i][0].click()},
handleDocsChange(i,e){if(!this.massPreview[i].__docs)this.$set(this.massPreview[i],"__docs",[]);this.massPreview[i].__docs.push(...e.target.files);e.target.value=null},
openCarteraModal(i){this.modalRowIndex=i;this.tempCarteras=JSON.parse(JSON.stringify(this.massPreview[i].__carteras||[]));this.$bvModal.show("modal-cartera")},
saveCarteras(){this.$set(this.massPreview[this.modalRowIndex],"__carteras",JSON.parse(JSON.stringify(this.tempCarteras)));this.$bvModal.hide("modal-cartera")},

onMassFileChange(evt){const f=evt.target.files[0];if(!f)return;const r=new FileReader();r.onload=e=>{const wb=XLSX.read(new Uint8Array(e.target.result),{type:"array"});const ws=wb.Sheets[wb.SheetNames[0]];this.massPreview=XLSX.utils.sheet_to_json(ws,{defval:""});this.previewHeaders=Object.keys(this.massPreview[0]||{})};r.readAsArrayBuffer(f)},
pgId(v){const n=parseInt(v,10);if(!isNaN(n))return n;return this.docentePagaduriasMap[String(v).trim().toLowerCase()]||0},
toNum(x){return Number(String(x).replace(/[^\d.-]/g,""))||0},

async processMass(){
if(!this.massPreview.length)return;this.sendingMass=true;this.massErrors=[];
for(let i=0;i<this.massPreview.length;i++){
const r=this.massPreview[i]
const payload={
doc:String(r["Cedula"]||"").trim(),
name:String(r["Nombre completo"]||"").trim(),
client_type:String(r["Tipo de cliente"]||"").trim(),
pagaduria_id:this.pgId(r["Pagaduria"]),
cuota:this.toNum(r["Valor cuota mensual ($)"]),
monto:this.toNum(r["Monto solicitado ($)"]),
tasa:this.toNum(r["Tasa mensual (%)"]),
plazo:Math.max(1,parseInt(r["Plazo (meses)"],10)||1),
tipo_credito:String(r["Tipo de credito"]||"").trim(),
carteras:(r.__carteras||[]).map(c=>({tipo_cartera:c.tipoCartera,nombre_entidad:c.nombreEntidad,valor_cuota:this.toNum(c.valorCuota),saldo:this.toNum(c.saldo),opera_x_desprendible:c.opera_x_desprendible}))
}
try{
const res=await axios.post("/credit-requests",payload)
const id=res.data&&res.data.data&&res.data.data.id
if(id&&(r.__docs||[]).length){
for(const file of r.__docs){const fd=new FormData();fd.append("archivo",file);await axios.post(`/credit-requests/${id}/documents`,fd,{headers:{"Content-Type":"multipart/form-data"}})}
}
}catch(e){this.massErrors.push({row:i+1,field:"-",msgs:[e.response&&e.response.data&&e.response.data.message||"error"]})}
}
this.sendingMass=false
if(!this.massErrors.length){this.$bvToast.toast("Carga masiva completada.",{title:"Éxito",variant:"success",solid:true,toaster:"b-toaster-top-center",autoHideDelay:4000});setTimeout(()=>location.reload(),1000)}
},

alert(v,m){this.alertVariant=v;this.alertMessage=m},
async submitForm(){
if(this.documentos.length<1){this.alert("warning","Debes añadir al menos un documento.");return}
if(!this.documentos.some(d=>d.file)){this.alert("warning","Selecciona un archivo antes de guardar.");return}
if(this.requireCarteras&&!this.form.carteras.length){this.alert("warning","Debes agregar al menos una cartera.");return}
if(this.showTipoPension&&(!this.form.tipo_pension||!this.form.resolucion)){this.alert("warning","Selecciona tipo de pensión y resolución.");return}
this.isLoading=true
try{
const payload={
doc:this.form.doc,name:this.form.name,client_type:this.form.client_type,pagaduria_id:this.form.pagaduria_id,tipo_credito:this.form.tipo_credito,
cuota:parseInt(this.form.cuota.replace(/\./g,""))||0,monto:parseInt(this.form.monto.replace(/\./g,""))||0,tasa:parseFloat(this.form.tasa.replace(/[^\d.]/g,""))||0,
plazo:this.form.plazo,tipo_pension:this.form.tipo_pension,resolucion:this.form.resolucion,
carteras:this.form.carteras.map(c=>({tipo_cartera:c.tipoCartera,nombre_entidad:c.nombreEntidad,valor_cuota:parseInt(String(c.valorCuota).replace(/\./g,""))||0,saldo:parseInt(String(c.saldo).replace(/\./g,""))||0,opera_x_desprendible:c.opera_x_desprendible}))
}
const res=await axios.post("/credit-requests",payload)
const id=res.data&&res.data.data&&res.data.data.id
if(id){for(const d of this.documentos.filter(x=>x.file)){const fd=new FormData();fd.append("archivo",d.file);await axios.post(`/credit-requests/${id}/documents`,fd,{headers:{"Content-Type":"multipart/form-data"}})}
this.$bvToast.toast("Crédito y documentos guardados con éxito.",{title:"Éxito",variant:"success",solid:true,toaster:"b-toaster-top-center",autoHideDelay:4000});setTimeout(()=>location.reload(),1200)}
}catch(e){this.alert("danger","Ocurrió un error.")}finally{this.isLoading=false}
}}
}
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
