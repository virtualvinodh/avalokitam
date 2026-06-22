# Backend API

Node.js/Express server (`server.js`) running on port 3001. Apache proxies these paths from port 8080.

## Routes

### `POST /ai/stream`
SSE streaming endpoint for AI verse generation and fixing.

**Body:**
```json
{ "mode": "generate", "topic": "கடல்", "verseType": "வெண்பா", "lang": "ta" }
{ "mode": "fix",      "verse": "...",  "verseType": "வெண்பா", "lang": "ta" }
```

**Headers required:** `x-session-id` (rate limiting per session)

**Events streamed:** `thinking`, `checking`, `iteration`, `done`, `sandhi`, `literal`, `explanation`, `tokens`, `usage`, `error`

Runs the generate-validate-refine loop (see [AI.md](../AI.md)):
- Calls Gemini to generate/fix a verse
- Calls the PHP parser to validate it
- Feeds parser errors back to Gemini
- Repeats up to 5 times

---

### `GET /ai/usage`
Returns how many AI uses the current session has consumed.

**Response:** `{ used, remaining, globalRemaining, globalLimit }`

Rate limits: 5 uses per session per day, 200 global per day.

---

### `POST /venpa/suggest`
Pure constraint solver — no AI. Used by the VenpaaFixer page.

**Body:** `{ "verse": "..." }`

**Response:** `{ suggestions, runs }` — lists valid next feet at each position given வெண்டளை bond rules.

---

### `POST /compositions`
Save a verse to SQLite. Used by ShareMixin when the user clicks சேமி.

**Body:** `{ "verse": "...", "metre": "வெண்பா" }`

**Response:** `{ "id": "abc123" }` — short random ID for the permalink `/poem/:id`

---

### `GET /compositions/:id`
Retrieve a saved verse by ID. Used by the `/poem/:id` page.

**Response:** `{ id, verse, metre, created_at }` or 404.

---

### `GET /health`
Returns server status and token usage stats.

**Response:** `{ status, model, stats }` — stats broken down by overall/daily/monthly.

## Files

| File | Purpose |
|------|---------|
| `server.js` | Express app, rate limiting, route handlers |
| `geminiLoop.js` | Generate-validate-refine loop, Gemini API calls, PHP parser calls |
| `systemPrompt.js` | Prompt builders for generate / fix / feedback / explain |
| `errorFeedback.js` | Converts parser XML into natural-language feedback for Gemini; VenpaaFixer suggestions |
| `db.js` | SQLite helpers (`saveComposition`, `getComposition`) |

## Environment variables

| Variable | Default | Notes |
|----------|---------|-------|
| `GEMINI_API_KEY` | — | Required |
| `GEMINI_MODEL` | `gemini-2.5-flash` | |
| `GEMINI_THINKING_LEVEL` | `minimal` | `minimal` / `low` / `medium` / `high` |
| `PHP_API_URL` | — | Set by supervisord in Docker: `http://localhost:8080/api.php` |
| `PORT` | `3001` | Set by supervisord in Docker |
| `DEV_TOKEN` | — | Optional; bypasses rate limits when passed as `x-dev-token` header |
| `DB_PATH` | `/tmp/compositions.db` | Set to `/var/data/compositions.db` for persistence |
