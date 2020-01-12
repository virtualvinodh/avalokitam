<template>
  <q-page padding>
  <div class="text-h5 q-mb-md tamil">அட, நீங்களும் பா எழுதலாங்க...</div>
       <div class="q-mb-md tamil">மரபுப்படி பாட்டெழுத தேவையான குறஞ்சபட்ச விஷயங்கள  ஒன்னொன்னுத்தையும் கீழ நீங்க கத்துக்கலாம். முன்னாடி பின்னாடின்னு உங்க வசதிக்கு ஏத்த மாதிரி எப்பிடி வேணுன்னாலும் போகலாம்.</div>

  <q-separator class="q-ma-md"/>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="4"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressEluttu"
      size="80px"
      :thickness="0.2"
      :track-color="currentSelection === 'eluttu' ? 'blue-2' : 'grey-3'"
      :color="progressEluttu < 4 ? 'grey-10' : 'green-9'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'eluttu' ? 'grey-10' : 'grey-8'" text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'eluttu'"
  > எழுத்து </q-avatar>
</q-knob>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="4"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressAcai"
      size="80px"
      :thickness="0.2"
      :track-color="currentSelection === 'acai' ? 'blue-2' : 'grey-3'"
      :color="progressAcai < 4 ? 'grey-10' : 'green-9'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'acai' ? 'grey-10' : 'grey-8'" text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'acai'"> அசை </q-avatar>
</q-knob>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="5"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressCir"
      size="80px"
      :thickness="0.2"
      :color="progressCir < 5 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'cir' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'cir' ? 'grey-10' : 'grey-7'" text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'cir'"> சீர் </q-avatar>
</q-knob>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="4"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressAti"
      size="80px"
      :thickness="0.2"
      :color="progressAti < 4 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'ati' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'ati' ? 'grey-10' : 'grey-7'" text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'ati'"> அடி </q-avatar>
</q-knob>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="7"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressTalai"
      size="80px"
      :thickness="0.2"
      :color="progressTalai < 7 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'talai' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'talai' ? 'grey-10' : 'grey-7'"  text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'talai'"> தளை </q-avatar>
</q-knob>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="11"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressTotai"
      size="80px"
      :thickness="0.2"
      :color="progressTotai < 11 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'todai' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'todai' ? 'grey-10' : 'grey-7'"  text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'todai'"> தொடை </q-avatar>
</q-knob>

<q-knob
      show-value
      readonly
      :min="1"
      :max="4"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressPa"
      size="80px"
      :thickness="0.2"
      :color="progressPa < 4 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'pa' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="60px" font-size="15px" :color="currentSelection === 'pa' ? 'grey-10' : 'grey-7'"  text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'pa'"> பா </q-avatar>
</q-knob>

<q-knob
      show-value
      readonly
      :min="1"
      :max="6"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressVenpa"
      size="90px"
      :thickness="0.2"
      :color="progressVenpa < 6 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'venpa' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="70px" font-size="14px" :color="currentSelection === 'venpa' ? 'grey-10' : 'grey-7'"   text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'venpa'"> வெண்பா </q-avatar>
</q-knob>

    <q-knob
      show-value
      readonly
      :min="1"
      :max="4"
      font-size="8px"
      class="q-ma-sm"
      v-model="progressPavinam"
      size="90px"
      :thickness="0.2"
      :color="progressPavinam < 4 ? 'grey-10' : 'green-9'"
      :track-color="currentSelection === 'pavinam' ? 'blue-2' : 'grey-3'"
    >
  <q-avatar size="70px" font-size="14px" :color="currentSelection === 'pavinam' ? 'grey-10' : 'grey-7'"  text-color="white" class="tamil q-ma-md cursor-pointer" @click="currentSelection = 'pavinam'"> பாவினம் </q-avatar>
