#!/usr/bin/env bash
#
# テーマの版数を 1 箇所から揃えて上げる。
#
# BCP の scripts/bump-version.sh と同じ役割。手で style.css だけ直すと
# supabase/theme.json が置き去りになり、リリースワークフローの
# 「Verify version」で確実に落ちる(BCP で 3 回やらかしている)。
#
# 使い方:  bash scripts/bump-version.sh 0.13.0
#
set -euo pipefail

VERSION="${1:-}"
if [[ ! "$VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "使い方: bash scripts/bump-version.sh <x.y.z>" >&2
  exit 1
fi

cd "$(dirname "$0")/.."

STYLE="style.css"
META="supabase/theme.json"

for f in "$STYLE" "$META"; do
  [[ -f "$f" ]] || { echo "見つかりません: $f" >&2; exit 1; }
done

TMP=$(mktemp)
sed -E "s|^(Version:[[:space:]]*)[0-9]+\.[0-9]+\.[0-9]+|\1$VERSION|" "$STYLE" > "$TMP"
mv "$TMP" "$STYLE"

LAST_UPDATED=$(date -u +"%Y-%m-%d %H:%M:%S")
TMP=$(mktemp)
jq --arg v "$VERSION" --arg t "$LAST_UPDATED" \
   '.version = $v | .last_updated = $t' "$META" > "$TMP"
mv "$TMP" "$META"

echo "✓ $STYLE      -> Version: $(grep -E '^Version:' "$STYLE" | sed -E 's/Version:[[:space:]]*//')"
echo "✓ $META  -> version: $(jq -r '.version' "$META")"
echo "✓ last_updated: $LAST_UPDATED"
echo
echo "min アセットを作り直す:"
echo "  npm ci && npm run build:min"
echo
echo "次:"
echo "  1. git add -A && git commit -m 'chore(release): v$VERSION'"
echo "  2. git push origin main"
echo "  3. git tag v$VERSION && git push origin v$VERSION"
