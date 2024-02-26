<template>
    <div class="container-fluid">
      <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="true" color="#0CEDB0" />
      <FormConsult @emitInfo="emitInfoHandler" />
  
      <div v-if="showResults">
        <h2>Obligaciones</h2>
        <div v-if="obligacionesAlDia.length">
          <h3>Obligaciones al Día</h3>
          <ul>
            <li v-for="(obligacion, index) in obligacionesAlDia" :key="index">
              {{ obligacion.descripcion }} - ${{ obligacion.valor }}
            </li>
          </ul>
        </div>
  
        <div v-if="obligacionesEnMora.length">
          <h3>Obligaciones en Mora</h3>
          <ul>
            <li v-for="(obligacion, index) in obligacionesEnMora" :key="index">
              {{ obligacion.descripcion }} - ${{ obligacion.valor }}
            </li>
          </ul>
        </div>
  
      </div>
    </div>
  </template>
  
  <script>
  import FormConsult from './FormConsult.vue';
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  
  export default {
    components: {
      FormConsult,
      Loading,
    },
    data() {
      return {
        isLoading: false,
        showResults: false,
        obligacionesAlDia: [],
        obligacionesEnMora: [],
        // Agrega más arrays o propiedades si necesitas manejar otros tipos de obligaciones
      };
    },
    methods: {
      emitInfoHandler(payload) {
        // Aquí procesas el payload para extraer las obligaciones al día, en mora, etc.
        // Por ejemplo:
        this.obligacionesAlDia = payload.obligacionesAlDia;
        this.obligacionesEnMora = payload.obligacionesEnMora;
  
        // Mostrar los resultados
        this.showResults = true;
      },
    },
  };
  </script>
  
  <style>
  /* Estilos adicionales si son necesarios */
  </style>
  