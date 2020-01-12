<template>
    <q-page padding>
    <h4>Tamil Prosody</h4>
    <p class="text-body">This attempts to provide a very brief introduction to Tamil
      prosody. For a detailed account, refer to Ulrike Niklas' article in the
      reference section.</p>

    <p class="text-body">To begin with, the Tamil prosodical system defines six basic components:
    <ol class="text-body">
      <li><em>Eḻuttu</em> - Letter </li>
      <li><em>Acai</em> - Metreme </li>
      <li><em>Cīr</em> - Metrical Foot </li>
      <li><em>Taḷai</em> - Linkage </li>
      <li><em>Aṭi</em> - Metrical Line </li>
      <li> <em>Toṭai</em> - Ornamentation </li>
    </ol>

    <p class="text-body">As a syllable-timed language, the system of Tamil prosody is completely dependant on syllablic
    patterns rather than being based on stress or intonation.</p>
    <h5>Letter</h5>
    <p class="text-body">
      The Tamil script being an Abugida a.k.a alpha-syllabary, <em>letters</em> in Tamil
      prosody corresponds to <em>orthographic graphemes</em>.
    </p>
    <p class="text-body">Tamil differentiates three types of letters (due to its
      orthography)</p>
    <ol class="text-body">
      <li><em>Uyireḻuttu</em> - Initial Vowels (<sup>i</sup>V) </li>

      <li><em>Meyyeḻuttu</em> - Pure Consonants (C) </li>

      <li><em>Uyirmeyyeḻuttu</em> - Consonant-Vowels (<u>CV</u>) </li>

    </ol>

    <p class="text-body">Each of the above is considered as a "letter" in Tamil. Tamil
      being a phonemic script, it can also be used to denote a phoneme.</p>

    <p class="text-body">For instance, the word <em>அவலோகிதம் : avalōkitam </em>
    would be analyzed as having <em> 6 letters </em> - <sup>i</sup>V <u>CV</u>&nbsp;
    <u>CV</u>&nbsp;<u>CV</u>&nbsp;<u>CV</u>&nbsp;C :: அ வ லோ கி த ம் :: a va lō ki ta m</p>

    <h5>Metreme</h5>
    <p class="text-body">Metreme is the basic unit of Tamil prosody, which in turn is used to contruct a matrical foot. A metreme can (metrically speaking) be either short or
      long.</p>

    <p class="text-body">
      The short metreme is called "<em>nēr</em>" and the long
      metreme is "<em>nirai</em>".
    </p>

    <p class="text-body">These may be conveniently expressed using regular expression as follows:</p>

    <table cellspacing="10" class="text-body">
      <tr>
        <td><strong>Metreme</strong></td>
        <td><strong>Phonetic</strong></td>
        <td><strong>Orthographic</strong></td>
      </tr>
      <tr>
        <td><em>Nēr</em></td>
        <td>C?(V̆|V̄)C*</td>
        <td>(<sup>i</sup>V̆|<sup>i</sup>V̄|<u>CV̆</u>|<u>CV̄</u>)C*
        </td>
      </tr>
      <tr>
        <td><em>Nirai</em></td>
        <td>C?V̆C(V̆|V̄)C*</td>
        <td>(<sup>i</sup>V̆|<u>CV̆</u>)(<u>CV̆</u>|<u>CV̄</u>)C*
        </td>
      </tr>
    </table>
    <p class="text-body">
      <em>V̆ - short vowel ; V̄ - long vowel</em>
    </p>

    <p class="text-body">
      For example, <em>ka, kā, kal, kāl, u, uṉ</em> are all <em>nēr</em> metremes,
      where as <em>viḻā, paka, pakal, akār</em> are <em>nirai</em>.    </p>

    <h5>Metrical Foot</h5>

    <p class="text-body">
      A metrical foot can be composed of at most 4 metremes. Therefore, the
      number of possible metrical feet are 2<sup>1</sup> + 2<sup>2</sup> +
      2<sup>3</sup> + 2<sup>4</sup> combinations of <em>nēr</em> & <em>nirai</em>
      metremes.
    </p>

    <p class="text-body">Different classes of metrical feet for double metremes and triple
      metremes are shown below:</p>

    <p class="text-body">Denoting <span class="breve">⏑</span> as <em>nēr</em> and <span class="breve">—</span> as <em>nirai</em> :</p>

    <table cellspacing="5" style="float: left;" class="text-body">
      <tr>
        <td><span class="breve">⏑</span> <span class="breve">⏑</span></td>
        <td>Tēmā</td>
      </tr>
      <tr>
        <td><span class="breve">—</span> <span class="breve">⏑</span></td>
        <td>Puḷimā</td>
      </tr>
    </table>
      <table cellspacing="5" class="text-body">
        <tr>
        <td><span class="breve">⏑</span> <span class="breve">—</span></td>
        <td>Kūviḷam</td>
        </tr>
        <tr>
          <td><span class="breve">—</span> <span class="breve">—</span></td>
          <td>Karuviḷam</td>
        </tr>
      </table>
      <p class="text-body"></p>
      <table cellspacing="5" style="float: left;" class="text-body">
        <tr>
          <td><span class="breve">⏑</span> <span class="breve">⏑</span> <span class="breve">⏑</span></td>
          <td>Tēmāṅkāy</td>
        </tr>
        <tr>
          <td><span class="breve">—</span> <span class="breve">⏑</span> <span class="breve">⏑</span></td>
          <td>Puḷimāṅkāy</td>
        </tr>
        <tr>
          <td><span class="breve">⏑</span> <span class="breve">—</span> <span class="breve">⏑</span></td>
          <td>Kūviḷaṅkāy</td>
        </tr>
        <tr>
          <td><span class="breve">—</span> <span class="breve">—</span> <span class="breve">⏑</span></td>
          <td>Karuviḷaṅkāy</td>
        </tr>
      </table>
        <table cellspacing="5" class="text-body">
          <tr>
            <td><span class="breve">⏑</span> <span class="breve">⏑</span> <span class="breve">—</span></td>
            <td>Tēmāṅkaṉi</td>
          </tr>
          <tr>
            <td><span class="breve">—</span> <span class="breve">⏑</span> <span class="breve">—</span></td>
            <td>Puḷimāṅkaṉi</td>
          </tr>
          <tr>
            <td><span class="breve">⏑</span> <span class="breve">—</span> <span class="breve">—</span></td>
            <td>Kūviḷaṅkaṉi</td>
          </tr>
          <tr>
            <td><span class="breve">—</span> <span class="breve">—</span> <span class="breve">—</span></td>
            <td>Karuviḷaṅkaṉi</td>
          </tr>
        </table> <br/>
        <p class="text-body">Note that the names of the classes themselves parse into the feet
          pattern they represent.</p>

        <h5>Linkage</h5>
        <p class="text-body">The relation between preceding feet and the succeeding feet is
          termed as "linkage". The type of linkage is dependant on the class
          of preceding feet and the first metreme of the succeeding feet.

        <p class="text-body">
          For instance, <em>iyaṟcīr veṇṭaḷai</em> type of linkage occurs in the following cases :
        </p>
        <p class="text-body">[Preceeding metrical feet → Succeeding metreme]</p>
        <span class="breve">⏑</span> <span class="breve">⏑</span> → <span class="breve">—</span>
        <br /> <span class="breve">—</span> <span class="breve">⏑</span> → <span class="breve">—</span>
        <br /> <span class="breve">⏑</span> <span class="breve">—</span> → <span class="breve">⏑</span>
        <br /> <span class="breve">—</span> <span class="breve">—</span> → <span class="breve">⏑</span>
        <br />
        <br/>
        <p class="text-body">Tamil prosody defines 7 specific types of linkages that may occur
          between metrical feet.</p>

        <h5>Metrical Line</h5>

        <p class="text-body">A Metrical line can be composed of several metrical feet. There
          is no specific upper limit for the number of feet that may appear
          in a line, although it rarely exceeds beyond 8. In most cases, it is
          around 3 - 6 feet per line.</p>

        <h5>Ornamentation</h5>
        <p class="text-body"> In Tamil, <em>toṭai</em> refers to a collection of several forms of poetic devices - both phonetic ( e.g. <em>etukai, mōṉai</em>) and lexical ( e.g. <em>muraṇ</em> - Usage of lexically
        opposite pairs such as light & dark). Etymologically, it is derived from <em>toṭu</em>, meaning 'making a flower garland'. Similar to that of linking
        different flowers (and forming patterns), patterns can be formed in a verse composition, by means of words and syllables. </p>
        <p class="text-body">Ornamentation in Tamil Prosody mainly occurs as :</p>

        <ol class="text-body">
          <li><em>Mōṉai</em> - Alliteration of the first letter </li>

          <li><em>Etukai</em> - Rhyme on the second letter </li>

        </ol>

        <p class="text-body">These can occur between feet in the same line and also
          between first foot of the lines in a verse.</p>

        <h5>Alliteration</h5>
        <p class="text-body">Alliteration in Tamil prosody occurs in the first letter. Apart from
        homophones, few other pairs of phones such as
        <em>m-v</em>,
        <em>ñ-n</em>,
        <em>u-o</em> etc. are considered equivalent for purposes of
        alliteration. </p>
        <h5>Rhyme</h5>
        <p class="text-body">Rhyme on second Letter is the another type of ornamentation
          found in Tamil prosody. For a feet to be considered as rhyming with
          another feet, the second letter must belong to the same consonant
          class while the first letter must agree in vowel length.</p>

        <p class="text-body">
          <em>pāṭam - kūṭam </em> is considered to rhyme, while <em>kal
            - kāl</em> do not.
        </p>

        <h5>Metre</h5>
        <p class="text-body">Classical Tamil prosody has 4 types of "major" metres (along with
          their subtypes). They are:</p>
        <ol class="text-body">
          <li>Veṇpā </li>

          <li>Āciriyappā </li>

          <li>Kalippā </li>

          <li>Vañcippā </li>

        </ol>
        <p class="text-body">The rules governing these metres are mostly concerned with
          classes of metrical feet allowed, number of feet per line and
          type of linkage occurring.</p>
        <p class="text-body">Apart from these main metres, there are four more "minor" metres
          that are considered belonging to the family (iṉam) of the above
          metres. These are

        <p class="text-body">

        <ol class="text-body">
          <li>Veṇpāviṉam</li>

          <li>Āciriyappāviṉam</li>

          <li>Kalippāviṉam</li>

          <li>Vañcippāviṉam</li>

        </ol>
        <p class="text-body">
          These don't have very strict rules as compared to major metres.
          They may superficially resemble the major metres, for instance, by
          the number of the feet per line. In fact, in most cases, the only
          rules governing these "family" metres are number of metrical feet
          in each line of the verse. Though some metres like <em>Kalitturai</em>
          also impose additional rules, like the necessity of all lines
          rhyming in the second letter.
        </p>

        <p class="text-body">
          These are also several sub-types of the major and minor metres. <em>Veṇpā</em>,
          for example, has 5 subtypes.

        <p class="text-body">

        <h4>
          Sample Analysis
          </h4>
                  <p class="text-body">
