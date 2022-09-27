const datamesModule = {
  namespaced: true,
  state: {
    datamesSed: null
  },
  mutations: {
    setDatamesSed(state, datames) {
      state.datamesSed = datames;
    }
  }
};

export default datamesModule;
