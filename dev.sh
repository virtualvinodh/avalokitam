#!/bin/bash
set -e

# Colors for prefixed output
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; CYAN='\033[0;36m'; NC='\033[0m'

# Kill all child processes on exit
cleanup() {
  echo -e "\n${YELLOW}Stopping all services...${NC}"
  kill $(jobs -p) 2>/dev/null
  wait 2>/dev/null
  echo -e "${GREEN}Done.${NC}"
}
trap cleanup EXIT INT TERM

# Check prerequisites
command -v node >/dev/null 2>&1 || { echo -e "${RED}node not found${NC}"; exit 1; }

# Check backend .env
if [ ! -f backend/.env ]; then
  echo -e "${RED}backend/.env not found — copy backend/.env.example and fill in GEMINI_API_KEY${NC}"
  exit 1
fi

# PHP parser: use Docker container if already running, else try local php
PHP_PORT=8080
if docker ps --format "{{.Ports}}" 2>/dev/null | grep -q "0.0.0.0:${PHP_PORT}->"; then
  echo -e "${CYAN}[php]${NC}  Using existing Docker container on :${PHP_PORT}"
  PHP_RUNNING=1
elif command -v php >/dev/null 2>&1; then
  PHP_RUNNING=0
else
  echo -e "${RED}PHP parser not available: install php locally or run: docker run -d -p 8080:8080 virtualvinodh/avalokitam:latest${NC}"
  exit 1
fi

echo -e "${GREEN}Starting services...${NC}"
echo -e "  ${CYAN}PHP parser${NC}   → http://localhost:${PHP_PORT}  ${PHP_RUNNING:+(Docker)}"
echo -e "  ${CYAN}Node backend${NC} → http://localhost:3001"
echo -e "  ${CYAN}Frontend${NC}     → http://localhost:9000"
echo ""

# 1. PHP — only start if not already served by Docker
if [ "${PHP_RUNNING}" != "1" ]; then
  php -S localhost:${PHP_PORT} -t phpbackend 2>&1 \
    | while IFS= read -r line; do echo -e "${CYAN}[php]${NC}  $line"; done &
fi

# 2. Node backend with nodemon (hot reload) — skip if port already in use
if lsof -i :3001 -sTCP:LISTEN >/dev/null 2>&1; then
  echo -e "${GREEN}[node]${NC} Port 3001 already in use — skipping (kill it first to get hot reload)"
else
  (cd backend && npx nodemon server.js 2>&1) \
    | while IFS= read -r line; do echo -e "${GREEN}[node]${NC} $line"; done &
fi

# 3. Quasar frontend dev server (HMR)
NODE_OPTIONS=--openssl-legacy-provider npx quasar dev 2>&1 \
  | while IFS= read -r line; do echo -e "${YELLOW}[ui]${NC}   $line"; done &

# Wait for all background jobs
wait
