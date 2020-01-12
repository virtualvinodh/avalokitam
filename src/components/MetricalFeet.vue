<template>
<div class="feet">
    <div class="feet q-ma-xs" v-for="(metreme, index) in foot[0]" :key="'metreme' + index">
      <span v-if="!hide">
      <div class="symbol" v-if="metreme[1]==='நேர்'">⏑</div>
      <div class="symbol" v-if="metreme[1]==='நிரை'">―</div>
      <div class="symbol" v-if="metreme[1]==='பு'">⏑<span class="pu">உ</span></div>
      </span>
      <div class="tamil metreme text-blue-10" v-if="metreme[1]==='நேர்'">{{metreme[0]}}</div>
      <div class="tamil metreme text-deep-orange-10" v-if="metreme[1]==='நிரை'">{{metreme[0]}}</div>
      <div class="tamil metreme text-blue-10" v-if="metreme[1]==='பு'">{{metreme[0]}}</div>
    </div>
    <div class="feet q-ma-xs q-mr-sm">
    </div> <br/>
    <div class="tamil" :class="feetTypeStyle(foot[1])"><b>{{foot[1]}}</b></div>
  </div>
</template>

<style>
.pu {
  font-size:30%;
}
.feet {
  text-align: center;
  float:left;
}
.symbol {
  font-size: 151%;
  margin-bottom: -12px;
}
.metreme {
  font-size: 120%
}
</style>

<script>
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  props: ['foot', 'hide'],
  mixins: [LinkMixin],
  data () {
    return {}
  }
}
</script>
