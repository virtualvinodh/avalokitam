<template>
<div class="feet">
    <div class="feet" v-for="(metreme, index) in foot[0]" :key="'metreme' + index">
      <div class="tamil metreme text-grey-6">{{metreme[0]}}</div>
    </div>
    <div class="feet q-ma-xs q-mr-sm"></div>
   <br/>
   <div class="feet">
    <div class="tamil matra">{{half(countMatra(reconMetreme))}}</div>
  </div>
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
.matra {
  margin-left: -10px;
  font-size: 110%;
  font-weight: bold;
}
</style>

<script>
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'ComponentName',
  props: ['foot'],
  mixins: [LinkMixin],
  computed: {
    reconMetreme: function () {
      var str = ''
      this.foot[0].forEach(function (metreme) {
        str += metreme[0]
      })

      return str
    }
  },
  data () {
    return {}
  }
}
</script>
