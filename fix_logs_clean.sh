set -e
FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"

git checkout -- "$FILE" 2>/dev/null || true

perl -0777 -i -pe 's/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g' "$FILE"

awk '
  {
    print
    if (!done1 && $0 ~ /couponsIngresos:\s*\{\s*handler\s*\(\)\s*\{/) {
      print "          console.log(\"[EmploymentHistory] watcher START\", { selectedPeriod: this.selectedPeriod })"
      print "          console.log(\"[EmploymentHistory] couponsIngresos(raw)\", this.couponsIngresos)"
      done1=1
    }
    if (!done2 && $0 ~ /this\.arrayCoupons\s*=\s*\[\s*\.\.\.\s*this\.couponsIngresos\.items\s*\]\s*;/) {
      print "            console.log(\"[EmploymentHistory] items length\", (this.arrayCoupons||[]).length)"
      print "            console.log(\"[EmploymentHistory] first 3\", (this.arrayCoupons||[]).slice(0,3))"
      done2=1
    }
    if (!done3 && $0 ~ /this\.arrayCoupons\.forEach\(/) {
      print "            console.log(\"[EmploymentHistory] keys item[0]\", Object.keys((this.arrayCoupons||[])[0]||{}))"
      done3=1
    }
    if (!done4 && $0 ~ /this\.setSelectedPeriod\s*\(\s*last\s*\)\s*;/) {
      print "            console.log(\"[EmploymentHistory] autoselected period\", last)"
      done4=1
    }
  }
' "$FILE" > "$FILE.tmp" && mv "$FILE.tmp" "$FILE"

if ! grep -q "created selectedPeriod" "$FILE"; then
  perl -0777 -i -pe 's{(export\s+default\s*\{\s*)}{$1\n  created(){ try { console.log("[EmploymentHistory] created selectedPeriod", this.selectedPeriod) } catch(e) {} },\n  mounted(){ try { console.log("[EmploymentHistory] mounted selectedPeriod", this.selectedPeriod); console.log("[EmploymentHistory] mounted pagaduriaPeriodos length", Array.isArray(this.pagaduriaPeriodos)? this.pagaduriaPeriodos.length : this.pagaduriaPeriodos) } catch(e) {} },\n}s' "$FILE"
fi

git add "$FILE"
git commit -m "chore(EmploymentHistory): logs en watcher, copia de items y autoselección de periodo"