</q-knob>

  <q-separator class="q-ma-md"/>

  <div class="q-pa-md">
    <q-stepper
      v-model="progressEluttu"
      ref="stepperEluttu"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'eluttu'"
    >
      <q-step
        :name="1"
        title="எழுத்து 1"
        icon="how_to_reg"
        :done="progressEluttu > 1"
      >
      <div class="q-mb-md"><b>எழுத்து</b>, இது தாங்க எல்லாத்துக்கும் அடிப்பட. எழுத்தில்லாம எதாச்சயும் நாம எழுதமுடியுமோ?</div>

       <div class="q-mb-md">செய்யுள்ள பொருத்த வரைக்கும். எழுத்துங்கள மூணு வகையா பிரிக்கலாம். <b>குறில்</b>, <b>நெடில்</b> அப்புறம் <b>மெய்</b>.</div>

       <div class="q-mb-md"><b>குறில்</b>’ங்கிறது <i>அ, இ, உ , எ, ஒ’</i>வும் இதுங்க கூட உயிர்மெய்யெழுத்துங்களும் <i>க, கி, கு, கெ, கொ</i>. இதுங்கள உச்சரிக்க ஒரு மாத்திர அளவு நேரம் போதும் (குத்துமதிப்பா ஒரு சொடக்கு இல்ல ஒருதடவ கண்ணிமைக்கிற நேரம்)</div>

      <div class="q-mb-md"><b>நெடில்</b>’ங்கிறது <i>ஆ, ஈ, ஊ, ஏ, ஓ</i>’வும் இதுங்க கூட உயிர்மெய்யெழுத்துங்களும் <i>கா, கீ, கூ, கே, கோ</i>. இதுங்கள உச்சரிக்க ரெண்டு மாத்திர அளவுக்கு நேரம் வேணும்.</div>

     <div class="q-mb-md"><b>மெய்</b>’ங்கிறது <i>க், ங், ச்</i>’ன்னு புள்ளி வச்ச எல்லா எழுத்தும். இதுங்கள உச்சரிக்க அர மாத்திர அளவு போதும்.</div>
      </q-step>

      <q-step
        :name="2"
        title="எழுத்து 2"
        icon="how_to_reg"
        :done="progressEluttu > 2"
      >
      <div class="q-mb-md">ஒன்னு நீங்க கவனிச்சிருக்கலாம். ஐ’யையும் ஔ’வையும் நாம பார்க்கவே இல்ல. இதுக்குக் காரணம் இருக்கு. கீழ இருக்குற வார்த்தய உச்சரிச்சு பாருங்க.</div>

       <div class="q-mb-md"><i>கையே, கலையோ, வினை </i></div>

       <div class="q-mb-md">இதுல எல்லாத்துலயும், ஐ’ங்கிறது முழுசா நாம உச்சரிக்கிறது இல்ல. இதுக்குப்பேரு தான் <b>ஐகாரக்குறுக்கம்</b>. ஔ’வும் இதே மாதிரிதான். அதுக்குப்பேர் <b>ஔகாரக்குறுக்கம்</b>.  </div>

       <div class="q-mb-md">இலக்கணப்படி நெடிலா இருந்தாலும், உச்சரிக்கும்போது கொறச்சு வர்ரதால, பாட்டெழுதும் போது ஐ’யும் ஔ’யும் குறிலாத்தான் எடுத்தக்கணும்.</div>

      </q-step>

      <q-step
        :name="3"
        title="எழுத்து 3"
        icon="how_to_reg"
        :done="progressEluttu > 3"
      >
      <div class="q-mb-md">நீங்க இதுவரைக்கும் எல்லாத்தயும் ஒழுங்கா புரிஞ்சிக்கிட்டீஞ்களாங்கிறத பார்ப்போம். கீழ வர்ர எழுத்துங்கள குறில், நெடில், மெய்’னு வகப்படுத்தனும்.</div>

      <ol>
        <span v-for="i in 10" :key="'test1' + i">
       <li class="q-ma-sm"><span class="q-mb-md q-ml-sm q-mr-md" >{{letters[i - 1][0]}}</span>
        <q-btn-toggle
          v-model="modelletters[i-1]"
          toggle-color="grey-10"
          dense
          :options="[
            {label: 'குறில்', value: 0},
            {label: 'நெடில்', value: 1},
            {label: 'மெய்', value: 2},
          ]"
        /> <span class="q-ml-md" v-if="resultLetter"><q-icon name="close" color="red" size="20px" v-if="modelletters[i-1] != letters[i - 1][1]"/> <q-icon name="done" color="green" size="20px" v-if="modelletters[i-1] == letters[i - 1][1]"/></span>
        </li>
      </span>
      </ol>
      <div class="q-mt-lg q-ml-lg">
      <q-btn label="சரியா இருக்கா?" @click="resultLetter = true" class="q-ma-xs"/>
      <q-btn label="இன்னொரு தடவ" @click="doRandomLetters" class="q-ma-xs" /></div>

      </q-step>

      <q-step
        :name="4"
        title="எழுத்து 4"
        icon="how_to_reg"
        :done="progressEluttu > 4"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க எழுத்த பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressEluttu == 1" color="grey-8" @click="$refs.stepperEluttu.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperEluttu.next()" color="grey-8" :label="progressEluttu === 4 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>
    <q-stepper
      v-model="progressAcai"
      ref="stepperAcai"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'acai'"
    >
      <q-step
        :name="1"
        title="அசை 1"
        icon="how_to_reg"
        :done="progressAcai > 1"
      >
      <div class="q-mb-md">ஒரே ஒரு எழுத்த வச்சு நாம பட்டெழுத முடியுமோ? முடியாதில்லயா? பாட்டெழுத நெற எழுத்துங்கள சேத்து எழுதனும். எழுத்துங்க சேர்ந்து வந்தா அதுக்குப்பேரு அசை.</div>

      <div class="q-mb-md"><b>அசை</b>'ங்கறது, குத்துமதிப்பா சொல்லனும்னா, நம்ம வாயசைக்கிறது. எல்லாத்துக்கும் இது ரொம்ப முக்கியம். வாயசைப்பு இல்லாம பா பாட முடியாது இல்லயா!</div>

       <div class="q-mb-md"><b>நேர்</b> இல்ல <b>நிரை</b>'ன்னு சொல்லிப்பாருங்க வாய் <u>ஒரு தடவ</u> மட்டும் தான் அசையும். <b>தேமா</b> இல்ல <b>புளிமா</b>'ன்னு சொல்லிப்பாருங்க, வாய் <u>ரெண்டு தடவ</u> அசையும். <b>தேமாங்கனி'</b>ன்னு சொன்னீங்கன்னா வாய் <u>மூணு தடவ</u> அசையும். <b>கருவிளநறுநிழல்</b>'னு சொன்னீங்கன்னா வாய் <u>நாலு வாட்டி</u> அசையும். இவ்வளவு தாங்க அசை. </div>
      </q-step>

      <q-step
        :name="2"
        title="அசை 2"
        icon="how_to_reg"
        :done="progressAcai > 2"
      >
      <div class="q-mb-md">இப்ப பாத்தீங்கன்னா, ரெண்டு விதமான வாயசைப்பு இருக்குங்க. ஒன்னு, ‘நேர்’ அசை இன்னொன்னு ‘நிரை’ அசை. நீங்க நேர் நிரை’ன்னு சொல்லிப்பார்த்தலே, உங்க வாய் ரெண்டு வேற விதமா அசையறத பார்க்கலாம். </div>

       <div class="q-mb-md">குறில் எழுத்து இல்ல நெடில் எழுத்து தனியா வந்தாலோ இல்ல ஒரு மெய்யெழுத்தோட கூடி வந்தாலோ அது நேர் அசைங்க.</div>

       <div class="q-mb-md">உதாரணம்: க, கா, கல், கால்</div>

       <div class="q-mb-md">ரெண்டு குறில் எழுத்தோ இல்லன்னா குறிலுக்கு அப்புறம் ஒரு நெடில் வந்தோலோ இல்ல இதுங்க கூட ஒரு மெய்யெழுத்தும் சேந்து வந்தாலோ அது நிரை அசைங்க.  </div>

       <div class="q-mb-md">உதாரணம்: கட, கடம், கடா, கடாம்</div>

      </q-step>

      <q-step
        :name="3"
        title="அசை 3"
        icon="how_to_reg"
        :done="progressAcai > 3"
      >
      <div class="q-mb-md">நீங்க சரியா கத்துக்கிட்டீங்களா’ன்னு பார்க்க ஒரு சின்ன பரீட்சைப்பார்ப்போம். </div>
      <div class="q-mb-md">கீழ இருக்கற நாலு அசைல எது நேர், எது நிரைன்னு சொல்லனும். அவ்வளவு தான்.</div>
      <div class="q-mb-md">முக்கியமான விஷயம், யாப்பு’ல ஐ’யும் ஔ’வும் குறில்’னு எடுத்துக்கனும்.</div>

       <ol>
        <span v-for="i in 5" :key="'test1' + i">
       <li class="q-ma-sm"><span class="q-mb-md q-ml-sm q-mr-md" >{{acais[i - 1][0]}}</span>
        <q-btn-toggle
          v-model="modelAcai[i-1]"
          toggle-color="grey-10"
          dense
          :options="[
            {label: 'நேர்', value: 'ner'},
            {label: 'நிரை', value: 'nirai'},
          ]"
        /> <span class="q-ml-md" v-if="resultAcai"><q-icon name="close" color="red" size="20px" v-if="modelAcai[i-1] != acais[i - 1][1]"/> <q-icon name="done" color="green" size="20px" v-if="modelAcai[i-1] == acais[i - 1][1]"/></span>
        </li>
      </span>
      </ol>
      <div class="q-mt-lg q-ml-lg">
      <q-btn label="சரியா இருக்கா?" @click="resultAcai = true" class="q-ma-xs"/>
      <q-btn label="இன்னொரு தடவ" @click="doRandomAcai"  class="q-ma-xs"/></div>
      </q-step>

      <q-step
        :name="4"
        title="அசை 4"
        icon="how_to_reg"
        :done="progressAcai > 4"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க சீர பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressAcai == 1" color="grey-8" @click="$refs.stepperAcai.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperAcai.next()" color="grey-8" :label="progressAcai === 4 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>

        <q-stepper
      v-model="progressCir"
      ref="stepperCir"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'cir'"
    >
      <q-step
        :name="1"
        icon="how_to_reg"
        title="சீர் 1"
        :done = "progressCir > 1"
      >
        <div class="q-mb-md">ஒரே ஒரு அசைய வச்சு பாட்டெழுத முடியுமோ? முடியாதில்லயா. அடுத்து, நாம பாக்க வேண்டியது சீர். நெறய அசைங்க சேந்து வந்தா அது சீர். (யாப்புல வார்த்தை’ன்னு சொல்லாம சீர்’னு சொல்லனும்.) அசை’ங்கள வித விதமான விதத்துல ‘சீரா’ அமைக்கிறதால சீர். ஒரு சீர்’ல ரெண்டு அசை’யோ மூணு அசை’யோ இல்ல அதிகபட்சம் நாலு அசை’யோ சேர்ந்து வரலாம்.</div>

        <div class="q-mb-md">இப்ப கீழ இருக்குற ரெண்டு தனித்தனி அடில இருக்குற வார்த்தைங்கள வாய்விட்டு சொல்லிப்பாருங்க.</div>

        <div class="q-mb-md"><b>அவலோகிதம், மதனாயுதம், குடியானவன், தரக்காரணி, பழிபோடுதல்</b></div>

        <div class="q-mb-md"><b>அமுதுததி, ஜனனபூமி, கடினமான, பணியறவு, அகவரிசை</b></div>
      </q-step>

      <q-step
        :name="2"
        icon="how_to_reg"
        title="சீர் 2"
        :done = "progressCir > 2"
      >
        <div class="q-mb-md">அவலோகிதம்’னு ஆரம்பிக்கிற அடில இருக்குற வார்த்தைங்க எல்லாம் ஒரு மாதிரியும், அமுதுததி’ன்னு ஆரம்பிக்கிற வார்த்தை எல்லாம் இன்னொரு மாதிரியும் இருக்குறது தெரிஞ்சிருக்கலாம். இத கண்டுப்பிடிக்கிறத்துக்குத்தான் வாய்ப்பாடு.</div>

        <div class="q-mb-md">ஏற்கனவே, நேர் நிரை’ன்னு பார்த்தோம் இல்லை. நேர் அசையும் நிரை அசையும் நெறய விதத்துல சேத்துவச்சு அசைங்கள உருவாக்கலாம். எந்த விதத்துல நேர்’ம் நிரை’யும் சேருதோ அந்த குறிப்பிட்ட சேர்க்கைக்குன்னு ஒரு பேரு இருக்குங்க. அதுக்கு அர்த்தம் என்னன்னா, அந்த வாய்ப்பாட்டோட பேர்ல வர்ர எல்லா சீர்’ம் குத்துமதிப்பா ஒரே மாதிரி வாயசைப்ப வச்சிருக்கும்’னு அர்த்தம்.</div>

        <div class="q-mb-md"><b>நிரை/நேர்/நிரை : அவ/லோ/கிதம், மத/னாயு/தம், குடி/யா/னவன், தரக்/கா/ரணி, பழி/போ/டுதல்</b></div>
        <div class="q-mb-md">இந்த வார்த்தைங்க எல்லாமே நிரை/நேர்/நிரை’னு மூணு அசையால வந்திருக்கு. இப்படி வர்ர சேர்க்கைக்கு <b>புளிமாங்கனி</b>’ன்னு பேர்.</div>

        <div class="q-mb-md"><b>நிரை/நிரை/நேர் : அமு/துத/தி, ஜன/னபூ/மி, கடி/னமா/ன, பணி/யற/வு, அக/வரி/சை</b></div>
        <div class="q-mb-md">இந்த வார்த்தைங்க எல்லாமே நிரை/நிரை/நேர்’னு மூணு அசையால வந்திருக்கு. இப்படி வர்ர சேர்க்கைக்கு <b>கருவிளங்காய்</b>’ன்னு பேர்.</div>

        <div class="q-mb-md"> <router-link to="reference?view=acai" target="_blank">இங்க</router-link> போய் நீங்க மத்த வாய்ப்பாடுங்கள பத்தி பார்த்துக்கலாம். </div>

      </q-step>

      <q-step
        :name="3"
        icon="how_to_reg"
        title="சீர் 3"
        :done = "progressCir > 3"
      >
        <div class="q-mb-md">பா எழுதறதுல முக்கியமா ரெண்டு வேல இருக்குங்க. ஒன்னு குறிப்பிட்ட வாய்ப்பாடு வர்ர மாதிரி சீர எழுதனும். இன்னொன்னு, ஒரு வேள தப்பான வாய்ப்பாடு வர்ர மாதிரி எழுதிட்டா அந்த வார்த்தைல இருக்கற இருக்குற எழுத்த கூட்டியோ குறைச்சோ இப்பிடி அப்படின்னு டிங்கரிங் பார்த்து சரியான வாய்ப்பாடு வர்ர மாதிரி மாத்தனும். மொதல் விஷயத்த இங்க பார்க்கலாம்.</div>

        <div class="q-mb-md">கீழ கேட்டிருக்குற மாதிரி வர்ர வாய்ப்பாட்டுல ஒரு நாலு சீர எழுதிப்பாருங்க. </div>

        <div class="q-mb-md">கவலைப்படாதீங்க, நீங்க எழுத எழுத கீழ அசபிரிச்சி காமிக்கும், அத வச்சு, கேக்கற சேர்க்கை வர்ர மாதிரி எழுத்த கூட்டவோ குறைக்கவோ செய்யுங்க. உதாரணம், ஒன்னும் காமிச்சிருக்கு.</div>

        <div class="q-mb-md"><q-input v-model="practiceWord[0]" @input="demo(0)" label="நேர்/நிரை/நிரை : கூவிளங்கனி"/>
          <span v-if="analysisPractice[0] != ''" :class="classanalysisPractice[0] != 'கூவிளங்கனி' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[0]}} : {{metremeanalysisPractice[0]}} : {{classanalysisPractice[0]}}</span></div>

        <div class="q-mb-md"><q-input v-model="practiceWord[1]" @input="demo(1)" label="நேர்/நேர் : தேமா"/>
          <span v-if="analysisPractice[1] != ''"  :class="classanalysisPractice[1] != 'தேமா' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[1]}} : {{metremeanalysisPractice[1]}} : {{classanalysisPractice[1]}}</span></div>

        <div class="q-mb-md"><q-input v-model="practiceWord[2]" @input="demo(2)" label="நிரை/நிரை : கருவிளம்"/>
          <span v-if="analysisPractice[2] != ''" :class="classanalysisPractice[2] != 'கருவிளம்' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[2]}} : {{metremeanalysisPractice[2]}} : {{classanalysisPractice[2]}}</span>
        </div>
        <div class="q-mb-md"><q-input v-model="practiceWord[3]" @input="demo(3)" label="நிரை/நேர்/நேர் : புளிமாங்காய்"/>
          <span v-if="analysisPractice[3] != ''" :class="classanalysisPractice[3] != 'புளிமாங்காய்' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[3]}} : {{metremeanalysisPractice[3]}} : {{classanalysisPractice[3]}}</span>
        </div>
        <div class="q-mb-md"><q-input v-model="practiceWord[4]" @input="demo(4)" label="நேர்/நிரை/நேர் : கூவிளங்காய்"/>
          <span v-if="analysisPractice[4] != ''" :class="classanalysisPractice[4] != 'கூவிளங்காய்' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[4]}} : {{metremeanalysisPractice[4]}} : {{classanalysisPractice[4]}}</span>
        </div>

        <q-btn label="உதாரணத்த பார்க்க" @click="showExamplesClass(0)" />
        </q-step>
      <q-step
        :name="4"
        icon="how_to_reg"
        title="சீர் 4"
        :done = "progressCir > 4"
      >
        <div class="q-mb-md">கீழ இருக்குற சீர்’ங்கள இருக்குற எழுத்த கூட்டியோ குறச்சோ கேட்டிருக்குற வாய்ப்பாடு வர்ர மாதிரி பண்ணுங்க.</div>

        <div class="q-mb-md"><q-input v-model="practiceWord[5]" @input="demo(5)" label="நேர்/நேர்/நேர் : தேமாங்காய்"/>
          <span v-if="analysisPractice[5] != ''"  :class="classanalysisPractice[5] != 'தேமாங்காய்' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[5]}} : {{metremeanalysisPractice[5]}} : {{classanalysisPractice[5]}}</span></div>

        <div class="q-mb-md"><q-input v-model="practiceWord[6]" @input="demo(6)" label="நேர்/நிரை : கூவிளம்"/>
          <span v-if="analysisPractice[6] != ''" :class="classanalysisPractice[6] != 'கூவிளம்' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[6]}} : {{metremeanalysisPractice[6]}} : {{classanalysisPractice[6]}}</span>
        </div>
        <div class="q-mb-md"><q-input v-model="practiceWord[7]" @input="demo(7)" label="நிரை/நேர் : புளிமா"/>
          <span v-if="analysisPractice[7] != ''" :class="classanalysisPractice[7] != 'புளிமா' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[7]}} : {{metremeanalysisPractice[7]}} : {{classanalysisPractice[7]}}</span>
        </div>
        <div class="q-mb-md"><q-input v-model="practiceWord[8]" @input="demo(8)" label="நிரை/நிரை/நேர் : கருவிளங்காய்"/>
          <span v-if="analysisPractice[8] != ''" :class="classanalysisPractice[8] != 'கருவிளங்காய்' ? 'text-red-8' : 'text-green-8'">{{syllanalysisPractice[8]}} : {{metremeanalysisPractice[8]}} : {{classanalysisPractice[8]}}</span>
        </div>

        <q-btn label="உதாரணத்த பார்க்க" @click="showExamplesClass(1)" />

      </q-step>

      <q-step
        :name="5"
        title="சீர் 5"
        icon="how_to_reg"
        :done="progressAcai > 4"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க சீர பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressCir == 1" color="grey-8" @click="$refs.stepperCir.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperCir.next()" color="grey-8" :label="progressCir === 5 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>

    </q-stepper>

    <q-stepper
      v-model="progressAti"
      ref="stepperAti"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'ati'"
    >

      <q-step
        :name="1"
        icon="how_to_reg"
        title="அடி 1"
        :done = "progressAti > 1"
      >
        <div class="q-mb-md">ஒரே சீர வச்சு பாட்டெழுத முடியுமோ? சீர்’ங்க சேர்ந்து வந்தா அது தான் அடி.</div>
        <div class="q-mb-md">ஒரு அடில எத்தன சீர் வருதோ, அந்த அடிப்படைல அந்த அடிக்குன்னு ஒரு பேரு இருக்குங்க.  </div>
        <div class="q-mb-md">கீழ இருக்குற குறள் வெண்பாவ பாருங்க.  </div>
        <div class="q-mb-md"><b>ஊடிப் பெறுகுவம் கொல்லோ நுதல்வெயர்ப்பக்<br/>
                                    கூடலில் தோன்றிய உப்பு</b>
          </div>
        <div class="q-mb-md">இதுல ரெண்டு அடி இருக்கு. முதல் அடில 4 சீர்’ம் ரெண்டாவது அடி'ல 3 சீர்’ம் வருது. </div>

      </q-step>

      <q-step
        :name="2"
        icon="how_to_reg"
        title="அடி 2"
        :done = "progressAti > 2"
      >
        <div class="q-mb-md">நாலு சீர் வர்ர அடிக்கு அளவடின்னு பேரு. மூணு சீர் வர்ர அடிக்கு சிந்தடின்னு பேரு. அஞ்சு சீர் வந்தா நெடிலடி.</div>

        <div class="q-mb-md">நெறய பா வகைகள்ள இப்படிப்பட்ட அடிங்க தான் வரணும் இப்படிப்பட்ட அடிங்க வரக்கூடாதுன்னு விதி இருக்கு. அதுக்கு, அடிங்களோட் பேரு என்ன, அதுங்கள்ள எத்தன சீர் வரணும்னு தெரிஞ்சிக்கிறது அவசியம்</div>

        <div class="q-mb-md"><router-link to="reference?view=adi" target="_blank">இங்க</router-link> போய் நீங்க மத்த அடிங்கள பத்தி பார்த்துக்கலாம். </div>

      </q-step>

      <q-step
        :name="3"
        icon="how_to_reg"
        title="அடி 3"
        :done = "progressAti > 3"
      >
        <div class="q-mb-md">கீழ இருக்குற அடிங்களோட பேரு என்னென்னு சொல்லுங்க பார்ப்போம்.</div>
        <ol>
        <span v-for="(question, index) in feetExam" :key="'questionfeet' + index">
          <li>{{question.text}} <br/>
          <q-btn-toggle
          v-model="modelFeet[index-1]"
          toggle-color="grey-10"
          dense
          class="q-mb-sm"
          :options="question.options"
        />
        <span class="q-ml-md" v-if="feetResult"><q-icon name="close" color="red" size="20px" v-if="modelFeet[index-1] != question.answer"/> <q-icon name="done" color="green" size="20px" v-if="modelFeet[index-1] == question.answer"/></span>
        </li>
      </span>
      </ol>
      <div class="q-mt-md q-ml-lg">
      <q-btn label="சரியா இருக்கா?" @click="feetResult = true" class="q-ma-xs"/>
    </div>
      </q-step>

      <q-step
        :name="4"
        icon="how_to_reg"
        title="அடி 4"
        :done = "progressAti > 4"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க அடிய பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressAti == 1" color="grey-8" @click="$refs.stepperAti.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperAti.next()" color="grey-8" :label="progressAti === 4 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>

    <q-stepper
      v-model="progressTalai"
      ref="stepperTalai"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'talai'"
    >

      <q-step
        :name="1"
        icon="how_to_reg"
        title="தளை 1"
        :done = "progressTalai > 1"
      >
        <div class="q-mb-md">சீர்’ங்க சேர்து வந்தா அடின்னு பார்த்தோம். இதுல ஒரு முக்கியமான விஷயத்த்த நாம இன்னும் பார்க்கல. அதாவது, <b>தள</b>. ரெண்டு சீர்’ங்க சேர்ந்து வரும்போது, எந்த வகைல அதுங்கள கோர்க்குறோங்கறது ரொம்ப முக்கியம். இதுக்குப்பேரு தான் தள. சீர்'ங்கள பல விதமாக கோர்க்கலாம். </div>

        <div class="q-mb-md">நீங்க ஒரு நூல்ல கலர் கலரா மணிய கோக்குறீங்கன்னு வச்சுகோங்க, எந்த மணிய கோக்குறீங்கங்கற தவிர்த்து எந்த வரிசைல கோர்க்குறீங்கன்னு முக்கியம் இல்லையா? அந்த வரிசைய சரி பார்க்குறதுக்குத்தான் தள. எந்த வார்த்தைங்கள உபயோகிக்கறோங்கறது முக்கியம், ஆனா அதோட சேர்த்து எந்த வரிசைல அந்த பிரயோகிக்கணுங்கிறதும் ரொம்ப முக்கியம். </div>

        <div class="q-mb-md">கீழ இருக்குற சீர்’ங்கள சொல்லிப்பாருங்க.</div>

        <div class="q-mb-md"><b>முழுதுணர் முனிவநிற்</b></div>
        <hr/>
        <div class="q-mb-md"><b>அருள்வீற் றிருந்த</b></div>

      </q-step>

      <q-step
        :name="2"
        icon="how_to_reg"
        title="தளை 2"
        :done = "progressTalai > 2"
      >
        <div class="q-mb-md">சீர்’ங்க நடவுல இருக்குற கோர்வ, முதல் அடில ஒரு மாதிர்யும் ரெண்டாவது அடில ஒரு மாதிரியும் வர்ரது தெரியலாம். இன்னும் சொல்லப்போனா தெரிஞ்சிருக்கனும். ஏன்னா முதல் அடில இருக்குற சீர்’ங்களுக்கு நடுவுல ஆசிரியத்தளயும். ரெண்டாவது அடியில வெண்டளயும் வந்திருக்கு.</div>

        <div class="q-mb-md"><b>முழுதுணர்</b> ⟵ <small class="text-grey-8">ஆசிரியத்தளை</small> ⟶ <b>முனிவநிற்</b></div>
        <div class="q-mb-md"><b>அருள்வீற்</b> ⟵ <small  class="text-grey-8">வெண்டளை</small> ⟶ <b>றிருந்த</b></div>

      </q-step>

      <q-step
        :name="3"
        icon="how_to_reg"
        title="தளை 3"
        :done = "progressTalai > 3"
      >

        <div class="q-mb-md">அது சரி. இந்த தளய எப்பிடி கணக்கு பன்றது. அது ரொம்பவும் சுலபங்க. ரெண்டு சீர்’ங்க அடுத்தடுத்து வரும்போது, மொதல் சீரோட கடைசி அசையையும் அடுத்த வர்ர சீரோட மொதல் அசையையும் கணக்குல எடுத்துக்கணும். ஏனா, எணயறது அதுங்க ரெண்டும் தானே?</div>

        <div class="q-mb-md">அதாவது, முதல் சீரோட வாய்பாடோட கடைசிக்கு காரணமான சீரையும், அடுத்த சீரோட மொதல் அசை நேரா இல்ல நிரையாங்கறது வச்சு அது எப்பிடிப்பட்ட தளைன்னு பார்க்கனும்.</div>

        <div class="q-mb-md">உதாரணமா, மா’க்கு அப்புறமா நிரை வந்துச்சுன்னா அதுக்கு பேரு வெண்டளை. இங்க மா’ன்னு சொல்றது தேமா, புளிமாங்கிற ரெண்டு வாய்ப்பாட்டையும் சேர்த்து. அதே நேரத்துல மா அப்புறமா நிரை வந்திடுச்சுன்னா அதுக்குப்பேரு ஆசிரியத்தள.</div>

        <div class="q-mb-md">
        <b>அருள்/வீற் ⟶ றிருந்/த</b> <br/>
        <i>(புளி)மா  ⟶ நிரை/நேர்</i><br/> <br/>

        இங்க பார்த்தீங்கன்னா . மா’க்கு அப்புறம் நிரை வந்ததால இந்த எணப்ப்புக்கு பேரு <b>வெண்டளை</b>. </div>

        <div class="q-mb-md">அதே மாதிரியே, <b>தொள்வீற் றிருந்த</b>: இங்க பார்த்தீங்கன்னா, இங்கயும் கூட தேமாக்கு அப்புறம் நிரை வந்ததால இதுவும் <b>வெண்டளை</b> தான். </div>

      </q-step>

      <q-step
        :name="4"
        icon="how_to_reg"
        title="தளை 4"
        :done = "progressTalai > 4"
      >
        <div class="q-mb-md">அடுத்து, <br/><br/>
        <b>முழு/துணர் ⟶ முனி/வநிற்</b> <br/>
        <i>(கரு)விளம் ⟶ நிரை/நிரை</i><br/><br/>

          இங்க விளம்'க்கு அப்புறமா நிரை வந்ததால <b>ஆசிரியத்தளை</b>.</div>

        <div class="q-mb-md">இந்த மாதிரி, இது மாதிரியான கணக்க வச்சு ஏழு வக தளைங்க இருக்கு. <router-link to="reference?view=talai" target="_blank">இங்க</router-link> போயி அத நீங்க தெரிஞ்சிக்கலாம்.</div>

      </q-step>

      <q-step
        :name="5"
        icon="how_to_reg"
        title="தளை 5"
        :done = "progressTalai > 5"
      >
        <div class="q-mb-md">முக்கியமான விஷயம்: தளைய பார்க்கும் போது அடிக்குள்ள மட்டும் இல்லாம, அடிங்களுக்கு நடுவுலயும் பார்க்கணும். அதாவது, </div>

        <div class="q-mb-md">முற்ற உணர்ந்தானை ஏத்தி மொழிகுவன் <br/> குற்றமொன்று இல்லா அறம்</div>

        <div class="q-mb-md">இங்க <i>மொழிகுவன் ⟶ குற்றமொன்று</i> நடுவுலயும் தளைய கணக்கு பண்ணனும்.</div>

      </q-step>

      <q-step
        :name="6"
        icon="how_to_reg"
        title="தளை 6"
        :done = "progressTalai > 6"
      >
        <div class="q-mb-md">கீழ இருக்குற பாட்டுல பார்த்தீங்கன்னா வெண்டளை தவிர்த்த வேற தளைங்க நெறய வந்திருக்கு. அத நீங்க சரி பண்ணி எல்லாம வெண்டளையா வர்ர மாதிரி பண்ணுங்க பார்ப்போம்! </div>

        <q-input v-model="versetalai1" type="textarea" rows="2" @input="getAnalysis"></q-input>
        <br/>
        <div v-for="index in (flattenedVerse1.length - 1) " :key="'talai'+index">
          <b>{{flattenedVerse1[index - 1]}} ⟶ {{flattenedVerse1[index]}} </b><br/>
          <i>{{flattenedVerseMF[index - 1][1]}} ⟶ {{flattenedVerseMF[index][0].map( x => x[1]).join("/")}}</i><br/>
          <span :class="flattenedVerseLink[index][2].includes('வெண்டளை') ? 'text-green' : 'text-red'">
            {{flattenedVerseLink[index][2]}}
          </span><br/><br/>
        </div>
        <q-btn @click="versetalai1 = 'சோதி சுபோதிநிழற் புத்தன் பொலங்கழல்\nஆதி உலகிற் தொடு'; getAnalysis()" >பதில பார்க்க</q-btn>
        <q-btn @click="versetalai1 = 'ஆதி போதிநிழற் புனிதன் பொலங்கழல்\nஆதி உலகிற் காண்'; getAnalysis()" >மூலச்செய்யுள்ள பார்க்க</q-btn>
      </q-step>

      <q-step
        :name="7"
        icon="how_to_reg"
        title="தளை 7"
        :done = "progressTalai > 7"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க தளைய பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressTalai == 1" color="grey-8" @click="$refs.stepperTalai.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperTalai.next()" color="grey-8" :label="progressTalai === 7 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>