For illustration purposes, let's consider a verse composed in the <em>Veṇpā</em>
          metre: </p>
          <p class="text-body">
            Of all Tamil metres, <em>Veṇpā</em> is usually considered the
            strictest with very rigid rules of composition.
          </p>
          <ul class="text-body">
            <li>A metrical feet may contain only two or three metremes. If
              three metremes, only <em>-kāy</em> class of metrical feet are
              allowed </li>

            <li>The verse may contain only <em>veṇṭaḷai</em> type of
              linkages </li>

            <li>All lines except the last should be composed of four feet, the
              last line being three feet. </li>

            <li>The last foot of the last line should fall under a special
              class (specific to this metre) </li>

          </ul>
          <hr />
          <p class="text-body">
            அகர முதல எழுத்தெல்லாம்
            ஆதி<br /> பகவன் முதற்றே உலகு
          </p>
          <p class="text-body">
            akara mutala eḻuttellām āti <br /> pakavaṉ mutaṟṟē
            ulaku
          </p>

          <h5>Scansion</h5>

          <p class="text-body">The above verse is scanned as follows:</p>

          <p class="text-body">
            அக/ர முத/ல எழுத்/தெல்/லாம்
            ஆ/தி<br /> பக/வன் முதற்/றே
            உல/கு
          </p>

          <p class="text-body">
            aka/ra muta/la eḻut/tel/lām ā/ti <br /> paka/vaṉ
            mutaṟ/ṟē ula/ku
          </p>

          <h5>Letters</h5>

          <p class="text-body">
            <sup>i</sup>V̆<u>CV̆</u>/<u>CV̆</u>&nbsp;<u>CV̆</u><u>CV̆</u>/<u>CV̆</u>&nbsp;<sup>i</sup>V̆<u>CV̆</u>C/<u>CV̆</u>C/<u>CV̄</u>C
            <sup>i</sup>V̄/<u>CV̆</u><br /> <u>CV̆</u><u>CV̆</u>/<u>CV̆</u>C&nbsp;<u>CV̆</u><u>CV̆</u>C/<u>CV̄</u>&nbsp;
            <sup>i</sup>V̆<u>CV̆</u>/<u>CV̆</u>
          </p>

          <h5>Metreme types</h5>

          <p class="text-body">
            nirai/nēr nirai/nēr nirai/nēr/nēr nēr/nēr<br /> nirai/nēr
            nirai/nēr nirai/pu
          </p>
          <span class="breve">—</span><span class="breve">⏑</span>&nbsp;&nbsp;
          <span class="breve">—</span><span class="breve">⏑</span>&nbsp;&nbsp;
          <span class="breve">—</span><span class="breve">⏑</span><span class="breve">⏑</span>&nbsp;&nbsp;
          <span class="breve">⏑</span><span class="breve">⏑</span> <br/>
          <span class="breve">—</span><span class="breve">⏑</span>&nbsp;&nbsp;
          <span class="breve">—</span><span class="breve">⏑</span>&nbsp;&nbsp;
          <span class="breve">—</span><span class="breve">⏑</span><sup>u</sup>

          <h5>Metrical feet class</h5>

          <p class="text-body">
            puḷimā puḷimā puḷimāṅkāy tēmā<br /> puḷimā
            puḷimā piṟappu
          </p>

          <p class="text-body">
            (The last feet of the <em>Veṇpā</em> verse needs a slightly
            different scan and classification)
          </p>

          <h5>Linkage</h5>
          <p class="text-body">
            akara → mutala : <em>iyaṟcīr veṇṭaḷai </em>(puḷimā
            → nirai)<br /> mutala → eḻuttellām : <em>iyaṟcīr
              veṇṭaḷai</em> (puḷimā → nirai)<br /> eḻuttellām →
            āti : <em>veṇcīr veṇṭaḷai</em> (puḷimāṅkāy →
            nēr)<br /> āti → pakavaṉ : <em>iyaṟcīr veṇṭaḷai </em>(tēmā
            → nirai)<br /> pakavaṉ → mutaṟṟē : <em>iyaṟcīr
              veṇṭaḷai </em>(puḷimā → nirai)<br /> mutaṟṟē →
            ulaku :<em> iyaṟcīr veṇṭaḷai</em> (puḷimā → nirai)<br />
          </p>

          <h5>Ornamentation</h5>
          <p class="text-body">
            Alliteration at feet level: <strong>a</strong>kara - <strong>ā</strong>ti
          </p>
          <p class="text-body">
            Rhyme at line level : a<strong>ka</strong>ra - pa<strong>ka</strong>vaṉ
          </p>

          <p class="text-body">
            This verse satisfies all rules of the <em>Veṇpā</em> metre.
            Being of two metrical lines, it falls under the sub-type of <em>Kuṟaḷ
              Veṇpā</em>.
          </p>

          <h4>Reference</h4>
          <ol class="text-body">
            <li>Niklas, U. (1988). Introduction to Tamil prosody. <em>Bulletin
                de l'Ecole française d'Extrême-Orient</em>, 77(1), 165-227. </li>
            <li> <router-link to="/reference?view=rules"  target="_blank">Others</router-link></li>
          </ol>
  </q-page>
</template>

<script>
export default {
  // name: 'PageName',
  data () {
    return {
    }
  }
}
</script>

<style scoped>
h4, h5, h6 {
  margin-bottom: 10px;
}
.breve {
  font-size:200%;
}
</style>
