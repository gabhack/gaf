set -e
FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"
cp "$FILE" "$FILE.bak"

perl -0777 -i -pe 's/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g' "$FILE"

awk -v OFS='' '
  BEGIN {
    blk="  watch: {\n    couponsIngresos: {\n      handler() {\n        try { console.log('\''[EmploymentHistory] watcher START'\'', { selectedPeriod: this.selectedPeriod }); } catch(e) {}\n        this.arrayCoupons = [];\n        if (this.couponsIngresos && this.couponsIngresos.items) {\n          this.arrayCoupons = [...this.couponsIngresos.items];\n          const extractNumber = str => {\n            if (!str) return null;\n            const match = String(str).match(/\\d+/);\n            return match ? match[0] : null;\n          };\n          this.arrayCoupons.forEach(c => { c.dias_laborados = extractNumber(c.dias_laborados); });\n          try {\n            const nums = (this.arrayCoupons||[]).map(c => parseInt(c.dias_laborados,10)||0);\n            const sum = nums.reduce((a,b)=>a+b,0);\n            console.log('\''[EmploymentHistory] items length'\'', this.arrayCoupons.length, '\''dias:'\'', nums, '\''sum:'\'', sum);\n          } catch(e) {}\n        }\n        if ((!this.selectedPeriod || !this.selectedPeriod.length) && this.pagaduriaPeriodos && this.pagaduriaPeriodos.length) {\n          const last = this.pagaduriaPeriodos[0].slice(0,7);\n          this.setSelectedPeriod(last);\n          try { console.log('\''[EmploymentHistory] autoselected period'\'', last); } catch(e) {}\n        }\n      },\n      immediate: true,\n      deep: true\n    },\n    selectedPeriod(val, old) { try { console.log('\''[EmploymentHistory] watcher selectedPeriod'\'', { old, val }); } catch(e) {} },\n    pagaduriaPeriodos(val, old) { try { console.log('\''[EmploymentHistory] watcher pagaduriaPeriodos'\'', { oldLen: Array.isArray(old)?old.length:old, newLen: Array.isArray(val)?val.length:val }); } catch(e) {} }\n  },\n";
  }
  function count(s,c,   n) { gsub("[^" c "]","",s); return length(s); }
  {
    if(!inwatch){
      if ($0 ~ /^[[:space:]]*watch[[:space:]]*:[[:space:]]*\{/){
        print blk;
        inwatch=1;
        depth=1;
        next;
      } else {
        print $0;
      }
    } else {
      openBr=count($0,"{");
      closeBr=count($0,"}");
      depth += openBr - closeBr;
      if (depth<=0) { inwatch=0; }
      next;
    }
  }
' "$FILE" > "$FILE.tmp" && mv "$FILE.tmp" "$FILE"

git add "$FILE"
git commit -m "fix(EmploymentHistory): reconstruye watch con logs válidos y corrige .length"
