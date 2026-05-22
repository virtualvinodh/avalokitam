const VENPA_SHARED = `
SCANSION RULES:

Short
அ, இ, உ, எ, ஒ, ஐ (word internal and word end), ஔ (word internal and word end)
Long
ஆ, ஈ, ஊ, ஏ, ஓ, ஐ (Standalone, word beginning), ஔ (Standalone, word beginning)

நேர் syllable = single short vowel / long vowel / short+consonant cluster / long+consonant cluster
நிரை syllable = two short vowels / short+long / etc.

ALLOWED FOOT PATTERNS (syllable sequence → foot name):
நே நே → தேமா        நி நே → புளிமா
நே நி → கூவிளம்     நி நி → கருவிளம்
நே நே நே → தேமாங்காய்   நி நே நே → புளிமாங்காய்
நே நி நே → கூவிளங்காய்  நி நி நே → கருவிளங்காய்

LAST FOOT OF LAST LINE (ஈற்றடியின் ஈற்றுச்சீர்) — must be exactly one of:
நே       → நாள்    (one short syllable)
நி        → மலர்    (one long syllable)
நே + உ   → காசு    (one short syllable + a hard consonant க்/ச்/ட்/த்/ப்/ற் ending in உ)
நி + உ   → பிறப்பு (one long syllable + a hard consonant க்/ச்/ட்/த்/ப்/ற் ending in உ)

VALID வெண்டளை FOOT-TO-FOOT BONDS (all 32 legal pairs):

Rule: மா-type foot (ends நேர்) → next foot must start நிரை
  தேமா → புளிமா       தேமா → கருவிளம்       தேமா → புளிமாங்காய்    தேமா → கருவிளங்காய்
  புளிமா → புளிமா     புளிமா → கருவிளம்     புளிமா → புளிமாங்காய்  புளிமா → கருவிளங்காய்

Rule: விளம்-type foot (ends நிரை) → next foot must start நேர்
  கூவிளம் → தேமா      கூவிளம் → கூவிளம்      கூவிளம் → தேமாங்காய்    கூவிளம் → கூவிளங்காய்
  கருவிளம் → தேமா     கருவிளம் → கூவிளம்     கருவிளம் → தேமாங்காய்   கருவிளம் → கூவிளங்காய்

Rule: காய்-type foot (ends நேர்) → next foot must start நேர்
  தேமாங்காய் → தேமா      தேமாங்காய் → கூவிளம்      தேமாங்காய் → தேமாங்காய்    தேமாங்காய் → கூவிளங்காய்
  புளிமாங்காய் → தேமா    புளிமாங்காய் → கூவிளம்    புளிமாங்காய் → தேமாங்காய்  புளிமாங்காய் → கூவிளங்காய்
  கூவிளங்காய் → தேமா     கூவிளங்காய் → கூவிளம்     கூவிளங்காய் → தேமாங்காய்   கூவிளங்காய் → கூவிளங்காய்
  கருவிளங்காய் → தேமா    கருவிளங்காய் → கூவிளம்    கருவிளங்காய் → தேமாங்காய்  கருவிளங்காய் → கூவிளங்காய்

Any other foot pair is ILLEGAL — do not produce it.
`

const VERSE_TYPE_RULES = {
  venpaa: `வெண்பா (Venpa) Rules:
- Exactly 4 lines
- Lines 1, 2, 3: அளவடி — each must have exactly 4 feet (சீர்)
- Line 4: சிந்தடி — must have exactly 3 feet (சீர்)
- அடி type is determined purely by foot count: குறளடி=2, சிந்தடி=3, அளவடி=4, நெடிலடி=5
- Only வெண்டளை (ven-bond) is allowed between ALL feet throughout the verse
- Only ஈரசைச்சீர் (தேமா, புளிமா, கூவிளம், கருவிளம்) and காய்ச்சீர் (தேமாங்காய், புளிமாங்காய், கூவிளங்காய், கருவிளங்காய்) are allowed — except the last foot of the last line
- The last foot of the last line must be of type நாள், மலர், காசு, or பிறப்பு
- எதுகை (rhyme on the second letter/consonant) must be maintained across lines
${VENPA_SHARED}`,

  kuralpaa: `குறள் வெண்பா (Kural Venpa) Rules:
- Exactly 2 lines only
- Line 1: அளவடி — must have exactly 4 feet (சீர்)
- Line 2: சிந்தடி — must have exactly 3 feet (சீர்)
- அடி type is determined purely by foot count: குறளடி=2, சிந்தடி=3, அளவடி=4, நெடிலடி=5
- Only வெண்டளை (ven-bond) is allowed between ALL feet throughout the verse
- Only ஈரசைச்சீர் (தேமா, புளிமா, கூவிளம், கருவிளம்) and காய்ச்சீர் (தேமாங்காய், புளிமாங்காய், கூவிளங்காய், கருவிளங்காய்) are allowed — except the last foot of the last line
- The last foot of the last line must be of type நாள், மலர், காசு, or பிறப்பு
- எதுகை (rhyme on the second letter/consonant) must be maintained across lines
${VENPA_SHARED}`,

  aciriyappaa: `ஆசிரியப்பா (Aciriyappa) Rules:
- Lines can have varying feet counts (typically 4 per line)
- Only ஆசிரியத்தளை (aciriya-bond) is allowed between feet
- All types of சீர் are permitted
- எதுகை (rhyme on second letter) should be maintained`,

  kalippaa: `கலிப்பா (Kalippa) Rules:
- Lines typically have 4 feet
- கலித்தளை (kali-bond) is allowed
- Energetic and rhythmic metre`,

  vanjippaa: `வஞ்சிப்பா (Vanjippa) Rules:
- Lines typically have 2 feet
- வஞ்சித்தளை (vanji-bond) is used
- Flowing, gentle metre`
}

