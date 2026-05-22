
const axios = require('axios')
const xml2js = require('xml2js')
const { buildGeneratePrompt, buildFixPrompt, buildFeedbackPrompt, buildPolishPrompt, buildSandhiAndExplainPrompt } = require('./systemPrompt')
const { formatFeedback } = require('./errorFeedback')

const MAX_ITERATIONS = 5
const POLISH_ENABLED = false

// Gemini 2.5 Pro stepped pricing: threshold based on prompt size
const PRICE = {
  short: { input: 1.25, output: 10.00 }, // prompts <= 200k tokens
  long:  { input: 2.50, output: 15.00 }  // prompts >  200k tokens
}

function tokenCost (usage) {
  const inputTokens = usage.promptTokenCount || 0
  const outputAndThinking = (usage.candidatesTokenCount || 0) + (usage.thoughtsTokenCount || 0)
  const tier = inputTokens > 200_000 ? PRICE.long : PRICE.short
  return inputTokens / 1e6 * tier.input + outputAndThinking / 1e6 * tier.output
}

function addUsage (acc, u) {
  acc.promptTokenCount     += u.promptTokenCount     || 0
  acc.candidatesTokenCount += u.candidatesTokenCount || 0
  acc.thoughtsTokenCount   += u.thoughtsTokenCount   || 0
}

async function callGemini (prompt, thinkingBudget = 1024, label = '') {
  const model = process.env.GEMINI_MODEL || 'gemini-2.5-pro'
  const url = `https://generativelanguage.googleapis.com/v1beta/models/${model}:generateContent?key=${process.env.GEMINI_API_KEY}`
  const body = {
    contents: [{ role: 'user', parts: [{ text: prompt }] }],
    generationConfig: { thinkingConfig: { thinkingBudget } }
  }
  const RETRIES = 3
  for (let attempt = 1; attempt <= RETRIES; attempt++) {
    try {
      const resp = await axios.post(url, body, { headers: { 'Content-Type': 'application/json' }, timeout: 120000 })
      const parts = resp.data.candidates?.[0]?.content?.parts || []
      const text = parts.map(p => p.text || '').join('').trim()
        .replace(/^```[\w]*\n?/gm, '').replace(/```$/gm, '').trim()
      const u = resp.data.usageMetadata || {}
      const cost = tokenCost(u)
      console.log(
        `[gemini${label ? ' ' + label : ''}] in:${u.promptTokenCount || 0} out:${u.candidatesTokenCount || 0} think:${u.thoughtsTokenCount || 0} → $${cost.toFixed(5)}`
      )
      return { text, thinking: '', usage: u }
    } catch (err) {
      const status = err.response?.status
      if ((status === 503 || status === 429) && attempt < RETRIES) {
        await new Promise(r => setTimeout(r, 2000 * attempt))
        continue
      }
      throw err
    }
  }
}

async function callParser (verse, lang = 'ta') {
  const params = new URLSearchParams()
  params.append('verse', verse)
  params.append('lang', lang)
  params.append('kurilu', '0')
  params.append('vencheck', '0')

  const response = await axios.post(process.env.PHP_API_URL, params, {
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    timeout: 15000
  })
  return response.data
}

const VALID_FEET_VENPA = ['தேமா', 'புளிமா', 'கூவிளம்', 'கருவிளம்', 'கூவிளங்காய்', 'கருவிளங்காய்', 'புளிமாங்காய்', 'தேமாங்காய்']
const VALID_END_FEET_VENPA = ['நாள்', 'மலர்', 'காசு', 'பிறப்பு']

