const MA_FEET = ['தேமா', 'புளிமா']
const VILAM_FEET = ['கூவிளம்', 'கருவிளம்']
const KAY_FEET = ['தேமாங்காய்', 'புளிமாங்காய்', 'கூவிளங்காய்', 'கருவிளங்காய்']
const ALL_VALID_FEET = [...MA_FEET, ...VILAM_FEET, ...KAY_FEET]

const FOOT_PATTERN = {
  'தேமா': 'நேர்-நேர்',
  'புளிமா': 'நிரை-நேர்',
  'கூவிளம்': 'நேர்-நிரை',
  'கருவிளம்': 'நிரை-நிரை',
  'தேமாங்காய்': 'நேர்-நேர்-நேர்',
  'புளிமாங்காய்': 'நிரை-நேர்-நேர்',
  'கூவிளங்காய்': 'நேர்-நிரை-நேர்',
  'கருவிளங்காய்': 'நிரை-நிரை-நேர்'
}

const LAST_FOOT_TYPES = ['நாள்', 'மலர்', 'காசு', 'பிறப்பு']
const LAST_FOOT_NIRAI = ['மலர்', 'பிறப்பு'] // start நிரை → need மா-type before them
const LAST_FOOT_NER   = ['நாள்', 'காசு']    // start நேர்  → need விளம்/காய்-type before them

function firstSylOfFoot (footName) {
  if (LAST_FOOT_NIRAI.includes(footName)) return 'நிரை'
  if (LAST_FOOT_NER.includes(footName)) return 'நேர்'
  const niraiFirst = ['புளிமா', 'கருவிளம்', 'புளிமாங்காய்', 'கருவிளங்காய்']
  return niraiFirst.includes(footName) ? 'நிரை' : 'நேர்'
}

function footClass (footName) {
  if (MA_FEET.includes(footName)) return 'மா'
  if (VILAM_FEET.includes(footName)) return 'விளம்'
  if (KAY_FEET.includes(footName)) return 'காய்'
  return null
}

function bondValid (prevName, currName) {
  const cls = footClass(prevName)
  const cf = firstSylOfFoot(currName)
  if (cls === 'மா') return cf === 'நிரை'
  if (cls === 'விளம்') return cf === 'நேர்'
  if (cls === 'காய்') return cf === 'நேர்'
  return false
}


function footLabel (foot) {
  const syls = foot.syllables.map(s => `${s.text}(${s.type})`).join(' ')
  return `${foot.footName} [${syls}]`
}

// Find all valid foot-name sequences of length chainLength where:
//   bondValid(leftAnchorName, seq[0]) AND
//   bondValid(seq[i], seq[i+1]) for all i AND
//   bondValid(seq[-1], rightAnchorName) if rightAnchorName is given
//
// Returns all valid foot-name sequences of length chainLength (up to MAX_TOTAL).
// For short chains this is exhaustive; for long ones the variety-first DFS
// surfaces the most diverse solutions first before hitting the cap.
function findChainFixes (leftAnchorName, chainLength, rightAnchorName) {
  const MAX_TOTAL = 16

  const anchorCls = footClass(leftAnchorName)
  const anchorReq = anchorCls === 'மா' ? 'நிரை' : (anchorCls === 'விளம்' || anchorCls === 'காய்') ? 'நேர்' : null
  if (!anchorReq) return []

  const validFirstFeet = ALL_VALID_FEET.filter(f => firstSylOfFoot(f) === anchorReq)
  const perBucket = Math.max(1, Math.ceil(MAX_TOTAL / validFirstFeet.length))
  const solutions = []

  for (const firstFoot of validFirstFeet) {
    const bucketStart = solutions.length

    function dfs (pos, prevName, path) {
      if (solutions.length - bucketStart >= perBucket) return
      if (pos === chainLength) {
        if (!rightAnchorName || bondValid(prevName, rightAnchorName)) {
          solutions.push([...path])
        }
        return
      }
      const cls = footClass(prevName)
      const req = cls === 'மா' ? 'நிரை' : (cls === 'விளம்' || cls === 'காய்') ? 'நேர்' : null
      if (!req) return
      // prefer feet different from prevName first to avoid boring all-same chains
      const candidates = ALL_VALID_FEET.filter(f => firstSylOfFoot(f) === req)
      const ordered = [...candidates.filter(f => f !== prevName), ...candidates.filter(f => f === prevName)]
      for (const foot of ordered) {
        path.push(foot)
        dfs(pos + 1, foot, path)
        path.pop()
      }
    }

    dfs(1, firstFoot, [firstFoot])
  }

  return solutions
}

