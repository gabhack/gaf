import { setCurrentPeriod } from './utils';

const embargosModule = {
  namespaced: true,
  state: {
    embargos: [],
    embargosType: '',
    selectedPeriod: ''
  },
  getters: {
    embargosPeriodos: state => {
      console.log('[embargosModule] state.embargos:', state.embargos);

      // Extrae todos los valores de nomina
      let periodos = state.embargos.reduce((acc, embargo) => {
        console.log('[embargosModule] embargo.nomina raw:', embargo.nomina);
        if (!acc.includes(embargo.nomina)) {
          acc.push(embargo.nomina);
        }
        return acc;
      }, []);

      // Asegura que el período actual esté incluido
      periodos = setCurrentPeriod(periodos);

      // Ordena de más reciente a más antiguo
      const sorted = periodos.sort((a, b) => new Date(b) - new Date(a));
      console.log('[embargosModule] embargosPeriodos computed:', sorted);
      return sorted;
    },
    embargosPerPeriod: state => {
      console.log('[embargosModule] selectedPeriod:', state.selectedPeriod);
      const items = state.embargos.filter(item => item.nomina === state.selectedPeriod);
      console.log('[embargosModule] embargosPerPeriod items:', items);
      return {
        items: items.map(item => ({ ...item, check: false })),
        total: items.length
      };
    }
  },
  mutations: {
    setEmbargos: (state, payload) => {
      console.log('[embargosModule] mutation setEmbargos payload:', payload);
      state.embargos = payload;
    },
    setEmbargosType: (state, payload) => {
      console.log('[embargosModule] mutation setEmbargosType:', payload);
      state.embargosType = payload;
    },
    setSelectedPeriod: (state, payload) => {
      console.log('[embargosModule] mutation setSelectedPeriod:', payload);
      state.selectedPeriod = payload;
    }
  },
  actions: {
    fetchEmbargos: ({ commit, getters }, data) => {
      console.log('[embargosModule] action fetchEmbargos data:', data);
      commit('setEmbargos', data);

      const periods = getters.embargosPeriodos;
      if (periods.length > 0) {
        console.log('[embargosModule] setting default selectedPeriod:', periods[0]);
        commit('setSelectedPeriod', periods[0]);
      }
    }
  }
};

export default embargosModule;
