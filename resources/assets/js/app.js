/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Vue
import Vue from 'vue';

// BootstrapVue
import 'bootstrap';
import BootstrapVue from 'bootstrap-vue';
import toastr from 'toastr';
Vue.use(BootstrapVue);
Vue.use(toastr);

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

// Vue Components
Vue.component('CustomHeader', require('./components/CustomHeader').default);
Vue.component('HomePage', require('./components/pages/HomePage').default);
Vue.component('AmiPersonas', require('./components/pages/AmiPersonas').default);
Vue.component('MoreInformation', require('./components/pages/MoreInformation').default);
Vue.component('Hego', require('./components/pages/Hego').default);
Vue.component('HegoInformation', require('./components/pages/HegoInformation').default);

// Pages
Vue.component('ConsultasIndex', require('./components/pages/Consultas/Index').default);

// Vue Icons
Vue.component('AdminSettingsIcon', require('./components/icons/AdminSettingsIcon').default);
Vue.component('AmiIcon', require('./components/icons/AmiIcon').default);
Vue.component('HegoIcon', require('./components/icons/HegoIcon').default);
Vue.component('HomeIcon', require('./components/icons/HomeIcon').default);
Vue.component('DownloadIcon', require('./components/icons/DownloadIcon').default);

//Intragration Pages
// Vue.component('integration', require('./components/pages/Integrations/index.vue').default);

//imports Data
Vue.component('imports-component', require('./components/pages/MassiveCharge/index.vue').default);
Vue.component('client-data-component', require('./components/pages/ConsultDataClient/index.vue').default);
Vue.component('history-component', require('./components/pages/ConsultDataClient/history.vue').default);
/* Vue Init */
const app = new Vue({
  el: '#app'
});
