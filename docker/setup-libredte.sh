#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
LIBREDTE="$ROOT/core/application/libraries/libredte-lib/trunk"
CONTAINER="${INFOSYS_WEB_CONTAINER:-infosys_web-web-1}"

if ! docker ps --format '{{.Names}}' | grep -qx "$CONTAINER"; then
  echo "Contenedor $CONTAINER no está corriendo."
  echo "Ejecuta primero: cd \"$ROOT\" && docker compose up -d"
  exit 1
fi

docker exec "$CONTAINER" bash -c "
set -e
cd /var/www/html/Infosys_web/core/application/libraries/libredte-lib/trunk
if [ ! -f composer.phar ]; then
  curl -sS https://getcomposer.org/installer | php -- --version=1.10.26 --install-dir=. --filename=composer.phar
fi
php composer.phar install --no-interaction --no-dev --prefer-dist
"

echo "LibreDTE instalado en: $LIBREDTE/vendor"
