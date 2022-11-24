const datamesModule = {
  namespaced: true,
  state: {
    datamesSed: null,
    cuotadeseada: null,
    conteoEgresos: null,
    conteoEgresosPlus: null
  },
  mutations: {
    setDatamesSed(state, datames) {
      state.datamesSed = datames;
    },
    setCuotaDeseada(state, payload) {
      state.cuotadeseada = payload;
    },
    setConteoEgresos(state, payload) {
      state.conteoEgresos = payload;
    },
    setConteoEgresosPlus(state, payload) {
      state.conteoEgresosPlus = payload;
    }
  }
};

export default datamesModule;
