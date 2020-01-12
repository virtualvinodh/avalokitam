<template>
  <div>
    <scansion :metricalFeet="metricalFeet"></scansion>
    <hr/>
    <div class="row tamil">
      <div class="q-ma-md col">
        <b>இயற்சீர்</b> <br/>{{counts.vilam + counts.ma + counts.venpa}} ({{classPercent(counts.vilam + counts.ma + counts.venpa)}}%)
      </div>
      <div class="q-ma-md col">
        <b>காய்ச்சீர்</b> <br/>{{counts.kay}} ({{classPercent(counts.kay)}}%)
      </div>
      <div class="q-ma-md col">
        <b>கனிச்சீர்</b> <br/>{{counts.kani}} ({{classPercent(counts.kani)}}%)
      </div>
      <div class="q-ma-md col">
        <b>பூச்சீர்</b> <br/>{{counts.pu}} ({{classPercent(counts.pu)}}%)
      </div>
      <div class="q-ma-md col">
        <b>நிழற்சீர்</b> <br/>{{counts.nizhal}} ({{classPercent(counts.nizhal)}}%)
      </div>
    </div>
    <q-expansion-item
        icon="category"
        label="குறியீடுகள்"
        class="q-mt-md"
        header-class="tamil bg-grey-8 text-white q-pa-sm"
        expand-icon-class="text-white"
        dense
        default-opened
    >
      <syllable-legend></syllable-legend>
    </q-expansion-item>
    <q-expansion-item
        icon="straighten"
        label="வாய்ப்பாடுகள்"
        class="q-mt-md"
        header-class="tamil bg-grey-8 text-white q-pa-sm"
        expand-icon-class="text-white"
        dense
    >
    <br/>
    <syllable-pattern></syllable-pattern>
    </q-expansion-item>
  </div>
</template>

<style scoped>
.line {
  display:inline-block;
  width: 100%;
}
</style>

<script>
import Scansion from '../components/Scansion'
import SyllableLegend from '../components/SyllableLegend'
import SyllablePattern from '../components/SyllablePattern'

export default {
  // name: 'ComponentName',
  components: {
    Scansion,
    SyllableLegend,
    SyllablePattern
  },
  computed: {
    wordClasses: function () {
      var wordClass = []
      this.metricalFeet.forEach(function (line) {
        line.forEach(function (word) {
          wordClass.push(word[1])
        })
      })

      return wordClass
    },
    counts: function () {
      let ma = ['தேமா', 'புளிமா']
      let vilam = ['கூவிளம்', 'கருவிளம்']
      let kay = 'காய்'
      let kani = 'கனி'
      let pu = 'பூ'
      let nizhal = 'ழல்'
      let venpa = ['நாள்', 'மலர்', 'காசு', 'பிறப்பு']

      var count = {
        ma: 0,
        vilam: 0,
        kay: 0,
        kani: 0,
        pu: 0,
        nizhal: 0,
        venpa: 0
      }

      this.wordClasses.forEach(function (clas) {
        if (ma.includes(clas)) {
          count.ma += 1
        } else if (vilam.includes(clas)) {
          count.vilam += 1
        } else if (clas.includes(kay)) {
          count.kay += 1
        } else if (clas.includes(kani)) {
          count.kani += 1
        } else if (clas.includes(pu)) {
          count.pu += 1
        } else if (clas.includes(nizhal)) {
          count.nizhal += 1
        } else if (venpa.includes(clas)) {
          count.venpa += 1
        }
      })

      return count
    }
  },
  props: ['metricalFeet'],
  methods: {
    classPercent: function (count) {
      return Math.round((count / this.wordClasses.length) * 100)
    }
  },
  data () {
    return {}
  }
}
</script>
