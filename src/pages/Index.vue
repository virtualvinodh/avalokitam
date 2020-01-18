<template>
  <q-page>
 <!-- debounce requests -->
 <!-- post request to the server -->
<q-splitter
  v-model="splitterModel"
  :horizontal="$q.platform.is.mobile"
>
<template v-slot:before>
 <div class="col-lg-4 col-xs-11 q-ma-md">
 <q-expansion-item
        icon="input"
        label="பா உள்ளிடும் வழிமுறைகள்"
        class="q-mt-md bg-grey-8"
        header-class="tamil bg-grey-8 text-white q-pa-sm"
        expand-icon-class="text-white"
        ref="howto"
        dense
  >
<div class="tamil q-ma-md text-white">
<div class="q-mb-sm">சந்திபிரிக்காத செய்யுளையே உள்ளிடவும். சந்தி பிரித்த செய்யுள் யாப்பு விதிகளை மீறலாம்</div>
<div class="q-mb-sm">அலகிடக்கூடாத எழுத்தை அடைப்புக்குறிக்குள் () இடவும்</div>
<div class="q-mb-sm">நெடிலடிகளையும் கழிநெடிலடிகளையும் ஒரே வரியாக இடவும்</div>
<div class="q-mb-sm">தனிச்சொல்லை - என்ற குறி மூலம் சுட்டவும் </div>
<div class="q-mb-sm">பிற நிறுத்தற்குறிகளையும் தேவையற்ற இடைவெளிகளையும் தவிர்க்கவும்</div>
<div class="q-mb-sm"></div>
<q-btn color="grey-10" @click="demoVerse"> மாதிரிச்செய்யுள் </q-btn>
  </div>
  <q-separator />
  </q-expansion-item>
  <q-input
      v-model="text"
      autofocus
      clearable
      type="textarea"
      placeholder="பாவினை உள்ளிடவும்"
      color="dark"
      :rows="rowsLength"
      class="tamil q-ma-md"
      @input="processText"
    />
    <q-checkbox v-model="modifyKazhinediladi" class="tamil col-lg-1 row q-mb-sm" @input="demo" v-if="visibleYappuruppu">
    <small>சீர் சீர் சீர்<br/>
  &nbsp;&nbsp;&nbsp;&nbsp;சீர் சீர் சீர்</small> => ஒரே கழிநெடிலடியாக கொள்க</q-checkbox>
    <q-checkbox v-model="showYappuruppu" class="tamil col-lg-1 row" @input="showYappuruppu">யாப்புறுப்புக்களை மட்டும் வெளியிடவும்<br/> <small>பாவினை கண்டறிய வேண்டாம்</small></q-checkbox>
    <div class="row">
      <q-checkbox v-model="checkTypeActive" class="tamil col-lg-1" @input="activateCheck"/>
      <q-select v-model="checkType" :options="verseTypes" label="விதிகளோடு பொருத்துக" class="q-ml-sm tamil col-xs-8 col-lg-6" borderless/>
    </div>

    <q-expansion-item
        expand-separator
        label="மாறுபட்ட அலகிடல்"
        class="tamil"
      >
        <q-checkbox v-model="kurilu" class="tamil col-lg-1" @input="demo" label="உயிர்முன் குற்றியலுகரத்தை அலகிடாது விடுக"/>
        <q-checkbox v-model="kurili" class="tamil col-lg-1" @input="demo" label="குற்றியலிகரத்தை அலகிடாது விடுக"/>
        <q-checkbox v-model="aythamkuri" class="tamil col-lg-1" @input="demo" label="ஆய்தத்தை குறில் எழுத்தாக அலகிடுக"/>
        <q-checkbox v-model="extralong" class="tamil col-lg-1" @input="demo" label="உயிரளபெடையை நெடில் எழுத்தாக அலகிடுக"/>
    </q-expansion-item>
