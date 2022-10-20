const datamesModule = {
  namespaced: true,
  state: {
    datamesSed: null,
    cuotadeseada: null,
    conteoEgresos: null
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
    }
  }
};

export default datamesModule;
