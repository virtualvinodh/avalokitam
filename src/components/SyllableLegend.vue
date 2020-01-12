<template>
  <div class="line">
    <div class="feet q-ma-xs q-mr-md">
      <div class="symbol">⏑</div>
      <div class="tamil text-blue-10">நேர்</div>
    </div>
        <div class="feet q-ma-xs q-mr-md">
          <div class="symbol">―</div>
          <div class="tamil text-deep-orange-10">நிரை</div>
        </div>
        <div class="feet q-ma-xs">
        <div class="symbol">⏑<span class="pu">உ</span></div>
          <div class="tamil text-blue-10">பு</div>
        </div>
  </div>
</template>

<style scoped>
.line {
  display:inline-block;
  width: 100%;
}
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
</style>

<script>

export default {
  // name: 'ComponentName',
  data () {
    return {}
  }
}
</script>
