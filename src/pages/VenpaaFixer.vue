<template>
  <q-page class="q-pa-md">
    <q-stepper v-model="step" flat animated header-nav class="tamil">

      <!-- ── Step 1: Input ──────────────────────────────────────────── -->
      <q-step :name="1" title="உள்ளிடு" icon="edit" :done="step > 1">
        <div class="row justify-center">
          <div class="col-12 col-md-6">
            <q-input
              v-model="text"
              autofocus clearable type="textarea"
              placeholder="வெண்பாவை இங்கே உள்ளிடவும்"
              color="dark" :rows="8" class="tamil"
              @input="processText"
            />
            <div class="q-mt-sm q-gutter-xs row">
              <q-chip v-if="lineCount.length" dense
                :color="lineCount.length >= 2 && lineCount.length <= 12 ? 'positive' : 'negative'"
                text-color="white" class="tamil" icon="format_list_numbered">
                {{ lineCount.length }} அடிகள்
              </q-chip>
              <q-chip v-if="lineCount.length >= 2" dense
                :color="lineCount[lineCount.length - 1] === 3 ? 'positive' : 'negative'"
                text-color="white" class="tamil" icon="last_page">
                ஈற்றடி {{ lineCount[lineCount.length - 1] }} சீர்
              </q-chip>
              <q-chip v-if="lineCount.length >= 2" dense
                :color="allBodyLinesHave4 ? 'positive' : 'negative'"
                text-color="white" class="tamil" icon="view_column">
                ஏனைய அடிகள் {{ lineCount.slice(0, -1).join(' / ') }} சீர்
              </q-chip>
            </div>
          </div>
        </div>
        <q-stepper-navigation>
          <q-btn @click="goToStep2" color="primary" label="அடுத்து: திருத்து"
            :disable="!step1Valid" class="tamil" />
          <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" class="q-ml-sm" />
        </q-stepper-navigation>
      </q-step>

      <!-- ── Step 2: Fix ────────────────────────────────────────────── -->
      <q-step :name="2" title="திருத்து" icon="build" :done="step > 2">
        <p class="text-body2 tamil text-grey-8 q-mb-md">
          சீரை மாற்றி வாய்ப்பாட்டையும் தளையையும் சரிசெய்யுங்கள்.
        </p>

        <div class="row q-col-gutter-md">

          <!-- ── Left: verse blocks ── -->
          <div class="col-12" :class="hasErrors ? 'col-md-7' : ''">
            <div v-for="(line, li) in lines" :key="'l' + li" class="q-mb-sm">

              <!-- Cross-line bond -->
              <div v-if="li > 0 && line.feet[0] && line.feet[0].linkage" class="q-mb-xs q-ml-xs">
                <span class="tamil cross-bond-text"
                  :class="line.feet[0].linkage.includes('வெண்டளை') ? 'text-blue-grey-6' : 'text-red-8 redunderline'">
                  ⤒ {{ line.feet[0].linkage }} ↦
                </span>
              </div>

              <div class="verse-block">
                <!-- TOP: word inputs + syllables -->
                <div class="vb-top">
                  <div v-for="(foot, fi) in line.feet" :key="'ft' + fi" class="foot-col"
                    :class="footColClass(li, fi)">
                    <q-input
                      :value="wordAt(li, fi)"
                      dense borderless class="tamil foot-input" input-class="text-center"
                      @input="updateWord(li, fi, $event)"
                    />
                    <div class="metremes-line">
                      <span v-for="(m, mi) in foot.metremes" :key="mi" class="metreme-item">
                        <span class="syl-sym">{{ m[1] === 'நேர்' ? '⏑' : '―' }}</span><span :class="metremeTypeStyle(m[1])" class="tamil">{{ m[0] }}</span>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- SEPARATOR -->
                <div class="layer-sep"></div>

                <!-- BOTTOM: foot classes + bond connectors -->
                <div class="vb-bottom">
                  <template v-for="(foot, fi) in line.feet">
                    <div v-if="fi > 0" :key="'bn' + fi" class="bond-con"
                      :class="foot.linkage && foot.linkage.includes('வெண்டளை') ? 'bond-ok' : 'bond-bad'">
                      <div class="bond-line"></div>
                      <span class="tamil bond-label">{{ foot.linkage ? shortLinkType(foot.linkage) : '?' }}</span>
                      <div class="bond-line"></div>
                    </div>
                    <div :key="'fc' + fi" class="foot-class-col"
                      :class="footClassColClass(li, fi, foot)">
                      <span class="tamil" :class="feetTypeStyle(getFootClass(li, fi, foot))">
                        <b>{{ getFootClass(li, fi, foot) }}</b>
                      </span>
                    </div>
                  </template>
                </div>
              </div>

              <div v-if="li < lines.length - 1" class="q-mt-xs q-ml-xs tamil text-grey-5 eol-arrow">⤓</div>
            </div>
          </div>

          <!-- ── Right: error sidebar ── -->
          <div v-if="hasErrors" class="col-12 col-md-5">
            <q-card flat bordered class="sidebar-card">
              <q-card-section class="q-pa-sm">

                <div class="row items-center q-mb-sm">
                  <q-icon name="warning_amber" color="orange-7" size="18px" class="q-mr-xs" />
                  <span class="text-subtitle2 tamil">{{ totalErrors }} பிழை{{ totalErrors > 1 ? 'கள்' : '' }}</span>
                  <q-space />
                  <q-btn v-if="activeChainKey" flat dense round icon="close" size="sm"
                    color="grey" @click="clearChain" />
                </div>

                <!-- Bond run errors -->
                <div v-if="linkageRuns.length">
                  <div class="sidebar-section-label tamil">தளை பிழைகள்</div>

                  <div v-for="(run, ri) in linkageRuns" :key="'run' + ri" class="q-mb-md">
                    <div class="run-location tamil q-mb-xs">
                      {{ runLocation(run) }}
                      <span v-if="run.minChanges" class="min-changes-badge">{{ run.minChanges }} மாற்றம்</span>
                    </div>

                    <div v-if="run.isPartial" class="partial-notice tamil q-mb-xs">
                      {{ run.totalLength }} சீர் தொடர் — முதல் 2 சீர் சரிசெய்து மீண்டும் சரிபார்க்கவும்
                    </div>

                    <div v-if="!run.solutions || run.solutions.length === 0" class="text-caption text-grey-6 tamil q-pl-sm">
                      சீர்த்தொடர் இல்லை — சுற்றியுள்ள சீர்களை மாற்றவும்
                    </div>

                    <div v-for="(sol, si) in run.solutions" :key="'s' + si"
                      class="chain-row tamil"
                      :class="activeChainKey === (ri + ':' + si) ? 'chain-row-active' : ''"
                      @click="toggleChain(ri, si, sol)">
                      <template v-for="(step, ki) in sol">
                        <span :key="'f' + ki" :class="step.changed ? 'chain-cls' : 'chain-kept'">{{ step.foot }}</span>
                        <span v-if="ki < sol.length - 1" :key="'a' + ki" class="chain-arrow">→</span>
                      </template>
                    </div>
                  </div>
                </div>

                <!-- Bad foot class errors -->
                <div v-if="classErrors.length" :class="linkageRuns.length ? 'q-mt-sm' : ''">
                  <div class="sidebar-section-label tamil">வாய்ப்பாடு பிழைகள்</div>
                  <div v-for="(err, ei) in classErrors" :key="'ce' + ei" class="q-mb-sm q-pl-sm">
                    <div class="run-location tamil q-mb-xs">
                      அடி {{ err.li + 1 }}, சீர் {{ err.fi + 1 }}: <span :class="feetTypeStyle(err.cls)">{{ err.cls }}</span>
                    </div>
                    <div class="text-caption text-grey-7 tamil">
                      {{ err.isLastOfLast ? 'நாள் · மலர் · காசு · பிறப்பு' : 'ஈரசைச்சீர் அல்லது காய்ச்சீர்' }}
                    </div>
                  </div>
                </div>

              </q-card-section>
            </q-card>
          </div>

        </div>

        <q-stepper-navigation class="q-mt-md">
          <q-btn flat @click="step = 1" color="grey" label="முந்தைய" class="tamil q-mr-sm" />
          <q-btn @click="step = 3" color="primary" label="முடிவு"
            :disable="!allValid" class="tamil" />
          <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" class="q-ml-sm" />
        </q-stepper-navigation>
      </q-step>

      <!-- ── Step 3: Done ───────────────────────────────────────────── -->
      <q-step :name="3" title="முடிவு" icon="celebration">
        <div class="text-center q-pa-md">
          <q-icon name="celebration" color="positive" size="56px" />
          <div class="text-h6 tamil q-mt-sm text-positive">வெண்பா நிறைவுற்றது!</div>
          <div class="tamil q-mt-md q-pa-md bg-grey-2 rounded-borders text-body1"
            style="white-space: pre-line; display: inline-block; min-width: 300px">{{ composedVerse }}</div>
        </div>
        <div class="q-mt-lg">
          <div class="text-subtitle2 tamil q-mb-sm">வெண்பா விதிகள்</div>
          <div v-for="(rule, ri) in venpaRules" :key="ri" class="row items-start q-mb-xs">
            <q-icon :name="ruleIcon(rule.result)" :color="ruleColor(rule.result)"
              size="18px" class="q-mr-sm q-mt-xs" />
            <span class="tamil text-body2 col">{{ rule.text }}</span>
          </div>
        </div>
        <q-stepper-navigation class="q-mt-md">
          <q-btn flat @click="restart" color="grey" label="மீண்டும்" class="tamil q-mr-sm" />
          <q-btn @click="copyVerse" color="primary" icon="content_copy" label="நகலெடு" class="tamil" />
        </q-stepper-navigation>
      </q-step>

    </q-stepper>
  </q-page>
