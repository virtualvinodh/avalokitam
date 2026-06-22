<template>
  <q-page class="q-pa-md">
    <q-stepper v-model="step" flat animated header-nav class="tamil">

      <!-- ── Step 1: Entry ─────────────────────────────────────────── -->
      <q-step :name="1" title="உள்ளிடு" icon="edit" :done="step > 1">

        <!-- Mode toggle -->
        <q-tabs v-model="inputMode" dense align="left" active-color="primary"
          indicator-color="primary" class="tamil q-mb-md" narrow-indicator>
          <q-tab name="fix" label="திருத்து" />
          <q-tab name="compose" label="இயற்று" />
        </q-tabs>
        <div v-if="inputMode === 'compose'" class="row items-center q-gutter-xs q-mb-sm">
          <q-chip
            v-for="t in [{ label: 'வெண்பா', value: 'venpaa' }, { label: 'குறள்', value: 'kuralpaa' }]"
            :key="t.value"
            dense clickable :selected="composeType === t.value"
            :color="composeType === t.value ? 'blue-grey-7' : 'grey-3'"
            :text-color="composeType === t.value ? 'white' : 'grey-7'"
            class="tamil"
            @click="composeType = t.value"
          >{{ t.label }}</q-chip>
        </div>

        <!-- Fix mode: textarea -->
        <div v-if="inputMode === 'fix'" class="row justify-center">
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

        <!-- Compose mode: blank word grid -->
        <div v-else>
          <div v-for="(lineWords, li) in editableWords" :key="'cg' + li" class="q-mb-sm">
            <div class="compose-line">
              <div v-for="(word, fi) in lineWords" :key="'cw' + fi" class="compose-cell">
                <q-input
                  :value="word"
                  class="tamil compose-input"
                  input-class="text-center"
                  :label="'சீர் ' + (fi + 1)"
                  @input="updateWord(li, fi, $event)"
                />
              </div>
            </div>
            <div v-if="li < editableWords.length - 1" class="q-mt-xs q-ml-xs tamil text-grey-5">⤓</div>
          </div>
        </div>

        <q-stepper-navigation class="q-mt-md">
          <q-btn @click="goToStep2" color="primary" label="அடுத்து →"
            :disable="inputMode === 'fix' ? !step1Valid : !allBoxesFilled" class="tamil" />
          <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" class="q-ml-sm" />
        </q-stepper-navigation>
      </q-step>

      <!-- ── Step 2: வாய்ப்பாடு ──────────────────────────────────────── -->
      <q-step :name="2" title="வாய்ப்பாடு" icon="spellcheck" :done="step > 2">

        <div>
          <!-- Status + action bar at top -->
          <div class="row items-center q-mb-md">
            <q-btn flat @click="step = 1" color="grey" icon="arrow_back" dense class="q-mr-sm" />
            <span v-if="lines.length && classErrors.length === 0" class="text-caption text-positive tamil col">✓ எல்லா சீர்களும் சரியான வகை.</span>
            <span v-else-if="classErrors.length" class="text-caption text-grey-7 tamil col">{{ classErrors.length }} பிழை{{ classErrors.length > 1 ? 'கள்' : '' }} — சீர்களை திருத்துக</span>
            <q-btn @click="checkBondsForFix" color="primary" label="தளை சரிபார்க்க →" :disable="!allFeetValid" size="sm" class="tamil" />
            <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" class="q-ml-sm" />
          </div>
          <div v-for="(line, li) in lines" :key="'l' + li" class="q-mb-sm">
            <div class="verse-block">
              <div class="vb-row vb-row-classes">
                <template v-for="(foot, fi) in line.feet">
                  <div :key="'fu' + fi" class="foot-unit" :class="[footColClass(li, fi), classErrBoundClass(li, fi)]">
                    <q-input :value="wordAt(li, fi)" dense borderless class="tamil foot-input" input-class="text-center" @input="updateWord(li, fi, $event)" />
                    <div class="metremes-line">
                      <span v-for="(m, mi) in foot.metremes" :key="mi" class="metreme-item">
                        <span :class="metremeTypeStyle(m[1])" class="tamil">{{ m[0] }}</span>
                      </span>
                    </div>
                    <div class="foot-unit-sep"></div>
                    <div class="foot-class-col" :class="footClassColClass(li, fi, foot)">
                      <span class="tamil" :class="isFootClassValid(li, fi, foot) ? feetTypeStyle(getFootClass(li, fi, foot)) : 'text-red-9'">
                        <b>{{ getFootClass(li, fi, foot) }}</b>
                      </span>
                    </div>
                    <div v-if="!isFootClassValid(li, fi, foot)" class="foot-hint tamil">
                      {{ li === lines.length - 1 && fi === line.feet.length - 1 ? 'நாள்/மலர்/காசு/பிறப்பு' : 'மா அல்லது காய்' }}
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <div v-if="li < lines.length - 1" class="q-mt-xs q-ml-xs tamil text-grey-5 eol-arrow">⤓</div>
          </div>
        </div>
      </q-step>

      <!-- ── Step 3: தளை ───────────────────────────────────────────── -->
      <q-step :name="3" title="தளை" icon="link" :done="step > 3">

        <!-- Minimal header -->
        <div class="row items-center q-mb-sm">
          <span v-if="linkageRuns.length" class="text-caption text-grey-7 tamil col">
            {{ linkageRuns.length }} தளைப் பிழை{{ linkageRuns.length > 1 ? 'கள்' : '' }} — சீர்களை தொட்டு திருத்துக
          </span>
          <span v-else class="text-caption text-positive tamil col">✓ எல்லா தளைகளும் வெண்டளை.</span>
          <q-spinner-oval v-show="showProgress" color="grey-8" size="1.5em" />
        </div>

        <!-- Verse grid with inline suggestions -->
        <div v-for="(line, li) in lines" :key="'bl' + li" class="q-mb-xs">
          <div v-if="li > 0 && line.feet[0] && line.feet[0].linkage" class="cross-bond-row q-mb-xs">
            <span class="tamil cross-bond-text"
              :class="line.feet[0].linkage.includes('வெண்டளை') ? 'text-blue-grey-6' : 'text-red-8 redunderline'">
              {{ shortLinkType(line.feet[0].linkage) }}
            </span>
          </div>
          <div class="verse-block">
            <div class="vb-pairs">
              <template v-for="(pair, pi) in linePairs(li)">
                <div :key="'pr' + pi" class="vb-pair">
                  <template v-for="item in pair.items">
                    <div v-if="item.type === 'bond'" :key="'pb' + item.fi" class="bond-con"
                      :class="[line.feet[item.fi].linkage && line.feet[item.fi].linkage.includes('வெண்டளை') ? 'bond-ok' : 'bond-bad', runBondBoundClass(li, item.fi)]">
                      <div class="bond-line"></div>
                      <span class="tamil bond-label">{{ line.feet[item.fi].linkage ? shortLinkType(line.feet[item.fi].linkage) : '?' }}</span>
                      <div class="bond-line"></div>
                    </div>
                    <div v-else :key="'pf' + item.fi" class="foot-unit" :class="[footColClass(li, item.fi), runBoundClass(li, item.fi), isInAnyRun(li, item.fi) ? 'run-foot-clickable' : '']"
                      @click="selectRunByFoot(li, item.fi)">
                      <q-input :value="wordAt(li, item.fi)" dense borderless class="tamil foot-input" input-class="text-center" @input="updateWord(li, item.fi, $event)" />
                      <div class="metremes-line">
                        <span v-for="(m, mi) in line.feet[item.fi].metremes" :key="mi" class="metreme-item">
                          <span :class="metremeTypeStyle(m[1])" class="tamil">{{ m[0] }}</span>
                        </span>
                      </div>
                      <div class="foot-unit-sep"></div>
                      <div class="foot-class-col" :class="footClassColClass(li, item.fi, line.feet[item.fi])">
                        <span class="tamil" :class="isFootClassValid(li, item.fi, line.feet[item.fi]) ? feetTypeStyle(getFootClass(li, item.fi, line.feet[item.fi])) : 'text-red-9'">
                          <b>{{ getFootClass(li, item.fi, line.feet[item.fi]) }}</b>
                        </span>
                      </div>
                      <div v-if="unselectedRunEndAt(li, item.fi) >= 0" class="run-tap-indicator">▼ பரிந்துரை</div>
                    </div>
                  </template>
                </div>
                <!-- cross-pair bond -->
                <div v-if="pair.crossBondFi !== null" :key="'cpb' + pi" class="bond-con cross-pair-bond"
                  :class="[line.feet[pair.crossBondFi].linkage && line.feet[pair.crossBondFi].linkage.includes('வெண்டளை') ? 'bond-ok' : 'bond-bad', runBondBoundClass(li, pair.crossBondFi)]">
                  <div class="bond-line"></div>
                  <span class="tamil bond-label">{{ line.feet[pair.crossBondFi].linkage ? shortLinkType(line.feet[pair.crossBondFi].linkage) : '?' }}</span>
                  <div class="bond-line"></div>
                </div>
              </template>
            </div>
          </div>

          <!-- Inline suggestions: shown below the line where the active run starts -->
          <div v-if="isActiveRunLine(li) && linkageRuns[activeRunIndex]" class="inline-suggestions tamil">
            <div v-if="linkageRuns[activeRunIndex].isPartial" class="partial-notice q-mb-xs">
              {{ linkageRuns[activeRunIndex].totalLength }} சீர்களில் பிழை — முதல் 2 சீரை திருத்தி மீண்டும் சரிபார்க்கவும்
            </div>
            <div v-if="!linkageRuns[activeRunIndex].solutions || linkageRuns[activeRunIndex].solutions.length === 0"
              class="text-caption text-grey-6">
              பரிந்துரை இல்லை — பக்கத்து சீர்களை மாற்றிப் பாருங்கள்
            </div>
            <template v-else>
              <div class="text-caption text-grey-5 q-mb-xs">பரிந்துரை{{ linkageRuns[activeRunIndex].solutions.length > 1 ? 'கள்' : '' }}</div>
              <div v-for="(sol, si) in linkageRuns[activeRunIndex].solutions" :key="'s' + si"
                class="chain-row"
                :class="activeChainKey === (activeRunIndex + ':' + si) ? 'chain-row-active' : ''"
                @click="toggleChain(activeRunIndex, si, sol)">
                <template v-for="(stp, ki) in sol">
                  <span :key="'f' + ki" :class="stp.changed ? 'chain-cls' : 'chain-kept'">{{ stp.foot }}</span>
                  <span v-if="ki < sol.length - 1" :key="'a' + ki" class="chain-arrow">→</span>
                </template>
              </div>
            </template>
          </div>

          <div v-if="li < lines.length - 1" class="q-mt-xs q-ml-xs tamil text-grey-5 eol-arrow">⤓</div>
        </div>

        <q-stepper-navigation class="q-mt-md">
          <q-btn flat @click="step = 2" color="grey" label="முந்தைய" class="tamil q-mr-sm" />
          <q-btn @click="step = 4" color="primary" label="முடிவு" :disable="!allValid" class="tamil" />
        </q-stepper-navigation>
      </q-step>

      <!-- ── Step 4: Done ───────────────────────────────────────────── -->
      <q-step :name="4" title="முடிவு" icon="celebration">
        <div v-if="!allValid" class="q-pa-md">
          <div class="text-center q-mb-md">
            <q-icon name="warning" color="warning" size="48px" />
            <div class="text-h6 tamil q-mt-sm text-warning">சில விதிகள் பொருந்தவில்லை.</div>
          </div>
          <div v-for="(rule, ri) in venpaRules.filter(r => r.result !== '1' && r.result !== 'info')" :key="ri"
            class="row items-start q-mb-xs">
            <q-icon name="cancel" color="negative" size="18px" class="q-mr-sm q-mt-xs" />
            <span class="tamil text-body2 col">{{ rule.text }}</span>
          </div>
          <q-stepper-navigation class="q-mt-md">
            <q-btn flat @click="step = hasClassErrors ? 2 : 3" color="primary" label="← திரும்பு" class="tamil" />
          </q-stepper-navigation>
        </div>
        <div v-else class="text-center q-pa-md">
          <q-icon name="celebration" color="positive" size="56px" />
          <div class="text-h6 tamil q-mt-sm text-positive">வெண்பா நிறைவுற்றது!</div>
          <div v-if="detectedMetre" class="q-mt-sm">
            <q-chip dense color="blue-grey-7" text-color="white" icon="music_note" class="tamil">
              {{ detectedMetre }}
            </q-chip>
          </div>
          <div class="tamil q-mt-md q-pa-md bg-grey-2 rounded-borders text-body1"
            style="white-space: pre-line; display: inline-block; max-width: 100%">{{ composedVerse }}</div>
          <q-stepper-navigation class="q-mt-lg">
            <q-btn flat @click="restart" color="grey" label="மீண்டும்" class="tamil q-mr-sm" />
            <q-btn @click="copyVerse" color="primary" icon="content_copy" label="நகலெடு" class="tamil q-mr-sm" />
            <q-btn @click="saveAndCopy(composedVerse)" color="grey-8" icon="bookmark" label="சேமி" class="tamil" :loading="saving" />
          </q-stepper-navigation>
          <div class="row justify-center q-gutter-xs q-mt-sm items-center">
            <q-btn dense flat icon="image" label="படம்" color="grey-7" class="tamil" size="sm" @click="downloadImage(composedVerse)" />
            <q-btn dense flat icon="share" label="பகிர்" color="grey-7" class="tamil" size="sm" @click="shareInstagram(composedVerse)" />
          </div>
        </div>
      </q-step>

    </q-stepper>
  </q-page>
