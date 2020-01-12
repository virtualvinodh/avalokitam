<template>
    <q-page padding>
        <div class="quote tamil">
        <div class="q-ml-xl">
          ஆயும் குணத்து <b>அவலோகிதன்</b> பக்கல் அகத்தியன் கேட்டு ஏயும் புவனிக்கு இயம்பிய தண்டமிழ் ஈங்கு உரைக்க <br/>[...]<br/>
          பன்னூறாயிரம் விதத்திற் பொலியும் புகழ் <b>அவலோகிதன்</b> மெய்த் தமிழே
        </div>
              <div class="q-ml-xl q-mt-md"> — வீரசோழியம் </div> <br/>
        </div>
        <div class="text-body1 tamil"> <b>அவலோகிதம்</b> - ஒரு தமிழ் யாப்பு மென்பொருள் ஆகும். உள்ளிடப்பட்ட உரையினை தமிழ் யாப்பு விதிகளின் படி ஆராய்ந்து - <i>எழுத்து, அசை, சீர், தளை, அடி, தொடை</i> ஆகிய உறுப்புக்களை கணக்கிட்டு, இவற்றைக்கொண்டு உள்ளீட்டின் <i>பாவகை</i>யினை கண்டறிந்து மேற்கூறிய யாப்புறுப்புகளையும் பாவிதிகளின் பொருத்தத்தையும் வெளியிடும். கீழ்க்கண்ட பாவகைகளை அவலோகிதம் கண்டுகொள்ளூம் திறன் கொண்டது. <br/><br/>

        <div class="q-ml-lg"><small><i><span v-for="vt in verseTypes" :key="vt.value">{{vt.label}}, </span></i></small></div><br/>

        பண்டைய தமிழ் மஹாயான பௌத்தர்களால் தமிழ் மொழியை அகத்தியருக்கு உபதேசித்தவராக கருதப்பட்ட, சகலபுத்தர்களின் மஹாகருணையின் உருவகமாக விளங்கும் பகவான் போதிசத்துவர் அவலோகிதேஸ்வரரின் பெயர் இம்மென்பொருளுக்கு இடப்பட்டது.
        </div> <br/>
        <div class="text-body">Avalokitam is a prosody analyzer for the Tamil language. By recognizing the prosodic patterns, the input verses are analyzed for all the six basic elements of Tamil prosody: <i>eḻuttu</i> (letter), <i>acai</i> (metreme), <i>cīr</i> (metrical foot), <i>taḷai</i> (linkage), <i>aṭi</i> (metrical line) & <i>toṭai</i> (ornamenation). The meter is then recognized by matching the elements with the elaborate and complex rules of Tamil prosody. The tool further provides an elaborate and user-friendly display of the prosodic analysis.

        It is named upon Bodhisattva Avalokiteśvara, whom the Tamil Mahayana Buddhists of the yore considered as the progenitor of the Tamil language.</div> <br/>

        <div class="text-body">The source code is released under GNU AGPL and can be found in <a href="https://github.com/virtualvinodh/avalokitam">Github</a>. Please report any bugs over there.</div>
        <br/>
        <span v-if="!$q.platform.is.cordova">
          <div class="text-body1 tamil">நீங்கள் அவலோகிதம் மென்பொருளுக்கு ஆதரவு அளிக்கவோ ஊக்கம் அளிக்கவோ விரும்பினால், கீழ பே-பால் மூலமாக நன்கொடை அளிக்கலாம்.</div> <br/>
          <div class="text-body">If you'd like to donate to support and/or appreciate the work, you can send a donation through Paypal.</div> <br/>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
  <input type="hidden" name="cmd" value="_s-xclick" />
  <input type="hidden" name="hosted_button_id" value="DLDU4TRF7KCNC" />
  <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
  <img alt="" border="0" src="https://www.paypal.com/en_IN/i/scr/pixel.gif" width="1" height="1" />
  </form>

  <!-- Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DLDU4TRF7KCNC&source=url -->

          <br/>
      </span>
        <div class="text-body">Please send your feedback/comments to vinodh@virtualvinodh.com</div>

  </q-page>
</template>

<script>
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'PageName',
  mixins: [LinkMixin],
  data () {
    return {
    }
  }
}
</script>

<style scoped>
.quote {
  text-align: left;
  margin-right: 0px;
  color: #777;
  font-style:italic;
  font-size:14px;
  line-height:20px;
}
</style>
