<template>
  <div>
      <component :feet="metricalFeet" :links="linkage"
      :is="$q.platform.is.mobile || $q.screen.lt.sm ? 'LinkageBondLine' : 'LinkageBond'">
      </component>
    <q-separator />
    <div class="row tamil">
      <span v-for="(link, index) in countLinks" :key="'link' + index">
        <div class="col q-ma-md"> <b>{{link[0]}}</b><br/> {{countLinks[index][1]}} ({{Math.round(CountLinksPercent[index][1])}}%) </div>
      </span>
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
      <linkage-legend class="q-mt-sm"></linkage-legend>
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
    <linkage-pattern></linkage-pattern>
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
import LinkageBond from '../components/LinkageBond'
import LinkageBondLine from '../components/LinkageBondLine'
import LinkageLegend from '../components/LinkageLegend'
import LinkagePattern from '../components/LinkagePattern'

export default {
  // name: 'ComponentName',
  computed: {
    linkageIndividual: function () {
      var links = this.linkage.flat().map(x => x[2]).map(x => x.split(' ').length > 1 ? x.split(' ')[1] : x)

      links.shift()

      return links
    },
    countLinks: function () {
      const map = this.linkageIndividual.reduce((acc, e) => acc.set(e, (acc.get(e) || 0) + 1), new Map())

      return Array.from(map.entries())
    },
    CountLinksPercent: function () {
      return this.countLinks.map(x => [x[0], (x[1] / this.linkageIndividual.length) * 100])
    }
  },
  components: {
    LinkageBond,
    LinkageBondLine,
    LinkageLegend,
    LinkagePattern
  },
  props: ['metricalFeet', 'linkage'],
  data () {
    return {}
  }
}
</script>
