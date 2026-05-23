<?PHP
set_time_limit(300);
ini_set("memory_limit", "256M");
Header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once "parsetreeclass.php";

$LONG_VOWELS  = array('A','I','U','E','O','W','Y');
$SHORT_VOWELS = array('a','i','u','e','o');
$MONAI_GROUPS = array('J'=>'Jn','n'=>'Jn','m'=>'mv','v'=>'mv','t'=>'tc','c'=>'tc');

// ── Prepare source ────────────────────────────────────────────────────────
@$ptreeA = new ProsodyParseTree("", "", "");
$source      = trim(tam2lat($_GET['source']));
$sourceLen   = count(str_split($source, 2));
$sourceMatra = $ptreeA->GetMatraCount($source);
$todaiSel    = $_GET['todaiSel'];
$todaiSelN   = (int)$_GET['todaiSelN'];

// ── Bond lookup (replaces per-word ProsodyParseTree in talai filter) ─────
function getBond($srcVpLat, $candVpLat) {
    static $firstAsaiOf = null;
    if ($firstAsaiOf === null) {
        $tmp = new ProsodyParseTree("", "", "");
        $firstAsaiOf = array();
        foreach ($tmp->WordType as $seq => $vp) {
            if (!isset($firstAsaiOf[$vp]))
                $firstAsaiOf[$vp] = (substr($seq, 0, 4) == 'nE_r') ? 'nE_r' : 'nirY';
        }
    }
    $class2 = isset($firstAsaiOf[$candVpLat]) ? $firstAsaiOf[$candVpLat] : null;
    if (!$class2) return '';
    if (substr($srcVpLat, -2) == 'mA'     && $class2 == 'nE_r') return 'நேரொன்றிய ஆசிரியத்தளை';
    if (substr($srcVpLat, -6) == 'viLa_m' && $class2 == 'nirY') return 'நிரையொன்றிய ஆசிரியத்தளை';
    if ((substr($srcVpLat, -2) == 'mA'     && $class2 == 'nirY') ||
        (substr($srcVpLat, -6) == 'viLa_m' && $class2 == 'nE_r')) return 'இயற்சீர் வெண்டளை';
    if ((substr($srcVpLat, -4) == 'kA_y'   && $class2 == 'nE_r') ||
        (substr($srcVpLat, -2) == 'pU'     && $class2 == 'nE_r')) return 'வெண்சீர் வெண்டளை';
    if ((substr($srcVpLat, -4) == 'kaVi'   && $class2 == 'nirY') ||
        (substr($srcVpLat, -6) == 'niZa_l' && $class2 == 'nirY') ||
        (substr($srcVpLat, -6) == 'NiZa_l' && $class2 == 'nirY')) return 'ஒன்றிய வஞ்சித்தளை';
    if ((substr($srcVpLat, -4) == 'kaVi'   && $class2 == 'nE_r') ||
        (substr($srcVpLat, -6) == 'niZa_l' && $class2 == 'nE_r') ||
        (substr($srcVpLat, -6) == 'NiZa_l' && $class2 == 'nE_r')) return 'ஒன்றா வஞ்சித்தளை';
    if ((substr($srcVpLat, -4) == 'kA_y'   && $class2 == 'nirY') ||
        (substr($srcVpLat, -2) == 'pU'     && $class2 == 'nirY')) return 'கலித்தளை';
    return '';
}

