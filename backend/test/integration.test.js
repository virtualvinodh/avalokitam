'use strict'
// Integration tests — require the PHP parser to be running on PHP_API_URL.
// Run with: node --test backend/test/integration.test.js
// (or npm test from backend/)

const { describe, it, before } = require('node:test')
const assert = require('node:assert/strict')
require('dotenv').config()
const { callParser, parseXML } = require('../geminiLoop')
const { getRunSuggestions } = require('../errorFeedback')

// ─── helpers ─────────────────────────────────────────────────────────────────

async function getSuggestions (verse) {
  const xml = await callParser(verse)
  const analysis = await parseXML(xml)
  return getRunSuggestions(analysis)
}

// Apply one solution from a run to the verse and return the new verse string.
function applyFix (verse, sol) {
  const lines = verse.split('\n').map(l => l.split(' '))
  sol.filter(s => s.changed).forEach(s => { lines[s.li][s.fi] = s.foot })
  return lines.map(l => l.join(' ')).join('\n')
}

// Verify a specific run's bond error is gone after applying a fix.
async function assertFixWorks (verse, run, sol, label) {
  const fixed = applyFix(verse, sol)
  const remaining = await getSuggestions(fixed)
  const stillBroken = remaining.some(r =>
    r.positions.some(p => run.positions.some(rp => rp.li === p.li && rp.fi === p.fi))
  )
  assert.ok(!stillBroken, `${label}: bond error at ${run.positions.map(p => `L${p.li + 1}F${p.fi + 1}`).join(',')} still present after fix`)
}

// Assert every solution option for every run in a verse actually fixes its bond.
async function assertAllOptionsWork (verse, expectedRuns) {
  const runs = await getSuggestions(verse)
  if (expectedRuns !== undefined) {
    assert.ok(runs.length >= expectedRuns, `expected at least ${expectedRuns} error run(s), got ${runs.length}`)
  }
  assert.ok(runs.length > 0, 'verse should have at least one bond error to test')

  for (const [ri, run] of runs.entries()) {
    assert.ok(run.solutions.length > 0, `run ${ri + 1} should have solutions`)
    for (const [si, sol] of run.solutions.entries()) {
      await assertFixWorks(verse, run, sol, `Run${ri + 1} Option${si + 1}`)
    }
  }
}

// ─── tests ───────────────────────────────────────────────────────────────────

describe('getRunSuggestions — integration tests', () => {

  it('T1: single விளம்→புளிமா bond error — all options fix it', async () => {
    // கருவிளம்→புளிமா: விளம்-class needs நேர் next, புளிமா starts நிரை ✗
    const verse = [
      'தேமா கருவிளம் புளிமா தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

  it('T2: single மா→தேமா bond error — all options fix it', async () => {
    // புளிமா→தேமா: மா-class needs நிரை next, தேமா starts நேர் ✗
    const verse = [
      'கருவிளம் புளிமா தேமா கருவிளம்',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

  it('T3: bond error before last foot நாள் — all options fix it', async () => {
    // தேமா before நாள்: நாள் starts நேர் → preceding must be விளம்/காய்-class
    // தேமா is மா-class ✗
    const verse = [
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'கருவிளம் தேமா நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

  it('T4: 2-foot bad-bond chain — all options fix both bonds', async () => {
    // கருவிளம்→புளிமா→தேமா: double break in the same run
    const verse = [
      'தேமா கருவிளம் புளிமா தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

  it('T5: காய்-class foot in error chain — all options fix it', async () => {
    // கூவிளங்காய்(காய்) → புளிமா: காய் needs நேர் next, புளிமா starts நிரை ✗
    const verse = [
      'தேமா கூவிளங்காய் புளிமா கருவிளம்',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

  it('T6: two independent errors in same verse — each option fixes its own run', async () => {
    const verse = [
      'தேமா கருவிளம் புளிமா தேமா',
      'தேமா கருவிளம் புளிமா தேமா',
      'தேமா கருவிளம் கருவிளம் தேமா',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

  it('T7: clean verse produces no error runs', async () => {
    // A manually verified clean வெண்டளை chain
    const verse = [
      'தேமா கருவிளம் தேமா கருவிளம்',
      'தேமா கருவிளம் தேமா கருவிளம்',
      'தேமா கருவிளம் தேமா கருவிளம்',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    const runs = await getSuggestions(verse)
    assert.equal(runs.length, 0, `expected clean verse, got ${runs.length} error run(s)`)
  })

  it('T8: குறள் வெண்பா (2-line) bond error — fix works', async () => {
    // 2-line குறள்: 4+3 feet
    const verse = [
      'தேமா கருவிளம் புளிமா கருவிளம்',
      'தேமா கருவிளம் நாள்'
    ].join('\n')
    await assertAllOptionsWork(verse)
  })

})
