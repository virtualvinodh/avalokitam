<template>
 <div id="linkages">
      <div v-for="(line, index1) in feet" :key="'line3'+index1">
        <span v-for="(foot, index2) in line" :key="'foot3' + index2">
          <div class="q-mb-md">
              <div class="row q-mb-xxs text-grey-7" v-for="(metreme, index) in foot[0]" :key="'metreme' + index">
                <div class="col-xs-3 col-md-2 tamil metreme2" :class="metreme[0]" :id="'word' + index1 + index2">
                  {{metreme[0]}}
                </div>
                <div v-if="index == 0" class="col-xs-2 col-md-1 q-pr-md tamil metreme2" :id="'metreme' + index1 + index2"
                :class="metremeTypeStyle(foot[0][0][1])">
                  <span v-if="index1 != 0 || index2 !=0">{{foot[0][0][1]}}</span>
                </div>
                <div v-if="index == foot[0].length - 1" class="col-xs-2 col-md-1 q-pr-md tamil metreme2" :id="'type' + index1 + index2"
                 :class="feetTypeStyle(foot[1])">
                  <span v-if="!(index1 == feet.length - 1 && index2 == line.length - 1)">{{shortFeetType(foot[1])}}</span>
                </div>
              </div>
          </div>
        </span>
      </div>
 </div>
</template>

<style>
.line {
  display:inline-block;
  width: 100%;
}
.labelline {
  margin-left: 140px;
  margin-top: 10px;
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
    window.addEventListener('resize', this.handleResize)
    jsplumb.jsPlumb.reset()
    this.linkageAdd()
  },
  updated: function () {
    jsplumb.jsPlumb.reset()
    this.linkageAdd()
  },
  methods: {
    linkageAdd: function () {
      var dhis = this
      // console.log(this.links)
      jsplumb.jsPlumb.setContainer('app')
      jsplumb.jsPlumb.ready(function () {
        dhis.feet.forEach(function (line, li) {
          line.forEach(function (foot, fi) {
            try {
              jsplumb.jsPlumb.connect({
                source: 'type' + li + fi,
                target: 'metreme' + li + (fi + 1),
                anchor: 'Right',
                endpoint: 'Blank',
                connector: ['Flowchart', { stub: 80 }],
                overlays: [
                  [ 'Label', { label: '<br/>' + (dhis.links[li][fi + 1][2]), cssClass: 'tamil labelline ' + dhis.linkTypeStyle(dhis.links[li][fi + 1][2]), location: [] } ]
                ]
              })
            } catch (e) {
              // console.log(e)
            }
          })
          // console.log(li)
          try {
            jsplumb.jsPlumb.connect({
              source: 'type' + li + (line.length - 1),
              target: 'metreme' + (li + 1) + 0,
              anchor: 'Right',
              endpoint: 'Blank',
              connector: ['Flowchart', { stub: 80 }],
              overlays: [
                [ 'Label', { label: '<br/>' + (dhis.links[li + 1][0][2]), cssClass: 'tamil labelline ' + dhis.linkTypeStyle(dhis.links[li + 1][0][2]), location: [] } ]
              ]
            })
          } catch (e) {
            // console.log(e)
          }
        })
      })
    },
    handleResize: function () {
      jsplumb.jsPlumb.reset()
      this.linkageAdd()
    }
  },
  data () {
    return {}
  }
}
</script>
