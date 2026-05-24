# Build Guide (macOS)

## Prerequisites

| Tool | Recommended | Notes |
|------|-------------|-------|
| Node.js | **16.x** | See note below |
| npm | 8+ | comes with Node |
| Docker Desktop | 4.x+ | must be running |

### Node version note

The frontend uses `@quasar/app@1.2.2` + `node-sass`, which has two issues on modern Node:

- **Node 16**: works out of the box. Use [nvm](https://github.com/nvm-sh/nvm): `nvm use 16`
- **Node 17–22**: Webpack 4's `md4` hash is not supported by OpenSSL 3. `build.sh` works around this automatically with `NODE_OPTIONS=--openssl-legacy-provider` — no action needed, just be aware if you run `quasar build` manually.
- **Node 23+**: untested, likely breaks.

`node-sass` also requires Python 2 + native build tools to compile. On macOS, Xcode Command Line Tools cover this. If you get a `node-gyp` error on first install run:

```bash
xcode-select --install
```

## First-time setup

```bash
npm install          # installs frontend deps (compiles node-sass — takes ~1 min)
cd backend && npm install && cd ..   # installs AI backend deps
```

## Build & run

```bash
./build.sh
```

This does three things in sequence:

1. `quasar build` → compiles the Vue SPA into `dist/spa/`
2. `docker build` → builds the production image (`avalokitam-prod`)
3. Restarts the `avalokitam-prod` container on `http://localhost:8080`

## Manual steps (if needed)

```bash
# Frontend only
NODE_OPTIONS=--openssl-legacy-provider npx quasar build

# Docker image only (uses existing dist/spa)
docker build -t avalokitam-prod .

# Restart container only
docker stop avalokitam-prod && docker rm avalokitam-prod
docker run -d --name avalokitam-prod -p 8080:8080 avalokitam-prod
```

## Environment variables

The AI backend needs a Gemini API key. Copy `.env.example` and fill it in:

```bash
cp backend/.env.example backend/.env
# edit backend/.env and set GEMINI_API_KEY=...
```

`backend/.env` is gitignored and must never be committed.

For production (Render), set `GEMINI_API_KEY` as an environment variable in the Render dashboard — do not bake it into the image.

## Why multi-stage Docker build doesn't work here

`node-sass` must compile a native binary using Python 2 + `node-gyp@3.x`. Modern Docker base images (Debian Bullseye+, Alpine 3.13+) no longer ship Python 2, so `npm ci` fails inside Docker. Building the SPA on the host Mac and copying `dist/spa/` into the image is the reliable workaround.
