import { setCurrentPeriod } from './utils'

const embargosModule = {
  namespaced: true,
  state: {
    embargos: [],
    embargosType: '',
    selectedPeriod: ''
  },
  getters: {
    embargosPeriodos: state => {
      let periodos = state.embargos.reduce((acc, e) => {
        if (!acc.includes(e.nomina)) acc.push(e.nomina)
        return acc
      }, [])
      periodos = setCurrentPeriod(periodos)
      return periodos.sort((a, b) => new Date(b) - new Date(a))
    },
    embargosPerPeriod: state => {
      const periodKey = state.selectedPeriod.slice(0, 7)
      const items = state.embargos.filter(i => i.nomina.slice(0, 7) === periodKey)
      return {
        items: items.map(i => ({ ...i, check: false })),
        total: items.length
      }
    }
  },
  mutations: {
    setEmbargos: (state, payload) => {
      state.embargos = payload
    },
    setEmbargosType: (state, payload) => {
      state.embargosType = payload
    },
    setSelectedPeriod: (state, payload) => {
      state.selectedPeriod = payload
    }
  },
  actions: {
    fetchEmbargos: ({ commit, getters }, data) => {
      commit('setEmbargos', data)
      if (getters.embargosPeriodos.length > 0) {
        commit('setSelectedPeriod', getters.embargosPeriodos[0])
      }
    }
  }
}

export default embargosModule