<q-stepper
      v-model="progressTotai"
      ref="stepperTodai"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'todai'"
    >

      <q-step
        :name="1"
        icon="how_to_reg"
        title="தொடை 1"
        :done = "progressTotai > 1"
      >
        <div class="q-mb-md">இப்ப நாம அசை, சீர், அடி, தளை அப்பிடி இப்பிடின்னு எல்லாத்தையும் வச்சு பாட்டெழுதிட்டோம். ஆனா, அது மட்டும் போதுமா?</div>
        <div class="q-mb-md">நீங்க எழுதுனது, படிக்கவும் கேக்கவும் நல்லா இருக்கணும் இல்லயா? அதுக்குத்தேவையானது தான் தொடை  </div>
        <div class="q-mb-md">பூவ ஆழகா தொடுக்குற மாதிரி, பா'வயும் அழகுக்காக தொடுக்கறதாலதான் இதுக்கு தொடை'ன்னு பேரு.</div>
        <div class="q-mb-md">கீழ இருக்குற பாட்ட படிச்சு பாருங்க.</div>
        <div class="q-mb-md"><b>பொருந்து போதியில் <br/>
இருந்த மாதவர் <br/>
திருந்து சேவடி <br/>
மருந்து ஆகுமே</b></div>

        <div class="q-mb-md">இத படிக்கவே நல்லா இருக்கில்லயா? அதுக்கு காரணம் அடி எதுகை.</div>

        <div class="q-mb-md">தொடை'ல முக்கியமா ரெண்டு விதம் இருக்குங்க: எதுக அப்புறம் மோன.</div>

      </q-step>

      <q-step
        :name="2"
        icon="how_to_reg"
        title="தொடை 2"
        :done = "progressTotai > 2"
      >
        <div class="q-mb-md">மொதல்ல எதுகன்னா என்னென்னு பாப்போம். </div>

        <div class="q-mb-md"><b>எதுக</b>ங்கிறது ரெண்டு வார்த்தைங்க நடுவுல <b>ரெண்டாவது எழுத்து ஒண்ணா வர்ரது</b>. இதுல முக்கியமான விஷயம் மொதல் எழுத்தோட <b>மாத்திர அளவு ஒண்ணா இருக்கணும்</b>. அதாவது <i>குடம் - கூடம்</i> எதுக கெடயாது. ஆனா, <i>குடம் முடம்</i> ரெண்டுத்துக்கு நடுவுலயும் எதுக இருக்கு. அதே மாதிரி, <i>கூடம் - மூடம்</i> இதுங்க நடவுலயும் எதுக இருக்கு.</div>

        <div class="q-mb-md">எதுகை'க்கு ஒரே எழுத்தே வரவேண்டியதில்ல, ஒரே வர்கத்துல வந்தா கூட போதும். அதாவது, <i>குடும் முடம்</i>, டு, ட ஒரே ட-வர்க்க'ங்கறதால இதுவும் எதுக தான். இதுக்குப்பேரு, <b>வர்க்க எதுக</b>. இது உயிர்மெய்யெழுத்துங்களுக்கு மட்டும் தான் பொருந்தும். அதனால, <i>கட்டு முடம்</i> இது எதுக கிடயாது. </div>
      </q-step>

      <q-step
        :name="3"
        icon="how_to_reg"
        title="தொடை 3"
        :done = "progressTotai > 3"
      >
        <div class="q-mb-md">கீழ இருக்குறதுங்க எதுகயா இல்லையான்னு சொல்லுங்க பார்ப்போம்.</div>
        <ol>
        <span v-for="(question, index) in etukaiExam" :key="'etukaifeet' + index">
          <li>{{question.text}} <br/>
          <q-btn-toggle
          v-model="modelEtukai[index-1]"
          toggle-color="grey-10"
          dense
          class="q-mb-sm"
          :options="question.options"
        />
        <span class="q-ml-md" v-if="etukaiResult"><q-icon name="close" color="red" size="20px" v-if="modelEtukai[index-1] != question.answer"/> <q-icon name="done" color="green" size="20px" v-if="modelEtukai[index-1] == question.answer"/></span>
        </li>
      </span>
      </ol>
      <div class="q-mt-md q-ml-lg">
      <q-btn label="சரியா இருக்கா?" @click="etukaiResult = true" class="q-ma-xs"/>
    </div>
      </q-step>

      <q-step
        :name="4"
        icon="how_to_reg"
        title="தொடை 4"
        :done = "progressTotai > 4"
      >
      <div class="q-mb-md">சில சமயங்கள, எதுக கண்டிப்பா வேணும், ஆனா எதுக வர்ர மாதிரி வார்த்தங்கள தேடுறத கஷ்டமா இருக்கலாம். கவலப்படாதீங்க, அதுக்கும் சிலபல வழிங்க இருக்கு.</div>

      <div class="q-mb-md">இன எழுத்து ஒண்ணா வர்ரது: <i>குலம் முழம்</i> : <b>இன எதுக/</b> (ல'வும் இடயினம் ழ'வும் இடயினம்) </div>
      <div class="q-mb-md">உயிரெழுத்து ஒண்ணா வர்ரது: <i>குடும் முதும்</i> : <b>உயிரெதுக</b> (டு-து ரெண்டுமே உகர உயிர்மெய்யெழுத்து) </div>
      <div class="q-mb-md">நெடில் எழுத்து ஒண்ணா வர்ரது: <i>கவாம் நிதேம்</i> : <b>நெடில எதுக</b> (வா, தே ரெண்டுமே நெடில் எழுத்து) </div>
      <div class="q-mb-md">நடுவுல ய்,ர்,ல்,ழ் வந்தா அத கணக்குல எடுத்துக்காம, அடுத்து வர்ர எழுத்த எடுத்துக்குறது. <i>காந்து காய்ந்து</i> : <b>ஆசிட எதுக</b> (ந், ந் - ரெண்டாவது வார்த்தைல இருக்கற ய்'அ கணக்குல எடுத்துக்காம இருந்தா) </div>
      <div class="q-mb-md">ஆனா, இப்படி வர்ரது அவ்வளவு சிறப்பில்லன்னு சொல்லுவாங்க. அதாவது எதுக வேணும்னா முடிஞ்ச வரைக்கும் நேரா வர்ர எதுகயோ இல்ல வர்க்க எதுகயோ வர்ர மாதிரி எழுதனும், அப்படி முடியாத நேரத்துல மேல வர்ர எதுகைங்கள உபயோகப்படுத்தலாம். </div>
      </q-step>

      <q-step
        :name="5"
        icon="how_to_reg"
        title="தொடை 4"
        :done = "progressTotai > 5"
      >
      <div class="q-mb-md">அடுத்து <b>மோன</b>. <b>மொதல் எழுத்து ஒன்னா வர்ரது</b> மோன. </div>

      <div class="q-mb-md">அதே எழுத்து வர்ரத தவிர்த்து, கீழ இருக்குற எழுத்துங்களும் ஒன்னுத்துக்கு ஒன்னு மோன.</div>
      <div class="q-mb-md">அ - ஆ - ஐ - ஔ <br/>
இ - ஈ - எ - ஏ <br/>
உ - ஊ - ஒ -ஓ <br/>
ஞ - ந <br/>
ம - வ <br/>
த - ச</div>
      <div class="q-mb-md">அதாவது, <i>கனை - கானை</i>, <i>நானம் - ஞாயிறு</i>, <i>குரை - கோரை</i>, <i>மகம் - வகம்</i> இதெல்லாம் மோனை தான் </div>
      <div class="q-mb-md">மோனை'க்கு ஒரே எழுத்தே வரவேண்டியதில்ல, ஒரே வர்கத்துல வந்தா கூட போதும். அதாவது, <i>மடும் மிடம்</i>, ம, மி ஒரே ம்-வர்க்க'ங்கறதால இதுவும் மோன தான். இதுக்குப்பேரு, <b>வர்க்க மோன</b>.</div>
      </q-step>

        <q-step
        :name="6"
        icon="how_to_reg"
        title="தொடை 6"
        :done = "progressTotai > 6"
      >
        <div class="q-mb-md">கீழ இருக்குறதுங்க மோனயா இல்லையான்னு சொல்லுங்க பார்ப்போம்.</div>
        <ol>
        <span v-for="(question, index) in monaiExam" :key="'monaifeet' + index">
          <li>{{question.text}} <br/>
          <q-btn-toggle
          v-model="modelMonai[index-1]"
          toggle-color="grey-10"
          dense
          class="q-mb-sm"
          :options="question.options"
        />
        <span class="q-ml-md" v-if="monaiResult"><q-icon name="close" color="red" size="20px" v-if="modelMonai[index-1] != question.answer"/> <q-icon name="done" color="green" size="20px" v-if="modelMonai[index-1] == question.answer"/></span>
        </li>
      </span>
      </ol>
      <div class="q-mt-md q-ml-lg">
      <q-btn label="சரியா இருக்கா?" @click="monaiResult = true" class="q-ma-xs"/>
    </div>
      </q-step>

    <q-step
        :name="7"
        icon="how_to_reg"
        title="தொடை 7"
        :done = "progressTotai > 7"
      >
      <div class="q-mb-md">சில சமயங்கள, மோன வேணும், ஆனா மோன வர்ர மாதிரி வார்த்தங்கள தேடுறத கஷ்டமா இருக்கலாம். கவலப்பாடாதீங்க, அதுக்கும் சிலபல வழிங்க இருக்கு.</div>

      <div class="q-mb-md">இனஎழுத்து ஒண்ணா வர்ரது: <i>குலம் முழம்</i> : <b>இன மோன</b> (ல'வும் இடயினம் ழ'வும் இடயினம்) </div>
      <div class="q-mb-md">நெடில் எழுத்து ஒண்ணா வர்ரது: <i>கவாம் நிதேம்</i> : <b>நெடில மோன</b> (வா, தே ரெண்டுமே நெடில் எழுத்து) </div>

      <div class="q-mb-md">ஆனா, எதுகைக்கு பார்த்தா மாதிரியே இங்கயும் இப்படி வர்ரது அவ்வளவு சிறப்பில்லன்னு சொல்லுவாங்க. அங்க மாதிரியே, இங்கயும் தேவைன்னா மட்டும் இத உபயோகிக்க படுத்திக்கலாம்.</div>
      </q-step>

    <q-step
        :name="8"
        icon="how_to_reg"
        title="தொடை 8"
        :done = "progressTotai > 8"
      >
      <div class="q-mb-md">சில சமயங்கள, மோன வேணும், ஆனா மோன வர்ர மாதிரி வார்த்தங்கள தேடுறத கஷ்டமா இருக்கலாம். கவலப்பாடாதீங்க, அதுக்கும் சிலபல வழிங்க இருக்கு.</div>

      <div class="q-mb-md">இனஎழுத்து ஒண்ணா வர்ரது: <i>குலம் தளம்</i> : இன மோன (கு'வும் வல்லினம் த'வும் வல்லினம்) </div>
      <div class="q-mb-md">நெடில் எழுத்து ஒண்ணா வர்ரது: <i>தேய் வேய்</i> : நெடில் மோன (தே, வே ரெண்டுமே நெடில் எழுத்து) </div>

      <div class="q-mb-md">எதுகைக்கு பார்த்தா மாதிரியே இப்படி வர்ரது அவ்வளவு சிறப்பில்லன்னு சொல்லுவாங்க. அங்க மாதிரியே, இங்கயும் தேவைன்னா மட்டும் இத உபயோகிக்க படுத்திக்கலாம்.</div>
      </q-step>

    <q-step
        :name="9"
        icon="how_to_reg"
        title="தொடை 9"
        :done = "progressTotai > 9"
      >
      <div class="q-mb-md">அப்புறம், கடைசி விஷயம். <b>ஒரே அடிக்குக்குள்ள பார்த்தா</b> <b> சீர் எதுக/மோன</b>. சீர் எதுக/மோன பார்க்கும் போது, ஒவ்வொரு அடியிலயும் இருக்கற மொதல்' சீர அடிப்படியா வச்சு எதுக/மோன பார்க்கனும். அதாவது அடியோட மொதல் சீர்க்கு ஏத்த மாதிரி மத்த சீர்'ங்கள்ள எதுக/மோன அமையனும் </div>

    <div class="q-mb-md"><i>அறிந்தானை ஏத்தி அறிவாங் கறிந்து <br/>
    செறிந்தார்க்குச் செவ்வன் உரைப்ப - செறிந்தார் <br/>
    சிறந்தமை ஆராய்ந்து கொண்டு</i></div>

    <div class="q-mb-md">மொதல்ல முதல் அடிக்கு மட்டும் சீர் எதுக பார்ப்போம்: அறிந்தானை ஏத்தி அறிவாங் கறிந்து<br/><br/>
    <i>அறிந்தானை - ஏத்தி</i> : எதுக இல்ல, <i>அறிந்தானை - அறிவாங்</i> : எதுக, <i>அறிந்தானை - கறிந்து</i> : எதுக<br/><br/>
    அதாவது மொதல் அடில ரெண்டு எடத்துல சீர் எதுக வந்திருக்கு:
    அ<b>றி</b>ந்தானை ஏத்தி அ<b>றி</b>வாங் க<b>றி</b>ந்து
    </div>

    <div class="q-mb-md">ரெண்டாவது அடிக்கு இப்ப சீர் எதுக பார்ப்போம்: செறிந்தார்க்குச் செவ்வன் உரைப்ப - செறிந்தார்<br/><br/>
    <i>செறிந்தார்க்குச்  - செவ்வன் </i> : எதுக இல்ல, <i>செறிந்தார்க்குச்  - உரைப்ப </i> : எதுக இல்ல, <i>செறிந்தார்க்குச்  - செறிந்தார்</i> : எதுக<br/><br/>
    அதாவது ரெண்டாவது அடில ஒரே எடத்துல சீர் எதுக வந்திருக்கு:
    செ<b>றி</b>ந்தார்க்குச் செவ்வன் உரைப்ப - செ<b>றி</b>ந்தார்
    </div>

    <div class="q-mb-md">சீர் மோனயையும் இதே மாதிரிதான் பார்க்கனும்</div>

    </q-step>

    <q-step
        :name="10"
        icon="how_to_reg"
        title="தொடை 10"
        :done = "progressTotai > 10"
      >

      <div class="q-mb-md"><b>அடிங்களுக்கு நடுவுல பார்த்தா அடி எதுக/மோன</b>. அடி எதுக/மோன பார்க்கும் போது, முதல் அடில இருக்குற முதல் சீர அடிப்படையா வச்சு அடுத்தடுத்து அடிகள்ள வர்ர மொதல் சீர்ல எதுக/மோன அமயனும். </div>

    <div class="q-mb-md"><i><u>அறிந்தானை</u> ஏத்தி அறிவாங் கறிந்து <br/>
    <u>செறிந்தார்க்குச்</u> செவ்வன் உரைப்ப - செறிந்தார் <br/>
    <u>சிறந்தமை</u> ஆராய்ந்து கொண்டு</i></div>

    <div class="q-mb-md">அடி எதுக பார்க்கும் போது: <br/><br/>
    <i>அறிந்தானை - செறிந்தார்க்கு</i> : எதுக, <i>அறிந்தானை - சிறந்தமை</i> : எதுக<br/><br/>

    அ<b>றி</b>ந்தானை ஏத்தி அறிவாங் கறிந்து <br/>
    செ<b>றி</b>ந்தார்க்குச் செவ்வன் உரைப்ப - செறிந்தார் <br/>
    சி<b>ற</b>ந்தமை ஆராய்ந்து கொண்டு<br/><br/>

    அதாவது எல்லா அடியலயும் அடி எதுக வந்திருக்கு.

    </div>
      </q-step>

      <q-step
        :name="11"
        icon="how_to_reg"
        title="தொடை 11"
        :done = "progressTotai > 11"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க தொடய பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressTotai == 1" color="grey-8" @click="$refs.stepperTodai.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperTodai.next()" color="grey-8" :label="progressTotai === 11 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>

    <q-stepper
      v-model="progressPa"
      ref="stepperPa"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'pa'"
    >
      <q-step
        :name="1"
        title="பா 1"
        icon="how_to_reg"
        :done="progressPa > 1"
      >
      <div class="q-mb-md">கீழ இருக்குற ரெண்டு பாட்ட தனித்தனியா படிச்சு பாருங்க.</div>

      <div class="q-mb-md"><i>கூற்றங் குமைத்த குரைகழற்காற் கும்பிட்டுத் <br/>
தோற்றந் துடைத்தேந் துடைத்தேமாற் - சீற்றஞ்செய்<br/>
யேற்றினான் றில்லை யிடத்தினா னென்னினியாம்<br/>
போற்றினா னல்கும் பொருள்</i></div>

      <div class="q-mb-md"><i>அருள்வீற் றிருந்த திருநிழற் போதி<br/>
முழுதுணர் முனிவநிற் பரவுதும் தொழுதக<br/>
ஒருமனம் எய்தி இருவினைப் பிணிவிட்டு<br/>
முப்பகை கடந்து நால்வகைப் பொருளுணர்ந்<br/>
தோங்குநீர் உலகிடை யாவரும்<br/>
நீங்கா இன்பமொடு நீடுவாழ் கெனவே</i></div>

      </q-step>

      <q-step
        :name="2"
        title="பா 2"
        icon="how_to_reg"
        :done="progressPa > 2"
      >
      <div class="q-mb-md">நீங்க பார்த்தீங்கன்னா, ரெண்டு பா'வும் ரெண்டு விதமான <b>ஓசநயம்</b> இருக்குறது கவனிச்சிருக்காம். இதுக்குக் காரணம், ரெண்டுமே வேற வேற விதமான பா வக. மொதல் வெண்பா, ரெண்டாவது ஆசிரியப்பா. ரெண்டயும் எழுதுறதுக்கு வேற வேற விதிங்கள கடபிடிக்கணும்.</div>

       <div class="q-mb-md"><b>ஒரு செய்யுள ஒரு குறிப்பிட்ட ஓசநயம் வர்ர மாதிரி சிலபல விதிகளுக்கு உட்பட்டு வர்ர மாதிரி எழுதுனா அது தான் பா.</b> ஒரு பாட்டுக்கு எப்பிடி ராகமோ, அதேமாதிரி தான் செய்யுளுக்கு ஓச. ஸ்வரங்கள விதிப்படி அமைச்சா வேற வேற ராகங்க வர்ர மாதிரி, யாப்பு உறுப்புகள விதிப்படி அமைச்சா வர்ரது தான் பா வகைங்க. ஒவ்வொரு பா வகைளயும் யாப்போட ஆறு உறுப்புங்களும்: எழுத்து, அச, சீர், அடி, தள, தொட எப்பிடி எப்பிடி வரணும் எப்பிடி எப்பிடி வரக்கூடாதுன்னு சொல்றது தான் யாப்பு விதி.</div>

       <div class="q-mb-md">உதாரணமா, ஒரு செய்யுள் ஆசிரியப்பா'வா வரணும்னா ஆசிரியத்தளை நெறய வரணும் மாச்சீரும் விளச்சீரும் மட்டும் தான் வரணும், அடிங்கள்ள அளவடிக்கு மேல வரக்கூடாது'ன்னு நெற விதிங்க இருக்கு. இந்த விதிங்கள கடபிடிச்சு செய்யுள எழுதுனா அந்த செய்யுள்ள அகவலோச ஒழுங்கா அமைஞ்சு ஆசிரியப்பாவா ஆகிடும். </div>

      </q-step>

      <q-step
        :name="3"
        title="பா 3"
        icon="how_to_reg"
        :done="progressPa > 3"
      >
      <div class="q-mb-md">நாலு விதமான பாவகைங்க இருக்குங்க.</div>
      <div class="q-mb-md"><ol><li>வெண்பா</li> <li>ஆசிரியப்பா</li> <li>கலிப்பா</li> <li>ஆசிரியப்பா</li> </ol></div>
      <div class="q-mb-md">இந்த பாவகைங்களுக்கு உண்டான விதிகளயும் அதுக்கான உதாரணங்களயும் நீங்க <router-link to="types" target="_blank">இங்க</router-link> பார்க்கலாம். பார்க்குறதோட மட்டுமில்லாம அந்த உதாரணங்கள நீங்க மாத்தியும் விளயாடலாம். </div>

      </q-step>

      <q-step
        :name="4"
        title="பா 4"
        icon="how_to_reg"
        :done="progressPa > 4"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க பா'வ பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressPa == 1" color="grey-8" @click="$refs.stepperPa.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperPa.next()" color="grey-8" :label="progressPa === 4 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>

    <q-stepper
      v-model="progressVenpa"
      ref="stepperVenpa"
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'venpa'"
    >
      <q-step
        :name="1"
        title="வெண்பா 1"
        icon="how_to_reg"
        :done="progressVenpa > 1"
      >
      <div class="q-mb-md">நீங்க யாப்போட ஆறு உறுப்புங்க பத்தியும் பா'ன்னா என்னங்கிறதயும் தெரிஞ்சிகிட்டீங்க. இப்ப நாம பாக்கப்போறது <b>வெண்பா எழுதுறது எப்படின்னு</b>.</div>

      <div class="q-mb-md">மொதல்ல தெரிஞ்சிக்க வேண்டியது: <b>வெண்பா எழுதுறது அவ்வளவு கஷ்டம் எல்லாம் கெடயாதுங்க</b>. வெண்பா எழுத வெறும் நாலஞ்சு விதிங்கள மதிச்சா போதும். அதுங்கள்ள ஒவ்வொன்னா படிப்படியா இப்ப பார்க்கலாம். கடைசியா, நீங்க முடிக்கும் போது நீங்க உங்களோட மொதல் வெண்பா'வ எழுதி முடிச்சிருப்பீங்க!</div>

      <div class="q-mb-md">உங்களுக்கு என்ன மாதிரி வெண்பா எழுதனும்?</div>
      <q-radio left-label v-model="venpaSize" :val="2" label="குறள் வெண்பா (ரெண்டடி)" /> <br/>
      <q-radio left-label v-model="venpaSize" :val="4" label="சாதா வெண்பா (நாலடி)" />

      </q-step>

      <q-step
        :name="2"
        title="வெண்பா 2"
        icon="how_to_reg"
        :done="progressVenpa > 2"
      >
      <div class="q-mb-md">வெண்பா'ல பார்த்தீங்கன்னா, மொத விதி: கடைசி அடில மூணு சீரும், மத்த அடிங்கள்ள நாலு சீரும் வரணும். கீழ மொதல் படியா வார்த்தங்கள நெறப்புங்க பார்ப்போம்.</div>

        <span v-for="index1 in venpaSize" :key="'venpaline1' + index1">
          <div class="q-gutter-y-md row" v-if="index1 < venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="''" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 4" :key="'venpaline1' + index2"></q-input>
          </div>
          <div class="q-gutter-y-md row" v-if="index1 == venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="''" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 3" :key="'venpaline1' + index2"></q-input>
          </div>
        </span>
        <br/>
        <span v-if="disableStep">
        <q-icon name="pan_tool" color="grey-10" size="30px" class="q-mr-sm"/> <i>கேட்டிருக்குற மாதிரி நெறப்பி அடுத்த கட்டத்துக்குப்போங்க.</i>
        </span>
        <span v-if="!disableStep">
          <q-icon name="done" color="green" size="30px" /> <i>சரியா பன்னிட்டீங்க! நீங்க அடுத்த கட்டத்துக்குப்போகலாம்</i>
        </span>
      </q-step>

      <q-step
        :name="3"
        title="வெண்பா 3"
        icon="how_to_reg"
        :done="progressVenpa > 3"
      >
      <div class="q-mb-md">வெண்பாவோட ரெண்டாவது விதி: <b>தேமா, புளிமா, கூவிளம், கருவிளம், தேமாங்காய், புளிமாங்காய், கூவிளங்காய், கருவிளங்காய்</b> - இத தவிர்த்து வேற வாய்ப்பாடு வர்ர எந்த சீரும் வரக்கூடாது. கீழ இருக்குறதுல இந்த வாய்ப்பாடுங்க மட்டும் வர்ர மாதிரி உங்க வார்த்தைங்கள மாத்துங்க பார்ப்போம். இப்பத்திக்கு வெண்பாவோட கடைசி வார்த்தைய விட்டுடுங்க. அத அப்புறம் பார்ப்போம்.</div>

        <span v-for="index1 in venpaSize" :key="'venpaline22' + index1">
          <div class="q-gutter-y-md row" v-if="index1 < venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="metricalFeet[((index1-1) * 4) + (index2-1)]" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 4" :key="'venpaline12' + index2" @input="analysisWrittenVerse"
            :bg-color="!feetTypesVenpa.includes(metricalFeet[((index1-1) * 4) + (index2-1)])? 'red-1' : ''"
            ></q-input>
          </div>
          <div class="q-gutter-y-md row" v-if="index1 == venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="index2 != 3 ? metricalFeet[((index1-1) * 4) + (index2-1)] : ''" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 3" :key="'venpaline12' + index2" @input="analysisWrittenVerse" :disable="index2 == 3"
              :bg-color="((index1-1) * 4) + (index2-1) != (venpaSize * 4) - 2  && !feetTypesVenpa.includes(metricalFeet[((index1-1) * 4) + (index2-1)])? 'red-1' : ''"
            ></q-input>
          </div>
        </span>
        <br/>
        <div class="tamil q-mb-md" v-if="$q.platform.is.mobile">
          <span v-for="wordIndex in (venpaSize * 4) - 2" :key="'venIndex' + wordIndex">
            {{words[wordIndex-1]}} :: {{metricalFeet[wordIndex-1]}} <br/>
          </span>
        </div>
        <span v-if="disableStep">
        <q-icon name="pan_tool" color="grey-10" size="30px" class="q-mr-sm"/> <i>கேட்டிருக்குற மாதிரி நெறப்பி அடுத்த கட்டத்துக்குப்போங்க.</i>
        </span>
        <span v-if="!disableStep">
          <q-icon name="done" color="green" size="30px" /> <i>சரியா பன்னிட்டீங்க! நீங்க அடுத்த கட்டத்துக்குப்போகலாம்</i>
        </span>
      </q-step>

      <q-step
        :name="4"
        title="வெண்பா 4"
        icon="how_to_reg"
        :done="progressVenpa > 4"
      >
      <div class="q-mb-md">இப்ப கடைசி வார்த்தய பார்த்தீங்கன்னா, இது கொஞ்சம் ஸ்பெஷல். அதாவது, அதுக்குன்னு தனி வாய்ப்பாடு இருக்கு. வெண்பாவோட கடைசி சீர் வெறும் நேரசையாவோ இல்ல நிரையாவோ இல்ல நேரசைக்கு அப்புறம் ஒரு உகர உயிர்மெழுத்தோடயோ இல்ல நிரையசைக்கு அப்புறம் ஒரு உகர உயிர்மெழுத்தோடயோ வரணும். இதுங்களுக்கு நாள், மலர், காசு, பிறப்புன்னு பேருங்க. </div>

      <div class="q-mb-md">பார்த்தீங்கன்னா, நாள்'ங்கிறது நேரசை மட்டும், மலர்'ங்க்றது நிரையசை மட்டும். கா/சு நேர் + உகர உயிர்மெய், பிறப்பு நிரை + உகர உயிர்மெய். இப்ப கீழ, <b>நாள், மலர், காசு, பிறப்பு</b> இது நாலும் வர்ர மாதிரி கீழ மாத்துங்க.</div>

        <span v-for="index1 in venpaSize" :key="'venpaline33' + index1">
          <div class="q-gutter-y-md row" v-if="index1 < venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="metricalFeet[((index1-1) * 4) + (index2-1)]" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 4" :key="'venpaline132' + index2" @input="analysisWrittenVerse" disable></q-input>
          </div>
          <div class="q-gutter-y-md row" v-if="index1 == venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="metricalFeet[((index1-1) * 4) + (index2-1)]" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 3" :key="'venpaline132' + index2" @input="analysisWrittenVerse" :disable="index2 != 3"
             :bg-color="((index1-1) * 4) + (index2-1) == (venpaSize * 4) - 2  && !venpaEnd.includes(metricalFeet[((index1-1) * 4) + (index2-1)])? 'red-1' : ''"></q-input>
          </div>
        </span>
        <br/>
        <span v-if="disableStep">
        <q-icon name="pan_tool" color="grey-10" size="30px" class="q-mr-sm"/> <i>கேட்டிருக்குற மாதிரி நெறப்பி அடுத்த கட்டத்துக்குப்போங்க.</i>
        </span>
        <span v-if="!disableStep">
          <q-icon name="done" color="green" size="30px" /> <i>சரியா பன்னிட்டீங்க! நீங்க அடுத்த கட்டத்துக்குப்போகலாம்</i>
        </span>
      </q-step>

      <q-step
        :name="5"
        title="வெண்பா 5"
        icon="how_to_reg"
        :done="progressVenpa > 5"
      >
      <div class="q-mb-md">அடுத்து கடைசியா நீங்க பார்க்க வேண்டியது தள. இப்ப பார்த்தீங்கன்னா <b>வெண்டளைய தவிர்த்து</b> வேற எந்த தளயும் வரக்கூடாது. நீங்கள் எழுதி இருக்குற வரைக்கும் வெண்டளைய தவிர்த்து வேற தளைங்க வந்திருக்கும், அதனால வெண்டள வர்ர மாதிரி வார்த்தைங்கள மாத்துங்க.</div>

      <div class="q-mb-md">முக்கியமான விஷயம்: சரியான தளைய வரவைக்குற அதே நேரத்துல வாய்ப்பாடும் சரியா இருக்குற மாதிரி பார்த்துக்கோங்க.</div>

        <span v-for="index1 in venpaSize" :key="'venpaline43' + index1">
          <div class="q-gutter-y-md row" v-if="index1 < venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="metricalFeet[((index1-1) * 4) + (index2-1)]" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 4" :key="'venpaline142' + index2" @input="analysisWrittenVerse"
            :bg-color="!feetTypesVenpa.includes(metricalFeet[((index1-1) * 4) + (index2-1)])? 'red-1' : ''"
            >
            ></q-input>
          </div>
          <div class="q-gutter-y-md row" v-if="index1 == venpaSize">
            <q-input v-model="words[((index1-1) * 4) + (index2-1)]" :label="metricalFeet[((index1-1) * 4) + (index2-1)]" class="col q-ma-sm"  style="max-width: 100px" v-for="index2 in 3" :key="'venpaline142' + index2" @input="analysisWrittenVerse"
            :bg-color="((index1-1) * 4) + (index2-1) != (venpaSize * 4) - 2 ? (!feetTypesVenpa.includes(metricalFeet[((index1-1) * 4) + (index2-1)]) ? 'red-1' : '') : (!venpaEnd.includes(metricalFeet[((index1-1) * 4) + (index2-1)]) ? 'red-1' : '')"
            ></q-input>
          </div>
        </span>

        <div class="q-mt-md" v-if="linkageList!=''">
          <div v-for="index in ((venpaSize * 4) - 2)" :key="'link' + index">
            {{words[index - 1]}} ⟵ <span :class="linkageList[index - 1].includes('வெண்டளை') ? 'text-green' : 'text-red'">{{linkageList[index - 1]}}</span> ⟶ {{words[index]}} <br/>({{linkagesAll[index-1][0]}} ⟶ {{linkagesAll[index-1][1]}}) <br/><br/>
          </div>
        </div>
        <br/>

        <span v-if="disableStep">
        <q-icon name="pan_tool" color="grey-10" size="30px" class="q-mr-sm"/> <i>கேட்டிருக்குற மாதிரி நெறப்பி அடுத்த கட்டத்துக்குப்போங்க.</i>
        </span>
        <span v-if="!disableStep">
          <q-icon name="done" color="green" size="30px" /> <i>சரியா பன்னிட்டீங்க! நீங்க அடுத்த கட்டத்துக்குப்போகலாம்</i>
        </span>
      </q-step>

      <q-step
        :name="6"
        title="வெண்பா 6"
        icon="how_to_reg"
        :done="progressVenpa > 6"
      >
      <div class="q-mb-md">சபாஷ்! அவ்வளவு தாங்க கீழ இருக்குறது உங்களோட மொதல் வெண்பா! வாழ்த்துக்கள் உங்களோட மொதல் மரபுப்பாவ எழுதிட்டீங்க!  </div>

      <b><pre class="tamil">{{writtenVerse}}</pre></b>

       <div class="q-mb-md">நிங்க எழுதி இருக்குறது <b>{{this.writtenMetre}}</b>. நீங்களும் இப்ப புலவர் :)</div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressVenpa == 1" color="grey-8" @click="$refs.stepperVenpa.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperVenpa.next()" color="grey-8" :label="progressVenpa === 6 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" :disable="disableStep"/>
        </q-stepper-navigation>
      </template>
    </q-stepper>