</template>

<style scoped>
.foot-input {
  min-width: 70px;
  border-bottom: 1.5px solid #90a4ae;
  border-radius: 3px 3px 0 0;
  background: #f5f5f5;
  transition: border-color 0.15s, background 0.15s;
  margin-bottom: 6px;
}
.foot-input:hover { border-bottom-color: #546e7a; background: #eeeeee; }

/* ── Verse block ── */
.verse-block {
  background: #fafafa;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 8px 12px;
  overflow-x: auto;
}
.vb-row {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  flex-wrap: nowrap;
  overflow-x: auto;
}

/* Bonds phase: pair-based layout */
.vb-pairs { display: flex; flex-direction: row; align-items: stretch; flex-wrap: nowrap; overflow-x: auto; }
.vb-pair  { display: flex; flex-direction: row; align-items: stretch; flex-shrink: 0; }

/* Mobile: bonds → 2 per row */
@media (max-width: 599px) {
  /* bonds phase: linear vertical chain */
  .vb-pairs { flex-direction: column; align-items: stretch; overflow-x: hidden; gap: 0; }
  .vb-pair  { flex-direction: column; width: 100%; }
  .vb-pair .foot-unit { width: 100%; min-width: 0; }
  /* bond connector: centered label with arrow characters */
  .vb-pair .bond-con,
  .cross-pair-bond {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 0; width: 100%;
    padding: 1px 0;
  }
  .vb-pair .bond-line,
  .cross-pair-bond .bond-line { display: none !important; }
  .bond-label::before { content: '↑'; display: block; text-align: center; font-size: 9px; line-height: 1.4; }
  .bond-label::after  { content: '↓'; display: block; text-align: center; font-size: 9px; line-height: 1.4; }
  .cross-pair-bond { margin-left: 0; }
}

/* Classes phase mobile: stack feet vertically, each foot as a column */
@media (max-width: 599px) {
  .vb-row-classes { flex-direction: column; overflow-x: hidden; gap: 6px; }
  .vb-row-classes .foot-unit {
    flex-direction: column; align-items: center;
    width: 100%; min-width: 0; padding: 6px 8px;
    border: 1px solid #e0e0e0; border-radius: 6px;
  }
  .vb-row-classes .foot-input     { width: 100%; }
  .vb-row-classes .metremes-line  { justify-content: center; margin-top: 2px; }
  .vb-row-classes .foot-unit-sep  { display: none; }
  .vb-row-classes .foot-class-col { width: auto; margin-top: 2px; text-align: center; }
  .vb-row-classes .foot-hint      { margin-top: 2px; font-size: 10px; text-align: center; }
}
.foot-unit {
  min-width: 72px;
  text-align: center;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  border-radius: 4px;
  padding: 2px 3px;
  transition: background 0.15s;
}
.foot-unit-sep {
  width: 100%;
  height: 1px;
  background: #e0e0e0;
  margin: 4px 0;
}
.metremes-line {
  display: flex;
  flex-direction: row;
  justify-content: center;
  gap: 5px;
  flex-wrap: wrap;
  flex: 1;
}
.metreme-item {
  display: inline-flex;
  align-items: baseline;
  gap: 1px;
}
.foot-class-col {
  width: 100%;
  text-align: center;
  padding: 2px 4px;
  border-radius: 4px;
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
.bond-ok .bond-line      { background: #90a4ae; }
.bond-bad .bond-line     { background: #ef9a9a; }
.bond-neutral .bond-line { background: #e0e0e0; }
.bond-label { font-size: 10px; white-space: nowrap; padding: 0 2px; }
.bond-ok .bond-label      { color: #546e7a; }
.bond-bad .bond-label     { color: #c62828; text-decoration: underline wavy #c62828; }
.bond-neutral .bond-label { color: #bdbdbd; }

/* Foot state colours */
.class-ok       { background: transparent; }
.class-bad      { background: #fff3f3; }
.col-class-bad  { background: #ffebee; border-radius: 4px; }
.foot-hint      { font-size: 9px; color: #c62828; text-align: center; margin-top: 3px; line-height: 1.2; }

/* run bounding rectangle */
.run-bound-single { border: 2px solid #e53935; border-radius: 5px; }
.run-bound-start  { border-top: 2px solid #e53935; border-bottom: 2px solid #e53935; border-left: 2px solid #e53935; border-radius: 5px 0 0 5px; }
.run-bound-mid    { border-top: 2px solid #e53935; border-bottom: 2px solid #e53935; }
.run-bound-end    { border-top: 2px solid #e53935; border-bottom: 2px solid #e53935; border-right: 2px solid #e53935; border-radius: 0 5px 5px 0; }
.run-bound-bond   { border-top: 2px solid #e53935; border-bottom: 2px solid #e53935; }
.run-bound-dim-single { border: 1.5px solid #ef9a9a; border-radius: 5px; }
.run-bound-dim-start  { border-top: 1.5px solid #ef9a9a; border-bottom: 1.5px solid #ef9a9a; border-left: 1.5px solid #ef9a9a; border-radius: 5px 0 0 5px; }
.run-bound-dim-mid    { border-top: 1.5px solid #ef9a9a; border-bottom: 1.5px solid #ef9a9a; }
.run-bound-dim-end    { border-top: 1.5px solid #ef9a9a; border-bottom: 1.5px solid #ef9a9a; border-right: 1.5px solid #ef9a9a; border-radius: 0 5px 5px 0; }
.run-bound-dim-bond   { border-top: 1.5px solid #ef9a9a; border-bottom: 1.5px solid #ef9a9a; }

/* Mobile vertical chain: rotate border logic — top/bottom become start/end, left/right become the sides */
@media (max-width: 599px) {
  .run-bound-start      { border: 0; border-top: 2px solid #e53935; border-left: 2px solid #e53935; border-right: 2px solid #e53935; border-radius: 5px 5px 0 0; }
  .run-bound-mid        { border: 0; border-left: 2px solid #e53935; border-right: 2px solid #e53935; border-radius: 0; }
  .run-bound-end        { border: 0; border-bottom: 2px solid #e53935; border-left: 2px solid #e53935; border-right: 2px solid #e53935; border-radius: 0 0 5px 5px; }
  .run-bound-bond       { border: 0; border-left: 2px solid #e53935; border-right: 2px solid #e53935; border-radius: 0; }
  .run-bound-dim-start  { border: 0; border-top: 1.5px solid #ef9a9a; border-left: 1.5px solid #ef9a9a; border-right: 1.5px solid #ef9a9a; border-radius: 5px 5px 0 0; }
  .run-bound-dim-mid    { border: 0; border-left: 1.5px solid #ef9a9a; border-right: 1.5px solid #ef9a9a; border-radius: 0; }
  .run-bound-dim-end    { border: 0; border-bottom: 1.5px solid #ef9a9a; border-left: 1.5px solid #ef9a9a; border-right: 1.5px solid #ef9a9a; border-radius: 0 0 5px 5px; }
  .run-bound-dim-bond   { border: 0; border-left: 1.5px solid #ef9a9a; border-right: 1.5px solid #ef9a9a; border-radius: 0; }
}
.chain-hi     { background: #fff8e1; }
.chain-sel    { background: #ffebee !important; }

/* Cross-line / eol */
.cross-bond-row { }
.cross-bond-text { font-size: 11px; }
.cross-bond-text::before { content: '⤒ '; }
.cross-bond-text::after  { content: ' ↦'; }
@media (max-width: 599px) {
  .cross-bond-row { display: flex; flex-direction: column; align-items: center; }
  .cross-bond-text { display: flex; flex-direction: column; align-items: center; }
  .cross-bond-text::before { content: '↑'; font-size: 9px; line-height: 1.4; }
  .cross-bond-text::after  { content: '↓'; font-size: 9px; line-height: 1.4; }
}
.redunderline { text-decoration: underline wavy red; }
.eol-arrow { font-size: 130%; }
@media (max-width: 599px) { .eol-arrow { display: none; } }

.run-location { font-size: 12px; color: #424242; }
.run-foot-clickable { cursor: pointer; }
.run-foot-clickable:hover { background: #fff8f8; }
.run-tap-indicator {
  font-size: 9px; color: #e53935; text-align: center;
  margin-top: 2px; letter-spacing: 0.3px; user-select: none;
}
.inline-suggestions {
  border: 1px solid #e0e0e0;
  border-top: none;
  border-radius: 0 0 6px 6px;
  padding: 6px 10px 8px;
  background: #fafafa;
}
.run-list { border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden; }
.run-list-item {
  display: flex; align-items: center; gap: 8px;
  padding: 7px 10px; cursor: pointer; font-size: 12px;
  border-bottom: 1px solid #f0f0f0; transition: background 0.12s;
}
.run-list-item:last-child { border-bottom: none; }
.run-list-item:hover { background: #f5f5f5; }
.run-list-active { background: #e3f2fd !important; font-weight: 600; }
.run-list-num {
  min-width: 18px; height: 18px; border-radius: 50%;
  background: #90a4ae; color: #fff; font-size: 10px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.run-list-active .run-list-num { background: #1976d2; }

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

/* ── Compose grid (step 1 blank boxes) ── */
.compose-line { display: flex; flex-direction: row; flex-wrap: wrap; gap: 8px; align-items: flex-start; }
.compose-cell { display: flex; flex-direction: column; align-items: center; flex: 1 1 80px; min-width: 0; }
.compose-input { width: 100%; min-width: 72px; }
.min-changes-badge { font-size: 10px; background: #fff3e0; color: #e65100; border-radius: 3px; padding: 1px 5px; margin-left: 4px; vertical-align: middle; font-family: sans-serif; }
.partial-notice { font-size: 11px; color: #7986cb; background: #e8eaf6; border-radius: 4px; padding: 3px 7px; }
.chain-arrow  { font-size: 11px; color: #bdbdbd; padding: 0 1px; }
.chain-cls    { font-size: 13px; color: #212121; font-weight: 600; }
</style>

<script>
import { LinkMixin } from '../mixin/LinkMixin'
import { ShareMixin } from '../mixin/ShareMixin'
var _ = require('underscore')

const AI_BACKEND = process.env.AI_BACKEND || ''

var VALID_LAST_CLASSES = ['நாள்', 'மலர்', 'காசு', 'பிறப்பு']

var VALID_BODY_FEET = ['தேமா', 'புளிமா', 'கூவிளம்', 'கருவிளம்', 'தேமாங்காய்', 'புளிமாங்காய்', 'கூவிளங்காய்', 'கருவிளங்காய்']
function isValidBody (cls) {
  return VALID_BODY_FEET.includes(cls)
}

export default {
  name: 'VenpaaFixer',
  mixins: [LinkMixin, ShareMixin],
  async mounted () {
    if (this.$route.query.verse) {
      this.text = this.$route.query.verse
      await this.goToStep2()
    }
  },
  watch: {
    composeType (val) {
      this.editableWords = val === 'kuralpaa'
        ? [['', '', '', ''], ['', '', '']]
        : [['', '', '', ''], ['', '', '', ''], ['', '', '', ''], ['', '', '']]
      this.parsedResult = null
    },
    inputMode (val) {
      if (val === 'compose') {
        this.editableWords = this.composeType === 'kuralpaa'
          ? [['', '', '', ''], ['', '', '']]
          : [['', '', '', ''], ['', '', '', ''], ['', '', '', ''], ['', '', '']]
        this.parsedResult = null
        this.text = ''
      } else {
        this.editableWords = []
        this.parsedResult = null
      }
    }
  },
  data () {
    return {
      step: 1,
      saving: false,
      inputMode: 'fix',
      composeType: 'venpaa',
      activeRunIndex: 0,
      text: '',
      editableWords: [],
      linkageRuns: [],
      activeChainKey: null,
      chainHighlightPositions: [],
      activeSolBounds: {},
      activeClassErrIdx: -1,
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
      return this.allFeetValid && this.linkageRuns.length === 0
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
    hasClassErrors () {
      return this.classErrors.length > 0
    },
    hasErrors () {
      return this.lines.length > 0 && (this.linkageRuns.length > 0 || this.classErrors.length > 0)
    },
    totalErrors () {
      return this.linkageRuns.length + this.classErrors.length
    },
    allRunBoundsMap () {
      var map = {}
      this.linkageRuns.forEach(function (run, ri) {
        var pts = run.leftAnchor ? [run.leftAnchor].concat(run.positions) : run.positions.slice()
        var lineBounds = {}
        pts.forEach(function (p) {
          if (!lineBounds[p.li]) lineBounds[p.li] = { min: p.fi, max: p.fi }
          else { lineBounds[p.li].min = Math.min(lineBounds[p.li].min, p.fi); lineBounds[p.li].max = Math.max(lineBounds[p.li].max, p.fi) }
        })
        Object.keys(lineBounds).forEach(function (li) {
          var b = lineBounds[li]
          map[li + '|' + ri] = b
        })
      })
      return map
    },
    allBoxesFilled () {
      return this.editableWords.length > 0 &&
        this.editableWords.every(function (line) { return line.every(function (w) { return w && w.trim() }) })
    }
  },
  methods: {
    wordAt (li, fi) {
      return (this.editableWords[li] && this.editableWords[li][fi]) || ''
    },
    updateWord (li, fi, value) {
      this.$set(this.editableWords[li], fi, value.replace(/ /g, ''))
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
    classErrBoundClass (li, fi) {
      var err = this.classErrors[this.activeClassErrIdx]
      if (!err || err.li !== li || err.fi !== fi) return ''
      return 'run-bound-single'
    },
    linePairs (li) {
      var line = this.lines[li]
      if (!line) return []
      var feet = line.feet
      var pairs = []
      for (var i = 0; i < feet.length; i += 2) {
        var items = [{ type: 'foot', fi: i }]
        if (i + 1 < feet.length) {
          items.push({ type: 'bond', fi: i + 1 })
          items.push({ type: 'foot', fi: i + 1 })
        }
        var crossFi = i + 2
        pairs.push({ items: items, crossBondFi: crossFi < feet.length ? crossFi : null })
      }
      return pairs
    },
    runBoundClass (li, fi) {
      var b = this.activeSolBounds[li]
      if (b && fi >= b.min && fi <= b.max) {
        if (b.min === b.max) return 'run-bound-single'
        if (fi === b.min) return 'run-bound-start'
        if (fi === b.max) return 'run-bound-end'
        return 'run-bound-mid'
      }
      for (var ri = 0; ri < this.linkageRuns.length; ri++) {
        var db = this.allRunBoundsMap[li + '|' + ri]
        if (db && fi >= db.min && fi <= db.max) {
          if (db.min === db.max) return 'run-bound-dim-single'
          if (fi === db.min) return 'run-bound-dim-start'
          if (fi === db.max) return 'run-bound-dim-end'
          return 'run-bound-dim-mid'
        }
      }
      return ''
    },
    runBondBoundClass (li, fi) {
      var b = this.activeSolBounds[li]
      if (b && fi - 1 >= b.min && fi <= b.max) return 'run-bound-bond'
      for (var ri = 0; ri < this.linkageRuns.length; ri++) {
        var db = this.allRunBoundsMap[li + '|' + ri]
        if (db && fi - 1 >= db.min && fi <= db.max) return 'run-bound-dim-bond'
      }
      return ''
    },
    footColClass (li, fi) {
      var key = li + '-' + fi
      if (this.step === 3) {
        if (this.chainHighlightPositions.indexOf(key) !== -1) return 'chain-sel'
        if (this.badChainPositions.has(key)) return 'chain-hi'
        return ''
      }
      var foot = this.lines[li] && this.lines[li].feet[fi]
      if (foot && !this.isFootClassValid(li, fi, foot)) return 'col-class-bad'
      return ''
    },
    footClassColClass (li, fi, foot) {
      var key = li + '-' + fi
      if (this.step === 3) {
        if (this.chainHighlightPositions.indexOf(key) !== -1) return 'chain-sel'
        if (this.badChainPositions.has(key)) return 'chain-hi'
        return 'class-ok'
      }
      if (!this.isFootClassValid(li, fi, foot)) return 'class-bad'
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
    isInAnyRun (li, fi) {
      for (var ri = 0; ri < this.linkageRuns.length; ri++) {
        var db = this.allRunBoundsMap[li + '|' + ri]
        if (db && fi >= db.min && fi <= db.max) return true
      }
      return false
    },
    unselectedRunEndAt (li, fi) {
      for (var ri = 0; ri < this.linkageRuns.length; ri++) {
        if (ri === this.activeRunIndex) continue
        var db = this.allRunBoundsMap[li + '|' + ri]
        if (db && db.max === fi) return ri
      }
      return -1
    },
    isActiveRunLine (li) {
      var run = this.linkageRuns[this.activeRunIndex]
      if (!run) return false
      var first = run.leftAnchor || run.positions[0]
      return first && first.li === li
    },
    selectRunByFoot (li, fi) {
      for (var ri = 0; ri < this.linkageRuns.length; ri++) {
        var run = this.linkageRuns[ri]
        var pts = run.leftAnchor ? [run.leftAnchor].concat(run.positions) : run.positions.slice()
        if (pts.some(function (p) { return p.li === li && p.fi === fi })) {
          this.selectRun(ri)
          return
        }
      }
    },
    selectRun (ri) {
      this.activeRunIndex = ri
      this.activeChainKey = null
      this.chainHighlightPositions = []
      var run = this.linkageRuns[ri]
      if (!run || !run.positions.length) { this.activeSolBounds = {}; return }
      var bounds = {}
      var addPoint = function (p) {
        if (!bounds[p.li]) bounds[p.li] = { min: p.fi, max: p.fi }
        else { bounds[p.li].min = Math.min(bounds[p.li].min, p.fi); bounds[p.li].max = Math.max(bounds[p.li].max, p.fi) }
      }
      if (run.leftAnchor) addPoint(run.leftAnchor)
      run.positions.forEach(addPoint)
      this.activeSolBounds = bounds
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
      // keep activeSolBounds from selectRun so the full-run rectangle stays
    },
    clearChain () {
      this.activeChainKey = null
      this.chainHighlightPositions = []
      // restore run-level bounding box if a run is still selected
      var run = this.linkageRuns[this.activeRunIndex]
      if (run && run.positions.length) {
        var bounds = {}
        var addPoint = function (p) {
          if (!bounds[p.li]) bounds[p.li] = { min: p.fi, max: p.fi }
          else { bounds[p.li].min = Math.min(bounds[p.li].min, p.fi); bounds[p.li].max = Math.max(bounds[p.li].max, p.fi) }
        }
        if (run.leftAnchor) addPoint(run.leftAnchor)
        run.positions.forEach(addPoint)
        this.activeSolBounds = bounds
      } else {
        this.activeSolBounds = {}
      }
    },
    ruleIcon (r) { return r === '1' ? 'check_circle' : r === 'info' ? 'info' : 'cancel' },
    ruleColor (r) { return r === '1' ? 'positive' : r === 'info' ? 'grey' : 'negative' },
    async goToStep2 () {
      if (this.inputMode === 'compose') {
        this.text = this.composedVerse
        this.parsedResult = null
      }
      if (!this.parsedResult) await this.parseVerse()
      if (this.step1Valid) {
        await this.fetchSuggestions()
        if (this.classErrors.length === 0) {
          await this.checkBondsForFix()
          if (this.linkageRuns.length === 0) this.step = 4
        } else {
          this.step = 2
        }
      }
    },
    async parseVerse () {
      if (!this.text || !this.text.trim()) { this.parsedResult = null; this.editableWords = []; return }
      this.text = this.text.split('\n').map(l => l.trim().replace(/ +/g, ' ')).join('\n').trim()
      this.showProgress = true
      const xml = await this.convertAsync(this.text)
      this.parsedResult = await this.getJson(xml)
      if (this.inputMode === 'fix' && this.parsedResult && this.parsedResult.verse && this.parsedResult.verse.MetricalLine) {
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
        if (this.step === 2 || this.step === 3) {
          await this.fetchSuggestions(verse)
          if (this.step === 3) {
            if (this.linkageRuns.length) this.selectRun(0)
            else { this.activeSolBounds = {}; this.activeChainKey = null; this.chainHighlightPositions = [] }
          }
        }
      } catch (_) {}
      this.showProgress = false
    },
    async checkBondsForFix () {
      this.showProgress = true
      try {
        await this.fetchSuggestions(this.composedVerse)
      } catch (_) {}
      this.showProgress = false
      this.step = 3
      this.$nextTick(() => { if (this.linkageRuns.length) this.selectRun(0) })
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
          this.activeRunIndex = 0
        }
      } catch (_) {
        this.linkageRuns = []
        this.activeRunIndex = 0
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
      this.inputMode = 'fix'
      this.composeType = 'venpaa'
      this.activeRunIndex = 0
      this.text = ''
      this.editableWords = []
      this.linkageRuns = []
      this.activeChainKey = null
      this.chainHighlightPositions = []
      this.activeSolBounds = {}
      this.activeClassErrIdx = -1
      this.parsedResult = null
    }
  }
}
</script>
