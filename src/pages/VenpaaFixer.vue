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
          <q-btn flat @click="startCompose('venpaa')" color="grey-7" icon="create"
            label="புதிய வெண்பா" class="tamil q-ml-sm" />
          <q-btn flat @click="startCompose('kuralpaa')" color="grey-7" icon="create"
            label="குறள் வெண்பா" class="tamil q-ml-sm" />
          <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" class="q-ml-sm" />
        </q-stepper-navigation>
      </q-step>

      <!-- ── Step 2: Fix / Compose ─────────────────────────────────── -->
      <q-step :name="2" title="திருத்து" icon="build" :done="step > 2">
        <p class="text-body2 tamil text-grey-8 q-mb-md">
          {{ composing ? 'ஒவ்வொரு கட்டத்திலும் ஒரு சீர் எழுதுங்கள்.' : 'சீரை மாற்றி வாய்ப்பாட்டையும் தளையையும் சரிசெய்யுங்கள்.' }}
        </p>

        <!-- ── Compose phased layout ── -->
        <div v-if="composing">

          <!-- Phase indicator -->
          <div class="row items-center q-gutter-xs q-mb-md">
            <q-chip dense :color="composePhase >= 1 ? 'grey-8' : 'grey-4'" text-color="white" class="tamil">1. நிரப்பு</q-chip>
            <q-icon name="chevron_right" color="grey-5" />
            <q-chip dense :color="composePhase >= 2 ? 'blue-8' : 'grey-4'" text-color="white" class="tamil">2. சீர் வகை</q-chip>
            <q-icon name="chevron_right" color="grey-5" />
            <q-chip dense :color="composePhase >= 3 ? 'orange-8' : 'grey-4'" text-color="white" class="tamil">3. தளை</q-chip>
          </div>

          <div class="row q-col-gutter-md">

            <!-- Grid + buttons -->
            <div class="col-12 col-md-7">
              <div class="q-mb-md">
                <div v-for="(lineWords, li) in editableWords" :key="'cl' + li" class="q-mb-xs">

                  <!-- Cross-line bond from previous line -->
                  <div v-if="li > 0 && composePhase === 3" class="compose-cross-bond q-mb-xs">
                    <span :class="crossBondOk(li) ? 'text-positive' : 'text-negative'">
                      ⤒ {{ crossBondOk(li) ? '✓' : '✗' }}
                    </span>
                  </div>

                  <div class="compose-line">
                    <template v-for="(word, fi) in lineWords">
                      <div :key="'cf' + fi" class="compose-cell">
                        <q-input
                          :value="wordAt(li, fi)"
                          outlined dense class="tamil compose-input"
                          input-class="text-center"
                          :label="'சீர் ' + (fi + 1)"
                          :color="composeCellColor(li, fi)"
                          :bg-color="composeCellBg(li, fi)"
                          @input="updateWord(li, fi, $event)"
                        />
                        <div class="compose-foot-label tamil">{{ composePhase >= 2 ? composeFootClass(li, fi) : '' }}</div>
                      </div>
                      <!-- Bond arrow to next cell (same line) -->
                      <div v-if="fi < lineWords.length - 1 && composePhase === 3" :key="'cb' + fi"
                        class="compose-bond-con"
                        :class="inlineBondOk(li, fi + 1) ? 'bond-ok' : 'bond-bad'">
                        <div class="compose-bond-line"></div>
                        <span class="compose-bond-tick">{{ inlineBondOk(li, fi + 1) ? '✓' : '✗' }}</span>
                        <div class="compose-bond-line"></div>
                      </div>
                    </template>
                  </div>

                  <div v-if="li < editableWords.length - 1" class="compose-line-arrow"
                    :class="composePhase === 3 && li < editableWords.length - 2 ? (crossBondOk(li + 1) ? 'text-positive' : 'text-grey-5') : 'text-grey-5'">⤓</div>
                </div>
              </div>

              <!-- Phase navigation -->
              <div class="row items-center q-gutter-sm">
                <q-btn v-if="composePhase > 1" flat dense color="grey" icon="arrow_back" size="sm" class="tamil" @click="composePhase--" />
                <q-btn v-if="composePhase === 1" unelevated color="grey-8"
                  label="சீர் வகை சரிபார்க்க →" :disable="!allBoxesFilled"
                  class="tamil" @click="checkClasses" />
                <q-btn v-if="composePhase === 2" unelevated color="blue-8"
                  label="தளை சரிபார்க்க →" :disable="!allFeetValid"
                  class="tamil" @click="checkBonds" />
                <q-btn v-if="composePhase === 3" unelevated color="positive"
                  label="முடிவு" :disable="!allValid"
                  class="tamil" @click="step = 3" />
                <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" />
              </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-md-5">

              <!-- Phase 1: structure rules -->
              <q-card v-if="composePhase === 1" flat bordered>
                <q-card-section class="q-pa-sm">
                  <div class="sidebar-section-label tamil">வெண்பா அமைப்பு</div>
                  <div class="tamil text-body2 q-mb-xs">• முதல் 3 அடி — 4 சீர் வீதம் (அளவடி)</div>
                  <div class="tamil text-body2 q-mb-md">• ஈற்றடி — 3 சீர் (சிந்தடி)</div>
                  <div class="text-caption text-grey-6 tamil">எல்லா கட்டங்களையும் நிரப்பிய பின் அடுத்த கட்டத்திற்கு செல்லுங்கள்.</div>
                </q-card-section>
              </q-card>

              <!-- Phase 2: foot type reference -->
              <q-card v-if="composePhase === 2" flat bordered>
                <q-card-section class="q-pa-sm">
                  <div class="sidebar-section-label tamil">சீர் வகைகள்</div>
                  <table class="foot-ref-table q-mb-sm">
                    <tr v-for="row in footRefRows" :key="row.name">
                      <td class="tamil text-weight-bold" style="padding-right:10px">{{ row.name }}</td>
                      <td class="text-grey-6" style="font-size:11px;font-family:monospace">{{ row.pattern }}</td>
                    </tr>
                  </table>
                  <div class="sidebar-section-label tamil q-mt-sm">ஈற்றுச்சீர் (கடைசி சீர் மட்டும்)</div>
                  <div class="tamil text-caption text-grey-7 q-mb-sm">நாள் · மலர் · காசு · பிறப்பு</div>
                  <div v-if="!allFeetValid" class="text-caption text-negative tamil">சிவப்பு சீர்களை சரியான வகையில் மாற்றவும்.</div>
                  <div v-else class="text-caption text-positive tamil">✓ எல்லா சீர்களும் சரியான வகை.</div>
                </q-card-section>
              </q-card>

              <!-- Phase 3: bond errors -->
              <q-card v-if="composePhase === 3" flat bordered>
                <q-card-section class="q-pa-sm">
                  <div class="sidebar-section-label tamil q-mb-xs">வெண்டளை விதி</div>
                  <div class="tamil text-caption text-grey-7 q-mb-sm">மா-வகை → அடுத்தது நிரை · விளம்/காய்-வகை → அடுத்தது நேர்</div>
                  <template v-if="linkageRuns.length">
                    <div v-for="(run, ri) in linkageRuns" :key="'cr' + ri" class="q-mb-md">
                      <div class="run-location tamil q-mb-xs">
                        {{ runLocation(run) }}
                        <span v-if="run.minChanges" class="min-changes-badge">{{ run.minChanges }} மாற்றம்</span>
                      </div>
                      <div v-for="(sol, si) in run.solutions" :key="'cs' + si"
                        class="chain-row tamil"
                        :class="activeChainKey === (ri + ':' + si) ? 'chain-row-active' : ''"
                        @click="toggleChain(ri, si, sol)">
                        <template v-for="(step, ki) in sol">
                          <span :key="'f' + ki" :class="step.changed ? 'chain-cls' : 'chain-kept'">{{ step.foot }}</span>
                          <span v-if="ki < sol.length - 1" :key="'a' + ki" class="chain-arrow">→</span>
                        </template>
                      </div>
                    </div>
                  </template>
                  <div v-else class="text-caption text-positive tamil">✓ எல்லா தளைகளும் வெண்டளை.</div>
                </q-card-section>
              </q-card>

            </div>
          </div>
        </div>

        <div v-if="!composing" class="row q-col-gutter-md">

          <!-- ── Left: verse blocks ── -->
          <div class="col-12" :class="lines.length ? 'col-md-7' : ''">
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

          <!-- ── Right: phased sidebar ── -->
          <div v-if="lines.length" class="col-12 col-md-5">
            <q-card flat bordered class="sidebar-card">
              <q-card-section class="q-pa-sm">

                <!-- Phase indicator -->
                <div class="row items-center q-gutter-xs q-mb-sm">
                  <q-chip dense :color="fixPhase === 'classes' ? 'blue-8' : 'grey-6'" text-color="white" size="sm" class="tamil">1. சீர் வகை</q-chip>
                  <q-icon name="chevron_right" color="grey-5" />
                  <q-chip dense :color="fixPhase === 'bonds' ? 'orange-8' : 'grey-4'" text-color="white" size="sm" class="tamil">2. தளை</q-chip>
                  <q-space />
                  <q-btn v-if="activeChainKey" flat dense round icon="close" size="sm" color="grey" @click="clearChain" />
                </div>

                <!-- Phase 1: foot class errors -->
                <template v-if="fixPhase === 'classes'">
                  <div v-if="classErrors.length">
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
                  <div v-else class="text-caption text-positive tamil q-mb-sm">✓ எல்லா சீர்களும் சரியான வகை.</div>
                  <q-btn unelevated color="blue-8" label="தளை சரிபார்க்க →"
                    :disable="!allFeetValid" size="sm"
                    class="tamil full-width q-mt-sm" @click="checkBondsForFix" />
                </template>

                <!-- Phase 2: bond errors -->
                <template v-if="fixPhase === 'bonds'">
                  <div v-if="linkageRuns.length">
                    <div class="sidebar-section-label tamil q-mb-xs">தளை பிழைகள்</div>
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
                  <div v-else class="text-caption text-positive tamil q-mb-sm">✓ எல்லா தளைகளும் வெண்டளை.</div>
                  <q-btn flat dense color="grey" icon="arrow_back" size="sm"
                    class="tamil q-mt-xs" label="சீர் வகை" @click="fixPhase = 'classes'" />
                </template>

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
          <div v-if="detectedMetre" class="q-mt-sm">
            <q-chip dense color="blue-grey-7" text-color="white" icon="music_note" class="tamil">
              {{ detectedMetre }}
            </q-chip>
          </div>
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

