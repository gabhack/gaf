<template>
  <div class="card-main p-4">
    <div class="d-flex flex-wrap align-items-end gap-2 mb-3 w-100">
      <b-form-select v-model="year"  :options="years"  class="form-control2"/>
      <b-form-select v-model="month" :options="months" class="form-control2"/>
      <b-form-select v-model="type"  :options="types"  class="form-control2"/>
      <b-button variant="success" :disabled="isSearching" @click="start">Buscar</b-button>

      <template v-if="isSearching">
        <b-progress :max="pagadurias.length" :value="done" height="22px" class="flex-grow-1"/>
        <span class="ms-2 fw-bold">{{ done }} / {{ pagadurias.length }}</span>
      </template>
    </div>

    <b-table small hover :items="rows" :fields="fields">
      <template #cell(datames)="d">
        <Status :loading="getLoad(d.item.id,'datames')" :value="d.item.datames"/>
      </template>
      <template #cell(cupones)="d">
        <Status :loading="getLoad(d.item.id,'cupones')" :value="d.item.cupones"/>
      </template>
      <template #cell(descuentos)="d">
        <Status :loading="getLoad(d.item.id,'descuentos')" :value="d.item.descuentos"/>
      </template>
      <template #cell(embargos)="d">
        <Status :loading="getLoad(d.item.id,'embargos')" :value="d.item.embargos"/>
      </template>
    </b-table>
  </div>
</template>

<script>
import axios from 'axios'
import { BTable,BFormSelect,BButton,BProgress,BSpinner } from 'bootstrap-vue'

const Status={
  props:{loading:Boolean,value:null},
  components:{BSpinner},
  template:`<div class="text-center" style="min-width:34px">
    <b-spinner small v-if="loading"/>
    <span class="text-success fw-bold" v-else-if="value===true">Sí</span>
    <span class="text-danger  fw-bold" v-else-if="value===false">No</span>
    <span v-else>-</span>
  </div>`
}

export default{
  components:{BTable,BFormSelect,BButton,BProgress,Status},
  data(){
    const n=new Date()
    return{
      pagadurias:[],
      rows:[],
      loading:{},
      done:0,
      isSearching:false,
      year:String(n.getFullYear()),
      month:String(n.getMonth()+1).padStart(2,'0'),
      type:'all',
      fields:[
        {key:'pagaduria',label:'Pagaduría'},
        {key:'datames',label:'DataMes'},
        {key:'cupones',label:'Cupones'},
        {key:'descuentos',label:'Descuentos'},
        {key:'embargos',label:'Embargos'}
      ],
      years:Array.from({length:6},(_,i)=>({value:String(2020+i),text:2020+i})),
      months:Array.from({length:12},(_,i)=>{const m=i+1;return{value:String(m).padStart(2,'0'),text:String(m).padStart(2,'0')}}),
      types:[
        {value:'all',text:'Todos'},
        {value:'datames',text:'DataMes'},
        {value:'cupones',text:'Cupones'},
        {value:'descuentos',text:'Descuentos'},
        {value:'embargos',text:'Embargos'}
      ]
    }
  },
  async mounted(){
    const {data}=await axios.get('/api/data-coverage')
    this.pagadurias=data
    this.rows=data.map(p=>({
      id:p.id,
      pagaduria:p.nombre,
      datames:null,cupones:null,descuentos:null,embargos:null
    }))
  },
  methods:{
    getLoad(id,key){return this.loading[id]&&this.loading[id][key]},
    async start(){
      if(this.isSearching) return
      this.isSearching=true
      this.done=0
      const tasks=[]
      for(const p of this.pagadurias){
        this.$set(this.loading,p.id,{})
        this.$set(this.loading[p.id],'busy',true)
        const url=`/api/data-coverage/pagaduria?id=${p.id}&year=${this.year}&month=${this.month}&type=${this.type}`
        const promise=axios.get(url,{timeout:300000})
          .then(({data})=>{
            const idx=this.rows.findIndex(r=>r.id===p.id)
            if(idx>-1){
              if(this.type==='all'){
                Object.assign(this.rows[idx],{
                  datames:data.datames,
                  cupones:data.cupones,
                  descuentos:data.descuentos,
                  embargos:data.embargos
                })
              }else{
                this.$set(this.rows[idx],this.type,data[this.type])
              }
            }
          })
          .catch(()=>{})
          .finally(()=>{
            this.done++
            this.$delete(this.loading,p.id,'busy')
          })
        tasks.push(promise)
      }
      await Promise.all(tasks)
      this.isSearching=false
    }
  }
}
</script>

<style scoped lang="scss">
.card-main{border-radius:.75rem;background:#fff;box-shadow:0 4px 12px rgba(0,0,0,.08)}
.form-control2{background:#fafafa;height:38px}
</style>
