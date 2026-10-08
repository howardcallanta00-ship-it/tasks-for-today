#!/bin/sh
set -eu

if [ "${MIGRATE_ON_START:-false}" = "true" ]; then
  php spark migrate --all
fi
if [ "${SEED_ON_START:-false}" = "true" ]; then
  php spark db:seed AppSeeder
fi
exec "$@"
