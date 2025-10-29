set -e

FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"
cp "$FILE" "$FILE.bak" 2>/dev/null || true

perl -0777 -i -pe 's/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g' "$FILE"

awk '
  BEGIN {
    printNext=0
  }
  {
    if (printNext==0) {
      if ($0 ~ /^[[:space:]]*watch[[:space:]]*:[[:space:]]*\{/ ) {
        print "  watch: {"
        print "    couponsIngresos: {"
        print "      handler() {"
        print "        console.log(\"[EmploymentHistory] watcher START\", { selectedPeriod: this.selectedPeriod })"
        print "        this.arrayCoupons = []"
        print "        const src = (this.couponsIngresos && this.couponsIngresos.items) ? this.couponsIngresos.items : []"
        print "        this.arrayCoupons = [...src]"
        print "        const extractNumber = (str) => {"
        print "          if (!str) return null"
        print "          const m = String(str).match(/\\d+/)"
        print "          return m ? m[0] : null"
        print "        }"
        print "        this.arrayCoupons.forEach(c => { c.dias_laborados = extractNumber(c.dias_laborados) })"
        print "        const nums = (this.arrayCoupons || []).map(c => parseInt(c.dias_laborados, 10) || 0)"
        print "        const sum = nums.reduce((a,b) => a + b, 0)"
        print "        console.log(\"[EmploymentHistory] items length\", this.arrayCoupons.length, \"dias:\", nums, \"sum:\", sum)"
        print "        if ((!this.selectedPeriod || !this.selectedPeriod.length) && this.pagaduriaPeriodos && this.pagaduriaPeriodos.length) {"
        print "          const last = this.pagaduriaPeriodos[0].slice(0,7)"
        print "          this.setSelectedPeriod(last)"
        print "          console.log(\"[EmploymentHistory] autoselected period\", last)"
        print "        }"
        print "      },"
        print "      immediate: true,"
        print "      deep: true"
        print "    },"
        print "    selectedPeriod(val, old) { console.log(\"[EmploymentHistory] watcher selectedPeriod\", { old, val }) },"
        print "    pagaduriaPeriodos(val, old) { console.log(\"[EmploymentHistory] watcher pagaduriaPeriodos\", { oldLen: Array.isArray(old)?old.length:old, newLen: Array.isArray(val)?val.length:val }) }"
        print "  },"
        printNext=1
        skip=1
        next
      } else {
        print
      }
    } else {
      if ($0 ~ /^[[:space:]]*\},[[:space:]]*$/) {
        afterWatchOnce=1
        print $0
        printNext=2
        next
      } else {
        next
      }
    }
  }
' "$FILE" > "$FILE.tmp"

if [ "$(wc -l < "$FILE.tmp")" -eq 0 ]; then
  echo "Archivo temporal vacío; abortando" >&2
  exit 1
fi

mv "$FILE.tmp" "$FILE"

git add "$FILE"
git commit -m "fix(EmploymentHistory): reconstruye watch con handler válido y logs; corrige .length"
