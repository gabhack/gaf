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

import DemographicData from './components/pages/Demographic/DemographicData.vue';
import DemographicData2 from './components/pages/Demographic/DemographicDataFiltroEntidad.vue';
import DemographicIndex from './components/pages/Demographic/IndexDemografico.vue';
import UploadPensiones from './components/pages/Pensiones/uploadColpensiones.vue';
import UploadFiducidiaria from './components/pages/Pensiones/upload-fiducidiaria.vue';
import CreditRequestBulk from './components/pages/CreditRequest/CreditRequestBulk.vue';

import JoinPensiones from './components/pages/Pensiones/joinpensiones.vue';
import Index from './components/pages/Integrations/index.vue';

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
Vue.component('Dashboard', require('./components/pages/Dashboard/Dashboard').default);

// Pages
Vue.component('ConsultasIndex', require('./components/pages/Consultas/Index').default);
Vue.component('whatsapp-bot', require('./components/pages/WhatsAppBot.vue').default);

// Vue Icons
Vue.component('AdminSettingsIcon', require('./components/icons/AdminSettingsIcon').default);
Vue.component('AmiIcon', require('./components/icons/AmiIcon').default);
Vue.component('HegoIcon', require('./components/icons/HegoIcon').default);
Vue.component('HomeIcon', require('./components/icons/HomeIcon').default);
Vue.component('DashIcon', require('./components/icons/DashIcon').default);
Vue.component('VisadoIcon', require('./components/icons/VisadoIcon').default);
Vue.component('MercadoIcon', require('./components/icons/MercadoIcon').default);
Vue.component('CarteraIcon', require('./components/icons/CarteraIcon').default);
Vue.component('InvestigacionIcon', require('./components/icons/InvestigacionIcon').default);
Vue.component('LocalizacionIcon', require('./components/icons/LocalizacionIcon').default);
Vue.component('DownloadIcon', require('./components/icons/DownloadIcon').default);

//Intragration Pages
Vue.component('integration', require('./components/pages/Integrations/index.vue').default);

//imports Data
Vue.component('imports-component', require('./components/pages/MassiveCharge/index.vue').default);
Vue.component('client-data-component', require('./components/pages/ConsultDataClient/index.vue').default);
Vue.component('client-data-component-draft', require('./components/pages/ConsultDataClientDraft/index.vue').default);
Vue.component('refund-component', require('./components/pages/ConsultDataClient/refundCartera.vue').default);
Vue.component('certificados', require('./components/pages/ConsultDataClient/certificados.vue').default);
Vue.component('history-component', require('./components/pages/ConsultDataClient/history.vue').default);
Vue.component('detail-history-component', require('./components/pages/ConsultDataClient/detailhistory.vue').default);
Vue.component(
	'detail-history-component-draft',
	require('./components/pages/ConsultDataClient/detailhistoryBorrador.vue').default
);
Vue.component('credit-calculator', require('./components/pages/CreditColsult/CreditCalculator.vue').default);
Vue.component('register-credit', require('./components/pages/CreditColsult/CreditForm.vue').default);
Vue.component('FormConsult', require('./components/pages/ConsultDataClientDraft/FormConsult.vue').default);
Vue.component('CreditRequestsList', require('./components/pages/CreditRequest/CreditRequestsList.vue').default);
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

//CUPONES

Vue.component('CouponsFormConsult', require('./components/pages/Coupons/CouponsFormConsult.vue').default);
//parametros
Vue.component('parametros', require('./components/pages/Parametros.vue').default);

//Datos demograficos de datames
Vue.component('DemographicData', DemographicData);
Vue.component('DemographicData2', DemographicData2);
Vue.component('DemographicIndex', DemographicIndex);
Vue.component('UploadPensiones', UploadPensiones);
Vue.component('UploadFiducidiaria', UploadFiducidiaria);
Vue.component('join-pensiones', JoinPensiones);

//Modulo usuarios
Vue.component('empresas', require('./components/pages/Empresas/Index.vue').default);
Vue.component('crear-empresas', require('./components/pages/Empresas/Create.vue').default);
Vue.component('editar-empresas', require('./components/pages/Empresas/Edit.vue').default);
Vue.component('ver-empresas', require('./components/pages/Empresas/Ver.vue').default);

Vue.component('area-comerciales', require('./components/pages/AreaComerciales/Index.vue').default);
Vue.component('crear-area-comerciales', require('./components/pages/AreaComerciales/Create.vue').default);
Vue.component('editar-area-comerciales', require('./components/pages/AreaComerciales/Edit.vue').default);
Vue.component('ver-area-comerciales', require('./components/pages/AreaComerciales/Ver.vue').default);

Vue.component('sedes', require('./components/pages/Sedes/Index.vue').default);
Vue.component('crear-sedes', require('./components/pages/Sedes/Create.vue').default);
Vue.component('editar-sedes', require('./components/pages/Sedes/Edit.vue').default);

//fintra
Vue.component('credit-form', require('./components/pages/CreditRequest/CreditForm.vue').default);
Vue.component('credit-request-bulk', CreditRequestBulk);
Vue.component('credit-requests-list',
    require('./components/pages/ConsultDataClientDraft/indexCreditRequest.vue').default
);

/* Vue Init */
const app = new Vue({
	el: '#app',
	store
});