<q-stepper
      v-model="progressPavinam"
      ref="stepperPavinam"
      header-nav
      class="tamil bigger"
      animated
      :vertical="$q.platform.is.mobile"
      done-color="green-10"
      active-color="grey-10"
      inactive-color="grey-6"
      v-show="currentSelection == 'pavinam'"
    >
      <q-step
        :name="1"
        title="பாவினம் 1"
        icon="how_to_reg"
        :done="progressPavinam > 1"
      >
      <div class="q-mb-md">கீழ இருக்குற பாட்ட பாருங்க.</div>

      <div class="q-mb-md"><i>பொருந்து போதியில்<br/>
இருந்த மாதவர்<br/>
திருந்து சேவடி<br/>
மருந்து ஆகுமே</i></div>

      </q-step>

      <q-step
        :name="2"
        title="பாவினம் 2"
        icon="how_to_reg"
        :done="progressPavinam > 2"
      >
      <div class="q-mb-md">பாக்கவே ரொம்ப சுலபமா இருக்கில்லயா? மூணே அடி, ஒரு அடில ரெண்டு வார்த்த. அவ்வளவே தான். இது புதுக்கவித மாதிரி தெரிஞ்சாலும், இது புதுக்கவித கிடையாது. இதுக்குப் பேரு வஞ்சித்துற.</div>

       <div class="q-mb-md">வெண்பாவோ ஆசிரியப்பாவோ கலிப்பாவோ வஞ்சிப்பாவோ எழுதனும்னா நெற விதிங்கள காடபிடிக்கணும். அது கொஞ்சம் கஷ்டங்கிறதுனால, பாவினம்'ன்னு ஒன்னு இருக்கு. இதுவும் மரபுப்பா தான். இதுக்கான விதிங்களும் யாப்பருங்கலக்காரிகைல இருக்கு.<b> தள, வாய்ப்பாடு, அது இதுன்னு இல்லாம அடி, சீர் மட்டும் வச்சு வர்ரது தான் பாவினம்.</b></div>

      </q-step>

      <q-step
        :name="3"
        title="பாவினம் 3"
        icon="how_to_reg"
        :done="progressPavinam > 3"
      >
      <div class="q-mb-md">உதாரணமா: ஒரே அளவுல மூணு அடி வந்துசுன்னா ஆசிரியத்தாழிச, ஒரே அளவுல ரெண்டு அடி வந்துச்சுன்னா குறள் வெண்செந்துற, நாலடில நாலு வார்த்த வந்துச்சுன்னா கலிவிருத்தம்.அவ்வளவே தான். ஒவ்வொரு பாவுக்கும் ஒரு இனம் இருக்கும். அதுக்கு அர்த்தம் என்னென்ன, வெளியில பார்க்குறதுக்கு அந்தப்பா மாதிரி தெரியும், ஆனா அந்தப்பாகிடையாது.</div>

      <div class="q-mb-md"><i>நண்பி தென்று தீய சொல்லார்<br/>
