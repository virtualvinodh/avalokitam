<template>
  <div>
      <div class="row">
        <div class="q-ma-sm col-lg-11" v-for="(rule, index) in ruleslist.Rule" :key="'rule' + index">
          <span v-if="index !== ruleslist.Rule.length - 1">
            {{rule}}
            <span v-if="ruleslist.Result[index] == '1'">
              <q-icon name="done" color="green" size="20px"/>
            </span>
            <span v-else-if="ruleslist.Result[index] == ''">
              <q-icon name="close" color="red"/>
            </span>
            <span v-else-if="ruleslist.Result[index] == 'info'">
              <q-icon name="info" color="blue" size="20px"/>
            </span>
            <span v-else>
              <q-icon name="close" color="red"/>
              <div v-if="type == 'venpaa'" class="text-red-4">{{ruleslist.Result[index]}}</div>
            </span>
          </span>
        </div>
      </div>
      <span v-if="explanation !== '' && (!this.checkactive || (this.checkactive && this.type == 'venpaa' && this.explanation.includes('வெண்பா ')) || (this.checkactive && this.type == 'aciriyappaa' && this.explanation.includes('ஆசிரியப்பா')) || (this.checkactive && this.type == '_Aciriya_viru_tta_m' && this.explanation.includes('ஆசிரியவிருத்தம்')) || (this.checkactive && this.type == 'vanjippaa' && this.explanation.includes('வஞ்சிப்பா')))">
        <q-separator color="white"/>
        <div class="q-ma-sm" :class="highlight ? 'highlight' : ''">{{explanation}}</div>
      </span>
  </div>
</template>

<style scoped>
.highlight {
  font-weight: bold;
}
</style>

<script>
export default {
  // name: 'ComponentName',
  props: ['ruleslist', 'explanation', 'type', 'checkactive', 'highlight'],
  data () {
    return {}
  }
}
</script>
