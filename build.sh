#!/bin/bash
set -e

echo "==> Building Quasar SPA..."
NODE_OPTIONS=--openssl-legacy-provider npx quasar build

echo "==> Building Docker image..."
docker build -t avalokitam-prod .

echo "==> Restarting container..."
docker stop avalokitam-prod 2>/dev/null || true
docker rm avalokitam-prod 2>/dev/null || true
docker run -d --name avalokitam-prod -p 8080:8080 avalokitam-prod

echo "==> Done. Running at http://localhost:8080"
