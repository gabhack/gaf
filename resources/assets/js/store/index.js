import Vue from 'vue';
import Vuex from 'vuex';
import datamesModule from './datamesModule';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    datamesModule
  }
});
