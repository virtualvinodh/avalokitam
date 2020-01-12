<template>
<div class="feet">
    <div class="feet" v-for="(metreme, index) in foot[0]" :key="'metreme' + index">
      <div class="tamil metreme">{{metreme[0]}}</div>
    </div>
    <div class="feet q-ma-xs q-mr-sm"></div>
  </div>
</template>

<style>
.feet {
  text-align: center;
  float:left;
}
.metreme {
  font-size: 120%
}
</style>

<script>
export default {
  // name: 'ComponentName',
  props: ['foot'],
  data () {
    return {}
  }
}
</script>
