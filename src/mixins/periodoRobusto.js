import axios from "axios"
const toInt=v=>{if(v==null)return 0;const s=String(v).replace(/[^\d\-.,]/g,"").replace(",",".");const n=Number(s);return Number.isFinite(n)?Math.trunc(n):0}
const pad2=n=>String(n).padStart(2,"0")
const lastDay=(y,m)=>new Date(y,m,0).getDate()
const uniqBy=(arr,key)=>{const s=new Set();return arr.filter(x=>{const k=key(x);if(s.has(k))return false;s.add(k);return true})}
export default{
  props:{cedula:{type:[String,Number],required:true},forceDefaultPeriod:{type:Boolean,default:false}},
  data(){return{periods:[],selectedPeriod:null,ymd:null,diasLaborados:null,historialLaboral:null,cupo:null,periodsError:null,loading:false}},
  computed:{canLoad(){return!!this.ymd}},
  watch:{selectedPeriod:{immediate:true,handler(v){this.ymd=this.parsePeriod(v);if(this.ymd?.year&&this.ymd?.month)this.fetchAllForPeriod(this.ymd.year,this.ymd.month)}}},
  mounted(){this.bootstrapPeriods()},
  methods:{
    parsePeriod(v){
      if(!v)return null
      if(typeof v==="object"&&v.year&&v.month){const y=Number(v.year),m=Number(v.month),d=v.day?Number(v.day):lastDay(y,m);return{year:y,month:m,day:d,label:`${y}-${pad2(m)}-${pad2(d)}`,labelMonth:`${y}-${pad2(m)}`}}
      const s=String(v).trim(),m=s.match(/^(\d{4})-(\d{1,2})(?:-(\d{1,2}))?$/);if(!m)return null
      const y=Number(m[1]),mm=Number(m[2]),dd=m[3]?Number(m[3]):lastDay(y,mm);return{year:y,month:mm,day:dd,label:`${y}-${pad2(mm)}-${pad2(dd)}`,labelMonth:`${y}-${pad2(mm)}`}
    },
    async bootstrapPeriods(){
      this.periodsError=null
      try{
        const {data}=await axios.get("/pagaduria/periodos")
        const raw=Array.isArray(data)?data:(Array.isArray(data?.data)?data.data:[])
        const norm=raw.map(p=>{if(typeof p==="string")return this.parsePeriod(p);if(p?.label)return this.parsePeriod(p.label);if(p?.date)return this.parsePeriod(p.date);if(p?.year&&p?.month)return this.parsePeriod(p);return null}).filter(Boolean)
        const list=uniqBy(norm,x=>x.label).sort((a,b)=>a.label<b.label?1:-1)
        this.periods=list.length?list:[]
        if(!this.periods.length&&this.forceDefaultPeriod){const d=new Date(),y=d.getFullYear(),m=d.getMonth()+1,dd=lastDay(y,m);this.periods=[{year:y,month:m,day:dd,label:`${y}-${pad2(m)}-${pad2(dd)}`,labelMonth:`${y}-${pad2(m)}`}]}
        if(this.periods.length){const prefer=this.periods[0];this.selectedPeriod=prefer.label;this.ymd=prefer}else{this.periodsError="Sin periodos"}
      }catch(_){
        if(this.forceDefaultPeriod){const d=new Date(),y=d.getFullYear(),m=d.getMonth()+1,dd=lastDay(y,m);this.periods=[{year:y,month:m,day:dd,label:`${y}-${pad2(m)}-${pad2(dd)}`,labelMonth:`${y}-${pad2(m)}`}];this.selectedPeriod=this.periods[0].label;this.ymd=this.periods[0]}else{this.periodsError="Error periodos"}
      }
    },
    async fetchAllForPeriod(year,month){
      this.loading=true
      try{
        const [dias,hist,cupo]=await Promise.all([
          this.fetchDiasLaborados(this.cedula,month,year),
          this.fetchHistorialLaboral(this.cedula,month,year),
          this.calcularCupo(this.cedula,month,year)
        ])
        this.diasLaborados=toInt(dias)
        this.historialLaboral=hist
        this.cupo=cupo
        this.$emit("dias-laborados",this.diasLaborados)
        this.$emit("periodo",this.ymd)
      }finally{this.loading=false}
    },
    pickDias(obj){
      if(obj==null)return 0
      if(typeof obj==="number"||typeof obj==="string")return toInt(obj)
      if(Array.isArray(obj)){const c=obj.find(x=>x&&("total_dias"in x||"dias_laborados"in x||"diasLaborados"in x||"dias"in x||"total"in x));return c?this.pickDias(c):0}
      if("total_dias"in obj)return toInt(obj.total_dias)
      if("dias_laborados"in obj)return toInt(obj.dias_laborados)
      if("diasLaborados"in obj)return toInt(obj.diasLaborados)
      if("dias"in obj)return toInt(obj.dias)
      if("total"in obj)return toInt(obj.total)
      if("data"in obj)return this.pickDias(obj.data)
      return 0
    },
    async fetchDiasLaborados(cedula,mes,anio){const {data}=await axios.get(`/demografico/dias-laborados/${cedula}/${mes}/${anio}`);return this.pickDias(data)},
    async fetchHistorialLaboral(cedula,mes,anio){const {data}=await axios.get(`/demografico/historial-laboral/${cedula}/${mes}/${anio}`);return data??null},
    async calcularCupo(cedula,mes,anio){const {data}=await axios.get(`/demografico/calcular-cupo/${cedula}/${mes}/${anio}`);return data??null},
    refresh(){if(this.ymd?.year&&this.ymd?.month)this.fetchAllForPeriod(this.ymd.year,this.ymd.month)}
  }
}
