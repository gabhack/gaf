<template>
  <div class="form-wrap">
    <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true" />

    <div class="form-center">
      <b-row class="align-items-center mb-4">
        <b-col cols="12" class="d-flex justify-content-between align-items-center">
          <h2 class="heading-title">Realizar consulta</h2>
          <CustomButton class="white" @click="pdfEmit">
            Descargar PDF
            <Download class="ml-2" />
          </CustomButton>
        </b-col>
      </b-row>

      <div class="panel">
        <div class="panel-body">
          <b-form @submit.prevent="validationForm">
            <b-row>
              <b-col cols="12" class="mb-3">
                <label class="panel-label mb-2"><span class="text-danger">*</span> Cédula</label>
                <input
                  required
                  inputmode="numeric"
                  pattern="[0-9]*"
                  maxlength="15"
                  autocomplete="off"
                  class="form-control"
                  :class="{ errorValid: activeId === 'doc' }"
                  placeholder="N° de documento"
                  type="text"
                  v-model.trim="dataclient.doc"
                  @input="onlyDigits('doc')"
                  @change="activeId = ''"
                  ref="doc"
                  aria-required="true"
                  :aria-invalid="activeId === 'doc' ? 'true' : 'false'"
                />
                <small class="hint">Solo números, sin puntos ni guiones</small>
              </b-col>

              <b-col cols="12" class="mb-3">
                <label class="panel-label mb-2"><span class="text-danger">*</span> Nombres y apellidos</label>
                <input
                  required
                  class="form-control"
                  placeholder="Nombre completo"
                  type="text"
                  v-model.trim="dataclient.name"
                  :class="{ errorValid: activeId === 'name' }"
                  @change="activeId = ''"
                  ref="name"
                  aria-required="true"
                  :aria-invalid="activeId === 'name' ? 'true' : 'false'"
                />
                <small class="hint">Como aparece en el documento</small>
              </b-col>

              <b-col cols="12" class="mb-3">
                <label class="panel-label mb-2"><span class="text-danger">*</span> Monto</label>
                <div class="currency-group">
                  <InputCurrency
                    class="currency-el"
                    v-model="dataclient.monto"
                    :validateClass="activeId === 'monto'"
                    @change="activeId = ''"
                    ref="monto"
                    placeholder="Ingrese un monto"
                    rules="required"
                  />
                </div>
                <small class="hint">Valor solicitado</small>
              </b-col>

              <b-col cols="12" class="mb-3">
                <label class="panel-label mb-2"><span class="text-danger">*</span> Cuota deseada</label>
                <div class="currency-group">
                  <InputCurrency
                    class="currency-el"
                    v-model="dataclient.cuotadeseada"
                    :validateClass="activeId === 'cuotadeseada'"
                    @change="activeId = ''"
                    ref="cuotadeseada"
                    placeholder="Valor de la cuota mensual"
                    rules="required"
                  />
                </div>
                <small class="hint">Cuánto desea pagar al mes</small>
              </b-col>

              <b-col cols="12" class="mb-4">
                <label class="panel-label mb-2"><span class="text-danger">*</span> Plazo</label>
                <div class="input-with-addon">
                  <input
                    required
                    class="form-control"
                    type="number"
                    min="1"
                    step="1"
                    v-model.number="dataclient.plazo"
                    placeholder="Ingrese el plazo"
                    :class="{ errorValid: activeId === 'plazo' }"
                    @change="activeId = ''"
                    ref="plazo"
                    aria-required="true"
                    :aria-invalid="activeId === 'plazo' ? 'true' : 'false'"
                  />
                  <span class="addon">meses</span>
                </div>
                <small class="hint">Cantidad de meses del crédito</small>
              </b-col>

              <b-col cols="12" class="mb-2">
                <CustomButton class="btn-cta" block @click="validationForm" :disabled="isLoading || flag">
                  Consultar Pagadurías
                </CustomButton>
                <small v-if="userRole !== 'ADMIN_SISTEMA'" class="d-block mt-2">
                  Consultas disponibles:
                  <b-badge variant="light">{{ user.consultas_diarias || 0 }}</b-badge>
                </small>
              </b-col>
            </b-row>
          </b-form>

          <b-row v-if="dataclient.pagadurias && !flag" class="mt-4">
            <b-col cols="12" class="mb-2">
              <h4 class="section-title">Consultar pagadurías</h4>
              <label class="panel-label mb-2">Pagadurías</label>
              <b-form-select
                v-model="dataclient.pagaduria"
                :options="pagaduriasOptions"
                value-field="value"
                text-field="text"
                @change="modalConfirmConsultPag"
              >
                <template #first>
                  <b-form-select-option :value="null" disabled hidden>Elija una pagaduría</b-form-select-option>
                </template>
              </b-form-select>
              <small class="hint">La selección no distingue mayúsculas</small>
            </b-col>
          </b-row>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import axios from 'axios'
