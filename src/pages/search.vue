<template>
  <q-page padding>
  <div class="text-h5 q-mb-md tamil">சொல் தேடல்</div>
  <div class="tamil">தங்களுடைய மூலச்சொல்லுடன் சிறந்த ஓசை நயத்துடன் பொருந்தக்கூடிய பல்வேறு சொற்களை இங்கு தாங்கள் தேடலாம்.</div>

  <q-input v-model="inputWord" label="மூலச்சொல்" class="tamil"/>
  <div class="tamil q-mt-lg">கீழ்க்கண்ட விதங்களில் பொருந்தக்கூடிய பிற சொற்களை காட்டுக</div>
  <div class="row tamil">
    <q-select :options="ornamentOptions" v-model="ornamentSel" label="தொடை" class="col-xs-5 col-lg-2 q-mr-md"/>
    <q-input v-model="ornamentCount" label="" :style="{'width':'20px'}" class="q-mr-md" v-show="ornamentSel.value == 'first' || ornamentSel.value == 'last'"/>
    <q-select :options="letterCountOptions" v-model="letterCountSel"  label="எழுத்தெண்ணிக்கை" class="col-xs-5 col-lg-2 q-mr-md" />
    <q-input v-model="letterCountVal" label="" :style="{'width':'20px'}" class="q-mr-md" v-show="letterCountSel.value == 'other'"/>
    <q-select :options="matraCountOptions" v-model="matraCountSel" label="மாத்திரை எண்ணிக்கை" class="col-xs-5 col-lg-2 q-mr-md"/>
    <q-input v-model="matraCountVal" label="" :style="{'width':'20px'}" class="q-mr-md" v-show="matraCountSel.value == 'other'"/>
    <q-select :options="patternOptions" v-model="patternSel"  label="வாய்ப்பாடு" class="col-xs-5 col-lg-2 q-mr-md"/>
    <q-select :options="linkageOptions" v-model="linkageSel"  label="தளை"  class="col-xs-5 col-lg-2 q-mr-md"/>
  </div>
    <br/>
    <q-btn @click="search" icon="search"> தேடுக </q-btn>
    <br/>
    <q-spinner-hourglass
    class="q-mt-lg"
          color="grey-8"
          size="3em"
          v-show="showProgress"
        />
    <div class="q-mt-lg tamil" v-show="resultWords !== ''">
      மொத்த சொற்கள்: {{resultWords.length}} <br/><br/>
    <span v-if="resultWords.length <= 10000">
      <q-chip outline color="grey-8" text-color="white" v-for="word in resultWords" :key="word" class="q-ma-xs">
        {{word}} <a :href="'https://ta.wiktionary.org/wiki/' + word" class="hrefword" target="_blank"><q-icon class="q-ml-xs" name="menu_book" color="grey-8" size="20px"/></a>

      </q-chip>
    </span>
    <span v-else class="tamil">
      <b>பத்தாயிரத்திற்கும் மேற்பட்ட சொற்கள் பொருந்துகின்றன. தயவு செய்து உங்கள் தேடல் விதிகளை இறுக்கமாக்குக.</b>
    </span>
  </div>
  </q-page>
</template>

<script>

