set -e
FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"
perl -0777 -i -pe "s/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g" "$FILE"
perl -0777 -i -pe "s/(couponsIngresos:\s*\{\s*handler\s*\(\)\s*\{\s*)/$1          try { console.log('[EmploymentHistory] couponsIngresos', this.couponsIngresos) } catch (e) {}\n/s" "$FILE"
perl -0777 -i -pe "s/(this\.arrayCoupons\.forEach\([^)]*\)\s*\)\s*;?\s*)/$1\n            try { console.log('[EmploymentHistory] arrayCoupons(dias_laborados)', this.arrayCoupons.map(c => ({ code: c.code, dias_laborados: c.dias_laborados }))) } catch (e) {}\n/s" "$FILE"
git add "$FILE"
git commit -m "fix(EmploymentHistory): corrige .length y agrega logs para días laborados"