function formatFeedback (analysis) {
  const { metreDetected, lines, violations } = analysis
  const sections = []

  // ── 1. Line count ──
  const totalLines = lines.length
  const expectedLines = 4
  if (totalLines !== expectedLines) {
    const diff = expectedLines - totalLines
    sections.push(
      `LINE COUNT: got ${totalLines} lines, need ${expectedLines} — ` +
      (diff > 0 ? `add ${diff} line(s)` : `remove ${Math.abs(diff)} line(s)`)
    )
  }

  // ── 2. Foot count per line ──
  const footCountErrors = []
  lines.forEach((line, li) => {
    if (!line.lineOk) {
      const diff = line.expectedFeet - line.footCount
      footCountErrors.push(
        `  Line ${li + 1}: got ${line.footCount} feet, need ${line.expectedFeet} — ` +
        (diff > 0 ? `add ${diff} சீர்` : `remove ${Math.abs(diff)} சீர்`)
      )
    }
  })
  if (footCountErrors.length) {
    sections.push('FOOT COUNT ERRORS:\n' + footCountErrors.join('\n'))
  }

  // ── 3. Invalid foot types ──
  const footTypeErrors = []
  lines.forEach((line, li) => {
    line.feet.forEach((foot, fi) => {
      if (!foot.footOk) {
        if (foot.isLastFoot) {
          footTypeErrors.push(
            `  Line ${li + 1}, Foot ${fi + 1}: ${footLabel(foot)} ✗ — last foot of last line must be நாள்/மலர்/காசு/பிறப்பு`
          )
        } else {
          footTypeErrors.push(
            `  Line ${li + 1}, Foot ${fi + 1}: ${footLabel(foot)} ✗ — replace with ஈரசைச்சீர் (${MA_FEET.join('/')}/${VILAM_FEET.join('/')}) or காய்ச்சீர் (${KAY_FEET.join('/')})`
          )
        }
      }
    })
  })
  if (footTypeErrors.length) {
    sections.push('INVALID FOOT TYPES:\n' + footTypeErrors.join('\n'))
  }

  // ── 4. Bond error chains (minimum-change solutions) ──
  const flatFeet = []
  lines.forEach((line, li) => {
    line.feet.forEach((foot, fi) => flatFeet.push({ foot, li, fi }))
  })

  const chainMessages = []
  let i = 0
  while (i < flatFeet.length) {
    if (!flatFeet[i].foot.bond || flatFeet[i].foot.bondOk) { i++; continue }

    const runStart = i
    while (i < flatFeet.length && flatFeet[i].foot.bond && !flatFeet[i].foot.bondOk) i++

    const runFeet = flatFeet.slice(runStart, i)
    const first = runFeet[0]
    const last = runFeet[runFeet.length - 1]
    const n = runFeet.length

    // Find left anchor entry and prevOfAnchor
    let anchorEntry = null
    let prevOfAnchorName = null
    if (first.fi > 0) {
      const ancFi = first.fi - 1
      anchorEntry = { li: first.li, fi: ancFi, footName: lines[first.li].feet[ancFi].footName }
      if (ancFi > 0) prevOfAnchorName = lines[first.li].feet[ancFi - 1].footName
      else if (first.li > 0) { const pl = lines[first.li - 1]; prevOfAnchorName = pl.feet[pl.feet.length - 1].footName }
    } else if (first.li > 0) {
      const prevLi = first.li - 1
      const prevFi = lines[prevLi].feet.length - 1
      anchorEntry = { li: prevLi, fi: prevFi, footName: lines[prevLi].feet[prevFi].footName }
      if (prevFi > 0) prevOfAnchorName = lines[prevLi].feet[prevFi - 1].footName
      else if (prevLi > 0) { const pl = lines[prevLi - 1]; prevOfAnchorName = pl.feet[pl.feet.length - 1].footName }
    }

    const rightAnchorFoot = i < flatFeet.length ? flatFeet[i].foot : null
    const rightAnchorName = rightAnchorFoot ? rightAnchorFoot.footName : null

    let loc
    if (n === 1) loc = `Line ${first.li + 1}, Foot ${first.fi + 1}`
    else if (first.li === last.li) loc = `Line ${first.li + 1}, Feet ${first.fi + 1}–${last.fi + 1}`
    else loc = `Line ${first.li + 1} Foot ${first.fi + 1} – Line ${last.li + 1} Foot ${last.fi + 1}`

    let msg = `  ${loc}:\n`
    msg += `    Bad foot${n > 1 ? 's' : ''}: ${runFeet.map(e => `"${e.foot.footName}" (Line ${e.li + 1} Foot ${e.fi + 1})`).join(', ')}\n`

    // Special case: bond error into last foot
    if (rightAnchorFoot && LAST_FOOT_TYPES.includes(rightAnchorFoot.footName)) {
      const needMaa = LAST_FOOT_NIRAI.includes(rightAnchorFoot.footName)
      const validBefore = needMaa ? MA_FEET : [...VILAM_FEET, ...KAY_FEET]
      const aEntry = flatFeet[runStart - 1]
      const aLoc = aEntry ? `Line ${aEntry.li + 1} Foot ${aEntry.fi + 1}` : 'the preceding foot'
      msg += `    The last foot of the verse is "${rightAnchorFoot.footName}", which starts with ${needMaa ? 'நிரை' : 'நேர்'} — so the foot before it must be ${needMaa ? 'மா-class' : 'விளம்/காய்-class'}.\n`
      msg += `    Fix: replace ${aLoc} with a Tamil word of one of these types: ${validBefore.join(' / ')}\n`
    } else if (anchorEntry) {
      const anchorCls = footClass(anchorEntry.footName)
      const anchorReq = anchorCls === 'மா' ? 'நிரை' : 'நேர்'
      const chainStr = n === 1
        ? `"${runFeet[0].foot.footName}"`
        : `[${runFeet.map(e => `"${e.foot.footName}"`).join(' → ')}]`
      msg += `    bad-bond chain: ${chainStr}\n`
      msg += `    Left anchor: "${anchorEntry.footName}" (Line ${anchorEntry.li + 1} Foot ${anchorEntry.fi + 1}, ${anchorCls}-class, needs ${anchorReq} next)`
      if (rightAnchorName) msg += ` | Right anchor: "${rightAnchorName}"`
      msg += '\n'

      const candidates = [anchorEntry, ...runFeet.map(e => ({ li: e.li, fi: e.fi, footName: e.foot.footName }))]
      const { minChanges, solutions } = findMinChangeSolutions(prevOfAnchorName, candidates, rightAnchorName)

      if (solutions.length === 0) {
        msg += `    No valid chain found — rewrite the surrounding feet.\n`
      } else {
        msg += `    Minimum fix — ${minChanges} change${minChanges !== 1 ? 's' : ''}:\n`
        solutions.slice(0, 6).forEach((sol, k) => {
          const changeDesc = sol.filter(s => s.changed)
            .map(s => `Line ${s.li + 1} Foot ${s.fi + 1} → ${s.foot} (${FOOT_PATTERN[s.foot]})`)
          msg += `      Option ${k + 1}: ${changeDesc.join('; ')}\n`
        })
        msg += `    Replace each marked foot with a new Tamil word of that type — do not reuse the existing word.\n`
      }
    } else {
      msg += `    No preceding foot found — rewrite from the start of the line.\n`
    }

    chainMessages.push(msg)
  }

  if (chainMessages.length) {
    sections.push('BOND ERRORS (வெண்டளை violations):\n' + chainMessages.join('\n'))
  }

  // ── 5. Rule violations (catch-all) ──
  if (violations.length) {
    const vtext = violations.map(v => `  - ${v.rule}: ${v.result}`).join('\n')
    sections.push('OTHER RULE VIOLATIONS:\n' + vtext)
  }

  return `METRE DETECTED: ${metreDetected}\n\n` + sections.join('\n\n')
}

