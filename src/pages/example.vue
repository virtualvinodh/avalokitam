<template>
  <q-page padding>
    <q-btn :color="view == 'venpa' ? 'grey-10': 'grey-7'" label="வெண்பா" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'venpa'"/>
    <q-btn :color="view == 'venpavinam' ? 'grey-10': 'grey-7'" label="வெண்பாவினம்" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'venpavinam'"/>
    <q-btn :color="view == 'aciriyappa' ? 'grey-10': 'grey-7'" label="ஆசிரியப்பா" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'aciriyappa'"/>
    <q-btn :color="view == 'aciriyappavinam' ? 'grey-10': 'grey-7'" label="ஆசிரியப்பாவினம்" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'aciriyappavinam'"/>
    <q-btn :color="view == 'kalippa' ? 'grey-10': 'grey-7'" label="கலிப்பா" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'kalippa'"/>
    <q-btn :color="view == 'kalippavinam' ? 'grey-10': 'grey-7'" label="கலிப்பாவினம்" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'kalippavinam'"/>
    <q-btn :color="view == 'vanjippaa' ? 'grey-10': 'grey-7'" label="வஞ்சிப்பா" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'vanjippaa'"/>
    <q-btn :color="view == 'vanjippavinam' ? 'grey-10': 'grey-7'" label="வஞ்சிப்பாவினம்" class="tamil" :class="$q.platform.is.mobile ? 'q-ma-xs' : 'q-ma-sm'" :dense="$q.platform.is.mobile" @click="view = 'vanjippavinam'"/>
    <div class="tamil q-ma-md">கீழே உள்ள பாக்களை தாங்கள் தாராளமாக மாற்றலாம். நீங்கள் மாற்ற மாற்ற, உங்கள் மாற்றம் பா விதிகளுக்கு உட்பட்டுள்ளதா என்று உடனடியாக காட்டுவிடும். மூல உதாரணத்தை திரும்பப்பெற ‘மீளமை’ என்பதை கிளிக் செய்யவும். பாவினை விலாவரியாக அவலோகிதம் கொண்டு ஆராய, ‘ஆராய்க’ என்பதை கிளிக் செய்யவும்.</div> <br/>
    <span v-for="(verse, index) in this.examples[view]" :key="'vv' + index">
      <q-splitter
        v-model="splitterModel"
        disable
        :horizontal="$q.platform.is.mobile"
      >
        <template v-slot:before>
          <div class="q-pa-md tamil">
            <div class="text-h6 q-mb-md">{{verse.label}}</div>
            <q-input
              v-model="verse.text"
              borderless
              autogrow
              type="textarea"
              placeholder="பாவினை உள்ளிடவும்"
              class="tamil q-ma-sm"
              @input="demoSpecific(view, index)"
            />
            <div class="q-mt-md">
              <q-btn label="ஆராய்க" color="grey-8" class="q-ml-md tamil" @click="explore(verse.text)" dense/>
              <q-btn label="மீளமை" color="grey-8" class="q-ml-md tamil" @click="verse.text = verse.text2; demo()" dense/>
            </div>
          </div>
        </template>

        <template v-slot:after>
          <div class="q-pa-md tamil text-grey-7">
            <rule-list :ruleslist="verse.analysis.verse[verse.value][0]" :explanation="verse.analysis.verse.Explanation[0]" :type="''" :checkactive="false" :highlight="true"></rule-list>
          </div>
        </template>
      </q-splitter>
      <q-separator />
    </span>
  </q-page>
</template>

<script>
import { LinkMixin } from '../mixin/LinkMixin'
import RuleList from '../components/RuleList'