async function parseXML (xmlString) {
  const parser = new xml2js.Parser({ explicitArray: true })
  const result = await parser.parseStringPromise(xmlString)
  const verse = result.verse
  const rawLines = verse.MetricalLine || []
  const totalLines = rawLines.length

  // VenpaLastWordClass is the authoritative வெண்பா-specific classification for the last foot
  const lwcRaw = verse.VenpaLastWordClass
  const venpaLastWordClass = lwcRaw
    ? (typeof lwcRaw[0] === 'object' ? lwcRaw[0]._ : lwcRaw[0]) || ''
    : ''

  const lines = rawLines.map((line, li) => {
    const lineType = line.$.type || ''
    const isLastLine = li === totalLines - 1
    const expectedLineType = isLastLine ? 'சிந்தடி' : 'அளவடி'
    const rawFeet = line.MetricalFoot || []

    const feet = rawFeet.map((foot, fi) => {
      const footName = foot.$.class || ''
      const bond = foot.$.linkage || null
      const isLastFoot = isLastLine && fi === rawFeet.length - 1
      return {
        footName: isLastFoot ? venpaLastWordClass || footName : footName,
        bond,
        bondOk: bond ? bond.includes('வெண்டளை') : true,
        isLastFoot,
        footOk: isLastFoot ? VALID_END_FEET_VENPA.includes(venpaLastWordClass) : VALID_FEET_VENPA.includes(footName),
        syllables: (foot.Metreme || []).map(m => ({
          text: m._,
          type: m.$.type === 'நேர்' ? 'நே' : m.$.type === 'நிரை' ? 'நி' : 'பு'
        }))
      }
    })

    return {
      lineType,
      isLastLine,
      expectedLineType,
      lineOk: lineType === expectedLineType,
      footCount: rawFeet.length,
      expectedFeet: isLastLine ? 3 : 4,
      feet
    }
  })

  const violations = []
  for (const block of (verse.venpaa || [])) {
    const rules = block.Rule || []
    const results = block.Result || []
    for (let i = 0; i < rules.length; i++) {
      const res = results[i]
      if (rules[i] === 'பொருத்தம்') continue
      if (res != null && res !== 'true' && res !== 'TRUE' && res !== '1' && res !== 'info') {
        violations.push({ rule: rules[i], result: res })
      }
    }
  }

  return { metreDetected: verse.$.metre || 'தெரியவில்லை', lines, violations }
}


async function sendDone (send, result, totals, context = null) {
  await send({ type: 'done', ...result })
  if (result.success) {
    try {
      const { text: raw, usage } = await callGemini(buildSandhiAndExplainPrompt(result.verse, context), 128, 'explain')
      if (usage) addUsage(totals, usage)
      const meaningIdx = raw.indexOf('MEANING:')
      const sandhiIdx = raw.indexOf('SANDHI_SPLIT:')
      if (sandhiIdx !== -1 && meaningIdx !== -1) {
        const sandhi = raw.slice(sandhiIdx + 'SANDHI_SPLIT:'.length, meaningIdx).trim()
        const meaning = raw.slice(meaningIdx + 'MEANING:'.length).trim()
        if (sandhi) await send({ type: 'sandhi', text: sandhi })
        if (meaning) await send({ type: 'explanation', text: meaning })
      } else if (raw) {
        await send({ type: 'explanation', text: raw })
      }
    } catch (_) {}
  }
  const tokens = {
    input: totals.promptTokenCount,
    output: totals.candidatesTokenCount,
    thinking: totals.thoughtsTokenCount,
    cost: tokenCost(totals)
  }
  await send({ type: 'tokens', ...tokens })
  console.log(`[generation] in:${tokens.input} out:${tokens.output} think:${tokens.thinking} → $${tokens.cost.toFixed(4)}`)
  return { ...result, tokens }
}

