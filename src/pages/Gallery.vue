<template>
  <q-page padding>
    <div class="row justify-center">
      <div class="col-xs-12 col-sm-10 col-md-7 col-lg-5">

        <div v-if="loading" class="flex flex-center q-mt-xl">
          <q-spinner-oval color="grey-6" size="3em" />
        </div>

        <div v-else-if="!current" class="text-center text-grey-5 tamil q-mt-xl">
          இன்னும் பாக்கள் இல்லை.
        </div>

        <div v-else>
          <!-- Counter -->
          <div class="row items-center justify-between q-mb-md">
            <div class="text-caption text-grey-5">{{ index + 1 }} / {{ total }}</div>
            <q-btn flat dense label="Random" icon="shuffle" color="grey-7" size="sm" @click="goRandom" />
          </div>

          <!-- Verse card -->
          <q-card flat bordered class="q-mb-md">
            <q-card-section class="cursor-pointer" @click.native="$router.push('/poem/' + current.id)">
              <div v-if="current.prompt" class="text-caption text-grey-5 tamil q-mb-sm">{{ current.prompt }}</div>
              <div class="tamil" style="white-space:pre-line;font-size:1.2em;line-height:2">{{ current.verse }}</div>
              <div class="row items-center justify-between q-mt-sm">
                <span class="text-caption text-grey-5 tamil">{{ current.metre }}</span>
                <span class="text-caption text-grey-5">{{ formatDate(current.created_at) }}</span>
              </div>
            </q-card-section>

            <template v-if="current.sandhi || current.literal || current.explanation">
              <q-separator />
              <q-card-section v-if="current.sandhi" class="q-py-sm">
                <div class="text-caption text-grey-5 tamil q-mb-xs">சந்திபிரித்த செய்யுள்</div>
                <div class="tamil text-grey-7" style="white-space:pre-line;font-size:0.95em;line-height:1.8">{{ current.sandhi }}</div>
              </q-card-section>
              <q-card-section v-if="current.literal" class="q-py-sm">
                <div class="text-caption text-grey-5 tamil q-mb-xs">தெளிவுரை</div>
                <div class="tamil text-grey-7" style="font-size:0.95em;line-height:1.6">{{ current.literal }}</div>
              </q-card-section>
              <q-card-section v-if="current.explanation" class="q-py-sm">
                <div class="text-caption text-grey-5 tamil q-mb-xs">பொழிப்புரை</div>
                <div class="tamil text-grey-7" style="font-size:0.95em;line-height:1.6">{{ current.explanation }}</div>
              </q-card-section>
            </template>
          </q-card>

          <!-- Prev / Next -->
          <div class="row justify-between">
            <q-btn flat dense icon="chevron_left" label="முந்தையது" class="tamil" color="grey-7" :disable="index === 0" @click="go(index - 1)" />
            <q-btn flat dense icon-right="chevron_right" label="அடுத்தது" class="tamil" color="grey-7" :disable="index === total - 1" @click="go(index + 1)" />
          </div>
        </div>

      </div>
    </div>
  </q-page>
</template>

<script>
const AI_BACKEND = process.env.AI_BACKEND || ''

export default {
  name: 'GalleryPage',
  data () {
    return {
      loading: false,
      current: null,
      index: 0,
      total: 0
    }
  },
  mounted () {
    this.go(0)
  },
  methods: {
    async go (idx) {
      this.loading = true
      try {
        const resp = await fetch(`${AI_BACKEND}/compositions/public?page=${idx + 1}&limit=1`)
        const data = await resp.json()
        if (data.compositions && data.compositions.length) {
          this.current = data.compositions[0]
          this.index = idx
          this.total = data.total
        }
      } finally {
        this.loading = false
      }
    },
    async goRandom () {
      this.loading = true
      try {
        const resp = await fetch(`${AI_BACKEND}/compositions/public/random`)
        const data = await resp.json()
        this.current = data
        this.index = Math.floor(Math.random() * this.total)
      } finally {
        this.loading = false
      }
    },
    formatDate (ts) {
      return new Date(ts).toLocaleDateString('ta-IN', { year: 'numeric', month: 'short', day: 'numeric' })
    }
  }
}
</script>
