<?PHP
set_time_limit(300);
ini_set("memory_limit", "512M");
Header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once "parsetreeclass.php";

$LONG_VOWELS  = array('A','I','U','E','O','W','Y');
$SHORT_VOWELS = array('a','i','u','e','o');
$MONAI_GROUPS = array('J'=>'Jn','n'=>'Jn','m'=>'mv','v'=>'mv','t'=>'tc','c'=>'tc');

// ── Build or fetch pre-indexed word table ─────────────────────────────────
// flat: array of [wordL, word, vaypatu, matra]
// etukai/monai: array of integer offsets into flat
$index = apc_fetch('wordindex', $found);
if (!$found) {
    $raw = explode("\n", file_get_contents('wordlistprocessed.txt'));
    $flat = array(); $etukaiIdx = array(); $monaiIdx = array();

    $iyaipuIdx = array();
    $firstIdx  = array(1 => array(), 2 => array(), 3 => array());
    $lastIdx   = array(1 => array(), 2 => array(), 3 => array());

    foreach ($raw as $line) {
        $parts = explode(',', $line);
        if (count($parts) < 3) continue;
        $word    = trim($parts[0]);
        $vaypatu = trim($parts[1]);
        $matra   = trim($parts[2]);
        if (!$word) continue;

        $wordL = tam2lat($word);
        $wlen  = strlen($wordL);
        $i = count($flat);
        $flat[] = array($wordL, $word, $vaypatu, $matra);

        // etukai key: vowel class + second consonant
        if ($wlen > 2) {
            $v = substr($wordL, 1, 1);
            if      (in_array($v, $LONG_VOWELS))  $vc = 'L';
            elseif  (in_array($v, $SHORT_VOWELS)) $vc = 'S';
            else                                   $vc = null;

            if ($vc !== null) {
                $sec = substr($wordL, 2, 2);
                $sk  = (substr($sec, 0, 1) == '_') ? $sec : substr($sec, 0, 1);
                $ek  = $vc . ':' . $sk;
                if (!isset($etukaiIdx[$ek])) $etukaiIdx[$ek] = array();
                $etukaiIdx[$ek][] = $i;
            }

            // monai key: first consonant, normalized by varga
            $f  = substr($wordL, 0, 1);
            $mk = isset($MONAI_GROUPS[$f]) ? $MONAI_GROUPS[$f] : $f;
            if (!isset($monaiIdx[$mk])) $monaiIdx[$mk] = array();
            $monaiIdx[$mk][] = $i;
        }

        // iyaipu: last syllable (last 2 chars)
        if ($wlen >= 2) {
            $ik = substr($wordL, -2);
            if (!isset($iyaipuIdx[$ik])) $iyaipuIdx[$ik] = array();
            $iyaipuIdx[$ik][] = $i;
        }

        // first N syllables (N=1,2,3)
        for ($n = 1; $n <= 3; $n++) {
            if ($wlen >= $n * 2) {
                $fk = substr($wordL, 0, $n * 2);
                if (!isset($firstIdx[$n][$fk])) $firstIdx[$n][$fk] = array();
                $firstIdx[$n][$fk][] = $i;
            }
        }

        // last N syllables (N=1,2,3)
        for ($n = 1; $n <= 3; $n++) {
            if ($wlen >= $n * 2) {
                $lk = substr($wordL, -($n * 2));
                if (!isset($lastIdx[$n][$lk])) $lastIdx[$n][$lk] = array();
                $lastIdx[$n][$lk][] = $i;
            }
        }
    }

    $index = array(
        'flat'   => $flat,
        'etukai' => $etukaiIdx,
        'monai'  => $monaiIdx,
        'iyaipu' => $iyaipuIdx,
        'first'  => $firstIdx,
        'last'   => $lastIdx
    );
    apc_store('wordindex', $index, 3600);
}

// ── Prepare source ────────────────────────────────────────────────────────
@$ptreeA = new ProsodyParseTree("", "", "");
$source      = trim(tam2lat($_GET['source']));
$sourceLen   = count(str_split($source, 2));
$sourceMatra = $ptreeA->GetMatraCount($source);
$todaiSel    = $_GET['todaiSel'];

