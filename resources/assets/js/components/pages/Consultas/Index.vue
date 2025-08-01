<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-6">
        <b-form @submit.prevent="onSubmit" action="consultas/consultar" method="post" enctype="multipart/form-data">
          <slot name="csrf-token"></slot>
          <b-form-row class="mb-4">
            <div class="col-6">
              <b-form-input
                name="documento"
                v-model="data1.documento"
                type="text"
                placeholder="Documento"
                :state="validateState('data1', 'documento')"
              />
              <b-form-invalid-feedback> Este es un campo obligatorio. </b-form-invalid-feedback>
            </div>
            <div class="col-4">
              <b-form-select
                name="tipo_consulta"
                v-model="data1.tipo_consulta"
                :state="validateState('data1', 'tipo_consulta')"
              >
                <template #first>
                  <b-form-select-option :value="null" disabled hidden>Tipo de Consulta</b-form-select-option>
                </template>
                <slot name="tipo-consulta-opciones"></slot>
              </b-form-select>
              <b-form-invalid-feedback> Este es un campo obligatorio. </b-form-invalid-feedback>
            </div>
            <div class="col-2">
              <b-button
                type="button"
                variant="outline-black-pearl"
                class="font-weight-exbold"
                @click="consultarPagadurias"
              >
                Consultar
              </b-button>
            </div>
          </b-form-row>
          <b-form-row class="mb-4">
            <div class="col-6">
              <b-form-group label="Autorización Política de datos">
                <b-form-file
                  name="autorizacion_file"
                  accept="application/pdf"
                  v-model="data2.autorizacion_file"
                  placeholder="Seleccionar Archivo"
                  :class="data2.autorizacion_file ? 'filled' : null"
                  :state="validateState('data2', 'autorizacion_file')"
                />
              </b-form-group>
            </div>
            <div class="col-6">
              <b-form-group label="Último Desprendible">
                <b-form-file
                  name="desprendible_file"
                  accept="application/pdf"
                  v-model="data2.desprendible_file"
                  placeholder="Seleccionar Archivo"
                  :class="data2.desprendible_file ? 'filled' : null"
                  :state="validateState('data2', 'desprendible_file')"
                />
              </b-form-group>
            </div>
          </b-form-row>
          <b-form-row v-if="desprendibles">
            <div class="col-6">
              <b-form-select
                name="registro_pagaduria"
                v-model="data2.registro_pagaduria"
                :state="validateState('data2', 'registro_pagaduria')"
              >
                <template #first>
                  <b-form-select-option :value="null" disabled hidden>Pagaduría</b-form-select-option>
                </template>
                <template v-for="registro in desprendibles">
                  <b-form-select-option :value="registro.id" :key="registro.id">
                    {{ `${registro.periodo} - ${adicionales[registro.id]}` }}
                  </b-form-select-option>
                </template>
              </b-form-select>
            </div>
            <div class="col-3">
              <b-button type="submit" variant="black-pearl" class="font-weight-bold"> Generar Reporte </b-button>
            </div>
          </b-form-row>
        </b-form>
      </div>
    </div>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';
export default {
  data() {
    return {
      data1: {
        documento: '',
        tipo_consulta: null
      },
      data2: {
        autorizacion_file: null,
        desprendible_file: null,
        registro_pagaduria: null
      },
      adicionales: null,
      desprendibles: null
    };
  },
  validations: {
    data1: {
      documento: {
        required
      },
      tipo_consulta: {
        required
      }
    },
    data2: {
      autorizacion_file: {
        required
      },
      desprendible_file: {
        required
      },
      registro_pagaduria: {
        required
      }
    }
  },
  methods: {
    validateState(data, name) {
      const { $dirty, $error } = this.$v[data][name];
      return $dirty ? !$error : null;
    },
    onSubmit(event) {
      this.$v.data2.$touch();
      if (this.$v.data2.$anyError) {
        return;
      }

      event.target.submit();
    },
    consultarPagadurias() {
      this.$v.data1.$touch();
      if (this.$v.data1.$anyError) {
        return;
      }

      const params = {
        documento: this.data1.documento
      };

      axios
        .post('api/ami/getDesprendiblesXDocumento', params)
        .then(res => {
          if (res.data.error) {
            this.$bvToast.toast(res.data.error, {
              title: 'Alerta',
              variant: 'danger',
              solid: true
            });
          } else {
            this.desprendibles = res.data.desprendibles;
            this.adicionales = res.data.adicionales;
          }
        })
        .catch(error => {
          this.$bvToast.toast(error, {
            title: 'Alerta',
            variant: 'danger',
            solid: true
          });
        });
    }
  }
};
</script>
