<template>
  <q-layout view="hHr Lpr fFf">
    <q-header elevated>
      <q-toolbar
       class="bg-grey-9">
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
          icon="menu"
          aria-label="Menu"
        />

      <q-btn
          round
          size="lg"
          text-color="white"
          color="#5D8D89"
          to ="/analyzer"
          class="q-ma-xs"
        >
         <span class="title-icon">அ</span>
        </q-btn>

        <q-toolbar-title class="">
          <div class="tamil">அவலோகிதம்</div>
          <div class="tamil"><small> யாப்பு மென்பொருள் </small></div>
        </q-toolbar-title>

      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      elevated
      :width="210"
      content-class="bg-grey-2"
    >
      <q-list class="q-ma-sm">
        <q-item class="tamil" to="/analyzer" active-class="bg-grey-4">
          <q-item-section avatar>
            <q-icon name="insert_comment" />
          </q-item-section>
          <q-item-section>
            <q-item-label>ஆராய்க</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil" to="/search" active-class="bg-grey-4">
          <q-item-section avatar>
            <q-icon name="search" />
          </q-item-section>
          <q-item-section>
            <q-item-label>சொல் தேடல்</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil" to="/types" active-class="bg-grey-4">
          <q-item-section avatar>
            <q-icon name="format_quote" />
          </q-item-section>
          <q-item-section>
            <q-item-label>பா வகைகள்</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil" to="/learn" active-class="bg-grey-4">
          <q-item-section avatar>
            <q-icon name="create" />
          </q-item-section>
          <q-item-section>
            <q-item-label>கற்க</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil" to="/reference" active-class="bg-grey-4">
          <q-item-section avatar>
            <q-icon name="how_to_reg" />
          </q-item-section>
          <q-item-section>
            <q-item-label>குறிப்புகள்</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil"  active-class="bg-grey-4" to="/prosody">
          <q-item-section avatar>
            <q-icon name="highlight" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Tamil Prosody</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil"  active-class="bg-grey-4" to="/download">
          <q-item-section avatar>
            <q-icon name="cloud_download" />
          </q-item-section>
          <q-item-section>
            <q-item-label>பதிவிறக்கம்</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil"  active-class="bg-grey-4" to="/help">
          <q-item-section avatar>
            <q-icon name="help" />
          </q-item-section>
          <q-item-section>
            <q-item-label>உதவி</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil" to="/about" active-class="bg-grey-4">
          <q-item-section avatar>
            <q-icon name="info" />
          </q-item-section>
          <q-item-section>
            <q-item-label>பற்றி</q-item-label>
          </q-item-section>
        </q-item>
      <q-expansion-item
        header-class="tamil"
        expand-separator
        icon="developer_board"
        label="பிற கருவிகள்"
      >
        <q-item class="tamil cursor-pointer" @click.native="openURL('http://aksharamukha.appspot.com')">
          <q-item-section>
            <q-item-label>அக்ஷரமுகம்</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil cursor-pointer" @click.native="openURL('http://tamiljinavani.appspot.com')">
          <q-item-section>
            <q-item-label>ஜினவாணி</q-item-label>
          </q-item-section>
        </q-item>
        <q-item class="tamil cursor-pointer" @click.native="openURL('http://anunaadam.appspot.com')">
          <q-item-section>
            <q-item-label>அனுநாதம்</q-item-label>
          </q-item-section>
        </q-item>
      </q-expansion-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

      <q-footer v-if="!$q.platform.is.mobile">
        <q-toolbar class="bg-grey-8 footer-quote">
          <small>© 2020&nbsp;<a href="http://www.virtualvinodh.com">Vinodh Rajan</a>&nbsp;vinodh@virtualvinodh.com. This software is released under GNU AGPL 3.0 license.</small>
        </q-toolbar>
      </q-footer>
  </q-layout>
</template>

<script>
import { openURL } from 'quasar'

export default {
  name: 'MyLayout',
  components: {
  },
  methods: {
    openURL
  },
  data () {
    return {
      leftDrawerOpen: false
    }
  }
}
</script>

<style>
/* @import url('https://fonts.googleapis.com/css?family=Mukta+Malar&subset=latin-ext,tamil'); */

@font-face {
  font-family: 'e-Pandya';
  src: url('../statics/e-VatteluttuOT.ttf')
}

@font-face {
  font-family: 'MuktaMalarLocal';
  src: url('../statics/MuktaMalar.ttf')
}

.title-icon {
  font-family: "e-Pandya", "e-PandyaLocal";
  font-size: 220%;
  margin-left: -10px;
  margin-top: -5px;
}
.tamil {
  font-family: "Mukta Malar", "MuktaMalarLocal";
}
.footer-quote {
  text-align: right;
  float:center;
}
.bigger {
  font-size:110%;
}
</style>