export default {
  // name: 'PageName',
  mixins: [LinkMixin],
  mounted: function () {
    for (let category in this.examples) {
      this.examples[category].forEach(async function (verse) {
        verse['text2'] = verse['text']
      })
    }
    this.demo()
  },
  components: {
    RuleList
  },
  data () {
    return {
      examples: {
        'venpa': [
          {
            value: 'venpaa',
            label: 'ஒரு விகற்ப குறள் வெண்பா',
            text: `முற்ற உணர்ந்தானை ஏத்தி மொழிகுவன்
குற்றமொன்று இல்லா அறம்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'இரு விகற்ப குறள் வெண்பா',
            text: `நற்காட்சி நன்ஞானம் நல்லொழுக்கம் இம்மூன்றும்
தொக்க அறச்சொல் பொருள்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'நேரிசை சிந்தியல் வெண்பா',
            text: `அறிந்தானை ஏத்தி அறிவாங் கறிந்து
செறிந்தார்க்குச் செவ்வன் உரைப்ப - செறிந்தார்
சிறந்தமை ஆராய்ந்து கொண்டு`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'இன்னிசை சிந்தியல் வெண்பா',
            text: `சுரையாழ அம்மி மிதப்ப வரையனைய
யானைக்கு நீத்து முயற்கு நிலையென்ப
கானக நாடன் சுனை`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'ஒரு விகற்ப நேரிசை வெண்பா',
            text: `கூற்றங் குமைத்த குரைகழற்காற் கும்பிட்டுத்
தோற்றந் துடைத்தேந் துடைத்தேமாற் - சீற்றஞ்செய்
யேற்றினான் றில்லை யிடத்தினா னென்னினியாம்
போற்றினா னல்கும் பொருள்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'இரு விகற்ப நேரிசை வெண்பா',
            text: `மாதவா போதி வரதா வருளமலா
பாதமே யோத சுரரைநீ - தீதகல
மாயா நெறியளிப்பா யின்றன் பகலாச்சீர்த்
தாயே யலகில்லா டாம்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'ஒரு விகற்ப இன்னிசை வெண்பா',
            text: `துகடீர் பெருஞ்செல்வம் தோன்றியக்கால் தொட்டுப்
பகடு நடந்தகூழ் பல்லாரோ டுண்க
அகடுற யார்மாட்டும் நில்லாது செல்வம்
சகடக்கால் போல வரும்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'பல விகற்ப இன்னிசை வெண்பா',
            text: `இன்றுகொல் அன்றுகொல் என்றுகொல் என்னாது
பின்றையே நின்றது கூற்றமென் றெண்ணி
ஒருவுமின் தீயவை ஒல்லும் வகையான்
மருவுமின் மாண்டார் அறம்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'பஃறொடை வெண்பா',
            text: `வையக மெல்லாங் கழினியா வையகத்துட்
செய்யகமே நாற்றிசையின் றேயங்கள் செய்யகத்துள்
வான்கரும்பே தொண்டை வளநாடு வான்கரும்பின்
சாறேயந் நாட்டிற் றிலையூர்கள் சாறட்ட
கட்டியே கச்சிப் புறமெல்லாங்க் கட்டியுட்
டானேற்ற மான சருக்கரை மாமணியே
ஆணேற்றான் கச்சி யகம்`,
            analysis: ''
          },
          {
            value: 'venpaa',
            label: 'கலிவெண்பா',
            text: `சுடர்த்தொடீஇ கேளாய் தெருவில்நாம் ஆடும்
மணற்சிற்றில் காலில் சிதையா அடைச்சிய
கோதை பரிந்து வரிப்பந்து கொண்டோடி
நோதக்க செய்யும் சிறுபட்டி மேல்ஓர்நாள்
அன்னையும் யானும் இருந்தேமா இல்லிரே
உண்ணுநீர் வேட்டேன் எனவந்தாற் கன்னை
அடர்பொற் சிரகத்தால் வாக்கிச் சுடரிழாய்
உண்ணுநீர் ஊட்டிவா என்றாள் எனயானும்
தன்னை அறியாது சென்றேன்மற் றென்னை
வளைமுன்கை பற்றி நலியத் தெருமந்திட்(டு)
அன்னாய் இவனொருவன் செய்ததுகாண்’ என்றேனா
அன்னை அலறிப் படர்தரத் தன்னையான்
உண்ணுநீர் விக்கினான் என்றேனா அன்னையும்
தன்னைப் புறம்பழித்து நீவமற் றென்னைக்
கடைக்கணால் கொல்வான்போல் நோக்கி நகைக்கூட்டம்
செய்தானக் கள்வன் மகன்`,
            analysis: ''
          }
        ],
        'aciriyappa': [
          {
            value: 'aciriyappaa',
            label: 'நேரிசை ஆசிரியப்பா ',
            text: `அருள்வீற் றிருந்த திருநிழற் போதி
முழுதுணர் முனிவநிற் பரவுதும் தொழுதக
ஒருமனம் எய்தி இருவினைப் பிணிவிட்டு
முப்பகை கடந்து நால்வகைப் பொருளுணர்ந்
தோங்குநீர் உலகிடை யாவரும்
நீங்கா இன்பமொடு நீடுவாழ் கெனவே`,
            analysis: ''
          },
          {
            value: 'aciriyappaa',
            label: 'இணைக்குறள் ஆசிரியப்பா',
            text: `நீரின் தண்மையும் தீயின் வெம்மையும்
சாரச் சார்ந்து
தீரத் தீரும்
சாரல் நாடன் கேண்மை
சாரச் சாரச் சார்ந்து
தீரத் தீரத் தீர்பொல் லாதே`,
            analysis: ''
          },
          {
            value: 'aciriyappaa',
            label: 'நிலைமண்டில ஆசிரியப்பா',
            text: `புலவன் தீர்த்தன் புண்ணியன் புராணன்
உலக நோன்பி னுயர்ந்தோன் வென்கோ
குற்றங் கெடுத்தோய் செற்றஞ் செறுத்தோய்
முற்ற வுணர்ந்த முதல்வா வென்கோ
காமற் கடந்தோய் ஏம மாயோய்
தீநெறிக் கடும்பகை கடுந்தோ யென்கோ
ஆயிர வாரத் தாழியந் திருந்தடி
நாவா யிரமிலேன் ஏத்துவ தெவனோ
`,
            analysis: ''
          }
        ],
        'kalippa': [
          {
            value: 'kalippaa',
            label: 'தரவுகொச்சகக் கலிப்பா',
            text: `செல்வப்போர்க் கதக்கண்ணன் செயிர்த்தெறிந்த சினவாழி
முல்லைத்தார் மறமன்னர் முடித்தலையை முருக்கிப்போய்
எல்லைநீர் வியன்கொண்மூ இடைநுழையும் மதியம்போல்
மல்லல்ஒங் கெழில்யானை மருமம்பாய்ந் தொளித்ததே
`,
            analysis: ''
          },
          {
            value: 'venkalippaa',
            label: 'வெண்கலிப்பா',
            text: `ஏர்மலர் நறுங்கோதை எருத்தலைப்ப இறைஞ்சித்தன்
வார்மலர்த் தடங்கண்ணார் வலைப்பட்டு வருந்தியவென்
தார்வரை அகன்மார்பன் தனிமையை அறியுங்கொல்
சீர்மலி கொடியிடை சிறந்து
`,
            analysis: ''
          }
        ],
        'vanjippaa': [
          {
            value: 'vanjippaa',
            label: 'குறளடி வஞ்சிப்பா',
            text: `மாகத்தினர் மாண்புவியினர்
யோகத்தினர் உரைமறையினர்
ஞானத்தினர் நயஆகமப்
பேரறிவினர் பெருநூலினர்
காணத்தகு பல்கணத்தினர்
என்றே
இன்னன பல்லோர் ஏத்தும் பெருமான்
மன்னவன் காந்த மலையுறை முருகனே`,
            analysis: ''
          },
          {
            value: 'vanjippaa',
            label: 'சிந்தடி வஞ்சிப்பா',
            text: `கொடிவாலன குருநிறத்தன குறுந்தாளன
வடிவாலெயிற் றழலுளையன வள்ளுகிரன
பணையெருத்தின் இணையரிமான் அணையேறித்
துணையில்லாத் துறவுநெறிக் கிறைவனாகி
எயினடுவண் இனிதிருந் தெல்லோர்க்கும்
பயில்படுவினை பத்தியலாற் செப்பியோன்
புணையெனத்
திருவுறு திருந்தடி திசைதொழ
வெருவுறும் நாற்கதி; வீடுநனி எளிதே`,
            analysis: ''
          }
        ],
        'venpavinam': [
          {
            value: 'kuRa_TTAZicY',
            label: 'குறட்டாழிசை',
            text: `நண்ணு வார்வினை நைய நாடொறும் நற்ற வர்க்கர சாய ஞானநல்
கண்ணி னானடி யேயடை வார்கள் கற்றவரே`,
            analysis: ''
          },
          {
            value: 'kuRa_Lve_Nce_ntuRY',
            label: 'குறள் வெண்செந்துறை',
            text: `போதிநிழற் புனிதன் பொலங்கழல்
ஆதி உலகிற் காண்`,
            analysis: ''
          },
          {
            value: 've_NTAZicY',
            label: 'வெண்டாழிசை',
            text: `நண்பி தென்று தீய சொல்லார்
முன்பு நின்று முனிவு செய்யார்
அன்பு வேண்டு பவர்`,
            analysis: ''
          },
          {
            label: 'வெள்ளொத்தாழிசை',
            value: 've_LLo_ttAZicY',
            text: `ஏரினைப் போற்றுதும்! ஏரினைப் போற்றுதும்!
பாருல கத்தோர் பசிப்பிணிக் கோர்மருந்தாய்
ஆருயி ரோம்புத லான்

கைத்தொழில் போற்றுதும் கைத்தொழில் போற்றுதும்
ஒத்துல கத்தே உயர்வாழ்வுக் கானபொருள்
அத்தனையுந் தான்தருத லான்

வாணிகம் போற்றுதும் வாணிகம் போற்றுதும்
ஏணிபோல் எப்பொருளும் எங்கும்இல் லென்னாமே
ஆணிபோ லேதருத லான்`,
            analysis: ''
          },
          {
            value: 've_NTuRY',
            label: 'வெண்டுறை',
            text: `படர்தருவெவ் வினைத்தொடர்பாற் பவத்தொடர்பப் பவதொடர்பாற் படராநிற்கும்
விடலரும்வெவ் வினைத்தொடர்பவ் வினைத்தொடர்புக் கொழிபுண்டோ வினையேற்கம்மா
விடர்பெரிது முடையேன்மற் றென்செய்கே னென்செய்கே
னடலரவ மரைக்கசைத்த வடிகேளோ வடிகேளோ`,
            analysis: ''
          },
          {
            value: 'veLiviru_tta_m',
            label: 'வெளிவிருத்தம்',
            text: `மருள்அறுத்த பெரும்போதி மாதவரைக் கண்டிலனால்! - என்செய்கோயான்!
அருள்இருந்த திருமொழியால் அறவழக்கங் கேட்டிலனால்! - என்செய்கோயான்!
பொருள்அறியும் அருந்தவத்துப் புரவலரைக் கண்டிலனால்! - என்செய்கோயான்!`,
            analysis: ''
          }
        ],
        'aciriyappavinam': [
          {
            value: '_Aciriya_ttAZicY',
            label: 'ஆசிரியத் தாழிசை',
            text: `வானுற நிமிர்ந்தனை வையகம் அளந்தனை
பான்மதி விடுத்தனை பல்லுயிர் ஓம்பினை
நீனிற வண்ணநின் நிரைகழல் தொழுதனம்`,
            analysis: ''
          },
          {
            value: '_Aciriya_ttAZicY',
            label: 'ஆசிரியத் தாழிசை',
            text: `கன்று குணிலாக் கனியுகுத்த மாயவன்
இன்றுநம் ஆனுள் வருமேல் அவன்வாயில்
கொன்றையம் தீங்குழல் கேளாமோ தோழி

பாம்பு கயிறாக் கடல்கடைந்த மாயவன்
ஈங்குநம் ஆனுள் வருமேல் அவன்வாயில்
ஆம்பலந் தீங்குழல் கேளாமோ தோழி

கொல்லையஞ் சாரல் குருந்தொசித்த மாயவன்
எல்லிநம் ஆனுள் வருமேல் அவன்வாயில்
முல்லையந் தீங்குழல் கேளாமோ தோழி`,
            analysis: ''
          },
          {
            value: '_Aciriya_ttuRY',
            label: 'ஆசிரியத் துறை',
            text: `இரங்கு குயில்முழவா இன்னிசையாழ் தேனா
அரங்கு மணிபொழிலா ஆடும் போலும் இளவேனில்
அரங்கு மணிபொழிலா ஆடு மாயின்
மரங்கொல் மணந்தகன்றார் நெஞ்சமென் செய்த திளவேனில்`,
            analysis: ''
          },
          {
            value: '_Aciriyaviru_tta_m',
            label: 'அறுசீர் கழிநெடிலடி ஆசிரிய விருத்தம்',
            text: `தொழும்அடியர் இதயமலர் ஒருபொழுதும் பிரிவரிய துணைவர் எனலாம்
எழும்இரவி கிரணநிகர் இலகுதுகில் புனைசெய்தருள் இறைவர் இடமாம்
குழுவுமறை யவருமுனி வரருமரி பிரமருர கவனும் எவரும்
தொழுகைய இமையவரும் அறம்மருவு துதிசெய்தெழு துடித புரமே!`,
            analysis: ''
          },
          {
            value: '_Aciriyaviru_tta_m',
            label: 'எழுசீர் கழிநெடிலடி ஆசிரிய விருத்தம்',
            text: `
தோடார் இலங்கு மலர்கோதி வண்டு வரிபாட நீடு துணர்சேர்
வாடாத போதி நெறிநீழல் மேய வரதன் பயந்த அறநூல்
கோடாத சீல விதமேவி வாய்மை குணனாக நாளும் முயல்வார்
வீடாத இன்ப நெறிசேர்வர்! துன்ப வினைசேர்தல் நாளும் இலரே
            `,
            analysis: ''
          },
          {
            value: '_Aciriyaviru_tta_m',
            label: 'எண்சீர் கழிநெடிலடி ஆசிரிய விருத்தம்',
            text: `எண்டிசையும் ஆகி இருள்அகல நூறி எழுதளிர்கள் சோதி முழுதுலகம் நாறி
வண்டிசைகள் பாடி மதுமலர்கள் வேய்ந்து மழைமருவு போதி உழைநிழல்கொள் வாமன்
வெண்டிரையின் மீது விரிகதிர்கள் காண வெறிதழல்கொள் மேனி அறிவனெழில் மேவு
புண்டரிக பாதம் நமசரனம் ஆகும் எனமுனிவர் தீமை புணர்பிறவி காணார்`,
            analysis: ''
          }
        ],
        'kalippavinam': [
          {
            value: 'kali_ttAZicY',
            label: 'கலித்தாழிசை',
            text: `வாள்வரி வேங்கை வழங்கும் சிறுநெறிஎம்
கேள்வரும் போழ்தில் எழால்வாழி வெண்திங்காள்
கேள்வரும் போழ்தில் எழாலாய்க் குறாலியரோ
நீள்வரி நாகத் தெயிறே வாழி வெண்திங்காள்`,
            analysis: ''
          },
          {
            value: 'kali_ttAZicY',
            label: 'கலித்தாழிசை',
            text: `கொய்தினை காத்தும் குளவி அடுக்கத்தெம்
பொய்தல் சிறுகுடி வாரல்நீ ஐய நலம்வேண்டின்

ஆய்தினை காத்தும் அருவி அடுக்கத்தெம்
மாசில் சிறுகுடி வாரல்நீ ஐய நலம்வேண்டின்

மென்தினை காத்தும் மிகுபூங் கமழ்சோலைக்
குன்றச் சிறுகுடி வாரல்நீ ஐய நலம்வேண்டின்`,
            analysis: ''
          },
          {
            value: 'kali_ttuRY',
            label: 'கலித்துறை',
            text: `ஆவி அந்துகில் புனைவதொன் றன்றிவே றறியாள்
தூவி அன்னமென் புனலிடைத் தோய்கிலா மெய்யாள்
தேவு தெண்கடல் அமிழ்துகொண் டனங்கவேள் செய்த
ஓவி யம்புகை யுண்டதே ஒக்கின்ற உருவாள்`,
            analysis: ''
          },
          {
            value: 'ka_TTaLY_kkali_ppA',
            label: 'கட்டளை கலிப்பா',
            text: `அன்னை யெத்தனை யெத்தனை அன்னையோ
அப்ப னெத்தனை யெத்தனை அப்பனோ
பின்னை யெத்தனை யெத்தனை பெண்டிரோ
பிள்ளை யெத்தனை யெத்தனை பிள்ளையோ
முன்னை யெத்தனை யெத்தனை சென்மமோ
மூட மாயடி யேனும றிந்திலேன்
இன்னு மெத்தனை யெத்தனை சென்மமோ
என்செய் வேன்கச்சி யேகம்ப நாதனே`,
            analysis: ''
          },
          {
            value: 'ka_TTaLY_kkali_ttuRY',
            label: 'கட்டளை கலித்துறை',
            text: `தனந்தரும் கல்வி தருமொரு நாளும் தளர்வறியா
மனந்தரும் தெய்வ வடிவும் தரும்நெஞ்சில் வஞ்சமில்லா
இனந்தரும் நல்லன எல்லாம் தருமன்பர் என்பவர்க்கே
கனந்தரும் பூங்குழ லாளபி ராமி கடைக்கண்களே`,
            analysis: ''
          },
          {
            value: 'kaliviru_tta_m',
            label: 'கலிவிருத்தம்',
            text: `பேணநோற் றதுமனைப் பிறவி பெண்மைபோல்
நாணநோற் றுயர்ந்தது நங்கை தோன்றலான்
மாணநோற் றீண்டிவள் இருந்த வாறெலாம்
காணநோற் றிலனவன் கமலக் கண்களால்`,
            analysis: ''
          }
        ],
        'vanjippavinam': [
          {
            value: 'va_Jci_ttAZicY',
            label: 'வஞ்சித்தாழிசை',
            text: `மடப்பிடியை மதவேழம்
தடக்கையான் வெயில்மறைக்கும்
இடைச்சுரம் இறந்தார்க்கே
நடக்குமென் மனனேகாண்

பேடையை இரும்போத்துத்
தோகையான் வெயில்மறைக்கும்
காடகம் இறந்தார்க்கே
ஓடுமென் மனனேகாண்

இரும்பிடியை இகல்வேழம்
பெருங்கையான் வெயில்மறைக்கும்
அருஞ்சுரம் இறந்தார்க்கே
விரும்புமென் மனனேகாண்`,
            analysis: ''
          },
          {
            value: 'va_Jci_ttuRY',
            label: 'வஞ்சித்துறை',
            text: `பொருந்து போதியில்
இருந்த மாதவர்
திருந்து சேவடி
மருந்து ஆகுமே`,
            analysis: ''
          },
          {
            value: 'va_Jciviru_tta_m',
            label: 'வஞ்சி விருத்தம்',
            text: `அணிதங்கு போதி வாமன்
பணிதங்கு பாதம் அல்லால்
துணிபொன் றிலாத தேவர்
மணிதங்கு பாதம் மேவார்`,
            analysis: ''
          }
        ]
      },
      result1: '',
      result2: '',
      view: 'venpa',
      splitterModel: 40
    }
  },
  methods: {
    explore: function (text) {
      let routeData = this.$router.resolve({ name: 'analyzer', query: { text: text } })
      window.open(routeData.href, '_blank')
    },
    demoSpecific: async function (category, sub) {
      var text = this.examples[category][sub].text
      let result = await this.convertAsync(text)
      this.examples[category][sub].analysis = await this.getJson(result)
      this.$set(this, 'examples', this.examples)
    },
    demo: async function () {
      var dhis = this
      for (let category in this.examples) {
        this.examples[category].forEach(async function (verse) {
          let text = verse.text
          let result = await dhis.convertAsync(text)
          verse.analysis = await dhis.getJson(result)
        })
      }
    }
  }
}
</script>

<style scoped>
</style>