// Returns per-foot constraint suggestions as structured JSON (no text formatting).
// For each foot with a bad bond, gives: what to change THIS foot to, or what to
// change the PREVIOUS foot to, so that the bond becomes valid.
function getSuggestions (analysis) {
  const { lines } = analysis
  const flatFeet = []
  lines.forEach((line, li) => {
    line.feet.forEach((foot, fi) => flatFeet.push({ foot, li, fi }))
  })

  const result = []

  flatFeet.forEach((entry, idx) => {
    const { foot, li, fi } = entry
    if (!foot.bond || foot.bondOk) return

    const prevFoot = idx > 0 ? flatFeet[idx - 1].foot : null
    const prevPrevFoot = idx >= 2 ? flatFeet[idx - 2].foot : null
    const nextFoot = idx < flatFeet.length - 1 ? flatFeet[idx + 1].foot : null

    // Option A: change THIS foot — must bond with prev AND with next (if any)
    const changeThis = prevFoot && footClass(prevFoot.footName)
      ? ALL_VALID_FEET.filter(f =>
          bondValid(prevFoot.footName, f) &&
          (!nextFoot || bondValid(f, nextFoot.footName))
        )
      : []

    // Option B: change PREV foot — must bond with its prev AND with this foot
    const changePrev = prevFoot && footClass(prevFoot.footName)
      ? (prevPrevFoot && footClass(prevPrevFoot.footName)
          ? findChainFixes(prevPrevFoot.footName, 1, foot.footName).map(s => s[0])
          : ALL_VALID_FEET.filter(f => bondValid(f, foot.footName)))
      : []

    result.push({
      li,
      fi,
      footName: foot.footName,
      prevFootName: prevFoot ? prevFoot.footName : null,
      suggestions: {
        changeThis: [...new Set(changeThis)],
        changePrev: [...new Set(changePrev)]
      }
    })
  })

  return result
}

