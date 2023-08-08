import Vue from 'vue';
import Vuex from 'vuex';
import datamesModule from './datamesModule';
import pagaduriasModule from './pagaduriasModule';
import embargosModule from './embargosModule';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        datamesModule,
        pagaduriasModule,
        embargosModule
    }
});
