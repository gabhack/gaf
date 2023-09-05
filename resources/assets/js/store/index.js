import Vue from 'vue';
import Vuex from 'vuex';

import datamesModule from './datamesModule';
import descuentosModule from './descuentosModule';
import embargosModule from './embargosModule';
import pagaduriasModule from './pagaduriasModule';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        datamesModule,
        descuentosModule,
        embargosModule,
        pagaduriasModule
    }
});
