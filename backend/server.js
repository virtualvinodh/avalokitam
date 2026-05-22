require('dotenv').config()
const express = require('express')
const cors = require('cors')
const { runLoop } = require('./geminiLoop')

const app = express()
app.use(cors())
app.use(express.json())

const FREE_LIMIT = 5
const DAILY_GLOBAL_LIMIT = 200
const usage = new Map() // key → { count, date }
let globalUsage = { count: 0, date: '' }

function today () {
  return new Date().toISOString().slice(0, 10)
}

function getIP (req) {
  return (req.headers['x-forwarded-for'] || req.socket.remoteAddress || '').split(',')[0].trim()
}

function getCount (key) {
  const entry = usage.get(key)
  if (!entry || entry.date !== today()) return 0
  return entry.count
}

function increment (key) {
  const count = getCount(key) + 1
  usage.set(key, { count, date: today() })
  return count
}

function getSessionUsage (req) {
  const id = req.headers['x-session-id']
  if (!id) return null
  return { id, count: getCount(id) }
}

app.get('/health', (req, res) => {
  res.json({ status: 'ok', model: process.env.GEMINI_MODEL })
})


app.get('/ai/usage', (req, res) => {
  const session = getSessionUsage(req)
  if (!session) return res.json({ used: 0, remaining: FREE_LIMIT })
  res.json({ used: session.count, remaining: Math.max(0, FREE_LIMIT - session.count) })
})

// POST /ai/stream  — SSE streaming version (generate or fix)
// Body: { mode: 'generate'|'fix', topic?, verse?, verseType, lang? }
app.post('/ai/stream', async (req, res) => {
  const { mode, topic, verse, verseType, lang = 'ta' } = req.body

  if (!verseType || (mode === 'generate' && !topic) || (mode === 'fix' && !verse)) {
    return res.status(400).json({ error: 'Missing required fields' })
  }

  const sessionId = req.headers['x-session-id']
  if (!sessionId) {
    return res.status(400).json({ error: 'Missing session ID' })
  }

  const ip = getIP(req)
  const sessionUsed = getCount(sessionId)
  const ipUsed = getCount(ip)

  if (sessionUsed >= FREE_LIMIT || ipUsed >= FREE_LIMIT) {
    return res.status(403).json({ error: 'limit_reached', remaining: 0 })
  }

  if (globalUsage.date !== today()) {
    globalUsage = { count: 0, date: today() }
  }
  if (globalUsage.count >= DAILY_GLOBAL_LIMIT) {
    return res.status(503).json({ error: 'service_limit_reached' })
  }
  globalUsage.count++

  increment(sessionId)
  increment(ip)
  const remaining = FREE_LIMIT - (sessionUsed + 1)

  res.setHeader('Content-Type', 'text/event-stream')
  res.setHeader('Cache-Control', 'no-cache')
  res.setHeader('Connection', 'keep-alive')
  res.flushHeaders()

  const emit = (data) => new Promise(resolve => {
    res.write(`data: ${JSON.stringify(data)}\n\n`)
    resolve()
  })

  try {
    await runLoop({ mode, topic, verse, verseType, lang, emit })
    await emit({ type: 'usage', remaining })
  } catch (err) {
    await emit({ type: 'error', message: err.message })
  }

  res.end()
})

const PORT = process.env.PORT || 3001
app.listen(PORT, () => {
  console.log(`Avalokitam AI backend running on port ${PORT}`)
  console.log(`Gemini model: ${process.env.GEMINI_MODEL}`)
  console.log(`PHP API: ${process.env.PHP_API_URL}`)
})
