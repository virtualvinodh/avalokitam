<template>
  <div>
    <ornament-type  :metricalFeet="metricalFeet" :ornaments="ornaments"></ornament-type>
    <q-expansion-item
        icon="straighten"
        label="வாய்ப்பாடுகள்"
        class="q-mt-md"
        header-class="tamil bg-grey-8 text-white q-pa-sm"
        expand-icon-class="text-white"
        dense
    >
    <br/>
    <ornament-pattern></ornament-pattern>
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
import OrnamentType from '../components/OrnamentType'
import OrnamentPattern from '../components/OrnamentPattern'

export default {
  // name: 'ComponentName',
  components: {
    OrnamentType,
    OrnamentPattern
  },
  props: ['metricalFeet', 'ornaments'],
  data () {
    return {}
  }
}
</script>