// Finds minimal-change solutions for a bad-bond run.
// Searches over [anchor, run[0], ..., run[N-1]] — at each position tries keeping
// the original foot first (prefer no change), then alternatives. Groups by change
// count and returns only the minimum-change solutions (up to MAX_SOLUTIONS).
function findMinChangeSolutions (prevAnchorName, candidates, rightAnchorName) {
  const MAX_SOLUTIONS = 16
  const byCount = {}
  let globalMin = Infinity

  function dfs (idx, prevName, path, changes) {
    if (changes > globalMin) return
    if (byCount[globalMin] && byCount[globalMin].length >= MAX_SOLUTIONS) return

    if (idx === candidates.length) {
      if (!rightAnchorName || bondValid(prevName, rightAnchorName)) {
        if (changes < globalMin) {
          for (const k of Object.keys(byCount)) {
            if (Number(k) > changes) delete byCount[k]
          }
          globalMin = changes
        }
        if (!byCount[changes]) byCount[changes] = []
        if (byCount[changes].length < MAX_SOLUTIONS) {
          byCount[changes].push(path.slice())
        }
      }
      return
    }

    const { footName: original, li, fi } = candidates[idx]

    // Try keeping original first — prefer minimal changes
    if (bondValid(prevName, original)) {
      path.push({ li, fi, foot: original, changed: false })
      dfs(idx + 1, original, path, changes)
      path.pop()
    }

    // Try alternatives (variety-first: prefer feet different from prevName)
    const cls = footClass(prevName)
    const req = cls === 'மா' ? 'நிரை' : (cls === 'விளம்' || cls === 'காய்') ? 'நேர்' : null
    if (req) {
      const alts = ALL_VALID_FEET.filter(f => f !== original && firstSylOfFoot(f) === req)
      const ordered = [...alts.filter(f => f !== prevName), ...alts.filter(f => f === prevName)]
      for (const foot of ordered) {
        path.push({ li, fi, foot, changed: true })
        dfs(idx + 1, foot, path, changes + 1)
        path.pop()
      }
    }
  }

  if (prevAnchorName) {
    dfs(0, prevAnchorName, [], 0)
  } else if (candidates.length > 0) {
    // No left constraint on anchor — keep it fixed, search run feet only
    const anc = candidates[0]
    dfs(1, anc.footName, [{ li: anc.li, fi: anc.fi, foot: anc.footName, changed: false }], 0)
  }

  // Rank equal-change solutions by ease of substitution (lower = easier to fix).
  // Shorter feet have more available Tamil words: 2-syllable மா feet > 3-syllable விளம் feet > 4-syllable காய் feet.
  const FOOT_TIER = {
    'தேமா': 1, 'புளிமா': 1,
    'கூவிளம்': 2, 'கருவிளம்': 2,
    'தேமாங்காய்': 3, 'புளிமாங்காய்': 3, 'கூவிளங்காய்': 3, 'கருவிளங்காய்': 3
  }
  function scoreSolution (sol) {
    return sol.filter(s => s.changed).reduce((sum, s) => sum + (FOOT_TIER[s.foot] || 4), 0)
  }

  const raw = globalMin === Infinity ? [] : (byCount[globalMin] || [])
  raw.sort((a, b) => scoreSolution(a) - scoreSolution(b))

  return {
    minChanges: globalMin === Infinity ? 0 : globalMin,
    solutions: raw
  }
}