<social-sharing :url="sharingURL"
                      :title="sharingQuoteVVerse"
                      :description="sharingQuoteVVerse"
                      :quote="sharingQuoteVVerse"
                      hashtags="tamil, poetry, prosody, avalokitam"
                      class="q-mt-md"
                      inline-template
                      >
  <div class="social">
      <network network="facebook" class="q-ma-md cursor-pointer">
        <img src="../statics/facebook.svg" width="20px">
      </network>
      <network network="whatsapp" class="q-ma-md cursor-pointer">
        <img src="../statics/whatsapp.svg" width="20px">
      </network>
      <network network="twitter" class="q-ma-md cursor-pointer">
        <img src="../statics/twitter.svg" width="20px">
      </network>
  </div>
</social-sharing>
  <div class="q-mt-md q-pa-md q-gutter-sm" v-if="venpaaCheckFlag && !checkTypeActive && !showYappuruppu">
    <q-banner class="bg-grey-8 text-white tamil">
      <template v-slot:avatar>
        <q-icon name="info" color="white" size="30px"/>
      </template>
      நீங்கள் வெண்பா இயற்ற முயல்வது போல தெரிகிறது.
      <template v-slot:action>
        <q-btn color="grey-10" label="வெண்பா விதிகளுடன் சரிபார்க்க" @click="activateVenpaCheck"/>
      </template>
    </q-banner>
  </div>
  <div class="q-mt-md q-pa-md q-gutter-sm" v-if="nonSpecialEtukai">
    <q-banner class="bg-grey-8 text-white tamil">
      <template v-slot:avatar>
        <q-icon name="info" color="white" size="30px"/>
      </template>
       சிறப்பற்ற எதுகை பயன்படுத்தப்பட்டுள்ளது. இயலுமாயின் எதுகையையோ அல்லது வர்க்க எதுகையையோ பயன்படுத்துவது சிறப்பாக இருக்கும்.
    </q-banner>
  </div>
  </div>