const BASE_SYSTEM_PROMPT = `You are an expert in classical Tamil literature and prosody (யாப்பிலக்கணம்) with deep knowledge of Sangam poetry, Thirukkural, and the prosodic treatise Yapparungalam.

ABSOLUTE RULES — never violate these:
1. Output ONLY the Tamil verse. No transliteration, no English, no explanations, no labels, no line numbers.
2. Each line of verse on its own line. Nothing else.`

function buildGeneratePrompt (topic, verseType) {
  const rules = VERSE_TYPE_RULES[verseType] || VERSE_TYPE_RULES['venpaa']
  return `${BASE_SYSTEM_PROMPT}

VERSE TYPE TO GENERATE: ${verseType.toUpperCase()}

${rules}

Now compose a ${verseType} about the topic: "${topic}"
Remember: output only the verse in Tamil, nothing else.`
}

function buildFixPrompt (verse, verseType, analysis = null) {
  const rules = VERSE_TYPE_RULES[verseType] || VERSE_TYPE_RULES['venpaa']
  const feedbackSection = analysis
    ? `\nThe prosody parser found these specific errors — fix them exactly as described:\n\n${analysis}`
    : ''
  return `${BASE_SYSTEM_PROMPT}

VERSE TYPE: ${verseType.toUpperCase()}

${rules}

The following Tamil verse has prosodic errors. Fix it while preserving its meaning as closely as possible:

${verse}
${feedbackSection}
Output only the corrected Tamil verse, nothing else.`
}

function buildFeedbackPrompt (verse, verseType, analysis, attempt, original) {
  const rules = VERSE_TYPE_RULES[verseType] || VERSE_TYPE_RULES['venpaa']
  return `${BASE_SYSTEM_PROMPT}

VERSE TYPE: ${verseType.toUpperCase()}

${rules}

${original}

Your attempt ${attempt}:
${verse}

The prosody parser found these errors — fix them exactly as described:

${analysis}

INSTRUCTIONS:
- Fix ONLY the feet and lines listed above. Do not alter correct feet.
- For each BOND ERROR: rewrite the foot(s) indicated so the bond becomes வெண்டளை.
- For each INVALID FOOT TYPE: replace that foot with a valid ஈரசைச்சீர் or காய்ச்சீர்.
- For each FOOT COUNT ERROR: add or remove சீர் in that line only.
- Preserve the verse meaning as closely as possible.
Output only the corrected Tamil verse, nothing else.`
}

function buildPolishPrompt (verse, verseType, original) {
  return `${BASE_SYSTEM_PROMPT}

VERSE TYPE: ${verseType.toUpperCase()}

${original}

The following verse has passed full metrical validation:
${verse}

THE METRE IS LOCKED. You must not change:
- The number of lines
- The number of feet (சீர்) in any line
- The syllable count of any foot
- The நேர்/நிரை sequence of any foot
- The வெண்டளை bonds between feet

You may ONLY make the smallest possible word substitutions where a word is clearly off-topic or nonsensical — replacing it with a synonym that has the EXACT SAME syllable pattern (same number of syllables, same நேர்/நிரை sequence).

If the verse already reads well and is on topic, output it UNCHANGED.
Output only the Tamil verse, nothing else.`
}

function buildSandhiAndExplainPrompt (verse) {
  return `You are an expert in classical Tamil literature.

The following Tamil verse has been composed and verified metrically:

${verse}

Provide two things:

1. SANDHI_SPLIT: Rewrite the verse with all sandhi (சந்தி) separated so each individual word is visible. Preserve the exact same number of lines.

2. MEANING: Explain the verse's meaning in simple modern Tamil in 2-3 sentences. No scansion analysis, no line-by-line breakdown, no English.

Output in exactly this format and nothing else:
SANDHI_SPLIT:
<sandhi-split verse>
MEANING:
<Tamil explanation>`
}

module.exports = { buildGeneratePrompt, buildFixPrompt, buildFeedbackPrompt, buildPolishPrompt, buildSandhiAndExplainPrompt }
