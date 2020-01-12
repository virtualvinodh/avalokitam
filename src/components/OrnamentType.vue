<template>
  <div class="q-ma-xs">
    <q-btn-toggle
      v-model="orn"
      toggle-color="grey-10"
      :options="[
        {label: 'எதுகை', value: 'rhyme'},
        {label: 'மோனை', value: 'alliteration'},
        {label: 'இயைபு', value: 'ultimarhyme'}
      ]"
      class="q-ma-md tamil"
    />
    <q-banner class="bg-grey-6 text-white tamil q-ma-md rounded">
      {{title[orn][0]}}
    </q-banner>
    <span v-for="(line, index1) in reconText.split('\n')" :key="'line'+index1">
      <q-separator class="q-ma-sm" v-if="index1 > 0 && typeof ornaments.foot[orn][index1] !== 'undefined'"/>
      <div class="q-mb-md" v-if="typeof ornaments.foot[orn][index1] !== 'undefined'">
        <span class="tamil">அடி {{index1 + 1}}: </span> <br/>
        <div v-for="index4 in ornaments.foot[orn][index1].length" :key="index4+'ind4'">
          <span v-for="(foot, index2) in line.split(' ')" :key="'foot' + index2" class="tamil metreme q-mr-sm">
              <span v-for="(syllable, index3) in syllabize(foot)" :key="'syll' + index3"
                  :class="highlightClass['syl' + index1 + index2 + index3 + index4] ? highlight : 'text-grey-7'">{{syllable}}</span>
          </span>
          <div class="tamil q-mt-xs">
          <i>{{ornaments.foot[orn][index1][index4-1][0]}} {{classTransl[ornaments.foot[orn][index1][index4-1][1]]}} {{ornamentTransl[orn]}}</i>
          </div>
        </div>
      </div>
    </span>
        <div class="tamil q-ml-sm text-blue-grey-10" v-if="Object.entries(ornaments['foot'][orn]).length === 0 && ornaments['foot'][orn].constructor === Object"><b>தொடை காணப்படவில்லை</b></div>
      <q-separator class="q-ma-sm"/>
    <q-banner class="bg-grey-6 text-white tamil q-ma-md rounded">
      {{title[orn][1]}}
    </q-banner>
    <span v-if="typeof ornaments['line'][orn] !== 'undefined'">
      <div v-for="index3 in ornaments.line[orn].length" :key="index3+'ind3'">
        <span v-for="(line, index1) in reconText.split('\n')" :key="'liness'+index1" class="tamil metreme q-mr-sm">
          <span v-if="orn != 'ultimarhyme'">
            <span v-for="(syl, index2) in syllabize(line.split(' ')[0])" :key="'linesyl' + index2"
            :class="highlightClass['linesyl' + index1 + index2 + index3] ? highlight : 'text-grey-7'"
            >{{syl}}</span>
          </span>
          <span v-if="orn == 'ultimarhyme'">
            <span v-for="(syl, index2) in syllabize(line.split(' ')[line.split(' ').length - 1])" :key="'linesyl' + index2"
            :class="highlightClass['linesyl' + index1 + index2 + index3] ? highlight : 'text-grey-7'"
            >{{syl}}</span>
          </span>
        </span>
          <div class="tamil q-mt-xs" v-if="orn !== 'ultimarhyme'">
            <i>{{idaiyittuVal[ornaments.line[orn][index3-1][0]] && (ornaments.line[orn][index3-1][0] == 'special' || ornaments.line[orn][index3-1][0] == 'varga') ? 'இடையிட்டு ' : ''}}{{classTransl[ornaments.line[orn][index3-1][0]]}} {{ornamentTransl[orn]}} </i>
          </div>
      </div>
    </span>
    <div class="tamil q-ml-sm text-blue-grey-10" v-if="typeof ornaments['line'][orn] === 'undefined'"><b>தொடை காணப்படவில்லை</b></div>
  </div>
</template>

<style scoped>
.line {
  display:inline-block;
  width: 100%;
}
.highlight {
  font-weight: bold;
}
</style>

