import { setCurrentPeriod } from './utils'

const descuentosModule = {
  namespaced: true,
  state: {
    descuentos: [],
    descuentosType: '',
    selectedPeriod: ''
  },
  getters: {
    descuentosPeriodos: state => {
      let periodos = state.descuentos.reduce((acc, d) => {
        if (!acc.includes(d.nomina)) acc.push(d.nomina)
        return acc
      }, [])
      periodos = setCurrentPeriod(periodos)
      return periodos.sort((a, b) => new Date(b) - new Date(a))
    },
    descuentosPerPeriod: state => {
      const periodKey = state.selectedPeriod.slice(0, 7)
      const items = state.descuentos.filter(
        i => i.nomina.slice(0, 7) === periodKey && i.mliquid !== 'ALERTA'
      )
      return {
        items: items.map(i => ({ ...i, check: false })),
        total: items.length
      }
    }
  },
  mutations: {
    setDescuentos: (state, payload) => {
      state.descuentos = payload
    },
    setDescuentosType: (state, payload) => {
      state.descuentosType = payload
    },
    setSelectedPeriod: (state, payload) => {
      state.selectedPeriod = payload
    }
  },
  actions: {
    fetchDescuentos: (ctx, data) => {
      ctx.commit('setDescuentos', data)
      if (ctx.getters.descuentosPeriodos.length > 0) {
        ctx.commit('setSelectedPeriod', ctx.getters.descuentosPeriodos[0])
      }
    }
  }
}

export default descuentosModule