// Returns bad-bond runs with minimal-change solutions.
// Each solution is an array of {li, fi, foot, changed} covering [anchor, ...runFeet],
// where changed=true marks positions that need to be replaced.
function getRunSuggestions (analysis) {
  const { lines } = analysis
  const flatFeet = []
  lines.forEach((line, li) => {
    line.feet.forEach((foot, fi) => flatFeet.push({ foot, li, fi }))
  })

  const runs = []
  let i = 0
  while (i < flatFeet.length) {
    if (!flatFeet[i].foot.bond || flatFeet[i].foot.bondOk) { i++; continue }

    const runStart = i
    while (i < flatFeet.length && flatFeet[i].foot.bond && !flatFeet[i].foot.bondOk) i++

    const runFeet = flatFeet.slice(runStart, i)
    const first = runFeet[0]

    let leftAnchor = null
    let prevOfAnchorName = null

    if (first.fi > 0) {
      const ancFi = first.fi - 1
      leftAnchor = { li: first.li, fi: ancFi, footName: lines[first.li].feet[ancFi].footName }
      if (ancFi > 0) {
        prevOfAnchorName = lines[first.li].feet[ancFi - 1].footName
      } else if (first.li > 0) {
        const pl = lines[first.li - 1]
        prevOfAnchorName = pl.feet[pl.feet.length - 1].footName
      }
    } else if (first.li > 0) {
      const prevLi = first.li - 1
      const prevFi = lines[prevLi].feet.length - 1
      leftAnchor = { li: prevLi, fi: prevFi, footName: lines[prevLi].feet[prevFi].footName }
      if (prevFi > 0) {
        prevOfAnchorName = lines[prevLi].feet[prevFi - 1].footName
      } else if (prevLi > 0) {
        const pl = lines[prevLi - 1]
        prevOfAnchorName = pl.feet[pl.feet.length - 1].footName
      }
    }

    const rightEntry = i < flatFeet.length ? flatFeet[i] : null
    const rightAnchorName = rightEntry ? rightEntry.foot.footName : null

    // candidates = [anchor, run[0], ..., run[N-1]]
    const candidates = []
    if (leftAnchor) {
      candidates.push({ li: leftAnchor.li, fi: leftAnchor.fi, footName: leftAnchor.footName })
    }
    runFeet.forEach(e => candidates.push({ li: e.li, fi: e.fi, footName: e.foot.footName }))

    // For long runs, solve only the first 2 feet (use run[2] as right anchor).
    // The user fixes that pair, re-scans, and gets the next step automatically.
    const PAIRWISE_THRESHOLD = 4
    const isPartial = leftAnchor && runFeet.length > PAIRWISE_THRESHOLD
    const effectiveCandidates = isPartial ? candidates.slice(0, 3) : candidates
    const effectiveRightAnchor = isPartial ? runFeet[2].foot.footName : rightAnchorName

    const { minChanges, solutions } = leftAnchor
      ? findMinChangeSolutions(prevOfAnchorName, effectiveCandidates, effectiveRightAnchor)
      : { minChanges: 0, solutions: [] }

    runs.push({
      positions: runFeet.map(e => ({ li: e.li, fi: e.fi })),
      leftAnchor,
      minChanges,
      solutions,
      isPartial,
      totalLength: runFeet.length
    })
  }

  return runs
}

module.exports = { formatFeedback, getSuggestions, getRunSuggestions, findMinChangeSolutions }
