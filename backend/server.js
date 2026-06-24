require('dotenv').config()
const express = require('express')
const cors = require('cors')
const { runLoop, callParser, parseXML } = require('./geminiLoop')
const { getSuggestions, getRunSuggestions } = require('./errorFeedback')
const { saveComposition, getComposition, getPublicCompositions, getRandomPublicComposition, getSourceCounts, setCompositionPublic, listCompositions, saveGenerationLog, updateManualFix, listGenerationLog, recordDailyStat, incrementFixClick, getDailyStats, getStatsTotals } = require('./db')

const app = express()
app.use(cors())
app.use(express.json())

const FREE_LIMIT = parseInt(process.env.FREE_LIMIT) || 5
const DAILY_GLOBAL_LIMIT = parseInt(process.env.DAILY_GLOBAL_LIMIT) || 200
const usage = new Map() // key → { count, date }
let globalUsage = { count: 0, date: '' }

const stats = {
  overall: { input: 0, output: 0, thinking: 0, generations: 0, cost: 0 },
  daily:   { input: 0, output: 0, thinking: 0, generations: 0, cost: 0, date: '' },
  monthly: { input: 0, output: 0, thinking: 0, generations: 0, cost: 0, month: '' }
}

function thisMonth () {
  return new Date().toISOString().slice(0, 7)
}

function recordTokens (tokens) {
  const d = today(), m = thisMonth()
  if (stats.daily.date !== d) stats.daily = { input: 0, output: 0, thinking: 0, generations: 0, cost: 0, date: d }
  if (stats.monthly.month !== m) stats.monthly = { input: 0, output: 0, thinking: 0, generations: 0, cost: 0, month: m }
  for (const s of [stats.overall, stats.daily, stats.monthly]) {
    s.input += tokens.input || 0
    s.output += tokens.output || 0
    s.thinking += tokens.thinking || 0
    s.cost += tokens.cost || 0
    s.generations++
  }
}

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

// POST /venpa/suggest — pure constraint solver, no AI involved.
// Returns per-foot suggestions for fixing வெண்டளை violations.
app.post('/venpa/suggest', async (req, res) => {
  const { verse } = req.body
  if (!verse) return res.status(400).json({ error: 'verse required' })
  try {
    const xml = await callParser(verse)
    const analysis = await parseXML(xml)
    res.json({ suggestions: getSuggestions(analysis), runs: getRunSuggestions(analysis) })
  } catch (err) {
    res.status(500).json({ error: err.message })
  }
})

app.patch('/compositions/:id', (req, res) => {
  const token = req.headers['x-dev-token'] || req.query.token
  if (!process.env.DEV_TOKEN || token !== process.env.DEV_TOKEN) {
    return res.status(401).json({ error: 'unauthorized' })
  }
  const { is_public } = req.body
  if (typeof is_public !== 'boolean') return res.status(400).json({ error: 'is_public required' })
  setCompositionPublic(req.params.id, is_public)
  res.json({ ok: true })
})

app.patch('/generation-log/:id', (req, res) => {
  const { manuallyFixedVerse } = req.body
  if (!manuallyFixedVerse) return res.status(400).json({ error: 'manuallyFixedVerse required' })
  updateManualFix(parseInt(req.params.id), manuallyFixedVerse)
  res.json({ ok: true })
})

app.get('/admin/generation-log', (req, res) => {
  const token = req.headers['x-dev-token'] || req.query.token
  if (!process.env.DEV_TOKEN || token !== process.env.DEV_TOKEN) {
    return res.status(401).json({ error: 'unauthorized' })
  }
  const page = Math.max(1, parseInt(req.query.page) || 1)
  const limit = Math.min(100, Math.max(1, parseInt(req.query.limit) || 20))
  const { rows, total } = listGenerationLog(page, limit)
  res.json({ logs: rows, total, page, pages: Math.ceil(total / limit) })
})

app.post('/ai/event', (req, res) => {
  const { type } = req.body
  if (type === 'fix_click') incrementFixClick()
  res.json({ ok: true })
})

app.get('/admin/stats', (req, res) => {
  const token = req.headers['x-dev-token'] || req.query.token
  if (!process.env.DEV_TOKEN || token !== process.env.DEV_TOKEN) {
    return res.status(401).json({ error: 'unauthorized' })
  }
  res.json({ stats: getDailyStats(60), totals: getStatsTotals() })
})

app.get('/admin/compositions', (req, res) => {
  const token = req.headers['x-dev-token'] || req.query.token
  if (!process.env.DEV_TOKEN || token !== process.env.DEV_TOKEN) {
    return res.status(401).json({ error: 'unauthorized' })
  }
  const page = Math.max(1, parseInt(req.query.page) || 1)
  const limit = Math.min(100, Math.max(1, parseInt(req.query.limit) || 50))
  const { rows, total } = listCompositions(page, limit)
  const sourceCounts = getSourceCounts()
  res.json({ compositions: rows, total, page, pages: Math.ceil(total / limit), limit, sourceCounts })
})

