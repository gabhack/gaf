set -e
FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"

perl -0777 -i -pe 's/(couponsIngresos:\s*\{\s*handler\s*\(\)\s*\{\s*\n)/$1          try { console.log("[EmploymentHistory] watcher couponsIngresos triggered") } catch(e) {}\n          try { console.log("[EmploymentHistory] selectedPeriod(start)", this.selectedPeriod) } catch(e) {}\n          try { console.log("[EmploymentHistory] pagaduriaPeriodos length", Array.isArray(this.pagaduriaPeriodos) ? this.pagaduriaPeriodos.length : this.pagaduriaPeriodos) } catch(e) {}\n/s' "$FILE"

perl -0777 -i -pe 's/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g' "$FILE"

perl -0777 -i -pe 's/(this\.arrayCoupons\s*=\s*\[\.\.\.this\.couponsIngresos\.items\]\s*;)/$1\n            try { console.log("[EmploymentHistory] items length", this.arrayCoupons.length) } catch(e) {}\n            try { console.log("[EmploymentHistory] item[0] keys", Object.keys(this.arrayCoupons[0] || {})) } catch(e) {}\n/s' "$FILE"

perl -0777 -i -pe 's/(this\.arrayCoupons\.forEach\([^)]*\)\s*\)\s*;)/$1\n            try { const nums = (this.arrayCoupons||[]).map(c => { const n = parseInt(c.dias_laborados,10); return isNaN(n)?0:n }); console.log("[EmploymentHistory] dias parsed", nums); const sum = nums.reduce((a,b)=>a+b,0); this.debug_total_dias = sum; console.log("[EmploymentHistory] totalDiasLaborados", sum) } catch(e) {}\n            try { console.table((this.arrayCoupons||[]).slice(0,10).map(c => ({ code: c.code, raw: c.dias_laborados }))) } catch(e) {}\n/s' "$FILE"

perl -0777 -i -pe 's/(this\.setSelectedPeriod\(last\)\s*;)/$1\n            try { console.log("[EmploymentHistory] autoselected period", last) } catch(e) {}\n/s' "$FILE"

perl -0777 -i -pe 's/(couponsIngresos:\s*\{\s*handler\s*\(\)\s*\{\s*.*?\}\s*,\s*immediate\s*:\s*true\s*,\s*deep\s*:\s*true\s*\})/$1,\n      selectedPeriod(val, old){ try { console.log("[EmploymentHistory] watcher selectedPeriod", {old, val}) } catch(e) {} },\n      pagaduriaPeriodos(val, old){ try { console.log("[EmploymentHistory] watcher pagaduriaPeriodos", {oldLen: Array.isArray(old)?old.length:old, newLen: Array.isArray(val)?val.length:val}) } catch(e) {} }\n/s' "$FILE"

perl -0777 -i -pe 's/(export default\s*\{\s*)/$1\n  created(){ try { console.log("[EmploymentHistory] created selectedPeriod", this.selectedPeriod) } catch(e) {} },\n  mounted(){ try { console.log("[EmploymentHistory] mounted selectedPeriod", this.selectedPeriod); console.log("[EmploymentHistory] mounted pagaduriaPeriodos length", Array.isArray(this.pagaduriaPeriodos)? this.pagaduriaPeriodos.length : this.pagaduriaPeriodos) } catch(e) {} },\n/s' "$FILE"

git add "$FILE"
git commit -m "chore(EmploymentHistory): logs detallados para diagnosticar Días Laborados"
