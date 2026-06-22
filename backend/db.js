const Database = require('better-sqlite3')
const path = require('path')

const dbPath = process.env.DB_PATH || '/tmp/compositions.db'
const db = new Database(dbPath)

db.exec(`
  CREATE TABLE IF NOT EXISTS compositions (
    id TEXT PRIMARY KEY,
    verse TEXT NOT NULL,
    metre TEXT,
    created_at INTEGER NOT NULL
  )
`)

function randomId () {
  return Math.random().toString(36).slice(2, 8)
}

function saveComposition (verse, metre) {
  let id = randomId()
  // retry on collision (astronomically rare but safe)
  while (db.prepare('SELECT 1 FROM compositions WHERE id = ?').get(id)) {
    id = randomId()
  }
  db.prepare('INSERT INTO compositions (id, verse, metre, created_at) VALUES (?, ?, ?, ?)')
    .run(id, verse, metre || null, Date.now())
  return id
}

function getComposition (id) {
  return db.prepare('SELECT * FROM compositions WHERE id = ?').get(id) || null
}

module.exports = { saveComposition, getComposition }
