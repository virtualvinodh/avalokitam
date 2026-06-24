<template>
  <q-page>
    <div :class="$q.screen.lt.md ? 'column' : 'row no-wrap'">

      <!-- LEFT: inputs -->
      <div :style="$q.screen.lt.md ? '' : 'width:35%;min-width:280px;max-width:420px'">
        <div class="q-ma-md">

          <!-- Global daily availability banner -->
          <q-banner
            v-if="globalRemaining !== null"
            dense
            :class="globalRemaining > 20 ? 'bg-green-1 text-green-9' : globalRemaining > 0 ? 'bg-orange-1 text-orange-9' : 'bg-red-1 text-red-9'"
            class="tamil q-mb-md rounded-borders"
            style="font-size:0.85rem"
          >
            <template v-slot:avatar>
              <q-icon :name="globalRemaining > 20 ? 'check_circle' : globalRemaining > 0 ? 'warning' : 'block'" />
            </template>
            <span v-if="globalRemaining > 0">இன்று <strong>{{ globalRemaining }}</strong> AI உருவாக்கல்கள் மீதமுள்ளன</span>
            <span v-else>இன்றைய AI உருவாக்கல்கள் முடிந்தன — நாளை மீண்டும் முயற்சிக்கவும்</span>
          </q-banner>

          <!-- Mode Toggle -->
          <q-btn-toggle
            v-model="mode"
            spread
            no-caps
            toggle-color="grey-9"
            color="grey-3"
            text-color="grey-9"
            class="tamil q-mb-lg"
            :options="[
              { label: 'வெண்பா இயற்றுக', value: 'generate', icon: 'auto_awesome' },
              { label: 'பிழை திருத்துக', value: 'fix', icon: 'build' }
            ]"
          />

          <!-- Verse variant selector (generate mode only) -->
          <div v-if="mode === 'generate'" class="q-mb-md">
            <div class="text-caption text-grey-6 tamil q-mb-xs">பா வகை</div>
            <q-btn-toggle
              v-model="verseType"
              spread
              no-caps
              dense
              toggle-color="grey-8"
              color="grey-2"
              text-color="grey-8"
              class="tamil"
              :options="[
                { label: 'வெண்பா', value: 'venpaa' },
                { label: 'குறள் வெண்பா', value: 'kuralpaa' }
              ]"
            />
          </div>

          <!-- Generate: topic input -->
          <div v-if="mode === 'generate'">
            <q-input
              v-model="topic"
              outlined
              clearable
              type="textarea"
              :rows="3"
              :maxlength="200"
              counter
              hint="அதிகபட்சம் 200 எழுத்துகள்"
              class="tamil q-mb-md"
              label="தலைப்பு அல்லது கருத்து"
              placeholder="எ.கா: தாய் அன்பு, மழை, நட்பு..."
            />
          </div>

          <!-- Fix: verse input -->
          <div v-if="mode === 'fix'">
            <q-input
              v-model="inputVerse"
              outlined
              clearable
              type="textarea"
              :rows="6"
              :maxlength="500"
              counter
              hint="அதிகபட்சம் 500 எழுத்துகள்"
              class="tamil q-mb-md"
              label="திருத்த வேண்டிய பா"
              placeholder="பிழையுள்ள பாவை இங்கே இடுக..."
            />
          </div>

          <!-- Per-session usage indicator -->
          <div v-if="remaining !== null" class="q-mb-sm">
            <q-badge
              :color="remaining > 1 ? 'grey-6' : remaining === 1 ? 'orange-8' : 'red-8'"
              class="tamil"
            >
              {{ remaining > 0 ? `உங்களுக்கு ${remaining} இலவச பயன்பாடு மீதமுள்ளது` : 'இலவச பயன்பாடு முடிந்தது' }}
            </q-badge>
          </div>

          <!-- Run Button -->
          <q-btn
            color="grey-9"
            :label="mode === 'fix' ? 'திருத்துக' : (verseType === 'kuralpaa' ? 'குறள் வெண்பா இயற்றுக' : 'வெண்பா இயற்றுக')"
            :icon="mode === 'generate' ? 'auto_awesome' : 'build'"
            :loading="loading"
            :disable="loading || remaining === 0 || (mode === 'generate' ? !(topic || '').trim() : !(inputVerse || '').trim())"
            class="tamil full-width q-mb-md"
            size="md"
            @click="run"
          >
            <template v-slot:loading>
              <q-spinner-dots class="on-left" />
              <span class="tamil">AI செயல்படுகிறது...</span>
            </template>
          </q-btn>
          <div class="text-caption text-grey-5 text-center tamil q-mt-xs">உங்கள் உரைக்கோளும் பாவும் மேம்பாட்டிற்காக பதிவு செய்யப்படும்.</div>

          <!-- Token stats (visible only with ?dev=1) -->
          <q-expansion-item
            v-if="showStats && usageStats"
            dense
            icon="bar_chart"
            label="Token Usage"
            header-class="text-caption text-grey-6 q-pl-none"
            class="q-mb-sm"
          >
            <div class="text-caption q-pa-xs" style="font-family:monospace; line-height:1.8">
              <div class="text-grey-5 q-mb-xs">This generation</div>
              <div v-if="genTokens">
                in: {{ genTokens.input.toLocaleString() }} &nbsp; out: {{ genTokens.output.toLocaleString() }} &nbsp; think: {{ genTokens.thinking.toLocaleString() }}
                &nbsp;·&nbsp; <span class="text-green-7">${{ genTokens.cost.toFixed(4) }}</span>
              </div>
              <div v-else class="text-grey-6">—</div>
              <q-separator class="q-my-xs" />
              <div class="text-grey-5 q-mb-xs">Daily · Monthly · Overall</div>
              <div v-for="(period, key) in { Today: usageStats.daily, Month: usageStats.monthly, Total: usageStats.overall }" :key="key">
                <span class="text-grey-6" style="display:inline-block;width:42px">{{ key }}</span>
                {{ period.input.toLocaleString() }} / {{ period.output.toLocaleString() }} / {{ period.thinking.toLocaleString() }}
                &nbsp;·&nbsp; {{ period.generations }} gen
                &nbsp;·&nbsp; <span class="text-green-7">${{ (period.cost || 0).toFixed(4) }}</span>
              </div>
            </div>
          </q-expansion-item>

          <!-- Error -->
          <q-banner v-if="errorMsg" class="bg-red-8 text-white tamil q-mt-sm" dense rounded>
            <template v-slot:avatar><q-icon name="error" /></template>
            {{ errorMsg }}
          </q-banner>

        </div>
      </div>

      <!-- RIGHT: iteration progress + result -->
      <div class="col">
        <div class="q-ma-md">

          <!-- Empty state -->
          <div v-if="!loading && iterations.length === 0" class="text-center q-mt-xl text-grey-5">
            <q-icon name="auto_awesome" size="60px" class="q-mb-md" />
            <div class="tamil text-h6">
              {{ mode === 'generate' ? 'தலைப்பு கொடுத்து பா இயற்றுங்கள்' : 'பிழையுள்ள பாவை இட்டு திருத்துங்கள்' }}
            </div>
            <div class="tamil text-caption q-mt-sm text-grey-6">AI வெண்பா இயற்றும்; யாப்பு பகுப்பாளர் சரிபார்க்கும்; பிழையிருந்தால் திருத்தி மீளும்.</div>
          </div>

          <!-- Live status: Gemini thinking -->
          <div v-if="currentThinking !== null" class="q-mb-md">
            <div class="row items-center q-mb-xs">
              <q-chip color="blue-8" text-color="white" size="sm" icon="psychology" class="tamil">
                முயற்சி {{ currentThinking.attempt }} — AI யோசிக்கிறது...
              </q-chip>
            </div>
            <div class="q-pa-sm rounded-borders row items-center" style="background:#f0f4ff">
              <q-spinner-dots color="blue-8" size="24px" class="q-mr-sm" />
              <span class="tamil text-grey-7">பா இயற்றப்படுகிறது...</span>
            </div>
            <q-expansion-item v-if="showStats" dense label="AI-க்கு அனுப்பிய உள்ளீடு" header-class="text-caption text-grey-6 q-pl-none" class="q-mt-xs">
              <div class="q-pa-sm rounded-borders text-caption" style="background:#eef2ff; white-space:pre-wrap; font-family:monospace; font-size:0.75em; max-height:200px; overflow-y:auto">{{ currentThinking.prompt }}</div>
            </q-expansion-item>
          </div>

          <!-- Live status: Parser checking -->
          <div v-if="currentChecking !== null" class="q-mb-md">
            <div class="row items-center q-mb-xs">
              <q-chip color="orange-8" text-color="white" size="sm" icon="rule" class="tamil">
                முயற்சி {{ currentChecking.attempt }} — யாப்பு சரிபார்க்கிறது...
              </q-chip>
            </div>
            <div
              class="tamil q-pa-sm rounded-borders q-mb-xs"
              style="background:#fff8f0; white-space:pre-line; font-size:1.05em; line-height:1.8; opacity:0.8; font-family:inherit"
            >{{ currentChecking.verse }}</div>
            <div class="q-pa-sm rounded-borders row items-center" style="background:#fff8f0">
              <q-spinner-dots color="orange-8" size="20px" class="q-mr-sm" />
              <span class="tamil text-grey-7">யாப்பு பகுப்பாளர் சரிபார்க்கிறது...</span>
            </div>
          </div>

          <!-- Iterations -->
          <div v-if="iterations.length > 0">
            <div class="text-caption text-grey-6 tamil q-mb-sm">செயல்முறை நிலை</div>

            <div v-for="(iter, i) in iterations" :key="i" class="q-mb-md">
              <div class="row items-center q-mb-xs">
                <q-chip
                  :color="iter.parserError ? 'grey-6' : iter.errors && iter.errors.length === 0 ? 'positive' : 'orange-8'"
                  text-color="white"
                  size="sm"
                  class="tamil"
                  :icon="iter.parserError ? 'warning' : iter.errors && iter.errors.length === 0 ? 'check_circle' : 'sync'"
                >
                  முயற்சி {{ iter.attempt }}
                  <span v-if="iter.parserError"> — பகுப்பாய்வு தோல்வி</span>
                  <span v-else-if="iter.errors && iter.errors.length === 0"> — சரியானது</span>
                  <span v-else-if="iter.errors"> — {{ iter.errors.length }} பிழை</span>
                </q-chip>
                <q-chip v-if="iter.metreType" color="grey-7" text-color="white" size="sm" class="tamil q-ml-xs">
                  {{ iter.metreType }}
                </q-chip>
              </div>

              <!-- Verse output -->
              <template v-if="success && i === iterations.length - 1 && finalVerse">
                <!-- 1. Final verse on dark background + action buttons -->
                <div class="rounded-borders q-mb-sm" style="background:#1a1a1a">
                  <div class="tamil q-pa-md text-white" style="white-space:pre-line; font-size:1.15em; line-height:1.9">{{ finalVerse }}</div>
                  <div class="row q-px-sm q-pb-sm">
                    <q-btn flat dense dark icon="file_copy" label="நகலெடு" class="tamil text-grey-4" size="sm" @click="copyVerse" />
                    <q-btn flat dense dark icon="bookmark" label="சேமி" class="tamil text-grey-4" size="sm" :loading="saving" @click="saveAndCopy(finalVerse)" />
                    <q-btn flat dense dark icon="image" label="படம்" class="tamil text-grey-4" size="sm" @click="downloadImage(finalVerse)" />
                    <q-btn flat dense dark icon="share" label="பகிர்" class="tamil text-grey-4" size="sm" @click="shareInstagram(finalVerse)" />
                    <q-btn flat dense dark icon="find_in_page" label="ஆராய்க" class="tamil text-grey-4" size="sm" @click="openInAnalyzer" />
                    <q-btn flat dense dark icon="refresh" label="மீண்டும்" class="tamil text-grey-4" size="sm" @click="run" />
                  </div>
                  <div class="q-px-sm q-pb-sm">
                    <q-checkbox v-model="compositionIsPublic" dark dense size="xs" color="grey-5" label="பொது தொகுப்பில் சேர்க்க" class="tamil text-grey-5" style="font-size:0.75em;opacity:0.7" />
                  </div>
                </div>
                <!-- 2. Sandhi split -->
                <div v-if="sandhi" class="q-mb-sm">
                  <div class="text-caption text-grey-5 tamil q-px-sm q-pt-xs">சந்திபிரித்த செய்யுள்</div>
                  <div class="tamil q-pa-sm rounded-borders" style="background:#f5f5f5; white-space:pre-line; font-size:1.05em; line-height:1.8; color:#333">{{ sandhi }}</div>
                </div>
                <!-- 3. Literal -->
                <div v-if="literal" class="q-mb-xs">
                  <div class="text-caption text-grey-5 tamil q-px-sm q-pt-xs">பொழிப்புரை</div>
                  <div class="tamil q-pa-sm text-grey-6" style="font-size:0.9em; line-height:1.7; font-style:italic">{{ literal }}</div>
                </div>
                <!-- 4. Meaning -->
                <div v-if="explanation" class="q-mb-sm">
                  <div class="text-caption text-grey-5 tamil q-px-sm q-pt-xs">தெளிவுரை</div>
                  <div class="tamil q-pa-sm text-grey-7" style="font-size:0.95em; line-height:1.7">{{ explanation }}</div>
                </div>
                <!-- Loading sandhi + explanation -->
                <div v-if="loadingExtra && !explanation" class="row items-center q-pa-sm q-mb-sm text-grey-5">
                  <q-spinner-dots size="18px" class="q-mr-sm" />
                  <span class="tamil text-caption">விளக்கம் உருவாக்கப்படுகிறது...</span>
                </div>
                <!-- 4. ScansionAll -->
                <div v-if="iter.parsedResult" class="q-pa-sm rounded-borders" style="background:#f5f5f5; overflow-x:auto">
                  <scansion-all
                    :metricalFeet="metricalFeetGet(iter.parsedResult)"
                    :hide="true"
                    :linkage="linkageGet(iter.parsedResult)"
                    :linetypes="linetypesGet(iter.parsedResult)"
                    :checkvenpa="true"
                    :venpalastword="safeVenpaLastWord(iter.parsedResult)"
                  />
                </div>
              </template>
              <div v-else class="q-pa-sm rounded-borders" style="background:#f5f5f5">
                <div class="tamil" style="white-space:pre-line; font-size:1.05em; line-height:1.8">{{ iter.verse }}</div>
              </div>

              <!-- Errors list -->
              <div v-if="iter.errors && iter.errors.length > 0" class="q-mt-xs q-pl-sm">
                <div
                  v-for="(err, j) in iter.errors"
                  :key="j"
                  class="text-caption text-negative tamil q-mt-xs"
                >
                  <q-icon name="warning" size="12px" class="q-mr-xs" />{{ err.rule }}
                </div>
              </div>

              <!-- Debug: prompt sent to AI -->
              <q-expansion-item v-if="showStats && iter.prompt" dense label="AI-க்கு அனுப்பிய உள்ளீடு" header-class="text-caption text-grey-5 q-pl-none" class="q-mt-xs">
                <div class="q-pa-sm rounded-borders text-caption" style="background:#f5f5f5; white-space:pre-wrap; font-family:monospace; font-size:0.75em; max-height:200px; overflow-y:auto">{{ iter.prompt }}</div>
              </q-expansion-item>

              <!-- Connector line between iterations -->
              <div v-if="i < iterations.length - 1" class="q-ml-sm q-my-xs" style="border-left:2px dashed #ccc; height:16px; margin-left:12px"/>
            </div>

            <!-- Best-effort banner (success uses the black card above) -->
            <q-banner
              v-if="finalVerse && !success"
              class="bg-orange-9 text-white tamil q-mt-md rounded-borders"
            >
              <template v-slot:avatar>
                <q-icon name="info" size="28px" />
              </template>
              <div class="text-caption q-mb-xs">சிறந்த முயற்சி — சில பிழைகள் இருக்கலாம்</div>
              <div class="tamil" style="white-space:pre-line; font-size:1.15em; line-height:1.9">{{ finalVerse }}</div>
              <template v-slot:action>
                <q-btn flat dense icon="file_copy" label="நகலெடு" class="tamil" @click="copyVerse" />
                <q-btn flat dense icon="bookmark" label="சேமி" class="tamil" :loading="saving" @click="saveAndCopy(finalVerse)" />
                <q-btn flat dense icon="image" label="படம்" class="tamil" @click="downloadImage(finalVerse)" />
                <q-btn flat dense icon="share" label="பகிர்" class="tamil" @click="shareInstagram(finalVerse)" />
                <q-btn flat dense icon="build" label="சுயமாக திருத்துக" class="tamil" @click="openInFixer" />
                <q-btn flat dense icon="refresh" label="மீண்டும்" class="tamil" @click="run" />
                <q-checkbox v-model="compositionIsPublic" dense size="xs" color="grey-5" label="பொது தொகுப்பில் சேர்க்க" class="tamil text-grey-5" style="font-size:0.75em;opacity:0.7" />
              </template>
            </q-banner>

            <!-- Donate banner — shown after first successful generation, once per session -->
            <q-banner
              v-if="showDonateBanner"
              dense
              class="bg-blue-1 text-blue-9 tamil q-mt-md rounded-borders"
              style="font-size:0.85rem"
            >
              <template v-slot:avatar>
                <q-icon name="favorite" color="blue-7" />
              </template>
              AI வெண்பா உருவாக்கல் ஒவ்வொன்றும் எங்களுக்கு சிறிய செலவை ஏற்படுத்துகிறது. இதை தொடர உதவ விரும்புகிறீர்களா?
              <template v-slot:action>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="q-mr-sm">
                  <input type="hidden" name="cmd" value="_s-xclick" />
                  <input type="hidden" name="hosted_button_id" value="DLDU4TRF7KCNC" />
                  <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                  <img alt="" border="0" src="https://www.paypal.com/en_IN/i/scr/pixel.gif" width="1" height="1" />
                </form>
                <q-btn flat dense icon="close" class="text-blue-7" @click="showDonateBanner = false" />
              </template>
            </q-banner>
          </div>

        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import ScansionAll from '../components/ScansionAll'
