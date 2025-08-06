<template>
    <div class="panel mb-3 col-md-12">
      <b-row>
        <b-col cols="6">
          <h3 class="heading-title">Realizar consulta</h3>
        </b-col>
  
        <b-col cols="6" class="d-flex justify-content-end align-items-center">
          <CustomButton class="white" @click="pdfEmit">
            Descargar PDF
            <Download class="ml-2" />
          </CustomButton>
        </b-col>
      </b-row>
  
      <div class="panel-body px-0">
        <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true" />
  
        <b-row>
          <b-col cols="12" md="4" class="mb-md-4 mb-2">
            <b class="panel-label mb-2"><span class="text-danger">*</span> Cédula</b>
            <input
              required
              class="form-control2"
              :class="{ errorValid: activeId === 'doc' }"
              placeholder="N° de documento"
              type="number"
              v-model="dataclient.doc"
              @change="activeId = ''"
              ref="doc"
            />
          </b-col>
  
          <b-col cols="12" md="4" class="mb-2">
            <b class="panel-label mb-2"><span class="text-danger">*</span> Nombres y apellidos</b>
            <input
              required
              class="form-control2"
              placeholder="Nombre completo"
              type="text"
              v-model="dataclient.name"
              :class="{ errorValid: activeId === 'name' }"
              @change="activeId = ''"
              ref="name"
            />
          </b-col>
  
          <b-col cols="12" md="4" class="mb-2">
            <b class="panel-label mb-2"><span class="text-danger">*</span> Monto</b>
            <InputCurrency
              v-model="dataclient.monto"
              :validateClass="activeId === 'monto'"
              @change="activeId = ''"
              ref="monto"
              placeholder="Ingrese un monto"
              rules="required"
            />
          </b-col>
  
          <b-col cols="12" md="4" class="mb-2 mb-md-4">
            <b class="panel-label mb-2"><span class="text-danger">*</span> Cuota deseada</b>
            <InputCurrency
              v-model="dataclient.cuotadeseada"
              :validateClass="activeId === 'cuotadeseada'"
              @change="activeId = ''"
              ref="cuotadeseada"
              placeholder="Cantidad de cuotas"
              rules="required"
            />
          </b-col>
  
          <b-col cols="12" md="4" class="mb-3 mb-md-0">
            <b class="panel-label mb-2"><span class="text-danger">*</span> Plazo</b>
            <input
              required
              class="form-control2"
              type="number"
              v-model.number="dataclient.plazo"
              placeholder="Ingrese el plazo"
              :class="{ errorValid: activeId === 'plazo' }"
              @change="activeId = ''"
              ref="plazo"
            />
          </b-col>
        </b-row>
  
        <b-row>
          <b-col cols="12" md="4" v-if="!dataclient.pagadurias && !flag">
            <CustomButton text="Consultar Pagadurías" @click="validationForm" />
            <small v-if="userRole !== 'ADMIN_SISTEMA'" class="d-block mt-2">
              Consultas disponibles: {{ user.consultas_diarias || 0 }}
            </small>
          </b-col>
  
          <b-col cols="12" md="4" v-else>
            <div v-if="!flag">
              <h3 class="heading-title pb-3">Consultar pagadurías</h3>
              <b class="panel-label mb-2">Pagadurías</b>
  
              <b-form-select
                v-if="dataclient.pagadurias"
                v-model="dataclient.pagaduria"
                required
                @change="modalConfirmConsultPag"
              >
                <option :value="null" disabled hidden>Elija una pagaduría</option>
                <option
                  v-for="(obj, key) in dataclient.pagadurias"
                  :key="key"
                  :value="key"
                >
                  {{ key }}
                </option>
              </b-form-select>
  
              <b-form-select v-else v-model="dataclient.pagaduria" disabled>
                <option :value="null">Ingrese una cédula y presione consultar</option>
              </b-form-select>
            </div>
          </b-col>
        </b-row>
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
    data () {
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
    mounted () {
      this.$store.dispatch('pagaduriasModule/loadPagaduriasTypes')
    },
    computed: {
      userRole () {
        return this.user.role.name
      },
      ...mapState('datamesModule', ['datamesSed', 'cuotadeseada']),
      ...mapState('pagaduriasModule', ['pagaduriasTypes'])
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
  
      pdfEmit () {
        this.$emit('downloadPdf')
      },
  
      selectedPagaduria () {
        if (!this.dataclient.pagaduria) return
  
        const key = this.dataclient.pagaduria
        const slug = key.replace(/\s+/g, '')
  
        this.setPagaduriaType(slug)
        this.setPagaduriaLabel(key)
        this.setCouponsType(`Coupons${slug}`)
        this.setEmbargosType(`Embargos${slug}`)
        this.setDescuentosType(`Descuentos${slug}`)
  
        if (this.dataclient.pagadurias[key]) {
          this.setDatamesSed(this.dataclient.pagadurias[key])
        }
      },
  
      showToastError (message, ref) {
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
  
      async validationForm () {
        if (!this.dataclient.doc) { this.showToastError('Debes llenar el campo de la cédula', 'doc'); return }
        if (!this.dataclient.name) { this.showToastError('Debes llenar el campo del nombre', 'name'); return }
        if (!this.dataclient.monto) { this.showToastError('Debes colocar el monto', 'monto'); return }
        if (!this.dataclient.cuotadeseada) { this.showToastError('Debes colocar la cuota deseada', 'cuotadeseada'); return }
        if (!this.dataclient.plazo) { this.showToastError('Debes colocar el plazo', 'plazo'); return }
        if (this.userRole !== 'ADMIN_SISTEMA' && this.user.consultas_diarias <= 0) {
          this.showToastError('No tienes consultas disponibles'); return
        }
        this.getAllPagadurias()
      },
  
      async getAllPagadurias () {
        if (!this.dataclient.doc || !this.dataclient.name) return
        this.isLoading = true
        this.dataclient.pagadurias = null
        this.setDatamesSed(null)
        this.setPagaduriaType('')
        this.setSelectedPeriod('')
        try {
          const { data } = await axios.get(`/pagadurias/per-doc/${this.dataclient.doc}`)
          if (Object.keys(data).length) {
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
  
      modalConfirmConsultPag () {
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
  
      async saveVisados () {
        try {
          this.isLoading = true
          const { data: demografico } = await axios.get(`/demografico/${this.dataclient.doc}`)
          if (!demografico.nombre_usuario) {
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
  .panel-label {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
  }
  small {
    font-size: 12px;
    color: #6c757d;
  }
  </style>
  