</template>
<template v-slot:after>
   <div class="q-mt-lg q-ml-md q-mr-sm col-lg-7 col-xs-11">
    <q-btn :color="view == 'eluttu' ? 'grey-10': 'grey-7'" icon="sort_by_alpha" label="எழுத்து" class="q-ma-sm tamil" dense @click="view = 'eluttu'"/>
    <q-btn :color="view == 'acai' ? 'grey-10': 'grey-7'" icon="line_style" label="அசை/சீர்" class="q-ma-sm tamil" dense @click="view = 'acai'"/>
    <q-btn :color="view == 'talai' ? 'grey-10': 'grey-7'" icon="link" label="தளை" class="q-ma-sm tamil" dense @click="view = 'talai'"/>
    <q-btn :color="view == 'adi' ? 'grey-10': 'grey-7'" icon="format_align_justify" label="அடி" class="q-ma-sm tamil" dense @click="view = 'adi'"/>
    <q-btn :color="view == 'todai' ? 'grey-10': 'grey-7'" icon="local_florist" label="தொடை" class="q-ma-sm tamil" dense @click="view = 'todai'"/>
    <q-btn :color="view == 'all' ? 'grey-10': 'grey-7'" icon="info" label="அனைத்தும்" class="q-ma-sm tamil" dense @click="view = 'all'"/>
    <q-spinner-oval
    class="q-ma-sm"
          color="grey-8"
          size="2em"
          v-show="showProgress"
        />
     <br/>
    <span v-if="typeof text === 'string' && text !=='' ">
      <q-banner class="bg-grey-8 text-white tamil q-ma-sm dense rounded" v-if="!showYappuruppu">
      <span v-if="!checkTypeActive && lineCount.length > 1">
        {{result.verse.$.metre == '' ? 'மன்னிக்கவும். பா வகையினை அறிந்துகொள்ள இயலவில்லை.' : 'இது ' + (soundCalculate(result) !== '' ? soundCalculate(result) + ' ஓசை உடைய ' : '') + ' ' + result.verse.$.metre}}

        <div class="q-mt-md" v-if="result.verse.$.metre == ''">
          ஆயினும், கீழ்க்கண்ட பா வகைகள் மிகவும் நெருக்கமாக ஓரளவுக்கு பொருந்துகின்றன. உங்கள் உள்ளீட்டை மாற்றி அவற்றின் ஏதேனும் ஒன்றின் விதிகளோடு பொருந்தி வரும்படி செய்யலாம்.

        <div>
        <q-expansion-item
            icon="description"
            :label="verseType.label"
            class="q-mt-md"
            header-class="tamil bg-grey-7 text-white q-pa-sm"
            expand-icon-class="text-white"
            @input="delayupdatingkey"
            ref="explan22"
            dense
            v-for="verseType in closestType(text, result.verse)"
            :key="'verseType' + verseType.value"
        >
          <rule-list :ruleslist="result.verse[verseType.value][0]" :explanation="result.verse.Explanation[0]" ::type="verseType.value" :checkactive="checkTypeActive"></rule-list>
        </q-expansion-item>

        </div>

        </div>
      </span>
      <span v-if="checkTypeActive">
        {{checkType.label}} விதிகள்
        {{result.verse[checkType.value][0].Result[result.verse[checkType.value][0].Rule.length - 1] == '1' ? ' பொருந்துகின்றன' : 'பொருந்தவில்லை'}}
      </span>
    <q-expansion-item
        icon="description"
        label="விளக்கம்"
        class="q-mt-md"
        header-class="tamil bg-grey-7 text-white q-pa-sm"
        expand-icon-class="text-white"
        @input="delayupdatingkey"
        ref="explan"
        dense
        v-show="checkTypeActive || result.verse.$.metre != ''"
    >
      <span v-if="!checkTypeActive">
        <rule-list :ruleslist="result.verse.ActiveRules[0]" :explanation="result.verse.Explanation[0]"></rule-list>
      </span>
      <span v-if="checkTypeActive">
        <rule-list :ruleslist="result.verse[checkType.value][0]" :explanation="result.verse.Explanation[0]" :type="checkType.value" :checkactive="checkTypeActive"></rule-list>
      </span>
    </q-expansion-item>
    </q-banner>
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
      <line-display  :metricalFeet="metricalFeetGet(result)" :linetypes="linetypesGet(result)"></line-display>
    </span>
    <span v-if="view == 'todai'">
      <ornament-display :metricalFeet="metricalFeetGet(result)" :ornaments="ornamentsGet(result)"></ornament-display>
    </span>
    <span v-if="view == 'all'">
      <scansion-all :metricalFeet="metricalFeetGet(result)" :hide="true" :linkage="linkageGet(result)" :linetypes="linetypesGet(result)" :checkvenpa="checkTypeActive && checkType.value === 'venpaa'" :venpalastword="vepaLastWordClass(result)"></scansion-all>
    </span>
    </span>
   </div>
 </template>
</q-splitter>
  </q-page>
</template>

<style scoped>
h6 {
  margin-top: 5px;
  margin-bottom: 15px;
}
</style>

<script>
import LinkageDisplay from '../components/LinkageDisplay'
import LetterDisplay from '../components/LetterDisplay'
import ScansionDisplay from '../components/ScansionDisplay'
import LineDisplay from '../components/LineDisplay'
import OrnamentDisplay from '../components/OrnamentDisplay'
import ScansionAll from '../components/ScansionAll'
import RuleList from '../components/RuleList'
import SocialSharing from 'vue-social-sharing'
import { BitlyClient } from 'bitly'
const bitly = new BitlyClient('', {})

var _ = require('underscore')

import { LinkMixin } from '../mixin/LinkMixin'

