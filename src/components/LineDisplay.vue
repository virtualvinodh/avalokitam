<template>
  <div>
    <line-type  :metricalFeet="metricalFeet" :linetypes="linetypes"></line-type>
    <q-expansion-item
        icon="straighten"
        label="வாய்ப்பாடுகள்"
        class="q-mt-md"
        header-class="tamil bg-grey-8 text-white q-pa-sm"
        expand-icon-class="text-white"
        dense
    >
    <br/>
    <line-pattern></line-pattern>
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
import LineType from '../components/LineType'
import LinePattern from '../components/LinePattern'

export default {
  // name: 'ComponentName',
  components: {
    LineType,
    LinePattern
  },
  props: ['metricalFeet', 'linetypes'],
  data () {
    return {}
  }
}
</script>
