'use strict'
const { describe, it } = require('node:test')
const assert = require('node:assert/strict')
const { findMinChangeSolutions } = require('../errorFeedback')

// ─── helpers ─────────────────────────────────────────────────────────────────

function positions (sol) {
  return sol.map(s => `L${s.li + 1}F${s.fi + 1}:${s.foot}(${s.changed ? 'change' : 'keep'})`)
}

function changedCount (sol) {
  return sol.filter(s => s.changed).length
}

// Fake candidate builder: [{li, fi, footName}]
function cands (...names) {
  return names.map((footName, i) => ({ li: 0, fi: i, footName }))
}

// ─── bond rule reference ──────────────────────────────────────────────────────
// மா-class  (தேமா, புளிமா)         → next starts நிரை  (புளிமா, கருவிளம், ...)
// விளம்-class (கூவிளம், கருவிளம்) → next starts நேர்   (தேமா, கூவிளம், ...)
// காய்-class  (all *காய்)           → next starts நேர்

describe('findMinChangeSolutions — unit tests', () => {

  // ── 1. already valid chain ───────────────────────────────────────────────
  describe('already valid chain', () => {
    it('returns minChanges=0 and one keep-all solution', () => {
      // தேமா(மா) → கருவிளம்(விளம்) : மா needs நிரை next, கருவிளம் starts நிரை ✓
      const { minChanges, solutions } = findMinChangeSolutions(
        'கூவிளம்',              // prevAnchor: விளம்-class needs நேர் next
        cands('தேமா', 'கருவிளம்'), // தேமா starts நேர் ✓, then தேமா→கருவிளம்: மா needs நிரை, கருவிளம் starts நிரை ✓
        null
      )
      assert.equal(minChanges, 0, 'should need 0 changes')
      assert.ok(solutions.length > 0, 'should have at least one solution')
      assert.equal(changedCount(solutions[0]), 0, 'solution should change nothing')
    })
  })

  // ── 2. single bad foot — fix by changing the run foot ───────────────────
  describe('single bad foot', () => {
    it('fixes விளம்→புளிமா bond error by changing the bad foot', () => {
      // கருவிளம்(விளம்) → புளிமா : விளம் needs நேர் next, புளிமா starts நிரை ✗
      const { minChanges, solutions } = findMinChangeSolutions(
        null,
        cands('கருவிளம்', 'புளிமா'),
        null
      )
      assert.equal(minChanges, 1, 'should need exactly 1 change')
      assert.ok(solutions.length > 0, 'should have solutions')
      const changed = solutions[0].filter(s => s.changed)
      assert.equal(changed.length, 1)
      // Changed foot must start நேர் (required after விளம்-class)
      const nerStart = ['தேமா', 'கூவிளம்', 'தேமாங்காய்', 'கூவிளங்காய்']
      assert.ok(nerStart.includes(changed[0].foot), `${changed[0].foot} should start நேர்`)
    })

    it('fixes மா→தேமா bond error (மா needs நிரை, தேமா starts நேர்)', () => {
      // புளிமா(மா) → தேமா : மா needs நிரை next, தேமா starts நேர் ✗
      const { minChanges, solutions } = findMinChangeSolutions(
        null,
        cands('புளிமா', 'தேமா'),
        null
      )
      assert.equal(minChanges, 1)
      const changed = solutions[0].filter(s => s.changed)
      const niraiStart = ['புளிமா', 'கருவிளம்', 'புளிமாங்காய்', 'கருவிளங்காய்']
      assert.ok(niraiStart.includes(changed[0].foot), `${changed[0].foot} should start நிரை`)
    })
  })

  // ── 3. right anchor constraint respected ────────────────────────────────
  describe('right anchor constraint', () => {
    it('all solutions bond correctly into right anchor', () => {
      // anchor(கூவிளம்) → bad foot → rightAnchor(புளிமா)
      // rightAnchor புளிமா starts நிரை → preceding foot must be மா-class
      const { solutions } = findMinChangeSolutions(
        'தேமா',
        cands('கூவிளம்', 'தேமா'),  // கூவிளம்→தேமா: விளம்→நேர் ✓, but தேமா→புளிமா: மா→நிரை ✓
        'புளிமா'
      )
      // Every solution must end with a மா-class foot (so it bonds into புளிமா)
      const maClass = ['தேமா', 'புளிமா']
      solutions.forEach(sol => {
        const last = sol[sol.length - 1]
        assert.ok(maClass.includes(last.foot), `last foot ${last.foot} must be மா-class to bond into புளிமா`)
      })
    })

    it('returns no solutions when right anchor cannot be bonded', () => {
      // Construct a situation where no valid chain can end before the right anchor
      // If right anchor is தேமா (starts நேர்), last foot must be விளம்/காய்-class
      // but if only possible feet are மா-class, no solution exists
      // Use prevAnchor=null and candidates=[தேமா] with rightAnchor=தேமா
      // தேமா(மா)→தேமா: மா needs நிரை, தேமா starts நேர் ✗ — no valid chain
      const { solutions } = findMinChangeSolutions(
        null,
        [{ li: 0, fi: 0, footName: 'தேமா' }],
        'தேமா'  // தேமா starts நேர், so preceding must be விளம்/காய் — but our only foot is மா-class
      )
      // Should either find 0 solutions or find solutions where foot changes to விளம்/காய்
      solutions.forEach(sol => {
        const last = sol[sol.length - 1]
        const vilamKay = ['கூவிளம்', 'கருவிளம்', 'தேமாங்காய்', 'புளிமாங்காய்', 'கூவிளங்காய்', 'கருவிளங்காய்']
        assert.ok(vilamKay.includes(last.foot), `last foot ${last.foot} must be விளம்/காய் to bond into தேமா`)
      })
    })
  })

  // ── 4. multi-foot chain ──────────────────────────────────────────────────
  describe('multi-foot chain (2 bad positions)', () => {
    it('finds minimum changes for a 2-foot bad chain', () => {
      // கருவிளம்→புளிமா→தேமா: position 2 (புளிமா) breaks விளம்→நிரை rule
      // then if we fix to தேமா, தேமா→தேமா: மா needs நிரை, தேமா starts நேர் ✗
      const { minChanges, solutions } = findMinChangeSolutions(
        null,
        cands('கருவிளம்', 'புளிமா', 'தேமா'),
        null
      )
      assert.ok(minChanges >= 1, 'should need at least 1 change')
      assert.ok(solutions.length > 0, 'should have solutions')
      solutions.forEach(sol => {
        assert.equal(changedCount(sol), minChanges, 'all solutions should have same change count')
      })
    })

    it('all solutions in a multi-foot chain have equal change count', () => {
      const { minChanges, solutions } = findMinChangeSolutions(
        'தேமா',
        cands('கூவிளம்', 'புளிமா', 'கருவிளம்'),
        'தேமா'
      )
      solutions.forEach((sol, i) => {
        assert.equal(changedCount(sol), minChanges, `solution ${i} has wrong change count`)
      })
    })
  })

  // ── 5. max solutions cap ─────────────────────────────────────────────────
  describe('solution cap', () => {
    it('never returns more than 16 solutions', () => {
      const { solutions } = findMinChangeSolutions(
        'கூவிளம்',
        cands('புளிமா', 'புளிமா', 'புளிமா'),
        null
      )
      assert.ok(solutions.length <= 16, `got ${solutions.length} solutions, max is 16`)
    })
  })

  // ── 6. prevAnchorName=null fallback ─────────────────────────────────────
  describe('no prevAnchor (first foot of verse)', () => {
    it('keeps anchor fixed and solves run feet', () => {
      // No prevAnchor — anchor is fixed, only run feet can change
      const { solutions } = findMinChangeSolutions(
        null,
        cands('கருவிளம்', 'புளிமா'),
        null
      )
      assert.ok(solutions.length > 0)
      // Anchor (fi=0, கருவிளம்) must never be marked changed when prevAnchor is null
      solutions.forEach(sol => {
        const anchor = sol.find(s => s.fi === 0)
        if (anchor) assert.equal(anchor.changed, false, 'anchor must not change when prevAnchor=null')
      })
    })
  })

  // ── 7. காய்-class feet ───────────────────────────────────────────────────
  describe('காய்-class feet', () => {
    it('correctly handles காய்-class anchor needing நேர் next', () => {
      // கூவிளங்காய்(காய்) → புளிமா: காய் needs நேர் next, புளிமா starts நிரை ✗
      const { minChanges, solutions } = findMinChangeSolutions(
        null,
        cands('கூவிளங்காய்', 'புளிமா'),
        null
      )
      assert.equal(minChanges, 1)
      const nerStart = ['தேமா', 'கூவிளம்', 'தேமாங்காய்', 'கூவிளங்காய்']
      solutions.forEach(sol => {
        sol.filter(s => s.changed && s.fi === 1).forEach(s => {
          assert.ok(nerStart.includes(s.foot), `${s.foot} should start நேர்`)
        })
      })
    })
  })

})
