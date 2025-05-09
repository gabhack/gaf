<template>
    <!-- Modal Visar Manualmente -->
    <b-modal
      id="modal-visar-manualmente"
      v-model="visible"
      title="Visar Manualmente"
      hide-footer
      centered
    >
      <!-- ======= FORMULARIO ======= -->
      <form v-if="localForm" @submit.prevent="submit">
        <!-- Documento -->
        <b-form-group label="Documento" label-for="doc">
          <b-form-input id="doc" v-model.trim="localForm.doc" required />
        </b-form-group>
  
        <!-- Nombre -->
        <b-form-group label="Nombre" label-for="nombre">
          <b-form-input id="nombre" v-model.trim="localForm.nombre" required />
        </b-form-group>
  
        <!-- Pagaduría -->
        <b-form-group label="Pagaduría" label-for="pagaduria">
          <b-form-input id="pagaduria" v-model.trim="localForm.pagaduria" required />
        </b-form-group>
  
        <div class="row">
          <!-- Plazo -->
          <div class="col-md-6">
            <b-form-group label="Plazo" label-for="plazo">
              <b-form-input
                id="plazo"
                type="number"
                min="1"
                v-model.number="localForm.plazo"
                required
              />
            </b-form-group>
          </div>
  
          <!-- Monto -->
          <div class="col-md-6">
            <b-form-group label="Monto" label-for="monto">
              <b-form-input
                id="monto"
                type="number"
                min="0"
                v-model.number="localForm.monto"
                required
              />
            </b-form-group>
          </div>
        </div>
  
        <!-- Cuota Crédito -->
        <b-form-group label="Cuota Crédito" label-for="cuotacredito">
          <b-form-input
            id="cuotacredito"
            type="number"
            min="0"
            v-model.number="localForm.cuotacredito"
            required
          />
        </b-form-group>
  
        <!-- Estado -->
        <b-form-group label="Estado" label-for="estado">
          <b-form-select
            id="estado"
            v-model="localForm.estado"
            :options="estadoOptions"
            required
          />
        </b-form-group>
  
        <!-- Causal -->
        <b-form-group label="Causal" label-for="causal">
          <b-form-select
            id="causal"
            v-model="localForm.causal"
            :options="causalesOptions"
            required
          />
        </b-form-group>
  
        <!-- Observación -->
        <b-form-group label="Observación" label-for="observacion">
          <b-form-textarea
            id="observacion"
            rows="2"
            v-model.trim="localForm.observacion"
            required
          />
        </b-form-group>
  
        <!-- Botones -->
        <div class="text-center mt-3">
          <button type="submit" class="btn-credit">Guardar</button>
          <button type="button" class="btn-credit ml-2" @click="visible = false">
            Cancelar
          </button>
        </div>
      </form>
    </b-modal>
  </template>
  
  <script>
  import axios from 'axios'
  import {
    BModal,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BFormTextarea
  } from 'bootstrap-vue'
  
  export default {
    name: 'ManualVisadoModal',
  
    components: {
      BModal,
      BFormGroup,
      BFormInput,
      BFormSelect,
      BFormTextarea
    },
  
    props: {
      value: { type: Boolean, default: false },     // v-model
      visadoForm: { type: Object, default: null }   // datos desde el padre
    },
  
    data () {
      return {
        localForm: null,
        estadoOptions: [
          { value: 'factible',    text: 'factible' },
          { value: 'no factible', text: 'no factible' }
        ]
      }
    },
  
    computed: {
      visible: {
        get () { return this.value },
        set (v) { this.$emit('input', v) }
      },
      causalesOptions () {
        if (!this.localForm) return []
        return this.localForm.estado === 'factible'
          ? [{ value: 'Sin causal', text: 'Sin causal' }]
          : [
              { value: 'Presenta obligaciones en mora', text: 'Presenta obligaciones en mora' },
              { value: 'Negado por cupo',                text: 'Negado por cupo' },
              { value: 'Cliente en proceso de retiro',   text: 'Cliente en proceso de retiro' },
              { value: 'No factible por pagaduria',      text: 'No factible por pagaduria' },
              { value: 'Ingresa descuento nuevo',        text: 'Ingresa descuento nuevo' }
            ]
      }
    },
  
    watch: {
      visadoForm: {
        immediate: true,
        deep: true,
        handler (val) {
          this.localForm = val ? JSON.parse(JSON.stringify(val)) : null
        }
      }
    },
  
    methods: {
      async submit () {
        try {
          /* -------- validación mínima -------- */
          const required = [
            'doc','nombre','pagaduria','plazo','monto',
            'cuotacredito','estado','causal','observacion'
          ]
          for (const k of required) {
            if (!this.localForm[k] && this.localForm[k] !== 0) {
              alert(`El campo "${k}" es obligatorio`)
              return
            }
          }
  
          /* -------- payload para visados -------- */
          const payload = {
            estado:       this.localForm.estado,
            cuotacredito: this.localForm.cuotacredito,
            monto:        this.localForm.monto,
            causal:       this.localForm.causal,
            observacion:  this.localForm.observacion,
            creditId:     this.localForm.creditId,
            doc:          this.localForm.doc,
            nombre:       this.localForm.nombre,
            pagaduria:    this.localForm.pagaduria,
            plazo:        this.localForm.plazo
          }
  
          /* -------- crear / actualizar visado -------- */
          if (this.localForm.visado_id) {
            await axios.put(`/visados/${this.localForm.visado_id}`, payload)
          } else {
            const { data } = await axios.post('/visados', payload)
            this.localForm.visado_id = data.id   // por si se necesita luego
          }
  
          /* -------- ACTUALIZAR ESTATUS DEL CRÉDITO -------- */
          await axios.patch(
            `/credit-requests/${this.localForm.creditId}/status`,
            { status: this.localForm.estado.toLowerCase() }
          )
  console.log("ENtro al manual")
          /* -------- feedback -------- */
          this.$bvToast?.toast('Visado guardado y estado actualizado.', {
            title: 'Éxito',
            variant: 'success',
            solid: true,
            toaster: 'b-toaster-top-center',
            autoHideDelay: 3500
          })
  
          /* -------- notificar al padre -------- */
          this.$emit('saved')
          this.visible = false
        } catch (err) {
          console.error('Error guardando visado', err)
          const msg = err.response?.data?.message || err.message
          this.$bvToast?.toast(`Error: ${msg}`, {
            title: 'Error',
            variant: 'danger',
            solid: true,
            toaster: 'b-toaster-top-center',
            autoHideDelay: 5000
          })
        }
      }
    }
  }
  </script>
  
  <style scoped>
  /* Botón reutilizado del proyecto */
  .btn-credit{
    color:#fff;background:#0cedb0;border:none;border-radius:5px;
    padding:7px 14px;font-size:14px;cursor:pointer;margin:2px;
  }
  /* Inputs coherentes con el resto del sistema */
  .form-control,
  .b-form-input,
  textarea{
    background:#fff !important;color:#000 !important;
  }
  label{font-weight:600;color:#000 !important;}
  </style>
  