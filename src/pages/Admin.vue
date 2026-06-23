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
      <div class="row items-center q-mb-md">
        <div class="text-h6">சேமிக்கப்பட்ட பாக்கள் <q-chip dense color="grey-7" text-color="white">{{ total }}</q-chip></div>
      </div>

      <q-table
        :data="compositions"
        :columns="columns"
        :loading="loading"
        row-key="id"
        flat
        bordered
        dense
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
          <q-td :props="props">
            {{ new Date(props.value).toLocaleString('ta-IN') }}
          </q-td>
        </template>
      </q-table>

      <div class="flex flex-center q-mt-md">
        <q-pagination v-model="page" :max="pages" :max-pages="7" boundary-links @input="fetch" color="grey-8" />
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
      tokenInput: '',
      token: '',
      authed: false,
      loading: false,
      compositions: [],
      total: 0,
      page: 1,
      pages: 1,
      columns: [
        { name: 'id', label: 'இணைப்பு', field: 'id', align: 'left', style: 'width:80px' },
        { name: 'metre', label: 'யாப்பு', field: 'metre', align: 'left', style: 'width:140px' },
        { name: 'verse', label: 'பா', field: 'verse', align: 'left' },
        { name: 'created_at', label: 'தேதி', field: 'created_at', align: 'left', style: 'width:160px' }
      ]
    }
  },
  methods: {
    login () {
      this.token = this.tokenInput
      this.authed = true
      this.fetch()
    },
    async fetch () {
      this.loading = true
      try {
        const resp = await fetch(`${AI_BACKEND}/admin/compositions?page=${this.page}&limit=10`, {
          headers: { 'x-dev-token': this.token }
        })
        if (resp.status === 401) { this.authed = false; return }
        const data = await resp.json()
        this.compositions = data.compositions
        this.total = data.total
        this.pages = data.pages
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