import CustomButton from '../../customComponents/CustomButton.vue'
import Download from '../../icons/Download.vue'
import InputCurrency from '../../customComponents/InputCurrency.vue'

export default {
  name: 'FormConsult',
  props: ['user'],
  components: { CustomButton, Download, InputCurrency },
  data() {
    return {
      flag: false,
      dataclient: {
        doc: '',
        name: '',
        cuotadeseada: 0,
        monto: 0,
        plazo: null,
        pagaduria: null,
        pagadurias: null,
        pagaduriaKey: null,
        visado: null
      },
      activeId: null,
      isLoading: false
    }
  },
  mounted() {
    this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
  },
  computed: {
    userRole() {
      return this.user.role.name
    },
    ...mapState('datamesModule', ['datamesSed', 'cuotadeseada']),
    ...mapState('pagaduriasModule', ['pagaduriasTypes']),
    pagaduriasOptions() {
      const p = this.dataclient.pagadurias || {}
      return Object.keys(p)
        .sort((a, b) => a.localeCompare(b, 'es', { sensitivity: 'base' }))
        .map(k => ({ value: k, text: k }))
    }
  },
  methods: {
    ...mapMutations('datamesModule', ['setDatamesSed', 'setCuotaDeseada']),
    ...mapMutations('pagaduriasModule', [
      'setPagaduriaType',
      'setPagaduriaLabel',
      'setCouponsType',
      'setSelectedPeriod'
    ]),
    ...mapMutations('embargosModule', ['setEmbargosType']),
    ...mapMutations('descuentosModule', ['setDescuentosType']),
    normalizeKey(v) {
      return (v || '').toString().trim().toUpperCase().replace(/\s+/g, '')
    },
    pdfEmit() {
      this.$emit('downloadPdf')
    },
    selectedPagaduria() {
      if (!this.dataclient.pagaduria) return
      const key = this.dataclient.pagaduria
      const slug = this.normalizeKey(key)
      this.setPagaduriaType(slug)
      this.setPagaduriaLabel(key)
      this.setCouponsType(`Coupons${slug}`)
      this.setEmbargosType(`Embargos${slug}`)
      this.setDescuentosType(`Descuentos${slug}`)
      if (this.dataclient.pagadurias[key]) {
        this.setDatamesSed(this.dataclient.pagadurias[key])
      }
    },
    showToastError(message, ref) {
      this.$bvToast.toast(message, {
        title: '¡Error!',
        autoHideDelay: 5000,
        solid: true,
        variant: 'danger'
      })
      if (ref) {
        this.$nextTick(() => {
          this.$refs[ref].focus()
          this.activeId = ref
        })
      }
    },
    onlyDigits(ref) {
      this.dataclient[ref] = (this.dataclient[ref] || '').toString().replace(/\D+/g, '')
    },
    async validationForm() {
      if (!this.dataclient.doc) { this.showToastError('Debes llenar el campo de la cédula', 'doc'); return }
      if (!this.dataclient.name) { this.showToastError('Debes llenar el campo del nombre', 'name'); return }
      if (!this.dataclient.monto || Number(this.dataclient.monto) <= 0) { this.showToastError('Debes colocar el monto', 'monto'); return }
      if (!this.dataclient.cuotadeseada || Number(this.dataclient.cuotadeseada) <= 0) { this.showToastError('Debes colocar la cuota deseada', 'cuotadeseada'); return }
      if (!this.dataclient.plazo || Number(this.dataclient.plazo) <= 0) { this.showToastError('Debes colocar el plazo', 'plazo'); return }
      if (this.userRole !== 'ADMIN_SISTEMA' && (this.user.consultas_diarias || 0) <= 0) { this.showToastError('No tienes consultas disponibles'); return }
      this.getAllPagadurias()
    },
    async getAllPagadurias() {
      if (!this.dataclient.doc || !this.dataclient.name) return
      this.isLoading = true
      this.dataclient.pagadurias = null
      this.dataclient.pagaduria = null
      this.setDatamesSed(null)
      this.setPagaduriaType('')
      this.setSelectedPeriod('')
      try {
        const { data } = await axios.get(`/pagadurias/per-doc/${this.dataclient.doc}`)
        if (Object.keys(data || {}).length) {
          this.dataclient.pagadurias = data
          this.setCuotaDeseada(this.dataclient.cuotadeseada)
        } else {
          this.$bvToast.toast('No hay información de este documento', {
            title: 'Aviso',
            autoHideDelay: 5000,
            solid: true,
            variant: 'info'
          })
        }
      } finally {
        this.isLoading = false
      }
    },
    modalConfirmConsultPag() {
      this.$bvModal
        .msgBoxConfirm('Esta acción tiene un costo', {
          title: '¿Está seguro que desea realizar la consulta?',
          size: 'sm',
          buttonSize: 'sm',
          okVariant: 'success',
          okTitle: 'Consultar',
          cancelTitle: 'Cancelar',
          cancelVariant: 'danger',
          centered: true
        })
        .then(async ok => {
          if (!ok) return
          const status = await this.saveVisados()
          if (status !== 201) return
          this.$emit('emitInfo', this.dataclient)
          this.dataclient.pagadurias = null
          this.flag = true
        })
    },
    async saveVisados() {
      try {
        this.isLoading = true
        const { data: demografico } = await axios.get(`/demografico/${this.dataclient.doc}`)
        if (!demografico || !demografico.nombre_usuario) {
          this.$bvToast.toast('No se encontró el nombre del usuario', {
            title: 'Error',
            autoHideDelay: 5000,
            solid: true,
            variant: 'danger'
          })
          return
        }
        this.selectedPagaduria()
        const payload = {
          pagaduria: this.dataclient.pagaduria,
          nombre: demografico.nombre_usuario,
          doc: this.dataclient.doc,
          plazo: this.dataclient.plazo
        }
        const { status, data } = await axios.post('/visados', payload)
        this.dataclient.visado = data
        return status
      } catch (e) {
        this.$bvToast.toast('Error al guardar el visado', {
          title: 'Error',
          autoHideDelay: 5000,
          solid: true,
          variant: 'danger'
        })
        throw e
      } finally {
        this.isLoading = false
      }
    }
  }
}
</script>

