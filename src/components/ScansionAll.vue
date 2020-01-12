<template>
  <div>
    <div v-for="(line, index) in metricalFeet" :key="'line'+index" class="line">
      <span v-for="(foot, index2) in line" :key="'foot' + index2">
        <metrical-feet2 :foot="foot" :hide="hide" :linkage="linkage" :lineind="index" :footind="index2" :checkvenpa="checkvenpa" :totallines="linetypes.length" :venpalastword="venpalastword"></metrical-feet2>
      </span>
      <div class="line tamil q-mt-md q-ml-lg" :class="lineTypeStyle(linetypes[index]) + ' ' + lineTypeClassVenpa(linetypes[index], index)"><b>{{linetypes[index]}}</b></div>
      <q-separator class="q-ma-md"/>
    </div>
    <br/>
  </div>
</template>

<style scoped>
.redunderline {
  text-decoration-line: underline;
  text-decoration-color: red;
  text-decoration-style: wavy;
}
.line {
  display:inline-block;
  width: 100%;
}
</style>

<script>
import MetricalFeet2 from '../components/MetricalFeet2'
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  components: {
    MetricalFeet2
  },
  props: ['metricalFeet', 'hide', 'linkage', 'linetypes', 'checkvenpa', 'venpalastword'],
  mixins: [LinkMixin],
  methods: {
    lineTypeClassVenpa: function (line, index) {
      if (this.checkvenpa) {
        if (index < this.linetypes.length - 1 && line !== 'அளவடி') {
          return 'redunderline'
        } else if (index === this.linetypes.length - 1 && line !== 'சிந்தடி') {
          return 'redunderline'
        } else {
          return ''
        }
      }
    }
  },
  data () {
    return {}
  }
}
</script>
