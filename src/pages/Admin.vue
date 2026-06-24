<template>
  <q-page padding>
    <div v-if="!authed" class="flex flex-center q-mt-xl">
      <q-card style="width:320px">
        <q-card-section>
          <div class="text-h6 q-mb-md">Admin</div>
          <q-input v-model="tokenInput" label="Token" type="password" outlined @keyup.enter="login" />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn label="உள்நுழை" color="grey-8" @click="login" />
        </q-card-actions>
      </q-card>
    </div>

    <div v-else>
      <q-tabs v-model="tab" dense align="left" class="q-mb-md" active-color="grey-9" indicator-color="grey-9">
        <q-tab name="verses" label="பாக்கள்" />
        <q-tab name="stats" label="புள்ளிவிவரம்" />
      </q-tabs>

      <!-- Verses tab -->
      <div v-if="tab === 'verses'">
        <div class="row items-center q-mb-sm">
          <div class="text-subtitle1">சேமிக்கப்பட்ட பாக்கள் <q-chip dense color="grey-7" text-color="white">{{ total }}</q-chip></div>
        </div>
        <q-table
          :data="compositions"
          :columns="verseCols"
          :loading="loadingVerses"
          row-key="id"
          flat bordered dense
          :pagination="{rowsPerPage: 0}"
          hide-pagination
        >
          <template v-slot:body-cell-verse="props">
            <q-td :props="props">
              <div class="tamil" style="white-space:pre-line;font-size:0.85em;line-height:1.6">{{ props.value }}</div>
            </q-td>
          </template>
          <template v-slot:body-cell-id="props">
            <q-td :props="props">
              <a :href="'/poem/' + props.value" target="_blank" class="text-grey-7">{{ props.value }}</a>
            </q-td>
          </template>
          <template v-slot:body-cell-created_at="props">
            <q-td :props="props">{{ new Date(props.value).toLocaleString('ta-IN') }}</q-td>
          </template>
        </q-table>
        <div class="flex flex-center q-mt-md">
          <q-pagination v-model="versePage" :max="versePages" :max-pages="7" boundary-links @input="fetchVerses" color="grey-8" />
        </div>
      </div>

      <!-- Stats tab -->
      <div v-if="tab === 'stats'">
        <div class="row items-center q-mb-sm">
          <div class="text-subtitle1">கடந்த 60 நாட்கள்</div>
        </div>
        <q-table
          :data="dailyStats"
          :columns="statCols"
          :loading="loadingStats"
          row-key="date"
          flat bordered dense
          :pagination="{rowsPerPage: 0}"
          hide-pagination
        >
          <template v-slot:body-cell-avg_attempts="props">
            <q-td :props="props">{{ props.value }}</q-td>
          </template>
          <template v-slot:body-cell-first_try_pct="props">
            <q-td :props="props">{{ props.value }}</q-td>
          </template>
          <template v-slot:body-cell-cost="props">
            <q-td :props="props">${{ props.value }}</q-td>
          </template>
        </q-table>
      </div>
    </div>
  </q-page>
</template>

<script>
const AI_BACKEND = process.env.AI_BACKEND || ''

export default {
  name: 'AdminPage',
  data () {
    return {
      tab: 'verses',
      tokenInput: '',
      token: '',
      authed: false,

      loadingVerses: false,
      compositions: [],
      total: 0,
      versePage: 1,
      versePages: 1,
      verseCols: [
        { name: 'id', label: 'இணைப்பு', field: 'id', align: 'left', style: 'width:80px' },
        { name: 'metre', label: 'யாப்பு', field: 'metre', align: 'left', style: 'width:140px' },
        { name: 'verse', label: 'பா', field: 'verse', align: 'left' },
        { name: 'created_at', label: 'தேதி', field: 'created_at', align: 'left', style: 'width:160px' }
      ],

      loadingStats: false,
      dailyStats: [],
      statCols: [
        { name: 'date', label: 'தேதி', field: 'date', align: 'left', style: 'width:110px' },
        { name: 'generations', label: 'உருவாக்கம்', field: 'generations', align: 'right', style: 'width:90px' },
        { name: 'fixes', label: 'திருத்தம்', field: 'fixes', align: 'right', style: 'width:80px' },
        { name: 'ai_failures', label: 'தோல்வி', field: 'ai_failures', align: 'right', style: 'width:70px' },
        {
          name: 'avg_attempts',
          label: 'சராசரி முயற்சி',
          field: row => {
            const total = row.generations + row.fixes
            return total ? (row.total_attempts / total).toFixed(1) : '—'
          },
          align: 'right',
          style: 'width:110px'
        },
        {
          name: 'first_try_pct',
          label: 'முதல் முயற்சி %',
          field: row => {
            const total = row.generations + row.fixes
            return total ? Math.round(row.first_try_successes / total * 100) + '%' : '—'
          },
          align: 'right',
          style: 'width:110px'
        },
        { name: 'fix_clicks', label: 'திருத்து அழுத்தம்', field: 'fix_clicks', align: 'right', style: 'width:110px' },
        {
          name: 'cost',
          label: 'செலவு',
          field: row => row.cost.toFixed(4),
          align: 'right',
          style: 'width:80px'
        }
      ]
    }
  },
  watch: {
    tab (val) {
      if (val === 'stats' && !this.dailyStats.length) this.fetchStats()
    }
  },
  methods: {
    login () {
      this.token = this.tokenInput
      this.authed = true
      this.fetchVerses()
    },
    async fetchVerses () {
      this.loadingVerses = true
      try {
        const resp = await fetch(`${AI_BACKEND}/admin/compositions?page=${this.versePage}&limit=10`, {
          headers: { 'x-dev-token': this.token }
        })
        if (resp.status === 401) { this.authed = false; return }
        const data = await resp.json()
        this.compositions = data.compositions
        this.total = data.total
        this.versePages = data.pages
      } finally {
        this.loadingVerses = false
      }
    },
    async fetchStats () {
      this.loadingStats = true
      try {
        const resp = await fetch(`${AI_BACKEND}/admin/stats`, {
          headers: { 'x-dev-token': this.token }
        })
        if (resp.status === 401) { this.authed = false; return }
        const data = await resp.json()
        this.dailyStats = data.stats
      } finally {
        this.loadingStats = false
      }
    }
  }
}
</script>
