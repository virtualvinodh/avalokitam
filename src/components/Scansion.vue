<template>
  <div>
    <div v-for="(line, index) in metricalFeet" :key="'line'+index" class="line">
      <metrical-feet v-for="(foot, index2) in line" :key="'foot' + index2" :foot="foot"></metrical-feet>
      <q-separator class="q-mt-xs" v-if="index > 0"/>
    </div>
    <br/>
  </div>
</template>

<style scoped>
.line {
  display:inline-block;
  width: 100%;
}
</style>

<script>
import MetricalFeet from '../components/MetricalFeet'

export default {
  // name: 'ComponentName',
  components: {
    MetricalFeet
  },
  props: ['metricalFeet'],
  data () {
    return {}
  }
}
</script>
