const MA_FEET = ['தேமா', 'புளிமா']
const VILAM_FEET = ['கூவிளம்', 'கருவிளம்']
const KAY_FEET = ['தேமாங்காய்', 'புளிமாங்காய்', 'கூவிளங்காய்', 'கருவிளங்காய்']
const ALL_VALID_FEET = [...MA_FEET, ...VILAM_FEET, ...KAY_FEET]

function firstSylOfFoot (footName) {
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

function getPrevFoot (lines, lineIdx, footIdx) {
  if (footIdx >= 0) return lines[lineIdx].feet[footIdx]
  if (lineIdx > 0) {
    const prevLine = lines[lineIdx - 1]
    return prevLine.feet[prevLine.feet.length - 1]
  }
  return null
}

function footLabel (foot) {
  const syls = foot.syllables.map(s => `${s.text}(${s.type})`).join(' ')
  return `${foot.footName} [${syls}]`
}

// Find all valid foot-name sequences of length chainLength where:
//   bondValid(leftAnchorName, seq[0]) AND
//   bondValid(seq[i], seq[i+1]) for all i AND
//   bondValid(seq[-1], rightAnchorName) if rightAnchorName is given
function findChainFixes (leftAnchorName, chainLength, rightAnchorName) {
  const solutions = []
  const MAX = 6

  function dfs (pos, prevName, path) {
    if (solutions.length >= MAX) return
    if (pos === chainLength) {
      if (!rightAnchorName || bondValid(prevName, rightAnchorName)) {
        solutions.push([...path])
      }
      return
    }
    const cls = footClass(prevName)
    const req = cls === 'மா' ? 'நிரை' : (cls === 'விளம்' || cls === 'காய்') ? 'நேர்' : null
    if (!req) return
    for (const foot of ALL_VALID_FEET) {
      if (firstSylOfFoot(foot) === req) {
        path.push(foot)
        dfs(pos + 1, foot, path)
        path.pop()
      }
    }
  }

  dfs(0, leftAnchorName, [])
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

  // ── 4. Bond error chains ──
  // Flatten all feet with their positions, then sweep for runs of bad bonds.
  // For each run: find left anchor (foot before run) and right anchor (foot after run),
  // then enumerate all valid foot sequences that thread both anchors.
  const flatFeet = []
  lines.forEach((line, li) => {
    line.feet.forEach((foot, fi) => flatFeet.push({ foot, li, fi }))
  })

  const chainMessages = []
  let i = 0
  while (i < flatFeet.length) {
    if (!flatFeet[i].foot.bond || flatFeet[i].foot.bondOk) { i++; continue }

    // Collect the run of consecutive bond errors
    const runStart = i
    while (i < flatFeet.length && flatFeet[i].foot.bond && !flatFeet[i].foot.bondOk) i++

    const chainFeet = flatFeet.slice(runStart, i)
    const first = chainFeet[0]
    const last = chainFeet[chainFeet.length - 1]

    const leftAnchor = getPrevFoot(lines, first.li, first.fi - 1)
    const rightAnchor = i < flatFeet.length ? flatFeet[i].foot : null

    // Location label
    let loc
    if (chainFeet.length === 1) {
      loc = `Line ${first.li + 1}, Foot ${first.fi + 1}`
    } else if (first.li === last.li) {
      loc = `Line ${first.li + 1}, Feet ${first.fi + 1}–${last.fi + 1}`
    } else {
      loc = `Line ${first.li + 1} Foot ${first.fi + 1} – Line ${last.li + 1} Foot ${last.fi + 1}`
    }

    const n = chainFeet.length
    let msg = `  ${loc} (${n} consecutive invalid bond${n > 1 ? 's' : ''}):\n`
    msg += `    Left anchor : ${leftAnchor ? footLabel(leftAnchor) : 'none'}\n`
    if (rightAnchor) msg += `    Right anchor: ${footLabel(rightAnchor)}\n`
    msg += `    Current     : ${chainFeet.map(e => footLabel(e.foot)).join(' → ')} ✗\n`

    const solutions = leftAnchor
      ? findChainFixes(leftAnchor.footName, n, rightAnchor ? rightAnchor.footName : null)
      : []

    if (n === 1) {
      // Option A: change the chain foot (B) — already in solutions
      const solsA = solutions.map(s => s[0])

      // Option B: change the left anchor (A) to a type that bonds with B and with A's predecessor
      const prevOfAnchorFoot = runStart >= 2 ? flatFeet[runStart - 2].foot : null
      const chainFoot = chainFeet[0].foot
      const solsB = prevOfAnchorFoot
        ? findChainFixes(prevOfAnchorFoot.footName, 1, chainFoot.footName).map(s => s[0])
        : ALL_VALID_FEET.filter(f => bondValid(f, chainFoot.footName))

      const aEntry = flatFeet[runStart - 1]
      const aLoc = `Line ${aEntry.li + 1}, Foot ${aEntry.fi + 1}`
      const bLoc = `Line ${first.li + 1}, Foot ${first.fi + 1}`

      msg += `    Fix EITHER:\n`
      if (solsA.length) msg += `    (A) Change ${bLoc} to type: ${solsA.join(' / ')}\n`
      if (solsB.length) msg += `    (B) Change ${aLoc} to type: ${solsB.join(' / ')}\n`
      msg += `    (find a Tamil word matching the foot's syllable pattern — do not reuse the current word)\n`
    } else if (solutions.length) {
      msg += `    Replace these ${n} feet with one of these valid sequences:\n`
      solutions.forEach((sol, k) => { msg += `      ${k + 1}. ${sol.join(' → ')}\n` })
    } else {
      msg += `    No standard replacement found — rewrite surrounding feet.\n`
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

module.exports = { formatFeedback }
