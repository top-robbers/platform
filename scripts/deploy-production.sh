#!/usr/bin/env bash

set -euo pipefail

BRANCH="${1:-main}"
APP_DIR="${APP_DIR:-/opt/top-robbers/platform}"
COMPOSE_FILE="${COMPOSE_FILE:-compose.production.yml}"
ENV_FILE="${ENV_FILE:-.env.production}"

cd "$APP_DIR"

echo "==> Fetching latest code"
git fetch --prune origin

echo "==> Checking out branch: $BRANCH"
git checkout "$BRANCH"

echo "==> Pulling latest changes"
git pull --ff-only origin "$BRANCH"

echo "==> Building production images"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" build --pull

echo "==> Starting containers"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" up -d --remove-orphans

echo "==> Clearing Laravel caches"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app php artisan optimize:clear

echo "==> Running database migrations"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app php artisan migrate --force

echo "==> Ensuring storage link exists"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app php artisan storage:link || true

echo "==> Optimizing Laravel"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" exec -T app php artisan optimize

echo "==> Current containers"
docker compose --env-file "$ENV_FILE" -f "$COMPOSE_FILE" ps

echo "==> Deployment completed"