முன்பு நின்று முனிவு செய்யார்<br/>
அன்பு வேண்டு பவர்</i></div>

      <div class="q-mb-md">மேலே இருக்குறது வெண்பா மாதிரி தெரிஞ்சாலும். இது வெண்பா கிடையாது. ஏன்னா, பாட்டுல வெண்டள இல்லாது ஆசிரியத்தள வந்து தள தட்டி இருக்குது. இது வெண்பாவினத்த சேர்ந்த <i>வெண்டாழிசை</i>.</div>

      <div class="q-mb-md"><b>வெண்பாவினம், ஆசிரியப்பாவினம், கலிப்பாவினம், </b> அப்புறம் <b>வஞ்சிப்பாவினம்னு</b> நாலு பாவினம் இருக்கு. ஒவ்வொரு இனத்துலயும் <b>தாழிச, துற, விருத்தம்</b>னு மூணு வக இருக்கு.</div>
      <div class="q-mb-md">இந்த பாவினங்களுக்கான உண்டான விதிகளயும் அதுக்கான உதாரணங்களயும் நீங்க <router-link to="types" target="_blank">இங்க</router-link> பார்க்கலாம். பார்க்குறதோட மட்டுமில்லாம அந்த உதாரணங்கள நீங்க மாத்தியும் விளயாடலாம். </div>

      </q-step>

      <q-step
        :name="4"
        title="பாவினம் 4"
        icon="how_to_reg"
        :done="progressPavinam > 4"
      >
      <div class="q-mb-md">அவ்வளவு தாங்க பாவினத்த பத்தி நீங்க தெரிஞ்சிக்க வேண்டியது. </div>

      <div class="q-mb-md">மத்த விஷயங்கள பத்தி தெரிஞ்சிக்க மேலே இருக்குற ஏதாச்சயும் தேர்ந்தெடுங்க. </div>

      </q-step>

      <template v-slot:navigation>
        <q-stepper-navigation>
          <q-btn :disable="progressPavinam == 1" color="grey-8" @click="$refs.stepperPavinam.previous()" label="முன்னாடி" class="q-mr-sm" />
          <q-btn @click="$refs.stepperPavinam.next()" color="grey-8" :label="progressPavinam === 4 ? 'அவ்வளவுதாங்க' : 'அடுத்து'" />
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
  </q-page>
