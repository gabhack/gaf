import axios from 'axios'
import { setCurrentPeriod, floatToInt, normalizePeriod } from './utils'

const buildValue = n => n.replace(/\s+/g, '').toUpperCase()

function buildSlug(name) {
  const up = name.trim().toUpperCase()
  if (up === 'FIDUPREVISORA') return 'datamesFidu'
  if (up === 'FOPEP') return 'datamesFopep'
  const parts = up.split(/\s+/)
  if (parts[0] === 'SEM' || parts[0] === 'SED') {
    const pre = parts[0].toLowerCase()
    const rest = parts.slice(1).map(p => p[0] + p.slice(1).toLowerCase()).join('')
    return `datames${pre[0].toUpperCase() + pre.slice(1)}${rest}`
  }
  return 'datames' + parts.map(p => p[0] + p.slice(1).toLowerCase()).join('')
}

const presetPagadurias = [
  { label: 'FIDUPREVISORA', value: 'FIDUPREVISORA', key: 'FIDUPREVISORA', slug: 'datamesFidu' },
  { label: 'FOPEP', value: 'FOPEP', key: 'FOPEP', slug: 'datamesFopep' }
]

const pagaduriasModule = {
  namespaced: true,
  state: () => ({
    coupons: [],
    couponsType: '',
    pagaduriaType: '',
    pagaduriaLabel: '',
    pagaduriasTypes: [],
    selectedPeriod: ''
  }),
  getters: {
    couponsPerPeriod: state => {
      if (!state.selectedPeriod || !state.coupons.length) return { items: [] }
      const key = state.selectedPeriod.slice(0, 7)
      const items = state.coupons.filter(
        i => i.inicioperiodo.slice(0, 7) === key || i.finperiodo.slice(0, 7) === key
      )
      return { items }
    },
    couponsIngresos: (state, g) => {
      if (!g.couponsPerPeriod.items.length) return { items: [], total: 0, amount: 0 }
      const items = g.couponsPerPeriod.items.filter(i => Number(i.egresos) > 0)
      return { items, total: items.length, amount: items.reduce((s, i) => s + Number(i.egresos), 0) }
    },
    ingresosExtras: (state, g) => g.couponsPerPeriod.items.filter(
      i => i.code !== 'SUEBA' && i.code !== 'INGCUP' && Number(i.ingresos) > 0
    ),
    valorIngreso: (state, g) => g.couponsPerPeriod.items.find(i => i.code === 'INGCUP')?.ingresos || 0,
    salarioBasico: (state, g) => g.couponsPerPeriod.items
      .filter(i => i.code === 'SUEBA')
      .map(i => ({ concept: i.concept, ingresos: i.ingresos })),
    pagaduriaPeriodos: state => {
      const list = state.coupons.reduce((a, c) => {
        const fin = c.finperiodo?.trim()
        if (fin && !isNaN(new Date(fin)) && !a.includes(fin)) a.push(fin)
        return a
      }, [])
      const withCurrent = setCurrentPeriod(list)
      return withCurrent.sort((a, b) => new Date(b) - new Date(a))
    },
    ingresosIncapacidad: state => {
      const items = state.coupons.filter(
        i => (i.code === 'PGINC' || i.code === 'PGINC100') && Number(i.ingresos) > 0
      )
      return { items, total: items.length, amount: items.reduce((s, i) => s + Number(i.ingresos), 0) }
    },
    ingresosIncapacidadPerPeriod: (state, g) => {
      if (!g.couponsPerPeriod.items.length) return { items: [], total: 0, amount: 0 }
      const items = g.couponsPerPeriod.items.filter(
        i => (i.code === 'PGINC' || i.code === 'PGINC100') && Number(i.ingresos) > 0
      )
      return { items, total: items.length, amount: items.reduce((s, i) => s + Number(i.ingresos), 0) }
    },
    incapacidadValida: (state, g) => {
      const months = 2
      const yNow = new Date().getFullYear()
      const mNow = new Date().getMonth() + 1
      const inc = g.ingresosIncapacidad.items
      const recent = inc.filter(i => {
        const y = +i.finperiodo.slice(0, 4)
        const m = +i.finperiodo.slice(5, 7)
        return y === yNow && m >= mNow - months + 1
      })
      const monthsFound = new Set(recent.map(i => i.finperiodo.slice(5, 7)))
      return monthsFound.size < months
    }
  },
  mutations: {
    setPagaduriasTypes: (s, p) => (s.pagaduriasTypes = p),
    setPagaduriaType: (s, p) => (s.pagaduriaType = p),
    setPagaduriaLabel: (s, p) => (s.pagaduriaLabel = p),
    setCoupons: (s, p) => (s.coupons = p),
    setCouponsType: (s, p) => (s.couponsType = p),
    setSelectedPeriod: (s, raw) => (s.selectedPeriod = normalizePeriod(raw))  },
  actions: {
    async loadPagaduriasTypes({ commit }) {
      try {
        const { data } = await axios.get('/pagadurias/namesAmi')
        const api = data.map(n => ({
          label: n,
          value: buildValue(n),
          key: n.toUpperCase(),
          slug: buildSlug(n)
        }))
        const merged = [...api, ...presetPagadurias.filter(
          p => !api.some(a => a.label.toUpperCase() === p.label.toUpperCase())
        )]
        commit('setPagaduriasTypes', merged)
      } catch (e) {
        commit('setPagaduriasTypes', presetPagadurias)
      }
    },
    fetchCoupons({ commit, getters }, rows) {
      const items = rows.map(r => ({
        ...r,
        nomtercero: r.concept,
        ingresos: floatToInt(r.ingresos),
        egresos: floatToInt(r.egresos),
        vaplicado: floatToInt(r.egresos)
      }))
      commit('setCoupons', items)
      const validPeriod = getters.pagaduriaPeriodos.find(p => {
        const key = p.slice(0, 7)
        return items.some(i => i.inicioperiodo.slice(0, 7) === key || i.finperiodo.slice(0, 7) === key)
      })
      if (validPeriod) commit('setSelectedPeriod', validPeriod)
    }
  }
}

export default pagaduriasModule
