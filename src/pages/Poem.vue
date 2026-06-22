<template>
  <q-page padding>
    <div v-if="loading" class="flex flex-center q-mt-xl">
      <q-spinner-oval color="grey-8" size="3em" />
    </div>

    <div v-else-if="error" class="flex flex-center q-mt-xl">
      <div class="text-center">
        <q-icon name="error_outline" size="48px" color="grey-5" />
        <div class="tamil text-grey-6 q-mt-md">இந்த பா கண்டுபிடிக்க இயலவில்லை.</div>
        <q-btn flat class="tamil q-mt-md" color="grey-7" label="முகப்பிற்கு செல்க" @click="$router.push('/analyzer')" />
      </div>
    </div>

    <div v-else>
      <!-- Verse display -->
      <div class="row justify-center q-mb-lg">
        <div class="col-xs-12 col-md-8 col-lg-6">
          <q-card flat bordered class="q-pa-lg text-center">
            <div class="tamil verse-text q-mb-sm" style="white-space:pre-line;font-size:1.4em;line-height:2.2">{{ verse }}</div>
            <div class="text-grey-5 q-mt-xs" style="font-size:0.8em">{{ createdDate }}</div>
            <div class="text-grey-6 tamil q-mt-xs" style="font-size:0.9em" v-if="metre">{{ metre }}</div>
          </q-card>

          <div class="row justify-center q-gutter-xs q-mt-md items-center">
            <q-btn dense flat icon="link" label="பகிர்" color="grey-7" class="tamil" size="sm" @click="shareLink(verse)" />
            <q-btn dense flat icon="image" label="படம்" color="grey-7" class="tamil" size="sm" @click="downloadImage(verse)" />
            <q-btn dense flat size="sm" @click="shareX(verse)" title="X (Twitter)">
              <img src="statics/twitter.svg" style="width:16px;height:16px;opacity:0.6" />
            </q-btn>
            <q-btn dense flat size="sm" @click="shareFacebook(verse)" title="Facebook">
              <img src="statics/facebook.svg" style="width:16px;height:16px;opacity:0.6" />
            </q-btn>
            <q-btn dense flat size="sm" @click="shareInstagram(verse)" title="Instagram">
              <img src="statics/instagram.svg" style="width:16px;height:16px;opacity:0.6" />
            </q-btn>
            <q-btn dense flat icon="find_in_page" label="ஆராய்க" color="grey-7" class="tamil" size="sm" @click="$router.push({ path: '/analyzer', query: { text: verse } })" />
          </div>
        </div>
      </div>

      <!-- Analysis -->
      <div v-if="result" class="row justify-center">
        <div class="col-xs-12 col-md-10 col-lg-8">
          <div class="row q-mb-sm">
            <q-btn :color="view == 'eluttu' ? 'grey-10': 'grey-7'" icon="sort_by_alpha" label="எழுத்து" class="q-ma-xs tamil" dense @click="view = 'eluttu'" size="sm"/>
            <q-btn :color="view == 'acai' ? 'grey-10': 'grey-7'" icon="line_style" label="அசை/சீர்" class="q-ma-xs tamil" dense @click="view = 'acai'" size="sm"/>
            <q-btn :color="view == 'talai' ? 'grey-10': 'grey-7'" icon="link" label="தளை" class="q-ma-xs tamil" dense @click="view = 'talai'" size="sm"/>
            <q-btn :color="view == 'adi' ? 'grey-10': 'grey-7'" icon="format_align_justify" label="அடி" class="q-ma-xs tamil" dense @click="view = 'adi'" size="sm"/>
            <q-btn :color="view == 'all' ? 'grey-10': 'grey-7'" icon="info" label="அனைத்தும்" class="q-ma-xs tamil" dense @click="view = 'all'" size="sm"/>
          </div>
          <span v-if="view == 'eluttu'">
            <letter-display :metricalFeet="metricalFeetGet(result)" :letters="lettersGet(result)"></letter-display>
          </span>
          <span v-if="view == 'acai'">
            <scansion-display :metricalFeet="metricalFeetGet(result)"></scansion-display>
          </span>
          <span v-if="view == 'talai'">
            <linkage-display :metricalFeet="metricalFeetGet(result)" :linkage="linkageGet(result)" :key="updateKey"></linkage-display>
          </span>
          <span v-if="view == 'adi'">
            <line-display :metricalFeet="metricalFeetGet(result)" :linetypes="linetypesGet(result)"></line-display>
          </span>
          <span v-if="view == 'all'">
            <scansion-all :metricalFeet="metricalFeetGet(result)" :hide="true" :linkage="linkageGet(result)" :linetypes="linetypesGet(result)" :checkvenpa="false" :venpalastword="vepaLastWordClass(result)"></scansion-all>
          </span>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { LinkMixin } from '../mixin/LinkMixin'
import { ShareMixin } from '../mixin/ShareMixin'
import ScansionDisplay from '../components/ScansionDisplay'
import LetterDisplay from '../components/LetterDisplay'
import LinkageDisplay from '../components/LinkageDisplay'
import LineDisplay from '../components/LineDisplay'
import ScansionAll from '../components/ScansionAll'

const AI_BACKEND = process.env.AI_BACKEND || ''

export default {
  name: 'PoemPage',
  mixins: [LinkMixin, ShareMixin],
  components: { ScansionDisplay, LetterDisplay, LinkageDisplay, LineDisplay, ScansionAll },
  data () {
    return {
      loading: true,
      error: false,
      verse: '',
      metre: '',
      createdAt: null,
      result: null,
      view: 'all',
      updateKey: 0
    }
  },
  computed: {
    createdDate () {
      if (!this.createdAt) return ''
      return new Date(this.createdAt).toLocaleDateString('ta-IN', { year: 'numeric', month: 'long', day: 'numeric' })
    }
  },
  async mounted () {
    const id = this.$route.params.id
    try {
      const resp = await fetch(`${AI_BACKEND}/compositions/${id}`)
      if (!resp.ok) { this.error = true; this.loading = false; return }
      const comp = await resp.json()
      this.verse = comp.verse
      this.metre = comp.metre
      this.createdAt = comp.created_at
      this.loading = false
      // Run analysis
      const xml = await this.convertAsync(comp.verse)
      this.result = await this.getJson(xml)
    } catch (e) {
      this.error = true
      this.loading = false
    }
  },
  methods: {
    _shareUrl () {
      return window.location.href
    },
    shareLink () {
      const url = window.location.href
      const notify = () => this.$q.notify({ message: 'இணைப்பு நகலெடுக்கப்பட்டது!', color: 'grey-8', position: 'top', timeout: 2000 })
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(notify).catch(() => { this._fallbackCopy(url); notify() })
      } else {
        this._fallbackCopy(url)
        notify()
      }
    }
  }
}
</script>

<style scoped>
.verse-text {
  font-family: 'Mukta Malar', serif;
  letter-spacing: 0.02em;
}
</style>