import { LinkMixin } from '../mixin/LinkMixin'
import { ShareMixin } from '../mixin/ShareMixin'

function copyToClipboard (text) {
  const el = document.createElement('textarea')
  el.value = text
  el.style.cssText = 'position:fixed;top:0;left:0;opacity:0'
  document.body.appendChild(el)
  el.focus()
  el.select()
  const ok = document.execCommand('copy')
  document.body.removeChild(el)
  return ok ? Promise.resolve() : Promise.reject(new Error('copy failed'))
}

const AI_BACKEND = process.env.AI_BACKEND || ''

function getOrCreateSessionId () {
  let id = localStorage.getItem('ai_session_id')
  if (!id) {
    id = 'sess_' + Math.random().toString(36).slice(2) + Date.now().toString(36)
    localStorage.setItem('ai_session_id', id)
  }
  return id
}

export default {
  name: 'PageAI',
  components: { ScansionAll },
  mixins: [LinkMixin, ShareMixin],
  data () {
    return {
      compositionSource: 'ai',
      compositionIsPublic: true,
      compositionPrompt: null,
      compositionLogId: null,
      logId: null,
      mode: 'generate',
      verseType: 'venpaa',
      topic: '',
      inputVerse: '',
      loading: false,
      iterations: [],
      finalVerse: null,
      success: false,
      showDonateBanner: false,
      errorMsg: null,
      currentThinking: null,
      currentChecking: null,
      pendingPrompt: null,
      remaining: null,
      globalRemaining: null,
      saving: false,
      explanation: null,
      sandhi: null,
      literal: null,
      loadingExtra: false,
      genTokens: null,
      usageStats: null
    }
  },
  mounted () {
    if (this.$route.query.verse) {
      this.inputVerse = this.$route.query.verse
      this.mode = 'fix'
      this.$nextTick(() => this.run())
    }
    const usageHeaders = { 'X-Session-Id': getOrCreateSessionId() }
    if (this.devToken) usageHeaders['X-Dev-Token'] = this.devToken
    fetch(AI_BACKEND + '/ai/usage', { headers: usageHeaders })
      .then(r => r.json())
      .then(d => { this.remaining = d.remaining; this.globalRemaining = d.globalRemaining !== undefined ? d.globalRemaining : null })
      .catch(() => {})
    this.fetchStats()
  },
  computed: {
    devToken () {
      return this.$route.query.dev || null
    },
    showStats () {
      return !!this.devToken
    }
  },
  watch: {
    mode () {
      this.reset()
    }
  },
  methods: {
    reset () {
      this.iterations = []
      this.finalVerse = null
      this.success = false
      this.showDonateBanner = false
      this.errorMsg = null
      this.currentThinking = null
      this.currentChecking = null
      this.pendingPrompt = null
      this.explanation = null
      this.sandhi = null
      this.literal = null
      this.loadingExtra = false
      this.genTokens = null
    },
    async run () {
      if (this.loading || this.remaining === 0) return
      this.reset()
      this.loading = true
      this.compositionPrompt = this.mode === 'generate' ? (this.topic || null) : null

      const detectedType = this.mode === 'fix'
        ? (this.inputVerse || '').split('\n').filter(l => l.trim()).length === 2 ? 'kuralpaa' : 'venpaa'
        : this.verseType
      const body = {
        mode: this.mode,
        verseType: detectedType,
        ...(this.mode === 'generate' ? { topic: this.topic } : { verse: this.inputVerse })
      }

      try {
        const headers = { 'Content-Type': 'application/json', 'X-Session-Id': getOrCreateSessionId() }
        if (this.devToken) headers['X-Dev-Token'] = this.devToken
        const response = await fetch(AI_BACKEND + '/ai/stream', {
          method: 'POST',
          headers,
          body: JSON.stringify(body)
        })

        if (response.status === 403) {
          this.errorMsg = 'இலவச பயன்பாடு முடிந்தது. மேலும் பயன்படுத்த உள்நுழைக.'
          this.remaining = 0
          this.loading = false
          return
        }

        if (response.status === 503) {
          this.errorMsg = 'இன்றைய AI பயன்பாடு முடிந்தது. நாளை மீண்டும் முயலுங்கள்.'
          this.loading = false
          return
        }

        if (!response.ok) throw new Error('Backend error ' + response.status)

        const reader = response.body.getReader()
        const decoder = new TextDecoder()
        let buffer = ''

        while (true) {
          const { done, value } = await reader.read()
          if (done) break
          buffer += decoder.decode(value, { stream: true })
          const lines = buffer.split('\n')
          buffer = lines.pop()

          for (const line of lines) {
            if (!line.startsWith('data: ')) continue
            let event
            try { event = JSON.parse(line.slice(6)) } catch (e) { continue }

            if (event.type === 'thinking') {
              this.currentThinking = { attempt: event.attempt, prompt: event.prompt }
              this.currentChecking = null
            } else if (event.type === 'checking') {
              this.pendingPrompt = this.currentThinking ? this.currentThinking.prompt : null
              this.currentChecking = { attempt: event.attempt, verse: event.verse, thinking: event.thinking || null }
              this.currentThinking = null
            } else if (event.type === 'iteration') {
              const prompt = this.pendingPrompt
              const thinking = this.currentChecking ? this.currentChecking.thinking : null
              this.pendingPrompt = null
              this.currentChecking = null
              this.currentThinking = null
              const idx = this.iterations.length
              this.iterations.push({ ...event, prompt, thinking, parsedResult: null })
              if (event.xml) {
                this.getJson(event.xml).then(parsed => {
                  this.$set(this.iterations, idx, { ...this.iterations[idx], parsedResult: parsed })
                })
              }
            } else if (event.type === 'done') {
              this.finalVerse = event.verse
              this.success = event.success
              this.loading = false
              if (event.success) { this.loadingExtra = true; this.showDonateBanner = true }
            } else if (event.type === 'tokens') {
              this.genTokens = event
              this.fetchStats()
            } else if (event.type === 'sandhi') {
              this.sandhi = event.text
            } else if (event.type === 'literal') {
              this.literal = event.text
            } else if (event.type === 'explanation') {
              this.explanation = event.text
              this.loadingExtra = false
            } else if (event.type === 'log_id') {
              this.logId = event.id
              this.compositionLogId = event.id
            } else if (event.type === 'usage') {
              this.remaining = event.remaining
              if (event.globalRemaining !== undefined) this.globalRemaining = event.globalRemaining
            } else if (event.type === 'error') {
              this.errorMsg = event.message
              this.loading = false
            }
          }
        }
      } catch (err) {
        this.errorMsg = 'AI backend-ஐ அணுக முடியவில்லை. Backend இயங்குகிறதா என சரிபாருங்கள்.'
        this.loading = false
      }
    },
    fetchStats () {
      fetch(AI_BACKEND + '/health')
        .then(r => r.json())
        .then(d => { this.usageStats = d.stats })
        .catch(() => {})
    },
    safeVenpaLastWord (result) {
      try { return this.vepaLastWordClass(result) } catch (e) { return 'None' }
    },
    copyVerse () {
      copyToClipboard(this.finalVerse)
        .then(() => this.$q.notify({ message: 'நகலெடுக்கப்பட்டது', color: 'grey-8', position: 'top' }))
        .catch(() => this.$q.notify({ message: 'நகலெடுக்க முடியவில்லை', color: 'red-8', position: 'top' }))
    },
    openInAnalyzer () {
      this.$router.push({ path: '/analyzer', query: { text: this.finalVerse } })
    },
    openInFixer () {
      fetch(AI_BACKEND + '/ai/event', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ type: 'fix_click' }) }).catch(() => {})
      const query = { verse: this.finalVerse }
      if (this.logId) query.log_id = this.logId
      this.$router.push({ path: '/venpa-fixer', query })
    }
  }
}
</script>
