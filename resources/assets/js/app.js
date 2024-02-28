/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Vue
import Vue from 'vue';

// Vuex
import store from './store';

// BootstrapVue
import 'jspdf-autotable';
import 'bootstrap';
import BootstrapVue from 'bootstrap-vue';
import toastr from 'toastr';
Vue.use(BootstrapVue);
Vue.use(toastr);

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import VueIframe from 'vue-iframes';

Vue.use(VueIframe);

import VueCurrencyFilter from 'vue-currency-filter';
Vue.use(VueCurrencyFilter, { symbol: '$' });

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.use(Loading);

import VueSwal from 'vue-swal';
Vue.use(VueSwal);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('loading', Loading);

// Vue Components
Vue.component('CustomHeader', require('./components/CustomHeader').default);
Vue.component('HomePage', require('./components/pages/HomePage').default);
Vue.component('Contact', require('./components/pages/Contact').default);
Vue.component('AmiPersonas', require('./components/pages/AmiPersonas').default);
Vue.component('MoreInformation', require('./components/pages/MoreInformation').default);
Vue.component('MoreInformationCartera', require('./components/pages/MoreInformationCartera').default);
Vue.component('Hego', require('./components/pages/Hego').default);
Vue.component('HegoInformation', require('./components/pages/HegoInformation').default);
Vue.component('Politicas', require('./components/pages/Politicas').default);
Vue.component('download-pdf-button', require('./components/DownloadPdfButton').default);

// Pages
Vue.component('ConsultasIndex', require('./components/pages/Consultas/Index').default);
Vue.component('whatsapp-bot', require('./components/pages/WhatsAppBot.vue').default);

// Vue Icons
Vue.component('AdminSettingsIcon', require('./components/icons/AdminSettingsIcon').default);
Vue.component('AmiIcon', require('./components/icons/AmiIcon').default);
Vue.component('HegoIcon', require('./components/icons/HegoIcon').default);
Vue.component('HomeIcon', require('./components/icons/HomeIcon').default);
Vue.component('DownloadIcon', require('./components/icons/DownloadIcon').default);

//Intragration Pages
Vue.component('integration', require('./components/pages/Integrations/index.vue').default);

//imports Data
Vue.component('imports-component', require('./components/pages/MassiveCharge/index.vue').default);
Vue.component('client-data-component', require('./components/pages/ConsultDataClient/index.vue').default);
Vue.component('client-data-component-draft', require('./components/pages/ConsultDataClientDraft/index.vue').default);
Vue.component('refund-component', require('./components/pages/ConsultDataClient/refundCartera.vue').default);
Vue.component('history-component', require('./components/pages/ConsultDataClient/history.vue').default);
Vue.component('detail-history-component', require('./components/pages/ConsultDataClient/detailhistory.vue').default);
Vue.component(
    'detail-history-component-draft',
    require('./components/pages/ConsultDataClient/detailhistoryBorrador.vue').default
);
Vue.component('credit-calculator', require('./components/pages/CreditColsult/CreditCalculator.vue').default);
Vue.component('register-credit', require('./components/pages/CreditColsult/CreditForm.vue').default);
Vue.component('FormConsult', require('./components/pages/ConsultDataClientDraft/FormConsult.vue').default);
//AMI Integración en solicitud credito

Vue.component(
    'form-consult-integration',
    require('./components/pages/ConsultDataClientDraft/FormConsultIntegration.vue').default
);
Vue.component(
    'consulta-pagadurias-form-consult',
    require('./components/pages/ConsultDataClientDraft/ConsultaPagaduriasFormConsult.vue').default
);
Vue.component(
    'client-data-component-draft-integration',
    require('./components/pages/ConsultDataClientDraft/indexIntegration.vue').default
);

/* Vue Init */
const app = new Vue({
    el: '#app',
    store
});
