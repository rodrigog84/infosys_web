#!/bin/sh
set -eu

APP=/var/www/html/Infosys_web

for dir in \
    "$APP/core/application/logs" \
    "$APP/core/application/cache" \
    "$APP/core/archivos" \
    "$APP/core/facturas"
do
    if [ -d "$dir" ]; then
        chmod -R 777 "$dir" || true
    fi
done

exec "$@"