</template>

<style scoped>
.foot-input { min-width: 70px; }

/* ── Verse block ── */
.verse-block {
  background: #fafafa;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 8px 12px;
  overflow-x: auto;
}
.vb-top {
  display: flex;
  flex-direction: row;
  align-items: flex-end;
  gap: 8px;
  flex-wrap: nowrap;
}
.foot-col {
  min-width: 72px;
  text-align: center;
  flex-shrink: 0;
  border-radius: 4px;
  padding: 2px 3px;
  transition: background 0.15s;
}
.metremes-line {
  display: flex;
  flex-direction: row;
  justify-content: center;
  gap: 5px;
  flex-wrap: wrap;
}
.metreme-item {
  display: inline-flex;
  align-items: baseline;
  gap: 1px;
}
.syl-sym { font-size: 105%; color: #bdbdbd; }

.layer-sep {
  height: 1px;
  background: #bdbdbd;
  margin: 8px -12px;
}

.vb-bottom {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  flex-wrap: nowrap;
  padding-top: 6px;
}
.foot-class-col {
  min-width: 72px;
  text-align: center;
  flex-shrink: 0;
  padding: 4px 6px;
  border-radius: 6px;
  transition: background 0.15s;
}
.bond-con {
  display: flex;
  flex-direction: row;
  align-items: center;
  flex-shrink: 0;
  min-width: 36px;
  padding: 0 2px;
}
.bond-line { flex: 1; height: 1px; min-width: 6px; }
.bond-ok .bond-line { background: #90a4ae; }
.bond-bad .bond-line { background: #ef9a9a; }
.bond-label { font-size: 10px; white-space: nowrap; padding: 0 2px; }
.bond-ok .bond-label  { color: #546e7a; }
.bond-bad .bond-label { color: #c62828; text-decoration: underline wavy #c62828; }

/* Foot state colours */
.class-ok     { background: transparent; }
.class-bad    { background: #fff3f3; }
.chain-hi     { background: #fff8e1; }
.chain-sel    { outline: 2px solid #26c6da; outline-offset: -2px; background: #e0f7fa !important; }

/* Cross-line / eol */
.cross-bond-text { font-size: 11px; }
.redunderline { text-decoration: underline wavy red; }
.eol-arrow { font-size: 130%; }

/* ── Sidebar ── */
.sidebar-card { position: sticky; top: 8px; }
.sidebar-section-label {
  font-size: 11px;
  font-weight: 600;
  color: #757575;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 6px;
}
.run-location { font-size: 12px; color: #424242; }

.chain-row {
  display: flex;
  flex-direction: row;
  align-items: center;
  flex-wrap: wrap;
  gap: 3px;
  padding: 6px 8px;
  margin-bottom: 4px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  cursor: pointer;
  background: #fff;
  transition: background 0.12s, border-color 0.12s;
}
.chain-row:hover { background: #f5f5f5; border-color: #bdbdbd; }
.chain-row-active { background: #e0f7fa !important; border-color: #26c6da !important; }

.chain-kept { font-size: 12px; color: #bdbdbd; font-style: italic; }
.min-changes-badge { font-size: 10px; background: #fff3e0; color: #e65100; border-radius: 3px; padding: 1px 5px; margin-left: 4px; vertical-align: middle; font-family: sans-serif; }
.partial-notice { font-size: 11px; color: #7986cb; background: #e8eaf6; border-radius: 4px; padding: 3px 7px; }
.chain-arrow  { font-size: 11px; color: #bdbdbd; padding: 0 1px; }
.chain-cls    { font-size: 13px; color: #212121; font-weight: 600; }
</style>

<script>
import { LinkMixin } from '../mixin/LinkMixin'
var _ = require('underscore')

const AI_BACKEND = 'http://localhost:3001'

var VALID_LAST_CLASSES = ['நாள்', 'மலர்', 'காசு', 'பிறப்பு']

function isValidBody (cls) {
  return cls.includes('மா') || cls.includes('விளம்') || cls.includes('காய்')
}

export default {
  name: 'VenpaaFixer',
  mixins: [LinkMixin],
  data () {
    return {
      step: 1,
      text: '',
      editableWords: [],
      linkageRuns: [],
      activeChainKey: null,
      chainHighlightPositions: [],
      parsedResult: null,
      showProgress: false,
      processText: _.debounce(this.parseVerse, 400),
      reparseDebounced: _.debounce(this.reparseFeet, 500)
    }
  },
  computed: {
    lineCount () {
      return this.text ? this.lineCountGet(this.text) : []
    },
    allBodyLinesHave4 () {
      return this.lineCount.slice(0, -1).every(c => c === 4)
    },
    step1Valid () {
      const lc = this.lineCount
      return lc.length >= 2 && lc.length <= 12 &&
             lc[lc.length - 1] === 3 &&
             lc.slice(0, -1).every(c => c === 4)
    },
    lines () {
      if (!this.parsedResult || !this.parsedResult.verse || !this.parsedResult.verse.MetricalLine) return []
      return this.parsedResult.verse.MetricalLine.map(line => ({
        type: line.$.type,
        feet: line.MetricalFoot.map(foot => ({
          metremes: foot.Metreme.map(m => [m._, m.$.type]),
          class: foot.$.class,
          linkage: foot.$.linkage || null
        }))
      }))
    },
    allFeetValid () {
      if (!this.lines.length) return false
      var lastLi = this.lines.length - 1
      var effectiveLast = this.effectiveLastClass
      return this.lines.every(function (line, li) {
        return line.feet.every(function (foot, fi) {
          var isLastOfLast = li === lastLi && fi === line.feet.length - 1
          if (isLastOfLast) return VALID_LAST_CLASSES.includes(effectiveLast)
          return isValidBody(foot.class)
        })
      })
    },
    allLinkagesValid () {
      if (!this.lines.length) return false
      return this.lines.every(line =>
        line.feet.every(foot => !foot.linkage || foot.linkage.includes('வெண்டளை'))
      )
    },
    allValid () {
      return this.allFeetValid && this.allLinkagesValid
    },
    composedVerse () {
      return this.editableWords.map(line => line.join(' ')).join('\n')
    },
    venpaLastWordClass () {
      if (!this.parsedResult || !this.parsedResult.verse) return null
      var raw = this.parsedResult.verse.VenpaLastWordClass
      return raw ? raw[0] : null
    },
    effectiveLastClass () {
      var lastLine = this.lines[this.lines.length - 1]
      if (!lastLine) return null
      var lastFoot = lastLine.feet[lastLine.feet.length - 1]
      if (!lastFoot) return null
      var vlwc = this.venpaLastWordClass
      return (vlwc && vlwc !== 'None') ? vlwc : lastFoot.class
    },
    venpaRules () {
      if (!this.parsedResult || !this.parsedResult.verse || !this.parsedResult.verse.venpaa) return []
      var v = this.parsedResult.verse.venpaa[0]
      return v.Rule.map(function (rule, i) { return { text: rule, result: v.Result[i] } })
    },
    badChainPositions () {
      var set = new Set()
      var lines = this.lines
      lines.forEach(function (line, li) {
        line.feet.forEach(function (foot, fi) {
          if (!foot.linkage || foot.linkage.includes('வெண்டளை')) return
          set.add(li + '-' + fi)
          if (fi > 0) {
            set.add(li + '-' + (fi - 1))
          } else if (li > 0) {
            var prevLine = lines[li - 1]
            set.add((li - 1) + '-' + (prevLine.feet.length - 1))
          }
        })
      })
      return set
    },
    classErrors () {
      var errors = []
      var lines = this.lines
      var lastLi = lines.length - 1
      var effectiveLast = this.effectiveLastClass
      lines.forEach(function (line, li) {
        line.feet.forEach(function (foot, fi) {
          var isLastOfLast = li === lastLi && fi === line.feet.length - 1
          var cls = isLastOfLast ? effectiveLast : foot.class
          var valid = isLastOfLast ? VALID_LAST_CLASSES.includes(cls) : isValidBody(cls)
          if (!valid) errors.push({ li, fi, cls, isLastOfLast })
        })
      })
      return errors
    },
    hasErrors () {
      return this.lines.length > 0 && (this.linkageRuns.length > 0 || this.classErrors.length > 0)
    },
    totalErrors () {
      return this.linkageRuns.length + this.classErrors.length
    }
  },
  methods: {
    wordAt (li, fi) {
      return (this.editableWords[li] && this.editableWords[li][fi]) || ''
    },
    updateWord (li, fi, value) {
      this.$set(this.editableWords[li], fi, value)
      this.reparseDebounced()
    },
    getFootClass (li, fi, foot) {
      var isLastOfLast = li === this.lines.length - 1 && fi === this.lines[li].feet.length - 1
      return isLastOfLast ? (this.effectiveLastClass || foot.class) : foot.class
    },
    isFootClassValid (li, fi, foot) {
      var cls = this.getFootClass(li, fi, foot)
      var isLastOfLast = li === this.lines.length - 1 && fi === this.lines[li].feet.length - 1
      return isLastOfLast ? VALID_LAST_CLASSES.includes(cls) : isValidBody(cls)
    },
    footColClass (li, fi) {
      var key = li + '-' + fi
      if (this.chainHighlightPositions.indexOf(key) !== -1) return 'chain-sel'
      if (this.badChainPositions.has(key)) return 'chain-hi'
      return ''
    },
    footClassColClass (li, fi, foot) {
      var key = li + '-' + fi
      if (this.chainHighlightPositions.indexOf(key) !== -1) return 'chain-sel'
      if (!this.isFootClassValid(li, fi, foot)) return 'class-bad'
      if (this.badChainPositions.has(key)) return 'chain-hi'
      return 'class-ok'
    },
    runLocation (run) {
      if (!run.positions.length) return ''
      var first = run.positions[0]
      var last = run.positions[run.positions.length - 1]
      if (first.li === last.li) {
        return first.fi === last.fi
          ? 'அடி ' + (first.li + 1) + ', சீர் ' + (first.fi + 1)
          : 'அடி ' + (first.li + 1) + ', சீர் ' + (first.fi + 1) + '–' + (last.fi + 1)
      }
      return 'அடி ' + (first.li + 1) + ' சீர் ' + (first.fi + 1) + ' – அடி ' + (last.li + 1) + ' சீர் ' + (last.fi + 1)
    },
    toggleChain (ri, si, sol) {
      var key = ri + ':' + si
      if (this.activeChainKey === key) {
        this.clearChain()
        return
      }
      this.activeChainKey = key
      this.chainHighlightPositions = sol
        .filter(function (step) { return step.changed })
        .map(function (step) { return step.li + '-' + step.fi })
    },
    clearChain () {
      this.activeChainKey = null
      this.chainHighlightPositions = []
    },
    ruleIcon (r) { return r === '1' ? 'check_circle' : r === 'info' ? 'info' : 'cancel' },
    ruleColor (r) { return r === '1' ? 'positive' : r === 'info' ? 'grey' : 'negative' },
    async goToStep2 () {
      if (!this.parsedResult) await this.parseVerse()
      if (this.step1Valid) {
        this.step = 2
        await this.fetchSuggestions()
      }
    },
    async parseVerse () {
      if (!this.text || !this.text.trim()) { this.parsedResult = null; this.editableWords = []; return }
      this.showProgress = true
      const xml = await this.convertAsync(this.text)
      this.parsedResult = await this.getJson(xml)
      if (this.parsedResult && this.parsedResult.verse && this.parsedResult.verse.MetricalLine) {
        this.editableWords = this.parsedResult.verse.MetricalLine.map(function (line) {
          return line.MetricalFoot.map(function (foot) {
            return foot.Metreme.map(function (m) { return m._ }).join('')
          })
        })
      }
      this.showProgress = false
    },
    async reparseFeet () {
      if (!this.editableWords.length) return
      this.showProgress = true
      this.clearChain()
      const verse = this.composedVerse
      const xml = await this.convertAsync(verse)
      this.parsedResult = await this.getJson(xml)
      if (this.step === 2) await this.fetchSuggestions(verse)
      this.showProgress = false
    },
    async fetchSuggestions (verse) {
      try {
        const resp = await fetch(AI_BACKEND + '/venpa/suggest', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ verse: verse || this.composedVerse })
        })
        if (resp.ok) {
          const data = await resp.json()
          this.linkageRuns = data.runs || []
        }
      } catch (_) {
        this.linkageRuns = []
      }
    },
    copyVerse () {
      var self = this
      if (navigator.clipboard) {
        navigator.clipboard.writeText(this.composedVerse).then(function () {
          self.$q.notify({ message: 'நகலெடுக்கப்பட்டது!', color: 'positive', position: 'top' })
        })
      }
    },
    copyHint (cls) {
      this.$q.notify({ message: cls, color: 'blue-8', position: 'top', timeout: 1500 })
    },
    restart () {
      this.step = 1
      this.text = ''
      this.editableWords = []
      this.linkageRuns = []
      this.activeChainKey = null
      this.chainHighlightPositions = []
      this.parsedResult = null
    }
  }
}
</script>
