set -e

FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"

if grep -n "watch:\s*{\s*try" -P "$FILE" >/dev/null 2>&1; then
  git checkout HEAD~1 -- "$FILE"
fi

perl -0777 -i -pe 's/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g' "$FILE"

perl -0777 -i -pe 's/(couponsIngresos:\s*\{\s*handler\s*\(\)\s*\{\s*)/$1
            try { console.log("[EmploymentHistory] watcher START", { selectedPeriod: this.selectedPeriod }) } catch(e) {}
            try { console.log("[EmploymentHistory] couponsIngresos(raw)", this.couponsIngresos) } catch(e) {}
/s' "$FILE"

perl -0777 -i -pe 's/(this\s*\.\s*arrayCoupons\s*=\s*\[\s*\.\.\.\s*this\s*\.\s*couponsIngresos\s*\.\s*items\s*\]\s*;)/$1
            try { console.log("[EmploymentHistory] items length", Array.isArray(this.arrayCoupons) ? this.arrayCoupons.length : 0) } catch(e) {}
            try { console.table((this.arrayCoupons||[]).slice(0,10).map(x => ({ code: x.code, dias_laborados: x.dias_laborados }))) } catch(e) {}
/s' "$FILE"

perl -0777 -i -pe 's/(this\s*\.\s*arrayCoupons\s*\.?\s*forEach\s*\([^)]*\)\s*\)\s*;?)/$1
            try {
              const parsed = (this.arrayCoupons||[]).map(c => {
                const n = parseInt(String(c.dias_laborados||"").match(/\d+/)?.[0]||"",10);
                return isNaN(n) ? 0 : n;
              });
              const sum = parsed.reduce((a,b)=>a+b,0);
              this.debug_total_dias = sum;
              console.log("[EmploymentHistory] dias parsed ->", parsed, "sum:", sum);
            } catch(e) {}
/s' "$FILE"

perl -0777 -i -pe 's/(if\s*\(\s*\(\s*!\s*this\s*\.\s*selectedPeriod[^)]*\)\s*&&\s*this\s*\.\s*pagaduriaPeriodos[^)]*\)\s*\{\s*const\s+last\s*=\s*this\s*\.\s*pagaduriaPeriodos\s*\[\s*0\s*\]\s*\.slice\s*\(\s*0\s*,\s*7\s*\)\s*;\s*this\s*\.\s*setSelectedPeriod\s*\(\s*last\s*\)\s*;)/$1
            try { console.log("[EmploymentHistory] autoselected period", last) } catch(e) {}
/s' "$FILE"

if ! grep -n "watcher selectedPeriod" "$FILE" >/dev/null 2>&1; then
  perl -0777 -i -pe 's/(watch\s*:\s*\{[^}]*couponsIngresos\s*:\s*\{\s*handler\s*\(\)\s*\{[\s\S]*?\}\s*,\s*immediate\s*:\s*true\s*,\s*deep\s*:\s*true\s*\}\s*\})/$1,\n      selectedPeriod(val, old){ try { console.log("[EmploymentHistory] watcher selectedPeriod", { old, val }) } catch(e) {} }\n/s' "$FILE"
fi

if ! grep -n "created selectedPeriod" "$FILE" >/dev/null 2>&1; then
  perl -0777 -i -pe 's/(export\s+default\s*\{\s*)/$1\n  created(){ try { console.log("[EmploymentHistory] created selectedPeriod", this.selectedPeriod) } catch(e) {} },\n  mounted(){ try { console.log("[EmploymentHistory] mounted selectedPeriod", this.selectedPeriod); console.log("[EmploymentHistory] pagaduriaPeriodos length", Array.isArray(this.pagaduriaPeriodos)?this.pagaduriaPeriodos.length:this.pagaduriaPeriodos) } catch(e) {} },\n/s' "$FILE"
fi

git add "$FILE"
git commit -m "fix(debug): watcher válido y logs detallados para Días Laborados"

echo "Listo. Reconstruye el front y revisa la consola del navegador para ver los logs [EmploymentHistory]."