// Build a sub-index from wordlist. Each APC key holds one todai type's buckets,
// storing full rows so requests never need to load more than one index.
function buildSubIndex($type, $n, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS) {
    $raw = explode("\n", file_get_contents('wordlistprocessed.txt'));
    $idx = array();
    foreach ($raw as $line) {
        $parts = explode(',', $line);
        if (count($parts) < 3) continue;
        $word    = trim($parts[0]);
        $vaypatu = trim($parts[1]);
        $matra   = trim($parts[2]);
        if (!$word) continue;
        $wordL = tam2lat($word);
        $wlen  = strlen($wordL);
        $row   = array($wordL, $word, $vaypatu, $matra);

        if ($type === 'flat') {
            $idx[] = $row;
        } elseif ($type === 'etukai' && $wlen > 2) {
            $v = substr($wordL, 1, 1);
            if      (in_array($v, $LONG_VOWELS))  $vc = 'L';
            elseif  (in_array($v, $SHORT_VOWELS)) $vc = 'S';
            else                                   $vc = null;
            if ($vc !== null) {
                $sec = substr($wordL, 2, 2);
                $sk  = (substr($sec, 0, 1) == '_') ? $sec : substr($sec, 0, 1);
                $idx[$vc . ':' . $sk][] = $row;
            }
        } elseif ($type === 'monai' && $wlen > 2) {
            $f  = substr($wordL, 0, 1);
            $mk = isset($MONAI_GROUPS[$f]) ? $MONAI_GROUPS[$f] : $f;
            $idx[$mk][] = $row;
        } elseif ($type === 'iyaipu' && $wlen >= 2) {
            $idx[substr($wordL, -2)][] = $row;
        } elseif ($type === 'first' && $wlen >= $n * 2) {
            $idx[substr($wordL, 0, $n * 2)][] = $row;
        } elseif ($type === 'last' && $wlen >= $n * 2) {
            $idx[substr($wordL, -($n * 2))][] = $row;
        }
    }
    return $idx;
}

// ── Resolve candidates ────────────────────────────────────────────────────
// Each branch fetches only the needed APC key, extracts its bucket, then
// frees the full index so peak PHP memory is ~50-80 MB rather than 512 MB.
$candidates     = array();
$useFlat        = false;
$needTodaiCheck = false;

if ($todaiSel == 'etukai') {
    $v = substr($source, 1, 1);
    if      (in_array($v, $LONG_VOWELS))  $vc = 'L';
    elseif  (in_array($v, $SHORT_VOWELS)) $vc = 'S';
    else                                   $vc = null;
    if ($vc !== null && strlen($source) > 2) {
        $sec = substr($source, 2, 2);
        $sk  = (substr($sec, 0, 1) == '_') ? $sec : substr($sec, 0, 1);
        $idx = apcu_fetch('idx_etukai', $found);
        if (!$found) { $idx = buildSubIndex('etukai', 0, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS); apcu_store('idx_etukai', $idx, 3600); }
        $candidates = isset($idx[$vc . ':' . $sk]) ? $idx[$vc . ':' . $sk] : array();
        unset($idx);
    }
} elseif ($todaiSel == 'monai') {
    $f  = substr($source, 0, 1);
    $mk = isset($MONAI_GROUPS[$f]) ? $MONAI_GROUPS[$f] : $f;
    $idx = apcu_fetch('idx_monai', $found);
    if (!$found) { $idx = buildSubIndex('monai', 0, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS); apcu_store('idx_monai', $idx, 3600); }
    $candidates = isset($idx[$mk]) ? $idx[$mk] : array();
    unset($idx);
} elseif ($todaiSel == 'iyaipu') {
    $ik = substr($source, -2);
    $idx = apcu_fetch('idx_iyaipu', $found);
    if (!$found) { $idx = buildSubIndex('iyaipu', 0, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS); apcu_store('idx_iyaipu', $idx, 3600); }
    $candidates = isset($idx[$ik]) ? $idx[$ik] : array();
    unset($idx);
} elseif ($todaiSel == 'first' && $todaiSelN >= 1 && $todaiSelN <= 3 && strlen($source) >= $todaiSelN * 2) {
    $fk     = substr($source, 0, $todaiSelN * 2);
    $apcKey = 'idx_first_' . $todaiSelN;
    $idx = apcu_fetch($apcKey, $found);
    if (!$found) { $idx = buildSubIndex('first', $todaiSelN, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS); apcu_store($apcKey, $idx, 3600); }
    $candidates = isset($idx[$fk]) ? $idx[$fk] : array();
    unset($idx);
} elseif ($todaiSel == 'last' && $todaiSelN >= 1 && $todaiSelN <= 3 && strlen($source) >= $todaiSelN * 2) {
    $lk     = substr($source, -($todaiSelN * 2));
    $apcKey = 'idx_last_' . $todaiSelN;
    $idx = apcu_fetch($apcKey, $found);
    if (!$found) { $idx = buildSubIndex('last', $todaiSelN, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS); apcu_store($apcKey, $idx, 3600); }
    $candidates = isset($idx[$lk]) ? $idx[$lk] : array();
    unset($idx);
} else {
    // todaiSel='none', or first/last with N out of range — iterate flat
    $useFlat        = true;
    $needTodaiCheck = ($todaiSel == 'first' || $todaiSel == 'last');
}

