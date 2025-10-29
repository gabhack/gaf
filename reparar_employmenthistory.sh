set -e

FILE="resources/assets/js/components/pages/ConsultDataClientDraft/EmploymentHistory.vue"

if [ -f "$FILE.bak" ]; then
  cp "$FILE.bak" "$FILE"
else
  LAST_GOOD_COMMIT="$(git rev-list -n 2 HEAD -- "$FILE" | tail -n 1 || true)"
  if [ -n "$LAST_GOOD_COMMIT" ]; then
    git checkout "$LAST_GOOD_COMMIT" -- "$FILE"
  fi
fi

sed -E -i '' 's/selectedPeriod\.len[a-zA-Z]*th/selectedPeriod.length/g' "$FILE"

if ! grep -q "\[EmploymentHistory] module loaded" "$FILE"; then
  awk '{
    print
    if (!done && $0 ~ /<script[^>]*>/) {
      print "if (typeof window !== \"undefined\") { console.log(\"[EmploymentHistory] module loaded\") }"
      done=1
    }
  }' "$FILE" > "$FILE.tmp" && mv "$FILE.tmp" "$FILE"
fi

git add "$FILE"
git commit -m "revert(EmploymentHistory): restaurado desde backup o commit previo; fix .length; log de carga"
