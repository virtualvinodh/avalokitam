# Build Guide (macOS)

## Prerequisites

| Tool | Notes |
|------|-------|
| Docker Desktop 4.x+ | must be running |

That's it. The Quasar SPA is compiled inside Docker — no local Node.js required.

## Build & run

```bash
./build.sh
```

This builds the production image (Quasar inside Docker, then PHP+Node runtime) and
restarts the `avalokitam-prod` container on `http://localhost:8080`.

## Environment variables

The AI backend needs a Gemini API key:

```bash
cp backend/.env.example backend/.env
# edit backend/.env and set GEMINI_API_KEY=...
```

`backend/.env` is gitignored and must never be committed.

For production (Render), set `GEMINI_API_KEY` as an environment variable in the
Render dashboard — do not bake it into the image.

## How the multi-stage build works

```
Stage 1 (node:20-bookworm-slim):
  npm ci --ignore-scripts     ← skips node-sass native compilation
  NODE_OPTIONS=--openssl-legacy-provider npx quasar build
    → dist/spa/

Stage 2 (php:7.4-apache):
  Copy dist/spa from stage 1
  Install PHP + APCu + Node.js 20 + supervisord
  Copy phpbackend/ and backend/
  Run Apache + Node.js via supervisord
```

### Key workarounds baked into the build

- **node-sass skipped**: `npm ci --ignore-scripts` avoids the Python 2 + node-gyp
  requirement. Quasar's sass-loader is patched (in `quasar.conf.js`) to use the
  pure-JS `sass` package (Dart Sass) instead.

- **OpenSSL legacy**: Node 20 + Webpack 4's md4 hash requires
  `NODE_OPTIONS=--openssl-legacy-provider`.

- **`quote()` fix**: The npm release of `quasar@1.2.2` calls `quote($i)` with a
  Number in its sass, which breaks Dart Sass. `quasar.conf.js` prepends a sass-level
  override via `prependData` that coerces numbers to strings via interpolation.

## Manual steps (if needed)

```bash
# Docker image only
docker build -t avalokitam-prod .

# Restart container only
docker stop avalokitam-prod && docker rm avalokitam-prod
docker run -d --name avalokitam-prod -p 8080:8080 avalokitam-prod
```
