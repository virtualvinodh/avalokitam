<template>
<div>
    <div class="feet">
      <span v-if="lineind > 0 && footind == 0">
          <div class="col-lg-1 tamil q-mt-sm" :class="linkTypeStyle(linkage[lineind][0][2]) + ' ' + linkTypeClassVenpa(linkage[lineind][0][2])">⤒ <small><b v-html="linktypePrev"></b></small> ↦</div>
      </span>
    </div>
<div class="feet">
    <div class="feet q-ma-xs q-mb-md" v-for="(metreme, index) in foot[0]" :key="'metreme' + index">
      <span v-if="!hide">
      <div class="symbol" v-if="metreme[1]==='நேர்'">⏑</div>
      <div class="symbol" v-if="metreme[1]==='நிரை'">―</div>
      <div class="symbol" v-if="metreme[1]==='பு'">⏑<span class="pu">உ</span></div>
      </span>
      <div class="tamil metreme text-blue-10" v-if="metreme[1]==='நேர்'">{{metreme[0]}}</div>
      <div class="tamil metreme text-deep-orange-10" v-if="metreme[1]==='நிரை'">{{metreme[0]}}</div>
      <div class="tamil metreme text-blue-10" v-if="metreme[1]==='பு'">{{metreme[0]}}</div>
    </div>
    <span v-if="lineind !== linkage.length -1 && footind == linkage[lineind].length - 1">
        <span class="tamil arrow" :class="linkTypeStyle(linkage[lineind + 1][0][2])  + ' ' + linkTypeClassVenpa(linkage[lineind + 1][0][2])">⤓</span>
    </span>
    <br/>
  <div>
    <div class="col-lg-1 tamil" :class="feetTypeStyle(footVenpaCheck) + ' ' + feetTypeClassVenpa(footVenpaCheck)"><b>{{footVenpaCheck}}</b></div>
    </div>
  </div>
    <div class="feet q-mb-md">
    <span v-if="footind < linkage[lineind].length - 1">
        <div class="col-lg-1 tamil q-mt-sm" :class="linkTypeStyle(linkage[lineind][footind + 1][2])  + ' ' + linkTypeClassVenpa(linkage[lineind][footind + 1][2])">↤ <small><b v-html="linktype"></b></small> ↦</div>
    </span>
  </div>
</div>
</template>

<style>
.redunderline {
  text-decoration-line: underline;
  text-decoration-color: red;
  text-decoration-style: wavy;
}
.arrow {
  font-size: 140%;
}
.pu {
  font-size:30%;
}
.feet {
  text-align: center;
  float:left;
}
.symbol {
  font-size: 151%;
  margin-bottom: -12px;
}
.metreme {
  font-size: 120%
}
</style>

<script>
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  props: ['foot', 'hide', 'lineind', 'footind', 'linkage', 'checkvenpa', 'totallines', 'venpalastword'],
  mixins: [LinkMixin],
  computed: {
    footVenpaCheck: function () {
      if (this.checkvenpa) {
        if (this.lineind === this.totallines - 1 && this.footind === 2) {
          return this.venpalastword !== 'None' ? this.venpalastword : this.foot[1]
        } else {
          return this.foot[1]
        }
      } else {
        return this.foot[1]
      }
    },
    linktype: function () {
      var link = this.linkage[this.lineind][this.footind + 1][2].replace(' ', '<br/>')

      return link
    },
    linktypePrev: function () {
      var link = this.linkage[this.lineind][this.footind][2].replace(' ', '<br/>')

      return link
    }
  },
  methods: {
    linkTypeClassVenpa: function (linkage) {
      if (this.checkvenpa) {
        if (!linkage.includes('வெண்டளை')) {
          return 'redunderline'
        } else {
          return ''
        }
      }
    },
    feetTypeClassVenpa: function (foot) {
      if (this.checkvenpa) {
        if (!(this.lineind === this.totallines - 1 && this.footind === 2) && !this.feetTypesVenpa.includes(foot)) {
          return 'redunderline'
        } else if ((this.lineind === this.totallines - 1 && this.footind === 2) && !this.endFeetTypesVenpa.includes(foot)) {
          return 'redunderline'
        }
      }
    }
  },
  data () {
    return {
      feetTypesVenpa: ['தேமா', 'புளிமா', 'கூவிளம்', 'கருவிளம்', 'கூவிளங்காய்', 'கருவிளங்காய்', 'புளிமாங்காய்', 'தேமாங்காய்'],
      endFeetTypesVenpa: ['நாள்', 'மலர்', 'காசு', 'பிறப்பு']
    }
  }
}
</script>