</template>

<script>
import { LinkMixin } from '../mixin/LinkMixin'

export default {
  // name: 'PageName',
  mixins: [LinkMixin],
  components: {
  },
  watch: {
    step: function () {
      this.switc = !this.swtic
    },
    progressEluttu: function () {
      localStorage.progressEluttu = this.progressEluttu
    },
    progressAcai: function () {
      localStorage.progressAcai = this.progressAcai
    },
    progressCir: function () {
      localStorage.progressCir = this.progressCir
    },
    progressAti: function () {
      localStorage.progressAti = this.progressAti
    },
    progressTalai: function () {
      localStorage.progressTalai = this.progressTalai
    },
    progressTotai: function () {
      localStorage.progressTotai = this.progressTotai
    },
    progressPa: function () {
      localStorage.progressPa = this.progressPa
    },
    progressPavinam: function () {
      localStorage.progressPavinam = this.progressPavinam
    },
    progressVenpa: function () {
      localStorage.progressVenpa = this.progressVenpa

      if (this.progressVenpa > 2) {
        this.analysisWrittenVerse()
      }
    },
    words: function () {
      localStorage.words = JSON.stringify(this.words)
    },
    writtenMetre: function () {
      localStorage.writtenMetre = this.writtenMetre
    },
    verseanalysis: function () {
      localStorage.verseanalysis = JSON.stringify(this.verseanalysis)
    },
    metricalFeet: function () {
      localStorage.metricalFeet = JSON.stringify(this.metricalFeet)
    },
    linkageList: function () {
      localStorage.linkageList = JSON.stringify(this.linkageList)
    },
    linkagesAll: function () {
      localStorage.linkagesAll = JSON.stringify(this.linkagesAll)
    },
    currentSelection: function () {
      localStorage.currentSelection = this.currentSelection
    }
  },
  mounted: function () {
    if (localStorage.currentSelection) {
      this.currentSelection = localStorage.currentSelection
    }
    if (localStorage.progressEluttu) {
      this.progressEluttu = parseInt(localStorage.progressEluttu)
    }
    if (localStorage.progressAcai) {
      this.progressAcai = parseInt(localStorage.progressAcai)
    }
    if (localStorage.progressCir) {
      this.progressCir = parseInt(localStorage.progressCir)
    }
    if (localStorage.progressAti) {
      this.progressAti = parseInt(localStorage.progressAti)
    }
    if (localStorage.progressTalai) {
      this.progressTalai = parseInt(localStorage.progressTalai)
    }
    if (localStorage.progressTotai) {
      this.progressTotai = parseInt(localStorage.progressTotai)
    }
    if (localStorage.progressPa) {
      this.progressPa = parseInt(localStorage.progressPa)
    }
    if (localStorage.progressPavinam) {
      this.progressPavinam = parseInt(localStorage.progressPavinam)
    }
    if (localStorage.writtenMetre) {
      this.writtenMetre = localStorage.writtenMetre
    }
    if (localStorage.verseanalysis) {
      this.verseanalysis = JSON.parse(localStorage.verseanalysis)
    }
    if (localStorage.metricalFeet) {
      this.metricalFeet = JSON.parse(localStorage.metricalFeet)
    }
    if (localStorage.linkageList) {
      this.linkageList = JSON.parse(localStorage.linkageList)
    }
    if (localStorage.linkagesAll) {
      this.linkagesAll = JSON.parse(localStorage.linkagesAll)
    }
    if (localStorage.progressVenpa) {
      this.progressVenpa = parseInt(localStorage.progressVenpa)

      if (this.progressVenpa > 2) {
        this.analysisWrittenVerse()
      }
    }
  },
  beforeMount: async function () {
    for (let i = 0; i < 5; i++) {
      this.acais.push(this.randomacai())
    }
    for (let i = 0; i < 10; i++) {
      this.letters.push(this.randomletter())
    }
    this.demo(0)
    this.demo(5)
    this.demo(6)
    this.demo(7)
    this.demo(8)

    this.analysistalai1 = await this.convertAsync(this.versetalai1)
    this.analysistalai1 = await this.getJson(this.analysistalai1)

    this.words = Array((this.venpaSize * 4) - 1).join('.').split('.')

    if (localStorage.words) {
      this.words = JSON.parse(localStorage.words)
    }
  },
  computed: {
    disableStep: function () {
      if (this.progressVenpa === 2) {
        return !this.writtenVerseComplete
      } else if (this.progressVenpa === 5) {
        return !this.linkageListCheck || this.endCheckVenpa || !this.otherCheckVenpa
      } else if (this.progressVenpa === 4) {
        return this.endCheckVenpa
      } else if (this.progressVenpa === 3) {
        return !this.otherCheckVenpa
      }
      return false
    },
    otherCheckVenpa: function () {
      var dhis = this
      return this.words.slice(0, (this.venpaSize * 4) - 2).filter((x, index) => !dhis.feetTypesVenpa.includes(dhis.metricalFeet[index])).length === 0
    },
    endCheckVenpa: function () {
      return !this.venpaEnd.includes(this.metricalFeet[(this.venpaSize * 4) - 2])
    },
    linkageListCheck: function () {
      if (this.linkageList !== '') {
        return this.linkageList.filter(x => !x.includes('வெண்டளை')).length === 0
      } else {
        return true
      }
    },
    writtenVerseComplete: function () {
      var dhis = this
      var complete = true
      this.words.forEach(function (word, index) {
        if (index < (dhis.venpaSize * 4) - 1) {
          if (word.length === 0) {
            complete = false
          }
        }
      })

      return complete
    },
    writtenVerse: function () {
      var verse = ''
      var dhis = this
      this.words.forEach(function (word, index) {
        if (index < (dhis.venpaSize * 4) - 1) {
          verse = verse + ' ' + word
          if (index > 0 && (index + 1) % 4 === 0) {
            verse = verse + '\n'
          }
        }
      })

      return verse
    },
    flattenedVerse1: function () {
      return this.versetalai1.split('\n').map(x => x.split(' ')).flat()
    },
    flattenedVerseMF: function () {
      return this.metricalFeetGet(this.analysistalai1).flat()
    },
    flattenedVerseLink: function () {
      return this.linkageGet(this.analysistalai1).flat()
    },
    syllanalysisPractice: function () {
      var syllAna = []
      var dhis = this
      this.analysisPractice.forEach(function (x) {
        syllAna.push(dhis.syllDiv(x))
      })
      return syllAna
    },
    metremeanalysisPractice: function () {
      var syllAna = []
      var dhis = this
      this.analysisPractice.forEach(function (x) {
        syllAna.push(dhis.metremeDiv(x))
      })
      return syllAna
    },
    classanalysisPractice: function () {
      var syllAna = []
      var dhis = this
      this.analysisPractice.forEach(function (x) {
        syllAna.push(dhis.classDiv(x))
      })
      return syllAna
    }
  },
  methods: {
    analysisWrittenVerse: async function () {
      this.verseanalysis = await this.convertAsync(this.writtenVerse)
      this.verseanalysis = await this.getJson(this.verseanalysis)

      this.metricalFeet = this.metricalFeetGet(this.verseanalysis).flat().map(x => x[1])

      if (this.vepaLastWordClass(this.verseanalysis) !== 'None') {
        this.metricalFeet[this.metricalFeet.length - 1] = this.vepaLastWordClass(this.verseanalysis)
      }

      this.linkagesAll = this.linkageGet(this.verseanalysis).flat()
      this.linkagesAll.shift()
      this.linkageList = this.linkagesAll.map(x => x[2]).filter(x => x !== '')

      this.writtenMetre = this.verseanalysis.verse.$.metre
    },
    getAnalysis: async function () {
      this.analysistalai1 = await this.convertAsync(this.versetalai1)
      this.analysistalai1 = await this.getJson(this.analysistalai1)
    },
    showExamplesClass: function (type) {
      this.practiceWord = this.resultWord
      if (type === 0) {
        this.demo(0)
        this.demo(1)
        this.demo(2)
        this.demo(3)
        this.demo(4)
      } else {
        this.demo(5)
        this.demo(6)
        this.demo(7)
        this.demo(8)
      }
    },
    classDiv: function (result) {
      // console.log(result)
      try {
        return result.verse.MetricalLine[0].MetricalFoot[0].$.class
      } catch (err) {
        // console.log(err)
      }
      return ''
    },
    syllDiv: function (result) {
      // console.log(result)
      try {
        return result.verse.MetricalLine[0].MetricalFoot[0].Metreme.map(x => x._).join('/')
      } catch (err) {
        // console.log(err)
      }
      return ''
    },
    metremeDiv: function (result) {
      try {
        return result.verse.MetricalLine[0].MetricalFoot[0].Metreme.map(x => x.$.type).join('/')
      } catch (err) {
        // console.log(err)
      }
      return ''
    },
    demo: async function (index) {
      // console.log('The index is this ' + index)

      this.analysisPractice[index] = await this.convertAsync(this.practiceWord[index] + '\n’')
      this.analysisPractice[index] = await this.getJson(this.analysisPractice[index])

      this.$set(this.analysisPractice, index, this.analysisPractice[index])
    },
    doRandomAcai: function () {
      this.resultAcai = false
      this.modelAcai = ['', '', '', '', '']
      for (let i = 0; i < 5; i++) {
        this.$set(this.acais, i, this.randomacai())
      }
    },
    doRandomLetters: function () {
      this.resultLetter = false
      this.modelletters = ['', '', '', '', '']
      for (let i = 0; i < 10; i++) {
        this.$set(this.letters, i, this.randomletter())
      }
    },
    randomletter: function () {
      let choice = this.getRandomInt(0, 2)

      let conIn = this.getRandomInt(0, this.consonants.length - 1)

      let shortVIn = this.getRandomInt(0, this.shortVaiau.length - 1)
      let shortVSIn = this.getRandomInt(0, this.shortVSaiau.length - 1)

      let longVIn = this.getRandomInt(0, this.longV.length - 1)
      let longVSIn = this.getRandomInt(0, this.longVS.length - 1)

      let letter = ''

      if (choice === 0) {
        letter = this.getRandomInt(0, 1) === 0 ? this.consonants[conIn] + this.shortVSaiau[shortVSIn] : this.shortVaiau[shortVIn]
      } else if (choice === 1) {
        letter = this.getRandomInt(0, 1) === 0 ? this.consonants[conIn] + this.longVS[longVSIn] : this.longV[longVIn]
      } else {
        letter = this.consonants[Math.floor(Math.random() * this.consonants.length)] + this.pulli[0]
      }

      return [letter, choice]
    },
    randomacai: function () {
      return this.getRandomInt(0, 1) === 0 ? this.ner() : this.nirai()
    },
    ner: function () {
      let conIn = this.getRandomInt(0, this.consonantsV.length - 1)
      let con2In = this.getRandomInt(0, this.consonantsV.length - 1)
      let con3In = this.getRandomInt(0, this.consonantsV.length - 1)
      let vowIn = this.getRandomInt(0, this.vowels.length - 1)
      let vowSIn = this.getRandomInt(0, this.vowelSignV.length - 1)

      let vowCon = this.getRandomInt(0, 1) === 0 ? this.consonantsV[con3In] : this.vowels[vowIn]
      let ner1 = this.getRandomInt(0, 1) === 0 ? this.consonantsV[conIn] + this.vowelSignV[vowSIn] : vowCon
      let ner2 = this.getRandomInt(0, 1) === 0 ? this.consonantsV[con2In] + this.pulli[0] : ''

      return [ner1 + ner2, 'ner']
    },
    nirai: function () {
      let conIn = this.getRandomInt(0, this.consonantsV.length - 1)
      let con2In = this.getRandomInt(0, this.consonantsV.length - 1)
      let con3In = this.getRandomInt(0, this.consonantsV.length - 1)
      let shortVIn = this.getRandomInt(0, this.shortVaiau.length - 1)
      let shortVSIn = this.getRandomInt(0, this.shortVSaiau.length - 1)
      let longVSIn = this.getRandomInt(0, this.longVS.length - 1)

      let nirai1 = this.getRandomInt(0, 1) === 0 ? this.consonantsV[conIn] + this.shortVSaiau[shortVSIn] : this.shortVaiau[shortVIn]
      let nirai2 = this.consonantsV[con2In] + this.longVS[longVSIn]
      let nirai3 = this.getRandomInt(0, 1) === 0 ? this.consonantsV[con3In] + this.pulli[0] : ''

      return [nirai1 + nirai2 + nirai3, 'nirai']
    }
  },
  data () {
    return {
      step: 1,
      feetTypesVenpa: ['தேமா', 'புளிமா', 'கூவிளம்', 'கருவிளம்', 'கூவிளங்காய்', 'கருவிளங்காய்', 'புளிமாங்காய்', 'தேமாங்காய்'],
      venpaEnd: ['நாள்', 'மலர்', 'காசு', 'பிறப்பு'],
      linkagesAll: [],
      writtenMetre: '',
      verseanalysis: '',
      venpaSize: 2,
      words: '',
      metricalFeet: '',
      linkageList: '',
      linkage: '',
      switc: false,
      showone: false,
      currentSelection: '',
      modelAcai: ['', '', '', '', ''],
      modelFeet: ['', '', '', '', ''],
      modelEtukai: ['', '', '', '', '', ''],
      modelMonai: ['', '', '', '', '', ''],
      modelIyaipu: ['', '', '', '', '', ''],
      progressEluttu: 1,
      progressCir: 1,
      progressAcai: 1,
      progressTalai: 1,
      progressAti: 1,
      progressTotai: 1,
      progressPa: 1,
      progressPavinam: 1,
      progressVenpa: 1,
      versetalai1: 'ஆதி போதிநிழற் புனிதன் பொலங்கழல்\nஆதி உலகிற் காண்',
      versetalai2: 'அருள்வீற் றிருந்த அருள்வீற் றிருந்த அருள்வீற் றிருந்த',
      analysistalai1: '',
      analysistalai2: '',
      resultAcai: false,
      resultLetter: false,
      practiceWord: ['போதிசத்துவன்', '', '', '', '', 'காபாலிகர்', 'அருகனே', 'வம்பு', 'நிகமந்தன்'],
      analysisPractice: ['', '', '', '', ''],
      resultWord: ['போதிசத்துவன்', 'புத்தன்', 'பகவதி', 'அறமன்றம்', 'மெய்யெழுத்து', 'காபாலி', 'பாகனே', 'வரம்பு', 'நிகமசேந்தன்'],
      start: 'op2',
      acais: [],
      letters: [],
      modelletters: ['', '', '', '', ''],
      startoptions: [
        {
          label: 'ஏற்கனவே தெரியும்',
          value: 'op1'
        },
        {
          label: 'தெரிஞ்சிக்கனும்',
          value: 'op2'
        },
        {
          label: 'அதப்பத்தி எனக்கு தெரிஞ்சுக்க நேரமில்ல. நேரடியா வெண்பா எழுதறத எப்படின்னு தெரிஞ்சா போதும். குத்துமதிப்பா நான் எழுதிக்கறேன்',
          value: 'op3'
        }
      ],
      feetResult: false,
      etukaiResult: false,
      monaiResult: false,
      iyaipuResul: false,
      feetExam: [
        {
          text: 'ஆவி அந்துகில் புனைவதொன் றன்றிவே றறியாள்',
          answer: 'நெடிலடி',
          options: [
            { label: 'குறளடி', value: 'குறளடி' },
            { label: 'நெடிலடி', value: 'நெடிலடி' },
            { label: 'சிந்தடி', value: 'சிந்தடி' }
          ]
        },
        {
          text: 'தடக்கையான் வெயில்மறைக்கும்',
          answer: 'குறளடி',
          options: [
            { label: 'சிந்தடி', value: 'சிந்தடி' },
            { label: 'அளவடி', value: 'அளவடி' },
            { label: 'குறளடி', value: 'குறளடி' }
          ]
        },
        {
          text: 'நீரின் தண்மையும் தீயின் வெம்மையும்',
          answer: 'அளவடி',
          options: [
            { label: 'அளவடி', value: 'அளவடி' },
            { label: 'சிந்தடி', value: 'சிந்தடி' },
            { label: 'நெடிலடி', value: 'நெடிலடி' }
          ]
        },
        {
          text: 'எயினடுவண் இனிதிருந் தெல்லோர்க்கும்',
          answer: 'சிந்தடி',
          options: [
            { label: 'நெடிலடி', value: 'நெடிலடி' },
            { label: 'அளவடி', value: 'அளவடி' },
            { label: 'சிந்தடி', value: 'சிந்தடி' }
          ]
        },
        {
          text: 'தோடார் இலங்கு மலர்கோதி வண்டு வரிபாட நீடு துணர்சேர்',
          answer: 'எழுசீர்க்கழிநெடிலடி',
          options: [
            { label: 'எழுசீர்க்கழிநெடிலடி', value: 'எழுசீர்க்கழிநெடிலடி' },
            { label: 'அறுசீர்க்கழிநெடிலடி', value: 'அறுசீர்க்கழிநெடிலடி' },
            { label: 'எண்சீர்க்கழிநெடிலடி', value: 'எண்சீர்க்கழிநெடிலடி' }
          ]
        }
      ],
      etukaiExam: [
        {
          text: 'தேடுக வாடினேன்',
          answer: 'ஆம்',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'நிதி சோதனை',
          answer: 'இல்லை',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'காலன் கலன்',
          answer: 'இல்லை',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'பொயன் கையன்',
          answer: 'ஆம்',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'குணி துணி',
          answer: 'ஆம்',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'கண்டு கணவு',
          answer: 'இல்லை',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        }
      ],
      monaiExam: [
        {
          text: 'தினம் கனம்',
          answer: 'இல்லை',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'கிலி கீளி',
          answer: 'ஆம்',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'துரை சிதை',
          answer: 'ஆம்',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'ஞாயிறு மாயிறு',
          answer: 'இல்லை',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'விதி மிதி',
          answer: 'ஆம்',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        },
        {
          text: 'கதி மதி',
          answer: 'இல்லை',
          options: [
            { label: 'ஆம்', value: 'ஆம்' },
            { label: 'இல்லை', value: 'இல்லை' }
          ]
        }
      ]
    }
  }
}
</script>

<style scoped>
.label {
  width: 0%;
  height: 0%;
}
</style>