// ── Resolve candidate indices ─────────────────────────────────────────────
if ($todaiSel == 'etukai') {
    $v = substr($source, 1, 1);
    if      (in_array($v, $LONG_VOWELS))  $vc = 'L';
    elseif  (in_array($v, $SHORT_VOWELS)) $vc = 'S';
    else                                   $vc = null;
    if ($vc !== null && strlen($source) > 2) {
        $sec = substr($source, 2, 2);
        $sk  = (substr($sec, 0, 1) == '_') ? $sec : substr($sec, 0, 1);
        $ek  = $vc . ':' . $sk;
        $candidateIdx = isset($index['etukai'][$ek]) ? $index['etukai'][$ek] : array();
    } else {
        $candidateIdx = array();
    }
    $useFlat = false;
} elseif ($todaiSel == 'monai') {
    $f  = substr($source, 0, 1);
    $mk = isset($MONAI_GROUPS[$f]) ? $MONAI_GROUPS[$f] : $f;
    $candidateIdx = isset($index['monai'][$mk]) ? $index['monai'][$mk] : array();
    $useFlat = false;
} elseif ($todaiSel == 'iyaipu') {
    $ik = substr($source, -2);
    $candidateIdx = isset($index['iyaipu'][$ik]) ? $index['iyaipu'][$ik] : array();
    $useFlat = false;
} elseif ($todaiSel == 'first') {
    $n = (int)$_GET['todaiSelN'];
    if ($n >= 1 && $n <= 3 && strlen($source) >= $n * 2) {
        $fk = substr($source, 0, $n * 2);
        $candidateIdx = isset($index['first'][$n][$fk]) ? $index['first'][$n][$fk] : array();
        $useFlat = false;
    } else {
        $useFlat = true;
    }
} elseif ($todaiSel == 'last') {
    $n = (int)$_GET['todaiSelN'];
    if ($n >= 1 && $n <= 3 && strlen($source) >= $n * 2) {
        $lk = substr($source, -($n * 2));
        $candidateIdx = isset($index['last'][$n][$lk]) ? $index['last'][$n][$lk] : array();
        $useFlat = false;
    } else {
        $useFlat = true;
    }
} else {
    $useFlat = true;
}

// ── Optional vaypatu formula ──────────────────────────────────────────────
$formula = null;
if ($_GET['vaypatuSel'] == "அதே வாய்ப்பாடு") {
    $ptree2  = new ProsodyParseTree(trim($_GET['source']), 'ta', False);
    $formula = lat2tam($ptree2->ParseTreeRoot[0]['pA']['aTi-1']['cI_r-1']['meta']);
}

// ── Main filter ───────────────────────────────────────────────────────────
$flat = $index['flat'];
$matchList = array();

$iterate = $useFlat ? $flat : $candidateIdx;

foreach ($iterate as $item) {
    if ($useFlat) {
        $row = $item;
    } else {
        $row = $flat[$item];
    }
    list($wordL, $word, $vaypatu, $wordMatra) = $row;
    $wordLen = count(str_split($wordL, 2));

    // Todai check for non-indexed types
    if ($useFlat && $todaiSel == 'iyaipu')
        $todaiOk = $ptreeA->checkIyaipu($source, $wordL);
    elseif ($useFlat && $todaiSel == 'first')
        $todaiOk = substr($wordL, 0, $_GET['todaiSelN']*2) == substr($source, 0, $_GET['todaiSelN']*2);
    elseif ($useFlat && $todaiSel == 'last')
        $todaiOk = substr($wordL, -$_GET['todaiSelN']*2) == substr($source, -$_GET['todaiSelN']*2);
    else
        $todaiOk = true; // pre-filtered by index, or 'none'

    // Letter count
    $lc = $_GET['letterCountSel'];
    if      ($lc == 'all')   $lcOk = true;
    elseif  ($lc == 'src')   $lcOk = ($wordLen == $sourceLen);
    elseif  ($lc == 'srcGt') $lcOk = ($wordLen > $sourceLen);
    elseif  ($lc == 'srcLs') $lcOk = ($wordLen < $sourceLen);
    elseif  ($lc == 'other') $lcOk = ($wordLen == $_GET['letterCountSelN']);
    else $lcOk = true;

    // Matra count
    $mc = $_GET['matraCountSel'];
    if      ($mc == 'all')   $mcOk = true;
    elseif  ($mc == 'src')   $mcOk = ($wordMatra == $sourceMatra);
    elseif  ($mc == 'srcGt') $mcOk = ($wordMatra > $sourceMatra);
    elseif  ($mc == 'srcLs') $mcOk = ($wordMatra < $sourceMatra);
    elseif  ($mc == 'other') $mcOk = ($wordLen == $_GET['matraCountSelN']);
    else $mcOk = true;

    // Vaypatu
    $vp = $_GET['vaypatuSel'];
    if      ($vp == "அனைத்தும்")           $vpOk = true;
    elseif  ($vp == "அதே வாய்ப்பாடு")     $vpOk = ($formula == $vaypatu);
    else                                    $vpOk = ($vp == $vaypatu);

    if ($todaiOk && $lcOk && $mcOk && $vpOk)
        $matchList[] = $word;
}

// ── Talai filter ──────────────────────────────────────────────────────────
$matchListTalai = array();
if ($_GET['talaiSel'] == "அனைத்தும்") {
    $matchListTalai = $matchList;
} else {
    foreach ($matchList as $word) {
        @$ptree = new ProsodyParseTree(lat2tam($source) . " " . $word, "", "");
        $bond   = trim($ptree->WordBond[0]['bond']);
        if (strpos($bond, $_GET['talaiSel']) !== false)
            $matchListTalai[] = $word;
    }
}

echo json_encode($matchListTalai, JSON_UNESCAPED_UNICODE);
?>
