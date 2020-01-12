<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-btn :color="view == 'eluttu' ? 'grey-10': 'grey-7'" icon="sort_by_alpha" label="எழுத்து" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'eluttu'"/>
      <q-btn :color="view == 'acai' ? 'grey-10': 'grey-7'" icon="line_style" label="அசை/சீர்" class="tamil"  :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'acai'"/>
      <q-btn :color="view == 'talai' ? 'grey-10': 'grey-7'" icon="link" label="தளை" class="tamil"  :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'talai'"/>
      <q-btn :color="view == 'adi' ? 'grey-10': 'grey-7'" icon="format_align_justify" label="அடி" class="tamil"  :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'adi'"/>
      <q-btn :color="view == 'todai' ? 'grey-10': 'grey-7'" icon="local_florist" label="தொடை" class="tamil"  :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'todai'"/>
      <q-btn :color="view == 'rules' ? 'grey-10': 'grey-7'" icon="traffic" label="பா/பாவின விதிகள்" class="tamil"  :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'rules'"/>
    </div>
    <div v-if="view == 'eluttu'">
      <letter-pattern></letter-pattern>
    </div>
    <div v-if="view == 'acai'">
      <syllable-legend class="q-mb-md"></syllable-legend>
      <syllable-pattern></syllable-pattern>
    </div>
    <div v-if="view == 'talai'">
      <linkage-pattern></linkage-pattern>
    </div>
    <div v-if="view == 'adi'">
      <line-pattern></line-pattern>
    </div>
    <div v-if="view == 'todai'">
      <Ornament-pattern></Ornament-pattern>
    </div>
    <div v-if="view == 'rules'" class="tamil">
      <h5> மேற்கோள்கள் </h5>
        <ol>
          <li>வெண்பா: <a href="http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l1.htm">http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l1.htm</a>
          <li>ஆசிரியப்பா: <a href="http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l2.htm">http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l2.htm</a>
          </li>
          <li>கலிப்பா: <a href="http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l3.htm">http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l3.htm</a></li>
          <li>வஞ்சிப்பா: <a href="http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l4.htm">http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02143l4.htm</a></li>
          <li>மரபுக் கவிதை: <a href="http://www.tamilvu.org/courses/degree/p203/p2031/html/p2031221.htm"> http://www.tamilvu.org/courses/degree/p203/p2031/html/p2031221.htm</a></li>
          <li>பாவின வடிவங்கள் 1: <a href="http://www.tamilvu.org/courses/degree/p203/p2031/html/p2031333.htm">http://www.tamilvu.org/courses/degree/p203/p2031/html/p2031333.htm</a></li>
          <li>பாவின வடிவங்கள் 2: <a href="http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02144in.htm">http://www.tamilvu.org/courses/diploma/a021/a0214/html/a02144in.htm</a></li>
          <li>ஆசிரியப்பாவின் இனங்கள்: <a href="http://www.tamilvu.org/courses/degree/d031/d0312/html/d0312333.htm">http://www.tamilvu.org/courses/degree/d031/d0312/html/d0312333.htm</a></li>
          <li>புறனடை 1: <a href="http://www.tamilvu.org/ta/courses-degree-d031-d0312-html-d0312554-20364">http://www.tamilvu.org/ta/courses-degree-d031-d0312-html-d0312554-20364</a></li>
          <li>புறனடை 2: <a href="http://www.tamilvu.org/courses/degree/d031/d0312/html/d0312552.htm">http://www.tamilvu.org/courses/degree/d031/d0312/html/d0312552.htm</a></li>
          <li>யாப்பிலக்கணம் 1: <a href="https://www.srmist.edu.in/tamilperayam/tamilperayam/diploma-dtt/Lessons/I_Year/dipl02/html/dip02005eap2.htm">https://www.srmist.edu.in/tamilperayam/tamilperayam/diploma-dtt/Lessons/I_Year/dipl02/html/dip02005eap2.htm</a></li>
          <li>யாப்பிலக்கணம் 2: <a href="http://www.tamilvu.org/ta/courses-diploma-a021-a0214-html-a02143l4-32495">http://www.tamilvu.org/ta/courses-diploma-a021-a0214-html-a02143l4-32495</a></li>
         <li>யாப்பிலக்கணம் 3: <a href="http://www.tamilvu.org/courses/degree/d031/d0312/html/d0312ind.htm">http://www.tamilvu.org/courses/degree/d031/d0312/html/d0312ind.htm</a></li>
         <li>பசுபதி, <a
              href="https://groups.google.com/forum/#!msg/yappulagam/Umr4M2rOzQs/xaSw4OecQbIJ">கவிதை
                இயற்றிக் கலக்கு</a> </li>
          <li>கட்டளைக் கலிப்பா: <a href="https://groups.google.com/forum/?hl=en#!msg/yappulagam/Umr4M2rOzQs/Pw9lK8vfl3gJ">https://groups.google.com/forum/?hl=en#!msg/yappulagam/Umr4M2rOzQs/Pw9lK8vfl3gJ</a>
          <li>அடி எதுகை: <a href="http://ramani-yappu.blogspot.com/2017/01/">http://ramani-yappu.blogspot.com/2017/01/</a></li>
          <li>திருத்தணிகை விசாகப் பெருமாளையர் (1939).
              <a href="http://noolaham.org/wiki/index.php/%E0%AE%A4%E0%AE%BF%E0%AE%B0%E0%AF%81%E0%AE%A4%E0%AF%8D%E0%AE%A4%E0%AE%A3%E0%AE%BF%E0%AE%95%E0%AF%88_%E0%AE%B5%E0%AE%BF%E0%AE%9A%E0%AE%BE%E0%AE%95%E0%AE%AA%E0%AF%8D%E0%AE%AA%E0%AF%86%E0%AE%B0%E0%AF%81%E0%AE%AE%E0%AE%BE%E0%AE%B3%E0%AF%88%E0%AE%AF%E0%AE%B0%E0%AF%8D_%E0%AE%87%E0%AE%AF%E0%AE%B1%E0%AF%8D%E0%AE%B1%E0%AE%BF%E0%AE%AF_%E0%AE%AF%E0%AE%BE%E0%AE%AA%E0%AF%8D%E0%AE%AA%E0%AE%BF%E0%AE%B2%E0%AE%95%E0%AF%8D%E0%AE%95%E0%AE%A3%E0%AE%AE%E0%AF%8D">யாப்பிலக்கணம்</a> </li>
          <li>கி.வா.ஜகன்னாதன் (2003).
              <a href="http://www.tamilvu.org/library/nationalized/pdf/92-ki.va.ja/kavipaadalam(119).pdf">கவி பாடலாம் வாங்க</a> </li>
          <li>Niklas, U. (1988). Introduction to Tamil prosody. <em>Bulletin
                de l'Ecole française d'Extrême-Orient</em>, 77(1), 165-227. </li>
        </ol>
    </div>
  </q-page>
</template>

<script>
import SyllablePattern from '../components/SyllablePattern'
import SyllableLegend from '../components/SyllableLegend'
import LinkagePattern from '../components/LinkagePattern'
import LinePattern from '../components/LinePattern'
import LetterPattern from '../components/LetterPattern'
import OrnamentPattern from '../components/OrnamentPattern'

export default {
  // name: 'PageName',
  mounted: function () {
    if (typeof this.$route.query.view !== 'undefined') {
      this.view = this.$route.query.view
    }
  },
  components: {
    SyllablePattern,
    SyllableLegend,
    LinePattern,
    LinkagePattern,
    LetterPattern,
    OrnamentPattern
  },
  data () {
    return {
      view: 'acai'
    }
  }
}
</script>

<style scoped>
</style>
