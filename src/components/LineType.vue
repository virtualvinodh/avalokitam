<template>
  <div>
    <span v-for="(line, index) in metricalFeet" :key="'line'+index">
      <q-separator class="q-ma-sm" v-if="index > 0"/>
      <div class="line text-grey-7 q-ma-xs">
        <display-feet v-for="(foot, index2) in line" :key="'foot' + index2" :foot="foot"></display-feet>
      </div>
      <div class="tamil q-ml-md" :class="lineTypeStyle(linetypes[index])"> <b> {{linetypes[index]}} </b></div>
    </span>
    <q-separator  class="q-mt-sm"/>
  <div class="q-ma-md tamil">மொத்த அடிகள் {{metricalFeet.length}}</div>
  </div>
</template>

<style scoped>
.line {
  display:inline-block;
  width: 100%;
}
</style>

<script>
import DisplayFeet from '../components/DisplayFeet'
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  components: {
    DisplayFeet
  },
  props: ['metricalFeet', 'linetypes'],
  mixins: [LinkMixin],
  data () {
    return {}
  }
}
</script>
