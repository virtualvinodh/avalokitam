<template>
  <div>
    <letter-type  :metricalFeet="metricalFeet" :letters="letters"></letter-type>
    <q-expansion-item
        icon="straighten"
        label="வாய்ப்பாடுகள்"
        class="q-mt-md"
        header-class="tamil bg-grey-8 text-white q-pa-sm"
        expand-icon-class="text-white"
        dense
    >
    <br/>
    <letter-pattern></letter-pattern>
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
import LetterType from '../components/LetterType'
import LetterPattern from '../components/LetterPattern'

export default {
  // name: 'ComponentName',
  components: {
    LetterType,
    LetterPattern
  },
  props: ['metricalFeet', 'letters'],
  data () {
    return {}
  }
}
</script>
