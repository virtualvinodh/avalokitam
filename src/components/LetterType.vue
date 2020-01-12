<template>
  <div>
    <q-banner class="bg-grey-6 text-white tamil q-ma-md rounded">
      எழுத்து எண்ணிக்கை
    </q-banner>
    <span v-for="(line, index) in metricalFeet" :key="'line2'+index">
      <q-separator class="q-ma-sm" v-if="index > 0"/>
      <div class="line q-ma-xs">
        <display-feet3 v-for="(foot, index2) in line" :key="'foot' + index2" :foot="foot"></display-feet3>
      </div> <br/>
      <span class="tamil q-ml-lg"><span class="matra2">{{textArray[index].map(x => syllabize(x).length).reduce((pv, cv) => pv + cv, 0)}}</span> (மெய்யுடன்), <span class="matra2">{{textArray[index].map(x => countSyllableWithout(x)).reduce((pv, cv) => pv + cv, 0)}}</span> (மெய்யில்லாது)</span>
    </span>
    <q-separator class="q-ma-md"/>
    <div class="row tamil">
      <div class="col-xs-4 col-md-2"><span class="count">உயிர்:</span> <span class="matra2"> {{letters.InitialVowels}}</span></div>
      <div class="col-xs-4 col-md-2"><span class="count">மெய்:</span> <span class="matra2"> {{letters.PureConsonants}}</span></div>
      <div class="col-xs-4 col-md-2"><span class="count">ஆய்தம்:</span> <span class="matra2"> {{letters.Aytham}}</span></div>
    </div>
    <div class="row tamil q-mt-sm">
      <div class="col-xs-4 col-md-2"><span class="count">உயிர்மெய்:</span> <span class="matra2"> {{letters.ConsonantVowels}}</span></div>
    </div>
    <div class="row tamil q-mt-sm">
      <div class="col-xs-4 col-md-2 text-grey-7"><span class="count">குறில்:</span> <span class="matra2"> {{letters.Short}}</span></div>
      <div class="col-xs-4 col-md-2 text-grey-7"><span class="count">நெடில்:</span> <span class="matra2"> {{letters.Long}}</span></div>
    </div>
    <div class="tamil q-mt-md text-grey-10">மொத்தம் <span class="matra2">{{parseInt(letters.InitialVowels) + parseInt(letters.PureConsonants) + parseInt(letters.Aytham) + parseInt(letters.ConsonantVowels)}}</span> எழுத்துக்கள்</div>
    <q-separator class="q-ma-md"/>
    <q-banner class="bg-grey-6 text-white tamil q-ma-md rounded">
      மாத்திரை எண்ணிக்கை
    </q-banner>
    <span v-for="(line, index) in metricalFeet" :key="'line3'+index">
      <q-separator class="q-ma-sm" v-if="index > 0"/>
      <div class="line q-ma-xs">
        <display-feet2 v-for="(foot, index2) in line" :key="'foot' + index2" :foot="foot"></display-feet2>
      </div> <br/>
      <span class="tamil q-ml-lg"><span class="matra2">{{half(textArray[index].map(x => countMatra(x)).reduce((pv, cv) => pv + cv, 0))}}</span></span>
    </span>
    <q-separator class="q-mb-md q-ma-sm"/>
        <span class="tamil q-mt-lg q-ml-xl">மொத்தம் <span class="matra2">{{half(textArray.map( x => x.map(y => countMatra(y))).flat().reduce((pv, cv) => pv + cv, 0))}}</span> மாத்திரைகள் </span>
  </div>
</template>

<style scoped>
.line {
  display:inline-block;
  width: 100%;
}
.count {
  font-size: 105%;
}
.matra2 {
  font-weight: bold;
  font-size: 110%;
}
</style>

<script>
import DisplayFeet2 from '../components/DisplayFeet2'
import DisplayFeet3 from '../components/DisplayFeet3'
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  components: {
    DisplayFeet2,
    DisplayFeet3
  },
  mixins: [LinkMixin],
  computed: {
    reconText: function () {
      var text = ''
      var word
      var lines
      this.metricalFeet.forEach(function (line) {
        lines = ''
        line.forEach(function (foot) {
          word = ''
          foot[0].forEach(function (metreme) {
            word += metreme[0]
          })
          lines = lines + ' ' + word
        })
        text = text + '\n' + lines.trim()
      })
      return text.trim()
    },
    textArray: function () {
      return this.reconText.split('\n').map(x => x.split(' '))
    }
  },
  props: ['metricalFeet', 'letters'],
  data () {
    return {}
  }
}
</script>