import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'PageName',
  components: {
  },
  mixins: [LinkMixin],
  methods: {
    search: function () {
      this.showProgress = true
      this.resultWords = ''
      this.inputWord = this.inputWord.trim()
      var urlPars = 'source=' + this.inputWord + '&todaiSel=' + this.ornamentSel.value + '&letterCountSel=' + this.letterCountSel.value + '&matraCountSel=' + this.matraCountSel.value + '&vaypatuSel=' + this.patternSel + '&talaiSel=' + this.linkageSel

      if (this.matraCountSel.value === 'other') {
        urlPars = urlPars + '&matraCountSelN=' + this.matraCountVal
      }

      if (this.letterCountSel.value === 'other') {
        urlPars = urlPars + '&letterCountSelN=' + this.letterCountVal
      }

      if (this.ornamentSel.value === 'first' || this.ornamentSel.value === 'last') {
        urlPars = urlPars + '&todaiSelN=' + this.ornamentCount
      }
      // console.log(urlPars)

      var dhis = this

      this.apiCall.post('/getRhymingWords.php?' + urlPars)
        .then(function (response) {
          dhis.showProgress = false
          // console.log(response.data)
          dhis.resultWords = JSON.parse(response.data.trim())
          // dhis.resultWords = JSON.parse(response.data)
        })
        .catch(function (error) {
          error = 'error'
          // console.log(error)
        })
    }
  },
  data () {
    return {
      inputWord: '',
      showProgress: false,
      ornamentSel: {
        label: 'எதுகை',
        value: 'etukai'
      },
      letterCountSel: {
        label: 'சம அளவு',
        value: 'src'
      },
      matraCountSel: {
        label: 'அனைத்தும்',
        value: 'all'
      },
      patternSel: 'அனைத்தும்',
      linkageSel: 'அனைத்தும்',
      ornamentCount: '2',
      letterCountVal: '2',
      matraCountVal: '5',
      resultWords: '',
      ornamentOptions: [
        {
          label: 'எதுகை',
          value: 'etukai'
        },
        {
          label: 'மோனை',
          value: 'monai'
        },
        {
          label: 'இயைபு',
          value: 'iyaipu'
        },
        {
          label: 'முதல் எழுத்துக்கள்',
          value: 'first'
        },
        {
          label: 'இறுதி எழுத்துக்கள்',
          value: 'last'
        },
        {
          label: 'ஏதும் இல்லை',
          value: 'none'
        }
      ],
      letterCountOptions: [
        {
          label: 'அனைத்தும்',
          value: 'all'
        },
        {
          label: 'சம அளவு',
          value: 'src'
        },
        {
          label: 'குறைவாக',
          value: 'srcLs'
        },
        {
          label: 'அதிகமாக',
          value: 'srcGt'
        },
        {
          label: 'பிற',
          value: 'other'
        }
      ],
      matraCountOptions: [
        {
          label: 'அனைத்தும்',
          value: 'all'
        },
        {
          label: 'சம அளவு',
          value: 'src'
        },
        {
          label: 'குறைவாக',
          value: 'srcLs'
        },
        {
          label: 'அதிகமாக',
          value: 'srcGt'
        },
        {
          label: 'பிற',
          value: 'other'
        }
      ],
      patternOptions: ['அனைத்தும்', 'அதே வாய்ப்பாடு', 'தேமா', 'புளிமா', 'கூவிளம்', 'கருவிளம்', 'தேமாங்காய்', 'புளிமாங்காய்', 'கூவிளங்காய்', 'கருவிளங்காய்', 'தேமாங்கனி', 'புளிமாங்கனி', 'கூவிளங்கனி', 'கருவிளங்கனி', 'தேமாந்தண்பூ', 'புளிமாந்தண்பூ', 'கூவிளந்தண்பூ', 'கருவிளந்தண்பூ', 'தேமாநறும்பூ', 'புளிமாநறும்பூ', 'கூவிளநறும்பூ', 'கருவிளநறும்பூ', 'தேமாநறுநிழல்', 'புளிமாநறுநிழல்', 'கூவிளநறுநிழல்', 'கருவிளநறுநிழல்', 'தேமாந்தண்ணிழல்', 'புளிமாந்தண்ணிழல்', 'கூவிளந்தண்ணிழல்', 'கருவிளந்தண்ணிழல்'],
      linkageOptions: ['அனைத்தும்', 'இயற்சீர் வெண்டளை', 'வெண்சீர் வெண்டளை', 'வெண்டளை', 'ஒன்றிய வஞ்சித்தளை', 'ஒன்றா வஞ்சித்தளை', 'வஞ்சித்தளை', 'நேரொன்றிய ஆசிரியத்தளை', 'நிரையொன்றிய ஆசிரியத்தளை', 'ஆசிரியத்தளை', 'கலித்தளை']
    }
  }
}
</script>

<style scoped>
.hrefword:link {
  text-decoration: none;
}

.hrefword:visited {
  text-decoration: none;
}

</style>
