#!/bin/bash
set -e

echo "==> Building image (Quasar + Docker)..."
docker build -t virtualvinodh/avalokitam:latest .

echo "==> Restarting container..."
docker stop avalokitam-prod 2>/dev/null || true
docker rm avalokitam-prod 2>/dev/null || true
docker run -d --name avalokitam-prod -p 8080:8080 virtualvinodh/avalokitam:latest

echo "==> Done. Running at http://localhost:8080"