// emit is an optional async callback(event) for streaming
async function runLoop ({ mode, verse, topic, verseType, lang, emit }) {
  const noop = () => {}
  const send = emit || noop

  const totals = { promptTokenCount: 0, candidatesTokenCount: 0, thoughtsTokenCount: 0 }
  const gemini = async (prompt, budget = 1024, label = '') => {
    const result = await callGemini(prompt, budget, label)
    if (result?.usage) addUsage(totals, result.usage)
    return result
  }
  const done = (result) => sendDone(send, result, totals, originalContext)

  const iterations = []
  let currentVerse = null
  let prompt

  const originalContext = mode === 'generate'
    ? `ORIGINAL REQUEST: compose a ${verseType} about "${topic}"`
    : `ORIGINAL VERSE TO FIX:\n${verse}`

  if (mode === 'generate') {
    prompt = buildGeneratePrompt(topic, verseType)
  } else {
    currentVerse = verse

    // Parse original verse first so the first prompt includes concrete errors
    let initialParsed = null
    try {
      const initXml = await callParser(verse, lang)
      initialParsed = await parseXML(initXml)
    } catch (_) {}

    if (initialParsed) {
      const hasErrors = initialParsed.violations.length > 0 ||
        initialParsed.lines.some(l => !l.lineOk || l.feet.some(f => !f.footOk || (f.bond && !f.bondOk)))

      if (!hasErrors) {
        // Already valid — nothing to fix
        const iter = { attempt: 1, verse, metreType: initialParsed.metreDetected, errors: [] }
        await send({ type: 'iteration', ...iter })
        return done({ success: true, verse, metreType: initialParsed.metreDetected, iterations: [iter] })
      }

      prompt = buildFixPrompt(verse, verseType, formatFeedback(initialParsed))
    } else {
      prompt = buildFixPrompt(verse, verseType)
    }
  }

  for (let attempt = 1; attempt <= MAX_ITERATIONS; attempt++) {
    // Signal that Gemini is thinking for this attempt, include the prompt for debugging
    await send({ type: 'thinking', attempt, prompt })

    let generated, thinking
    try {
      ;({ text: generated, thinking } = await gemini(prompt, 1024, `attempt ${attempt}`))
    } catch (err) {
      throw new Error(`Gemini API error on attempt ${attempt}: ${err.message}`)
    }

    currentVerse = generated

    // Signal that the parser is now checking the verse
    await send({ type: 'checking', attempt, verse: currentVerse, thinking })

    let xmlResult
    try {
      xmlResult = await callParser(currentVerse, lang)
    } catch (err) {
      const iter = { attempt, verse: currentVerse, errors: [], parserError: err.message }
      iterations.push(iter)
      await send({ type: 'iteration', ...iter })
      break
    }

    let parsed
    try {
      parsed = await parseXML(xmlResult)
    } catch (err) {
      const iter = { attempt, verse: currentVerse, errors: [], parserError: 'XML parse failed' }
      iterations.push(iter)
      await send({ type: 'iteration', ...iter })
      break
    }

    const { metreDetected: metreType, violations: errors } = parsed
    const iter = { attempt, verse: currentVerse, metreType, errors, xml: xmlResult }
    iterations.push(iter)
    await send({ type: 'iteration', ...iter })

    if (errors.length === 0) {
      return done({ success: true, verse: currentVerse, metreType, iterations })
    }

    if (POLISH_ENABLED && errors.length === 0) {
      // ── Polish phase (toggle POLISH_ENABLED at top of file to re-enable) ──
      const validVerse = currentVerse
      const validMetreType = metreType

      let nextAttempt = attempt + 1
      const polishPrompt = buildPolishPrompt(validVerse, verseType, originalContext)
      await send({ type: 'thinking', attempt: nextAttempt, prompt: polishPrompt })

      let polished, polishThinking
      try { ;({ text: polished, thinking: polishThinking } = await gemini(polishPrompt, 1024, 'polish')) } catch (_) {
        return done({ success: true, verse: validVerse, metreType: validMetreType, iterations })
      }

      await send({ type: 'checking', attempt: nextAttempt, verse: polished, thinking: polishThinking })

      let pXml, pParsed
      try {
        pXml = await callParser(polished, lang)
        pParsed = await parseXML(pXml)
      } catch (_) {
        return done({ success: true, verse: validVerse, metreType: validMetreType, iterations })
      }

      const pIter = { attempt: nextAttempt, verse: polished, metreType: pParsed.metreDetected, errors: pParsed.violations, xml: pXml }
      iterations.push(pIter)
      await send({ type: 'iteration', ...pIter })

      if (pParsed.violations.length === 0) {
        return done({ success: true, verse: polished, metreType: pParsed.metreDetected, iterations })
      }

      // Polish broke metre — try up to 3 fix attempts
      let fixVerse = polished
      let fixParsed = pParsed
      for (let f = 1; f <= 3; f++) {
        nextAttempt++
        const fixPrompt = buildFeedbackPrompt(fixVerse, verseType, formatFeedback(fixParsed), nextAttempt, originalContext)
        await send({ type: 'thinking', attempt: nextAttempt, prompt: fixPrompt })

        let fixed, fixThinking
        try { ;({ text: fixed, thinking: fixThinking } = await gemini(fixPrompt, 1024, `fix-${f}`)) } catch (_) { break }

        await send({ type: 'checking', attempt: nextAttempt, verse: fixed, thinking: fixThinking })

        let fXml, fParsed
        try {
          fXml = await callParser(fixed, lang)
          fParsed = await parseXML(fXml)
        } catch (_) { break }

        const fIter = { attempt: nextAttempt, verse: fixed, metreType: fParsed.metreDetected, errors: fParsed.violations, xml: fXml }
        iterations.push(fIter)
        await send({ type: 'iteration', ...fIter })

        if (fParsed.violations.length === 0) {
          return done({ success: true, verse: fixed, metreType: fParsed.metreDetected, iterations })
        }

        fixVerse = fixed
        fixParsed = fParsed
      }

      // Could not recover polish — fall back to pre-polish valid verse
      return done({ success: true, verse: validVerse, metreType: validMetreType, iterations })
    }

    if (attempt < MAX_ITERATIONS) {
      prompt = buildFeedbackPrompt(currentVerse, verseType, formatFeedback(parsed), attempt, originalContext)
    }
  }

  const last = iterations[iterations.length - 1]
  const result = {
    success: false,
    verse: last.verse,
    metreType: last.metreType || '',
    iterations,
    message: `Could not produce a fully valid verse after ${MAX_ITERATIONS} attempts. Best result shown.`
  }
  return done(result)
}

module.exports = { runLoop }
