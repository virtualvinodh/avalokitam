const Database = require('better-sqlite3')
const path = require('path')

const dbPath = process.env.DB_PATH || '/tmp/compositions.db'
const db = new Database(dbPath)

db.exec(`
  CREATE TABLE IF NOT EXISTS compositions (
    id TEXT PRIMARY KEY,
    verse TEXT NOT NULL,
    metre TEXT,
    source TEXT,
    created_at INTEGER NOT NULL
  )
`)
try { db.exec('ALTER TABLE compositions ADD COLUMN source TEXT') } catch (_) {}

db.exec(`
  CREATE TABLE IF NOT EXISTS generation_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    created_at INTEGER NOT NULL,
    mode TEXT,
    verse_type TEXT,
    prompt TEXT,
    attempts INTEGER,
    success INTEGER,
    final_verse TEXT,
    iterations_json TEXT,
    sandhi TEXT,
    literal TEXT,
    explanation TEXT,
    cost REAL,
    manually_fixed_verse TEXT
  )
`)

db.exec(`
  CREATE TABLE IF NOT EXISTS daily_stats (
    date TEXT PRIMARY KEY,
    generations INTEGER DEFAULT 0,
    fixes INTEGER DEFAULT 0,
    total_attempts INTEGER DEFAULT 0,
    first_try_successes INTEGER DEFAULT 0,
    ai_failures INTEGER DEFAULT 0,
    fix_clicks INTEGER DEFAULT 0,
    input_tokens INTEGER DEFAULT 0,
    output_tokens INTEGER DEFAULT 0,
    thinking_tokens INTEGER DEFAULT 0,
    cost REAL DEFAULT 0
  )
`)

function randomId () {
  return Math.random().toString(36).slice(2, 8)
}

function saveComposition (verse, metre, source) {
  let id = randomId()
  while (db.prepare('SELECT 1 FROM compositions WHERE id = ?').get(id)) {
    id = randomId()
  }
  db.prepare('INSERT INTO compositions (id, verse, metre, source, created_at) VALUES (?, ?, ?, ?, ?)')
    .run(id, verse, metre || null, source || null, Date.now())
  return id
}

function getComposition (id) {
  return db.prepare('SELECT * FROM compositions WHERE id = ?').get(id) || null
}

function saveGenerationLog ({ mode, verseType, prompt, attempts, success, finalVerse, iterationsJson, sandhi, literal, explanation, cost }) {
  const result = db.prepare(`
    INSERT INTO generation_log (created_at, mode, verse_type, prompt, attempts, success, final_verse, iterations_json, sandhi, literal, explanation, cost)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `).run(Date.now(), mode, verseType, prompt, attempts, success ? 1 : 0, finalVerse, iterationsJson, sandhi || null, literal || null, explanation || null, cost || 0)
  return result.lastInsertRowid
}

function updateManualFix (id, verse) {
  db.prepare('UPDATE generation_log SET manually_fixed_verse = ? WHERE id = ?').run(verse, id)
}

function listGenerationLog (page, limit) {
  const offset = (page - 1) * limit
  const rows = db.prepare('SELECT * FROM generation_log ORDER BY created_at DESC LIMIT ? OFFSET ?').all(limit, offset)
  const { total } = db.prepare('SELECT COUNT(*) as total FROM generation_log').get()
  return { rows, total }
}

function getSourceCounts () {
  const rows = db.prepare(`
    SELECT COALESCE(source, 'unknown') AS source, COUNT(*) AS count
    FROM compositions GROUP BY source ORDER BY count DESC
  `).all()
  const total = rows.reduce((s, r) => s + r.count, 0)
  return { rows, total }
}

function listCompositions (page, limit) {
  const offset = (page - 1) * limit
  const rows = db.prepare('SELECT * FROM compositions ORDER BY created_at DESC LIMIT ? OFFSET ?').all(limit, offset)
  const { total } = db.prepare('SELECT COUNT(*) as total FROM compositions').get()
  return { rows, total }
}

const upsertStat = db.prepare(`
  INSERT INTO daily_stats (date, generations, fixes, total_attempts, first_try_successes, ai_failures, input_tokens, output_tokens, thinking_tokens, cost)
  VALUES (@date, @generations, @fixes, @total_attempts, @first_try_successes, @ai_failures, @input_tokens, @output_tokens, @thinking_tokens, @cost)
  ON CONFLICT(date) DO UPDATE SET
    generations        = generations        + excluded.generations,
    fixes              = fixes              + excluded.fixes,
    total_attempts     = total_attempts     + excluded.total_attempts,
    first_try_successes= first_try_successes+ excluded.first_try_successes,
    ai_failures        = ai_failures        + excluded.ai_failures,
    input_tokens       = input_tokens       + excluded.input_tokens,
    output_tokens      = output_tokens      + excluded.output_tokens,
    thinking_tokens    = thinking_tokens    + excluded.thinking_tokens,
    cost               = cost               + excluded.cost
`)

function recordDailyStat ({ mode, attempts, firstTry, success, tokens }) {
  const date = new Date().toISOString().slice(0, 10)
  upsertStat.run({
    date,
    generations: mode === 'generate' ? 1 : 0,
    fixes: mode === 'fix' ? 1 : 0,
    total_attempts: attempts,
    first_try_successes: firstTry ? 1 : 0,
    ai_failures: success ? 0 : 1,
    input_tokens: tokens.input || 0,
    output_tokens: tokens.output || 0,
    thinking_tokens: tokens.thinking || 0,
    cost: tokens.cost || 0
  })
}

function incrementFixClick () {
  const date = new Date().toISOString().slice(0, 10)
  db.prepare(`
    INSERT INTO daily_stats (date, fix_clicks) VALUES (?, 1)
    ON CONFLICT(date) DO UPDATE SET fix_clicks = fix_clicks + 1
  `).run(date)
}

function getDailyStats (limit = 30) {
  return db.prepare('SELECT * FROM daily_stats ORDER BY date DESC LIMIT ?').all(limit)
}

function getStatsTotals () {
  return db.prepare(`
    SELECT
      SUM(generations)         AS generations,
      SUM(fixes)               AS fixes,
      SUM(ai_failures)         AS ai_failures,
      SUM(total_attempts)      AS total_attempts,
      SUM(first_try_successes) AS first_try_successes,
      SUM(fix_clicks)          AS fix_clicks,
      SUM(input_tokens)        AS input_tokens,
      SUM(output_tokens)       AS output_tokens,
      SUM(thinking_tokens)     AS thinking_tokens,
      SUM(cost)                AS cost
    FROM daily_stats
  `).get()
}

module.exports = { saveComposition, getComposition, getSourceCounts, listCompositions, saveGenerationLog, updateManualFix, listGenerationLog, recordDailyStat, incrementFixClick, getDailyStats, getStatsTotals }
