#!/usr/bin/env bash
# Builds an installable plugin zip in dist/.
set -euo pipefail

SLUG="free-support-chat-widget-by-brane"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
BUILD="$(mktemp -d)"
trap 'rm -rf "$BUILD"' EXIT

mkdir -p "$BUILD/$SLUG" "$ROOT/dist"
cp -R "$ROOT/$SLUG.php" "$ROOT/uninstall.php" "$ROOT/readme.txt" "$ROOT/LICENSE" \
	"$ROOT/includes" "$ROOT/assets" "$ROOT/languages" "$BUILD/$SLUG/"

rm -f "$ROOT/dist/$SLUG.zip"
(cd "$BUILD" && zip -rq "$ROOT/dist/$SLUG.zip" "$SLUG")
echo "Built dist/$SLUG.zip"