export default {
  name: 'PageIndex',
  mixins: [LinkMixin],
  components: {
    SocialSharing,
    ScansionDisplay,
    LetterDisplay,
    LinkageDisplay,
    LineDisplay,
    OrnamentDisplay,
    ScansionAll,
    RuleList
  },
  data () {
    return {
      text: '',
      visibleYappuruppu: false,
      modifyKazhinediladi: false,
      showYappuruppu: false,
      result: '',
      kurilu: false,
      aythamkuri: false,
      kurili: false,
      extralong: false,
      splitterModel: 35,
      checkTypeActive: false,
      puranadai: ['Facebook', 'Twitter'],
      checkType: {
        label: 'வெண்பா',
        value: 'venpaa'
      },
      updateKey: false,
      view: 'acai',
      venpaaCheckFlag: false,
      showProgress: false,
      processText: _.debounce(this.demo, 300)
    }
  },
  mounted: function () {
    if (typeof this.$route.query.text !== 'undefined') {
      this.text = this.$route.query.text
      this.demo()
    }
  },
  watch: {
    text: function () {
      var lines = this.text.split('\n')
      var index = 0
      var check = false
      while (index < lines.length) {
        if (lines[index][0] !== ' ' && lines[index][1] !== ' ' && typeof lines[index + 1] !== 'undefined' && lines[index + 1][0] === ' ' && lines[index + 1][1] === ' ') {
          check = check || true

          index = index + 2
        } else {
          index = index + 1
        }
      }

      this.visibleYappuruppu = check
    }
  },
  computed: {
    rowsLength: function () {
      if (typeof this.text !== 'string' || this.text === '') {
        if (this.$q.platform.is.mobile) {
          return 4
        } else {
          return 6
        }
      } else {
        return this.lineCount.length + 2
      }
    },
    sharingURL: function () {
      if (typeof this.text === 'string' && this.text !== '') {
        return 'http://www.avalokitam.com/analyzer?text=' + this.text.replace(/\n/g, '%0D%0A').replace(/ /g, '%20')
      } else {
        return ''
      }
    },
    sharingQuoteVVerse: function () {
      return 'நான் இயற்றிய மரபுப்பாவை இங்கே பாருங்களேன்.'
    },
    nonSpecialEtukai: function () {
      // console.log(this.result['verse'])
      if (typeof this.result['verse'] === 'undefined') {
        return false
      } else {
        return this.result.verse['NonSpecialLineEtukai'][0] === '1' && (this.result.verse.$.metre.includes('விருத்தம்') || this.result.verse.$.metre.includes('கலித்துறை'))
      }
    },
    lineCount: function () {
      return this.lineCountGet(this.text)
    },
    venpaaCheck: function () {
      var lineCountFront = this.lineCount.slice(0, -1)
      var lineCountLast = this.lineCount[this.lineCount.length - 1]

      return Math.min(...lineCountFront) === 4 && Math.max(...lineCountFront) === 4 && lineCountLast === 3
    }
  },
  methods: {
    shortenURL: async function (url) {
      let result = ''
      try {
        result = await bitly.shorten(url)
      } catch (e) {
        result = ''
      }
      return result
    },
    soundCalculate: function (result) {
      var links = this.countLinksPercent(result)
      if (this.result.verse.$.metre.includes('வெண்பா')) {
        if (links.length === 1) {
          if (links[0] === 'வெண்சீர் வெண்டளை') {
            return 'ஏந்திசைச் செப்பல்'
          }
          if (links[0] === 'இயற்சீர் வெண்டளை') {
            return 'தூங்கிசைச் செப்பல்'
          }
        } else {
          return 'ஒழுகிசைச் செப்பல்'
        }
      }

      if (this.result.verse.$.metre.includes('ஆசிரியப்பா')) {
        if (links.length === 1) {
          if (links[0] === 'நேரொன்றிய ஆசிரியத்தளை') {
            return 'ஏந்திசை அகவல்'
          }
          if (links[0] === 'நிரையொன்றிய ஆசிரியத்தளை') {
            return 'தூங்கிசை அகவல்'
          }
        }
        if (links.length === 3) {
          if (links.includes('நேரொன்றிய ஆசிரியத்தளை') && links.includes('நிரையொன்றிய ஆசிரியத்தளை') && links.includes('இயற்சீர் வெண்டளை')) {
            return 'ஒழுகிசை அகவல்'
          }
        }
      }

      if (this.result.verse.$.metre.includes('கலிப்பா') && !this.result.verse.$.metre.includes('கட்டளை')) {
        if (links.length === 1) {
          if (links[0] === 'கலித்தளை') {
            return 'ஏந்திசைத் துள்ளல்'
          }
        } else if (links.length === 3) {
          if (links.includes('கலித்தளை') && links.includes('வெண்சீர் வெண்டளை') && links.includes('இயற்சீர் வெண்டளை')) {
            return 'அகவல் துள்ளல்'
          } else {
            return 'பிரிந்திசைத் துள்ளல்'
          }
        } else {
          return 'பிரிந்திசைத் துள்ளல்'
        }
      }

      if (this.result.verse.$.metre.includes('வஞ்சிப்பா')) {
        // console.log(links)
        if (links.length === 1) {
          if (links[0] === 'ஒன்றிய வஞ்சித்தளை') {
            return 'ஏந்திசைத் தூங்கல்'
          }
          if (links[0] === 'ஒன்றா வஞ்சித்தளை') {
            return 'அகவல் தூங்கல்'
          }
        } else {
          return 'பிரிந்திசைத் தூங்கல்'
        }
      }

      return ''
    },
    countLinksPercent: function (result) {
      var linkage = this.linkageGet(result)

      var linkageIndividual = linkage.flat().map(x => x[2])

      linkageIndividual.shift()

      if (this.result.verse.$.metre.includes('வஞ்சிப்பா')) {
        let vanjiLimit = this.lineCount.slice(0, this.lineCount.indexOf(1)).reduce((a, b) => a + b) - 1
        linkageIndividual = linkageIndividual.slice(0, vanjiLimit)
      }

      const map = linkageIndividual.reduce((acc, e) => acc.set(e, (acc.get(e) || 0) + 1), new Map())

      var countLinks = Array.from(map.entries())

      return countLinks.map(x => [x[0], (x[1] / linkageIndividual.length) * 100]).map(x => x[0])
    },
    activateVenpaCheck: function () {
      this.checkTypeActive = true
      this.checkType = { 'label': 'வெண்பா', 'value': 'venpaa' }
      this.activateCheck()
    },
    kuriluModify: function (text) {
      text = text.replace(/([அஆஇஈஉஊஎஏஐஒஓஔகஙசஜஞடணதநனபமயரறலளழவஷஸஹாிீுூெேைொோௌ])([கஙசஜஞடணதநனபமயரறலளழவஷஸஹாிீுூெேைொோௌ])([கசடதபறயவ]ு)( |\n|\r|\r\n)([அஆஇஈஉஊஎஏஒஓஔ])/g, '$1$2($3)$4$5')
      text = text.replace(/([ஆஈஊஏஓாீூேோ்ஃ])([கசடதபறயவ]ு)( | ?\n| ?\r| ?\r\n)([அஆஇஈஉஊஎஏஒஓஔ])/g, '$1($2)$3$4')

      return text
    },
    kuriliModify: function (text) {
      text = text.replace(/([அஆஇஈஉஊஎஏஐஒஓஔகஙசஜஞடணதநனபமயரறலளழவஷஸஹாிீுூெேைொோௌ])([கஙசஜஞடணதநனபமயரறலளழவஷஸஹாிீுூெேைொோௌ])([கசடதபற]ி)(ய)/g, '$1$2($3)$4')
      text = text.replace(/([ஆஈஊஏஓாீூேோ்ஃ])([கசடதபற]ி)(ய)/g, '$1($2)$3')

      text = text.replace(/மியா/g, '(மி)யா')

      return text
    },
    aythamModify: function (text) {
      return text.replace(/ஃ/g, 'கு')
    },
    alapedaiModify: function (text) {
      text = text.replace(/ஆஅ/g, 'ஆ(அ)')
      text = text.replace(/ஈஇ/g, 'ஈ(இ)')
      text = text.replace(/ஊஉ/g, 'ஊ(உ)')
      text = text.replace(/ஏஎ/g, 'ஏ(எ)')
      text = text.replace(/ஓஒ/g, 'ஓ(ஒ)')

      text = text.replace(/ஐஇ/g, 'ஐ(இ)')
      text = text.replace(/ஔஉ/g, 'ஔ(உ)')

      text = text.replace(/ாஅ/g, 'ா(அ)')
      text = text.replace(/ீஇ/g, 'ீ(இ)')
      text = text.replace(/ூஉ/g, 'ூ(உ)')
      text = text.replace(/ேஎ/g, 'ே(எ)')
      text = text.replace(/ோஒ/g, 'ோ(ஒ)')

      text = text.replace(/ைஇ/g, 'ை(இ)')
      text = text.replace(/ௌஉ/g, 'ௌ(உ)')

      return text
    },
    delayupdatingkey: async function () {
      // console.log('starting update')
      await this.sleep(500)
      // console.log('starting updated')
      this.updateKey = !this.updateKey
    },
    activateCheck: function () {
      if (this.checkTypeActive) {
        this.$refs.explan.show()
      }
      if (this.checkType.value === 'venpaa') {
        this.view = 'all'
      }
    },
    demoVerse: function () {
      this.text = 'மாதவா போதி வரதா வருளமலா\nபாதமே யோத சுரரைநீ - தீதகல\nமாயா நெறியளிப்பா யின்றன் பகலாச்சீர்த்\nதாயே யலகில்லா டாம்'
      this.$refs.howto.hide()
      this.demo()
    },
    kazhinediladi: function () {
      /* Check for  Kazhinediladi */

      var lines = this.text.split('\n')

      var textNew = ''

      var index = 0

      while (index < lines.length) {
        if (lines[index][0] !== ' ' && lines[index][1] !== ' ' && typeof lines[index + 1] !== 'undefined' && lines[index + 1][0] === ' ' && lines[index + 1][1] === ' ') {
          textNew += lines[index].trim() + ' ' + lines[index + 1] + '\n'

          index = index + 2
        } else {
          textNew += lines[index].trim() + '\n'
          index = index + 1
        }
      }

      return textNew.trim()
    },
    demo: async function () {
      if (typeof this.text === 'string' && this.text !== '') {
        this.showProgress = true
        this.updateKey = !this.updateKey

        /* Remove Punctionation */

        var punct = [',', '\\.', ';', ':', '\\?', '\\!', '"', '\'']

        var text = this.text

        if (this.modifyKazhinediladi) {
          text = this.kazhinediladi()
        } else {
          text = text.replace(/ {2,}/g, ' ')
          text = text.split('\n').map(x => x.trim()).join('\n')
        }

        punct.forEach(function (val) {
          text = text.replace(new RegExp(val, 'g'), '')
        })

        // text = text.split('\n').filter(x => x.trim() !== '').join('\n').trim()

        if (this.kurilu) {
          text = this.kuriluModify(text)
        }

        if (this.kurili) {
          text = this.kuriliModify(text)
        }

        if (this.aythamkuri) {
          text = this.aythamModify(text)

          // console.log(text)
        }

        if (this.extralong) {
          text = this.alapedaiModify(text)
        }

        text = text.replace(/\(.\)/g, '')

        this.result = await this.convertAsync(text)
        // console.log('The pre result is')
        // console.log(this.result)
        this.result = await this.getJson(this.result)
        // console.log('The post result is')

        this.venpaaCheckFlag = this.venpaaCheck && !this.result.verse.$.metre.includes('வெண்பா')
        this.showProgress = false
      }
    }
  }
}
</script>
