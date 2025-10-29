set -e
git switch -c debug/dias-laborados-trace-2 2>/dev/null || git checkout -b debug/dias-laborados-trace-2

mkdir -p resources/assets/js/debug resources/js/debug

cat > resources/assets/js/debug/axiosDiasTrace.js <<'JS'
let installed = false
export default function installAxiosDiasTrace(axios) {
  if (!axios || installed) return
  installed = true
  const matchUrl = (url = '') => {
    const u = String(url).toLowerCase()
    return u.includes('coupon') || u.includes('cupon') || u.includes('ingreso') || u.includes('historial') || u.includes('laboral') || u.includes('pagaduria')
  }
  axios.interceptors.request.use(cfg => {
    if (matchUrl(cfg.url)) {
      const method = (cfg.method || 'get').toUpperCase()
      const params = cfg.params || {}
      let data = cfg.data
      try { if (typeof data === 'string') data = JSON.parse(data) } catch(e) {}
      console.groupCollapsed(`[DIAS][REQ] ${method} ${cfg.url}`)
      console.log('params', params)
      console.log('data', data)
      console.groupEnd()
    }
    return cfg
  })
  axios.interceptors.response.use(res => {
    try {
      const cfg = res.config || {}
      if (matchUrl(cfg.url)) {
        const body = res.data
        let items = null
        if (Array.isArray(body)) items = body
        else if (body && Array.isArray(body.items)) items = body.items
        else if (body && body.data && Array.isArray(body.data.items)) items = body.data.items
        const sample = items ? items.slice(0, 10).map(i => ({
          code: i?.code ?? null,
          periodo: i?.periodo ?? null,
          dias_laborados: i?.dias_laborados ?? null
        })) : null
        console.groupCollapsed(`[DIAS][RES] ${cfg.method?.toUpperCase() || ''} ${cfg.url}`)
        console.log('status', res.status)
        console.log('items_count', items ? items.length : null)
        if (sample) console.table(sample)
        console.groupEnd()
      }
    } catch(e) {
      console.error('[DIAS][RES][error]', e)
    }
    return res
  }, err => {
    try {
      const cfg = err?.config || {}
      if (cfg && matchUrl(cfg.url)) {
        console.groupCollapsed(`[DIAS][ERR] ${cfg.method?.toUpperCase() || ''} ${cfg.url}`)
        console.log('status', err?.response?.status)
        console.log('data', err?.response?.data)
        console.groupEnd()
      }
    } catch(e) {}
    throw err
  })
}
JS

cat > resources/assets/js/debug/periodTrace.js <<'JS'
export function tracePeriod(stage, selectedPeriod, list = []) {
  const raw = selectedPeriod
  const normYM = normalizeYM(raw)
  const normYMD = normalizeYMD(raw)
  const listShow = Array.isArray(list) ? list.slice(0, 10) : []
  console.groupCollapsed(`[DIAS][PERIOD][${stage}]`)
  console.log('raw', raw)
  console.log('normYM', normYM)
  console.log('normYMD', normYMD)
  console.log('list_head', listShow)
  console.groupEnd()
}
export function normalizeYM(v) {
  if (!v) return null
  const s = String(v).trim()
  let m = s.match(/^(\d{4})[-/](\d{2})(?:[-/]\d{2})?$/)
  if (!m) return null
  return `${m[1]}-${m[2]}`
}
export function normalizeYMD(v) {
  if (!v) return null
  const s = String(v).trim()
  let m = s.match(/^(\d{4})[-/](\d{2})(?:[-/](\d{2}))?$/)
  if (!m) return null
  const y = parseInt(m[1],10)
  const mo = parseInt(m[2],10)
  let d = m[3] ? parseInt(m[3],10) : null
  if (!d || isNaN(d)) {
    const last = new Date(y, mo, 0).getDate()
    d = last
  }
  const dd = String(d).padStart(2,'0')
  return `${m[1]}-${m[2]}-${dd}`
}
JS

cat > resources/assets/js/debug/registerDebugMixins.js <<'JS'
import Vue from 'vue'
import { tracePeriod } from './periodTrace'

if (!window.__DIAS_DEBUG_ACTIVE_V2) {
  window.__DIAS_DEBUG_ACTIVE_V2 = true
  const tryWatch = (vm, key, deep = false, label = key) => {
    if (key in vm || (vm.$data && Object.prototype.hasOwnProperty.call(vm.$data, key))) {
      vm.$watch(key, (n, o) => {
        if (label === 'selectedPeriod') {
          const list = Array.isArray(vm.pagaduriaPeriodos) ? vm.pagaduriaPeriodos : []
          tracePeriod(`${vm.$options.name || 'anon'}:${label} changed`, n, list)
        } else {
          console.log(`[DIAS][STATE] ${vm.$options.name || 'anon'}:${label}`, n)
        }
      }, { deep })
    }
  }
  Vue.mixin({
    mounted() {
      const list = Array.isArray(this.pagaduriaPeriodos) ? this.pagaduriaPeriodos : []
      tracePeriod(`${this.$options.name || 'anon'}:mounted`, this.selectedPeriod, list)
      tryWatch(this, 'selectedPeriod', false, 'selectedPeriod')
      tryWatch(this, 'pagaduria', false, 'pagaduria')
      tryWatch(this, 'pagaduriaPeriodos', true, 'pagaduriaPeriodos')
      tryWatch(this, 'couponsIngresos', true, 'couponsIngresos')
      tryWatch(this, 'arrayCoupons', true, 'arrayCoupons')
    }
  })
}
export {}
JS

cat > resources/assets/js/debug/bootDiasDebug.js <<'JS'
import installAxiosDiasTrace from './axiosDiasTrace'
import './registerDebugMixins'
try {
  const ax = window.axios || window.Axios || null
  if (ax) installAxiosDiasTrace(ax)
} catch(e) {}
JS

if [ -f "resources/assets/js/app.js" ]; then
  tmpfile=$(mktemp)
  printf '%s\n' 'import "./debug/bootDiasDebug"' | cat - resources/assets/js/app.js > "$tmpfile"
  mv "$tmpfile" resources/assets/js/app.js
fi
if [ -f "resources/js/app.js" ]; then
  tmpfile=$(mktemp)
  printf '%s\n' 'import "./debug/bootDiasDebug"' | cat - resources/js/app.js > "$tmpfile"
  mv "$tmpfile" resources/js/app.js
fi

git add resources/assets/js/debug/axiosDiasTrace.js resources/assets/js/debug/periodTrace.js resources/assets/js/debug/registerDebugMixins.js resources/assets/js/debug/bootDiasDebug.js resources/assets/js/app.js resources/js/app.js 2>/dev/null || true
git commit -m "chore(debug): interceptores axios y trazas de periodo para dias_laborados"