if ($useFlat) {
    $candidates = apcu_fetch('idx_flat', $found);
    if (!$found) { $candidates = buildSubIndex('flat', 0, $LONG_VOWELS, $SHORT_VOWELS, $MONAI_GROUPS); apcu_store('idx_flat', $candidates, 3600); }
}

// ── Optional vaypatu formula ──────────────────────────────────────────────
$formula = null;
if ($_GET['vaypatuSel'] == "அதே வாய்ப்பாடு") {
    $ptree2  = new ProsodyParseTree(trim($_GET['source']), 'ta', False);
    $formula = lat2tam($ptree2->ParseTreeRoot[0]['pA']['aTi-1']['cI_r-1']['meta']);
}

// ── Main filter ───────────────────────────────────────────────────────────
$matchList    = array();
$matchVpLat   = array(); // parallel: latin vaypatu for each matched word (used by talai filter)

foreach ($candidates as $row) {
    list($wordL, $word, $vaypatu, $wordMatra) = $row;
    $wordLen = count(str_split($wordL, 2));

    // Todai check only needed when falling back to flat with a todai constraint
    if ($needTodaiCheck) {
        if ($todaiSel == 'first')
            $todaiOk = substr($wordL, 0, $todaiSelN * 2) == substr($source, 0, $todaiSelN * 2);
        elseif ($todaiSel == 'last')
            $todaiOk = substr($wordL, -($todaiSelN * 2)) == substr($source, -($todaiSelN * 2));
        else
            $todaiOk = true;
    } else {
        $todaiOk = true;
    }

    // Letter count
    $lc = $_GET['letterCountSel'];
    if      ($lc == 'all')   $lcOk = true;
    elseif  ($lc == 'src')   $lcOk = ($wordLen == $sourceLen);
    elseif  ($lc == 'srcGt') $lcOk = ($wordLen > $sourceLen);
    elseif  ($lc == 'srcLs') $lcOk = ($wordLen < $sourceLen);
    elseif  ($lc == 'other') $lcOk = ($wordLen == (int)$_GET['letterCountSelN']);
    else $lcOk = true;

    // Matra count
    $mc = $_GET['matraCountSel'];
    if      ($mc == 'all')   $mcOk = true;
    elseif  ($mc == 'src')   $mcOk = ($wordMatra == $sourceMatra);
    elseif  ($mc == 'srcGt') $mcOk = ($wordMatra > $sourceMatra);
    elseif  ($mc == 'srcLs') $mcOk = ($wordMatra < $sourceMatra);
    elseif  ($mc == 'other') $mcOk = ($wordMatra == (int)$_GET['matraCountSelN']);
    else $mcOk = true;

    // Vaypatu
    $vp = $_GET['vaypatuSel'];
    if      ($vp == "அனைத்தும்")           $vpOk = true;
    elseif  ($vp == "அதே வாய்ப்பாடு")     $vpOk = ($formula == $vaypatu);
    else                                    $vpOk = ($vp == $vaypatu);

    if ($todaiOk && $lcOk && $mcOk && $vpOk) {
        $matchList[]  = $word;
        $matchVpLat[] = tam2lat($vaypatu);
    }
}

// ── Talai filter ──────────────────────────────────────────────────────────
$matchListTalai = array();
if ($_GET['talaiSel'] == "அனைத்தும்") {
    $matchListTalai = $matchList;
} else {
    $srcPtree   = new ProsodyParseTree(lat2tam($source), 'ta', False);
    $sourceVpLat = $srcPtree->ParseTreeRoot[0]['pA']['aTi-1']['cI_r-1']['meta'];
    foreach ($matchList as $i => $word) {
        $bond = getBond($sourceVpLat, $matchVpLat[$i]);
        if ($bond !== '' && strpos($bond, $_GET['talaiSel']) !== false)
            $matchListTalai[] = $word;
    }
}

echo json_encode($matchListTalai, JSON_UNESCAPED_UNICODE);
?>
