<template>
 <div id="linkages">
      <div v-for="(line, index1) in feet" :key="'line3'+index1" class="linelinkage">
            <div class="bord22" v-if="index1 !== 0">
              <div class="bord22 text-grey-7"><q-icon name="vertical_align_top"></q-icon></div>
              <div class="bord22 q-ma-xs text-grey-7" v-for="(metreme, index) in feet[index1-1][feet[index1-1].length - 1][0]" :key="'metreme' + index">
                <div class="tamil metreme2" :class="metreme[0]" :id="'word' + (index1 - 1) + 'prev'">
                  {{metreme[0]}}
                </div>
                <span v-if="index == 0" class="tamil" :id="'metreme' + (index1 - 1) + 'prev'">
                </span>
                <span v-if="index == feet[index1-1][feet[index1-1].length - 1][0].length - 1" class="tamil" :id="'type' + (index1 - 1) + 'prev'"
                :class="feetTypeStyle(shortFeetType(feet[index1-1][feet[index1-1].length - 1][1]))">
                  <b>{{shortFeetType(feet[index1-1][feet[index1-1].length - 1][1])}}</b>
                </span>
              </div>
              <div class="bord22 q-ma-xs q-mr-lg">
                <div></div>
              </div>
          </div>
        <span v-for="(foot, index2) in line" :key="'foot3' + index2">
          <div class="bord22 text-grey-7" :ref="'foot' + index1 + index2"
          :class="getTopClass(index1, index2)"
          >
              <div class="bord22 q-ma-xs" v-for="(metreme, index) in foot[0]" :key="'metreme' + index">
                <div class="tamil metreme2" :class="metreme[0]" :id="'word' + index1 + index2">
                  {{metreme[0]}}
                </div>
                <span v-if="index == 0" class="tamil" :id="'metreme' + index1 + index2"
                :class="metremeTypeStyle(foot[0][0][1])">
                  <span v-if="index1 != 0 || index2 !=0"><b>{{foot[0][0][1]}}</b></span>
                </span>
                <span v-if="index == foot[0].length - 1" class="tamil" :id="'type' + index1 + index2"
                 :class="feetTypeStyle(foot[1])">
                  <span v-if="index2 < line.length - 1"><b>{{shortFeetType(foot[1])}}</b></span>
                </span>
              </div>
              <div class="bord22 q-ma-xs q-mr-md">
                <div></div>
              </div>
          </div>
        </span>
              <q-separator v-if="index1 > 0"/>
      </div>
 </div>
</template>

<style>
.linkbottomwidth {
  margin-top: 40px;
}
.linelinkage {
  display:inline-block;
  margin-bottom: 40px;
  width: 100%;
}
.label {
  margin-top: -20px;
  font-weight: bold;
}
.bord22 {
  text-align: center;
  float:left;
}
.breve2 {
  font-size: 150%;
  margin-bottom: -12px;
}
.metreme2 {
  font-size: 120%
}
</style>

<script>
import jsplumb from 'jsplumb'
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  props: ['feet', 'links'],
  mixins: [LinkMixin],
  components: {
  },
  beforeDestroy: function () {
    window.removeEventListener('resize', this.handleResize)
  },
  destroyed: function () {
    jsplumb.jsPlumb.reset()
  },
  mounted: function () {
    // console.log('Reloading the component')
    window.addEventListener('resize', this.handleResize)
    // console.log('Mounted')
    // console.log(JSON.stringify(this.$refs))
    this.wrapDetect()
    jsplumb.jsPlumb.reset()
    this.linkageAdd()
  },
  updated: function () {
    if (this.updatedFeet) {
      // console.log('Checking update after updated feet')
      this.wrapDetect()
      this.updatedFeet = false
    }
    jsplumb.jsPlumb.reset()
    this.linkageAdd()
  },
  watch: {
    feet: function (oldV, newV) {
      // console.log('Updated feet')
      this.updatedFeet = true
    }
  },
  methods: {
    refresh: async function () {
      this.wrapDetect()
      await this.sleep(1000)
      jsplumb.jsPlumb.reset()
      this.linkageAdd()
    },
    demo: function () {
      // console.log(this.$refs.foot00[0].getBoundingClientRect().y)
      // console.log(this.$refs.foot03[0].getBoundingClientRect().y)
      // console.log(this.$refs.foot03[0].getBoundingClientRect().y - this.$refs.foot00[0].getBoundingClientRect().y)
    },
    getTopClass: function (index1, index2) {
      try {
        if (this.lineWrap[index1][index2]) {
          return 'linkbottomwidth'
        } else {
          return ''
        }
      } catch (e) {
        // console.log(e)
        return ''
      }
    },
    wrapDetect: function () {
      var dhis = this
      this.lineWrap = []
      // console.log(this.feet)
      // console.log('trying to detect wrapping')
      this.feet.forEach(function (line, lineIndex) {
        var feetWrap = []
        line.forEach(function (foot, footIndex) {
          var footInitial = 'foot' + lineIndex + '0'
          var footFinal = 'foot' + lineIndex + footIndex
          var lineDiff = dhis.$refs[footFinal][0].getBoundingClientRect().y - dhis.$refs[footInitial][0].getBoundingClientRect().y
          if (lineDiff > 0 && Math.round(lineDiff) !== 40) {
            // console.log(lineDiff)
            feetWrap.push(true)
          } else {
            feetWrap.push(false)
          }
        })
        dhis.lineWrap.push(feetWrap)
      })
      // console.log(this.lineWrap)
    },
    linkageAdd: function () {
      var dhis = this
      // console.log(this.links)
      jsplumb.jsPlumb.setContainer('app')
      jsplumb.jsPlumb.ready(function () {
        dhis.feet.forEach(function (line, li) {
          if (li > 0) {
            jsplumb.jsPlumb.connect({
              source: 'type' + (li - 1) + 'prev',
              target: 'metreme' + li + 0,
              endpoint: 'Blank',
              connector: 'Flowchart',
              overlays: [
                [ 'Label', { label: '<br/>' + dhis.shortLinkType(dhis.links[li][0][2]), location: 0.5, cssClass: 'tamil label ' + dhis.linkTypeStyle(dhis.links[li][0][2]) } ]
              ]
            })
          }
          line.forEach(function (foot, fi) {
            try {
              jsplumb.jsPlumb.connect({
                source: 'type' + li + fi,
                target: 'metreme' + li + (fi + 1),
                endpoint: 'Blank',
                connector: 'Flowchart',
                overlays: [
                  [ 'Label',
                    { label: '<br/>' + dhis.shortLinkType(dhis.links[li][fi + 1][2]), location: 0.5, cssClass: 'tamil label ' + dhis.linkTypeStyle(dhis.links[li][fi + 1][2]) } ]
                ]
              })
            } catch (e) {
              // console.log(e)
            }
          })
        })
      })
    },
    handleResize: function () {
      this.wrapDetect()
      jsplumb.jsPlumb.reset()
      this.linkageAdd()
    }
  },
  data () {
    return {
      lineWrap: [],
      updatedFeet: false
    }
  }
}
</script>
