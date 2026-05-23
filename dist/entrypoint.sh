#!/bin/bash
set -e

# Inject the AI backend URL (set in Render dashboard) into Apache vhost
if [ -z "$AI_BACKEND_URL" ]; then
  echo "WARNING: AI_BACKEND_URL not set — AI features will not work"
  AI_BACKEND_URL="http://localhost:3001"
fi

# Strip trailing slash
AI_BACKEND_URL="${AI_BACKEND_URL%/}"

sed -i "s|AI_BACKEND_URL_PLACEHOLDER|${AI_BACKEND_URL}|g" \
  /etc/apache2/sites-enabled/000-default.conf

echo "AI backend proxied to: ${AI_BACKEND_URL}"

exec apache2ctl -D FOREGROUND