<style scoped lang="scss">
.form-wrap {
  background: transparent;
}
.form-center {
  max-width: 820px;
  margin: 0 auto;
  padding: 8px 12px;
}
.heading-title {
  font-size: 28px;
  font-weight: 800;
  margin: 0;
  color: #121b26;
}
.panel {
  background: #ffffff;
  border: 1px solid #e7eaee;
  border-radius: 14px;
  box-shadow: 0 6px 20px rgba(16, 24, 40, 0.06);
}
.panel-body {
  padding: 20px;
}
.section-title {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 8px;
  color: #121b26;
}
.panel-label {
  font-size: 16px;
  font-weight: 700;
  color: #121b26;
}
.hint {
  display: block;
  margin-top: 6px;
  font-size: 13px;
  color: #6c757d;
}

/* Inputs */
.form-control {
  height: 54px;
  border-radius: 12px;
  border: 1.25px solid #d0d5dd;
  background: #ffffff;
  color: #111827;
  font-size: 16px;
  transition: all 0.15s ease;
}
.form-control::placeholder {
  color: #9aa4af;
  font-weight: 600;
}
.form-control:focus {
  background: #ffffff;
  border-color: #0CEDB0;
  box-shadow: 0 0 0 3px rgba(12, 237, 176, 0.18);
}
.errorValid {
  border-color: #dc3545 !important;
  box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.18);
}

/* Currency fields — single $ (no duplicados) y campo claro y ancho completo */
.currency-group {
  display: block;
  width: 100%;
  height: 54px;
  border-radius: 12px;
  border: 1.25px solid #d0d5dd;
  background: #ffffff;
  padding: 0 12px;
  transition: all 0.15s ease;
}
.currency-group:focus-within {
  border-color: #0CEDB0;
  box-shadow: 0 0 0 3px rgba(12, 237, 176, 0.18);
}
.currency-el {
  width: 100%;
  height: 100%;
}
.currency-group :deep(input),
.currency-group :deep(.form-control) {
  width: 100% !important;
  height: 52px !important;
  border: none !important;
  outline: none !important;
  background: transparent !important;
  padding: 0 2px !important;
  font-size: 16px !important;
  color: #111827 !important;
  box-shadow: none !important;
}
/* Si InputCurrency trae su propio prefijo visual, mantenemos SOLO ese.
   Ocultamos cualquier prefijo extra generado internamente por el wrapper del componente */
.currency-group :deep(.prefix), 
.currency-group :deep(.input-prefix),
.currency-group :deep(.currency-prefix) {
  /* no mostramos prefijos duplicados del wrapper */
}

/* "meses" chip */
.input-with-addon {
  position: relative;
}
.input-with-addon .form-control {
  padding-right: 74px;
}
.addon {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  background: #eef6f3;
  border: 1px solid #e7eaee;
  border-radius: 10px;
  padding: 6px 10px;
  font-size: 13px;
  color: #121b26;
}

/* Botones */
.white {
  background: #ffffff !important;
  color: #121b26 !important;
  border: 1px solid #e7eaee !important;
  height: 44px;
  border-radius: 12px;
}
.white:hover {
  border-color: #0CEDB0 !important;
}
.btn-cta {
  height: 54px;
  border-radius: 12px;
  font-size: 16px;
  width: 100%;
}

@media (min-width: 992px) {
  .panel-body {
    padding: 26px 30px;
  }
}
</style>