app.get('/compositions/public/random', (req, res) => {
  const comp = getRandomPublicComposition()
  if (!comp) return res.status(404).json({ error: 'none found' })
  res.json(comp)
})

app.get('/compositions/public', (req, res) => {
  const page = Math.max(1, parseInt(req.query.page) || 1)
  const limit = Math.min(50, Math.max(1, parseInt(req.query.limit) || 20))
  const { rows, total } = getPublicCompositions(page, limit)
  res.json({ compositions: rows, total, page, pages: Math.ceil(total / limit) })
})

app.post('/compositions', (req, res) => {
  const { verse, metre, source, is_public, prompt, log_id } = req.body
  if (!verse || !verse.trim()) return res.status(400).json({ error: 'verse required' })
  const id = saveComposition(verse.trim(), metre, source, is_public, prompt, log_id)
  res.json({ id })
})

app.get('/compositions/:id', (req, res) => {
  const comp = getComposition(req.params.id)
  if (!comp) return res.status(404).json({ error: 'not found' })
  res.json(comp)
})

app.get('/health', (_req, res) => {
  res.json({ status: 'ok', model: process.env.GEMINI_MODEL, stats })
})


app.get('/ai/usage', (req, res) => {
  const isDev = process.env.DEV_TOKEN && req.headers['x-dev-token'] === process.env.DEV_TOKEN
  if (isDev) return res.json({ used: 0, remaining: 999, globalRemaining: 999, globalLimit: DAILY_GLOBAL_LIMIT })
  const session = getSessionUsage(req)
  const globalCount = globalUsage.date === today() ? globalUsage.count : 0
  const globalRemaining = Math.max(0, DAILY_GLOBAL_LIMIT - globalCount)
  if (!session) return res.json({ used: 0, remaining: FREE_LIMIT, globalRemaining, globalLimit: DAILY_GLOBAL_LIMIT })
  res.json({ used: session.count, remaining: Math.max(0, FREE_LIMIT - session.count), globalRemaining, globalLimit: DAILY_GLOBAL_LIMIT })
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
  const isDev = process.env.DEV_TOKEN && req.headers['x-dev-token'] === process.env.DEV_TOKEN

  if (!isDev) {
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
  }

  const remaining = isDev ? 999 : FREE_LIMIT - (getCount(sessionId))
  const globalRemaining = isDev ? 999 : Math.max(0, DAILY_GLOBAL_LIMIT - globalUsage.count)

  res.setHeader('Content-Type', 'text/event-stream')
  res.setHeader('Cache-Control', 'no-cache')
  res.setHeader('Connection', 'keep-alive')
  res.flushHeaders()

  const emit = (data) => new Promise(resolve => {
    res.write(`data: ${JSON.stringify(data)}\n\n`)
    resolve()
  })

  try {
    const result = await runLoop({ mode, topic, verse, verseType, lang, emit })
    if (result?.tokens) {
      recordTokens(result.tokens)
      recordDailyStat({
        mode,
        attempts: result.iterations?.length || 1,
        firstTry: (result.iterations?.length || 1) === 1 && result.success,
        success: result.success,
        tokens: result.tokens
      })
      const logId = saveGenerationLog({
        mode,
        verseType,
        prompt: mode === 'generate' ? topic : verse,
        attempts: result.iterations?.length || 1,
        success: result.success,
        finalVerse: result.verse,
        iterationsJson: JSON.stringify((result.iterations || []).map(i => ({ attempt: i.attempt, verse: i.verse, errors: i.errors, feedback: i.feedback || null }))),
        sandhi: result.sandhi,
        literal: result.literal,
        explanation: result.explanation,
        cost: result.tokens.cost,
        model: process.env.GEMINI_MODEL || 'gemini-2.5-flash',
        thinkingLevel: process.env.GEMINI_THINKING_LEVEL || 'minimal',
        finalMetre: result.metreType || null,
        inputErrorCount: result.inputErrorCount ?? null
      })
      await emit({ type: 'log_id', id: logId })
      await emit({ type: 'tokens', ...result.tokens })
    }
    await emit({ type: 'usage', remaining, globalRemaining })
  } catch (err) {
    await emit({ type: 'error', message: err.message })
  }

  res.end()
})

const PORT = process.env.PORT || 3001
app.listen(PORT, '127.0.0.1', () => {
  console.log(`Avalokitam AI backend running on port ${PORT}`)
  console.log(`Gemini model: ${process.env.GEMINI_MODEL}`)
  console.log(`PHP API: ${process.env.PHP_API_URL}`)
})