/* ── Compose grid ── */
.compose-grid { max-width: 600px; }
.compose-line { display: flex; flex-direction: row; flex-wrap: wrap; gap: 8px; align-items: flex-start; }
.compose-cell { display: flex; flex-direction: column; align-items: center; min-width: 100px; }
.compose-input { width: 100px; }
.compose-foot-label { font-size: 11px; color: #757575; margin-top: 2px; min-height: 16px; }
.compose-line-arrow { font-size: 120%; color: #bdbdbd; padding: 2px 0 2px 4px; }
.compose-cross-bond { font-size: 11px; padding-left: 4px; }
.compose-bond-con {
  display: flex; flex-direction: row; align-items: center;
  align-self: center; margin-bottom: 18px; padding: 0 2px;
}
.compose-bond-line { flex: 1; height: 2px; min-width: 6px; }
.compose-bond-tick { font-size: 10px; padding: 0 1px; }
.bond-ok .compose-bond-line  { background: #66bb6a; }
.bond-ok .compose-bond-tick  { color: #66bb6a; }
.bond-bad .compose-bond-line { background: #ef5350; }
.bond-bad .compose-bond-tick { color: #ef5350; }
.foot-ref-table { border-collapse: collapse; width: 100%; }
.foot-ref-table tr td { padding: 2px 4px; }
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

var VALID_BODY_FEET = ['தேமா', 'புளிமா', 'கூவிளம்', 'கருவிளம்', 'தேமாங்காய்', 'புளிமாங்காய்', 'கூவிளங்காய்', 'கருவிளங்காய்']
function isValidBody (cls) {
  return VALID_BODY_FEET.includes(cls)
}

export default {
  name: 'VenpaaFixer',
  mixins: [LinkMixin],
  data () {
    return {
      step: 1,
      composing: false,
      composePhase: 1,
      fixPhase: 'classes',
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
      if (this.parsedResult && this.parsedResult.verse && this.parsedResult.verse.MetricalLine) {
        return this.parsedResult.verse.MetricalLine.map(line => ({
          type: line.$.type,
          feet: line.MetricalFoot.map(foot => ({
            metremes: foot.Metreme.map(m => [m._, m.$.type]),
            class: foot.$.class,
            linkage: foot.$.linkage || null
          }))
        }))
      }
      if (this.editableWords.length > 0) {
        return this.editableWords.map((lineWords, li) => ({
          type: li < this.editableWords.length - 1 ? 'அளவடி' : 'சிந்தடி',
          feet: lineWords.map(() => ({ metremes: [], class: '', linkage: null }))
        }))
      }
      return []
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
    detectedMetre () {
      if (!this.parsedResult || !this.parsedResult.verse) return null
      return this.parsedResult.verse.$ ? this.parsedResult.verse.$.metre : null
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
    },
    allBoxesFilled () {
      return this.editableWords.length > 0 &&
        this.editableWords.every(function (line) { return line.every(function (w) { return w && w.trim() }) })
    },
    footRefRows () {
      return [
        { name: 'தேமா', pattern: 'நேர்-நேர்' },
        { name: 'புளிமா', pattern: 'நிரை-நேர்' },
        { name: 'கூவிளம்', pattern: 'நேர்-நிரை' },
        { name: 'கருவிளம்', pattern: 'நிரை-நிரை' },
        { name: 'தேமாங்காய்', pattern: 'நேர்-நேர்-நேர்' },
        { name: 'புளிமாங்காய்', pattern: 'நிரை-நேர்-நேர்' },
        { name: 'கூவிளங்காய்', pattern: 'நேர்-நிரை-நேர்' },
        { name: 'கருவிளங்காய்', pattern: 'நிரை-நிரை-நேர்' }
      ]
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
        this.fixPhase = this.classErrors.length === 0 ? 'bonds' : 'classes'
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
      const verse = this.composedVerse
      if (!verse.replace(/[\s\n]/g, '')) return
      this.showProgress = true
      this.clearChain()
      try {
        const xml = await this.convertAsync(verse)
        this.parsedResult = await this.getJson(xml)
        if (this.step === 2) await this.fetchSuggestions(verse)
      } catch (_) {}
      this.showProgress = false
    },
    startCompose (type) {
      this.text = ''
      this.parsedResult = null
      this.linkageRuns = []
      this.activeChainKey = null
      this.chainHighlightPositions = []
      this.composing = true
      this.composePhase = 1
      this.editableWords = type === 'kuralpaa'
        ? [['', '', '', ''], ['', '', '']]
        : [['', '', '', ''], ['', '', '', ''], ['', '', '', ''], ['', '', '']]
      this.step = 2
    },
    async checkClasses () {
      this.showProgress = true
      try {
        var verse = this.composedVerse
        var xml = await this.convertAsync(verse)
        this.parsedResult = await this.getJson(xml)
      } catch (_) {}
      this.showProgress = false
      if (this.allFeetValid) {
        await this.checkBonds()
      } else {
        this.composePhase = 2
      }
    },
    async checkBondsForFix () {
      this.showProgress = true
      try {
        await this.fetchSuggestions(this.composedVerse)
      } catch (_) {}
      this.showProgress = false
      this.fixPhase = 'bonds'
    },
    async checkBonds () {
      this.showProgress = true
      try {
        await this.fetchSuggestions(this.composedVerse)
      } catch (_) {}
      this.showProgress = false
      this.composePhase = 3
    },
    inlineBondOk (li, fi) {
      if (!this.lines[li] || !this.lines[li].feet[fi]) return true
      var lnk = this.lines[li].feet[fi].linkage
      return !lnk || lnk.includes('வெண்டளை')
    },
    crossBondOk (li) {
      if (!this.lines[li] || !this.lines[li].feet[0]) return true
      var lnk = this.lines[li].feet[0].linkage
      return !lnk || lnk.includes('வெண்டளை')
    },
    composeFootClass (li, fi) {
      if (!this.lines[li] || !this.lines[li].feet[fi]) return ''
      return this.getFootClass(li, fi, this.lines[li].feet[fi])
    },
    composeCellColor (li, fi) {
      if (this.composePhase < 2) return 'grey-5'
      var cls = this.composeFootClass(li, fi)
      var word = this.wordAt(li, fi)
      if (!word) return 'grey-4'
      var isLastOfLast = li === this.editableWords.length - 1 && fi === this.editableWords[li].length - 1
      var valid = isLastOfLast ? VALID_LAST_CLASSES.includes(cls) : VALID_BODY_FEET.includes(cls)
      return valid ? 'positive' : 'negative'
    },
    composeCellBg (li, fi) {
      if (this.composePhase < 2) return 'white'
      var cls = this.composeFootClass(li, fi)
      var word = this.wordAt(li, fi)
      if (!word) return 'grey-1'
      var isLastOfLast = li === this.editableWords.length - 1 && fi === this.editableWords[li].length - 1
      var valid = isLastOfLast ? VALID_LAST_CLASSES.includes(cls) : VALID_BODY_FEET.includes(cls)
      return valid ? 'green-1' : 'red-1'
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
      this.composing = false
      this.composePhase = 1
      this.fixPhase = 'classes'
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