<script>
import { LinkMixin } from '../mixin/LinkMixin'
export default {
  // name: 'ComponentName',
  components: {
  },
  props: ['metricalFeet', 'ornaments'],
  mixins: [LinkMixin],
  mounted: function () {
  },
  computed: {
    idaiyittuVal: function () {
      var idaiyittu = []
      idaiyittu['special'] = true
      idaiyittu['varga'] = true
      if (typeof this.ornaments.line.rhyme !== 'undefined') {
        this.ornaments.line.rhyme.forEach(function (rhymeEntry, index2) {
          if (rhymeEntry[0] === 'special' || rhymeEntry[0] === 'varga') {
            idaiyittu[rhymeEntry[0]] = true
            for (let y = 0; y < rhymeEntry[1].length - 1; y++) {
              if (rhymeEntry[1][y + 1] - rhymeEntry[1][y] !== 2) {
                idaiyittu[rhymeEntry[0]] = false
              }
            }
          }
        })
      }

      return idaiyittu
    },
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
    },
    highlightClass: function () {
      var classH = {}
      var dhis = this
      var syl = ['ய்', 'ர்', 'ழ்', 'ல்']
      if (this.orn === 'rhyme') {
        for (let foot in this.ornaments.foot.rhyme) {
          this.ornaments.foot.rhyme[foot].forEach(function (rhymeEntry, index) {
            rhymeEntry[2].forEach(function (footIndex) {
              if (rhymeEntry[1] !== 'acitai') {
                classH['syl' + foot + (footIndex - 1) + '1' + (index + 1)] = true
              } else {
                if (!syl.includes(dhis.syllabize(dhis.textArray[foot][footIndex - 1])[1])) {
                  classH['syl' + foot + (footIndex - 1) + '1' + (index + 1)] = true
                } else {
                  classH['syl' + foot + (footIndex - 1) + '2' + (index + 1)] = true
                }
              }
            })
          })
        }
      } else if (this.orn === 'alliteration') {
        for (let foot in this.ornaments.foot.alliteration) {
          this.ornaments.foot.alliteration[foot].forEach(function (rhymeEntry, index) {
            rhymeEntry[2].forEach(function (footIndex) {
              classH['syl' + foot + (footIndex - 1) + '0' + (index + 1)] = true
            })
          })
        }
      } else if (this.orn === 'ultimarhyme') {
        // console.log(this.textArray)
        for (let foot in this.ornaments.foot.ultimarhyme) {
          this.ornaments.foot.ultimarhyme[foot][0][2].forEach(function (footIndex) {
            let ultimaIndex = dhis.syllabize(dhis.textArray[foot][footIndex - 1]).length - 1
            classH['syl' + foot + (footIndex - 1) + ultimaIndex + '1'] = true
          })
        }
      }
      if (this.orn === 'rhyme') {
        if (typeof this.ornaments.line.rhyme !== 'undefined') {
          this.ornaments.line.rhyme.forEach(function (rhymeEntry, index2) {
            rhymeEntry[1].forEach(function (element, index) {
              if (rhymeEntry[0] !== 'acitai') {
                classH['linesyl' + (element - 1) + '1' + (index2 + 1)] = true
              } else {
                if (!syl.includes(dhis.syllabize(dhis.textArray[element - 1][0])[1])) {
                  classH['linesyl' + (element - 1) + '1' + (index2 + 1)] = true
                } else {
                  classH['linesyl' + (element - 1) + '2' + (index2 + 1)] = true
                }
              }
            })
          })
        }
      } else if (this.orn === 'alliteration') {
        if (typeof this.ornaments.line.alliteration !== 'undefined') {
          this.ornaments.line.alliteration.forEach(function (rhymeEntry, index2) {
            rhymeEntry[1].forEach(function (element, index) {
              classH['linesyl' + (element - 1) + '0' + (index2 + 1)] = true
            })
          })
        }
      } else if (this.orn === 'ultimarhyme') {
        if (typeof this.ornaments.line.ultimarhyme !== 'undefined') {
          this.ornaments.line.ultimarhyme.forEach(function (rhymeEntry, index2) {
            rhymeEntry[1].forEach(function (element, index) {
              let ultimaIndex = dhis.syllabize(dhis.textArray[element - 1][dhis.textArray[element - 1].length - 1]).length - 1
              classH['linesyl' + (element - 1) + ultimaIndex + (index2 + 1)] = true
            })
          })
        }
      }
      return classH
    }
  },
  data () {
    return {
      orn: 'rhyme',
      idaiyittu: false,
      highlight: 'highlight text-cyan-10',
      title: { 'rhyme': ['சீர் எதுகை', 'அடி எதுகை'], 'alliteration': ['சீர் மோனை', 'அடி மோனை'], 'ultimarhyme': ['சீர் இயைபு', 'அடி இயைபு'] },
      ornamentTransl: { 'rhyme': 'எதுகை', 'alliteration': 'மோனை', 'ultimarhyme': 'இயைபு' },
      classTransl: { 'special': '', 'varga': 'வர்க்க', 'uyir': 'உயிர்', 'inam': 'இன', 'acitai': 'ஆசிடை', 'nedil': 'நெடில்' }

    }
  },
  methods: {
  }
}
</script>
