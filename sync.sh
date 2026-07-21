#!/usr/bin/env bash
# Sync tech stack dari docs/SRS.md ke section STACK_START..STACK_END di .claude/CLAUDE.md

set -euo pipefail

SRS="docs/SRS.md"
CLAUDE=".claude/CLAUDE.md"
LOG_DIR="logs"
LOG_FILE="$LOG_DIR/sync.log"

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log() { echo -e "${GREEN}[sync]${NC} $1" | tee -a "$LOG_FILE"; }
warn() { echo -e "${YELLOW}[sync warn]${NC} $1" | tee -a "$LOG_FILE"; }
error() { echo -e "${RED}[sync error]${NC} $1" | tee -a "$LOG_FILE"; exit 1; }

mkdir -p "$LOG_DIR"
echo "--- sync $(date '+%Y-%m-%d %H:%M:%S') ---" >> "$LOG_FILE"

[ -f "$SRS" ] || error "$SRS tidak ditemukan."
[ -f "$CLAUDE" ] || error "$CLAUDE tidak ditemukan."

# Extract Tech Stack table dari SRS.md (kolom kedua, skip header dan separator)
STACK=$(awk '/^## Tech Stack/{found=1; next} found && /^\|/{
    if ($0 ~ /^[|][-]+/) next;
    if ($0 ~ /Layer.*Choice/) next;
    split($0, a, "|");
    gsub(/^[ \t]+|[ \t]+$/, "", a[3]);
    if (a[3] != "") print "- " a[3]
} found && /^---/{exit}' "$SRS")

if [ -z "$STACK" ]; then
    warn "Tidak ada Tech Stack table yang ditemukan di $SRS. CLAUDE.md tidak diubah."
    exit 0
fi

TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
NEW_BLOCK="<!-- STACK_START -->\n## Stack (auto-synced from SRS.md)\n\n$STACK\n\n_Last synced: $TIMESTAMP_\n<!-- STACK_END -->"

perl -i -0pe "s|<!-- STACK_START -->.*?<!-- STACK_END -->|$NEW_BLOCK|s" "$CLAUDE"

log "Stack berhasil di-sync ke $CLAUDE"
log "Stack yang di-sync:"
echo "$STACK" | while IFS= read -r line; do log "  $line"; done
