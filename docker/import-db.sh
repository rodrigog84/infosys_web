#!/bin/sh
set -eu

DUMP="${1:-$HOME/Downloads/infosys_web.sql}"

if [ ! -f "$DUMP" ]; then
    echo "No encuentro el dump: $DUMP"
    echo "Uso: ./docker/import-db.sh /ruta/al/archivo.sql"
    exit 1
fi

echo "Importando $DUMP (puede tardar varios minutos)..."
docker compose exec -T db mysql -uroot infosys_web < "$DUMP"
echo "Listo."
