#!/usr/bin/env bash

set -euo pipefail

project_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
artifact_root="$(mktemp -d "${TMPDIR:-/tmp}/php-mcp-schema-artifact.XXXXXX")"
trap 'rm -rf "$artifact_root"' EXIT

php_executable="${PHP_BINARY:-$(command -v php)}"
composer_executable="$(command -v composer)"

archive="$artifact_root/php-mcp-schema.zip"
extracted="$artifact_root/extracted"
source_root="$artifact_root/source"
expected="$artifact_root/expected-files.txt"
actual="$artifact_root/actual-files.txt"

mkdir -p "$source_root"
while IFS= read -r -d '' relative_path; do
    source_path="$project_root/$relative_path"
    if [[ ! -f "$source_path" && ! -L "$source_path" ]]; then
        continue
    fi
    mkdir -p "$source_root/$(dirname "$relative_path")"
    cp -p "$source_path" "$source_root/$relative_path"
done < <(
    git -C "$project_root" ls-files --cached -z
    git -C "$project_root" ls-files --others --exclude-standard -z -- src
)

"$php_executable" "$composer_executable" archive \
    --working-dir="$source_root" \
    --format=zip \
    --dir="$artifact_root" \
    --file=php-mcp-schema \
    --no-interaction

printf '%s\n' \
    CHANGELOG.md \
    LICENSE.md \
    README.md \
    composer.json > "$expected"
find "$source_root/src" -type f -print \
    | sed "s|^$source_root/||" \
    >> "$expected"
sort -o "$expected" "$expected"

unzip -Z1 "$archive" \
    | sed '/\/$/d' \
    | sort \
    > "$actual"
diff -u "$expected" "$actual"

mkdir -p "$extracted"
unzip -q "$archive" -d "$extracted"

# Composer libraries normally omit their lock. Copy the reviewed source lock
# only as offline resolution metadata for this extracted-root no-dev proof.
cp "$source_root/composer.lock" "$extracted/composer.lock"
COMPOSER_DISABLE_NETWORK=1 "$php_executable" "$composer_executable" --no-cache install \
    --working-dir="$extracted" \
    --no-dev \
    --classmap-authoritative \
    --no-interaction \
    --no-progress
"$php_executable" "$composer_executable" dump-autoload \
    --working-dir="$extracted" \
    --no-dev \
    --optimize \
    --strict-psr \
    --strict-ambiguous \
    --no-interaction
"$php_executable" "$composer_executable" check-platform-reqs \
    --working-dir="$extracted" \
    --no-dev

"$php_executable" "$project_root/tests/artifact/smoke.php" "$extracted/vendor/autoload.php"

digest="$("$php_executable" -r 'echo hash_file("sha256", $argv[1]);' "$archive")"
bytes="$(wc -c < "$archive" | tr -d ' ')"
files="$(wc -l < "$actual" | tr -d ' ')"
printf 'verified artifact sha256=%s bytes=%s files=%s\n' "$digest" "$bytes" "$files"
