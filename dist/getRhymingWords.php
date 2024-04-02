<?PHP

/*
 * Copyright (C) 2015 Vinodh Rajan vinodh@virtualvinodh.com
 *
 * This file is a part
 * of Avalokitam. Avalokitam is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version. This program is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General
 * Public License for more details. You should have received a copy of the GNU
 * Affero General Public License along with this program. If not, see
 * <http://www.gnu.org/licenses/>.
 */

set_time_limit(300); // run it for 5 minutes

Header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

 require_once "parsetreeclass.php";

 @$ptreeA = new ProsodyParseTree ("", "", "");

 # The Wordlist was extracted from Wiktionary
 $wordListCont = file_get_contents('wordlistprocessed.txt');
 $wordList = explode("\n",$wordListCont);

 $source = trim(tam2lat($_GET['source']));
 $sourceSyll = str_split($source,2);
 $sourceLen = count($sourceSyll);
 $sourceMatra = $ptreeA->GetMatraCount($source);

if($_GET['vaypatuSel'] == "அதே வாய்ப்பாடு") {
 		$ptree2 = new ProsodyParseTree ( trim ($_GET['source']), 'ta', False );
   	$formula = lat2tam($ptree2->ParseTreeRoot[0]['pA']['aTi-1']['cI_r-1']['meta']);
	}

 $matchList = [];

#$wordList = array_splice($wordList,0,2000);

 foreach($wordList as $wordPair)
 {
	$wordSplit = explode(",",$wordPair);
	$word = trim($wordSplit[0]);
	$vaypatu = trim($wordSplit[1]);
	$wordMatra = trim($wordSplit[2]);

	$wordL = tam2lat($word);
	$wordSyll = str_split($wordL,2);
	$wordLen = count($wordSyll);

	# Selectors based on options

	if($_GET['todaiSel'] == "none")
		$todaiSel = True;
	else if($_GET['todaiSel'] == "etukai")
		$todaiSel = $ptreeA->CheckEtukaiSpecialVarga($source,$wordL);
	else if($_GET['todaiSel'] == "monai")
		$todaiSel = $ptreeA->CheckMonaiVarga($source,$wordL);
	else if($_GET['todaiSel'] == "iyaipu")
		$todaiSel = $ptreeA->checkIyaipu($source,$wordL);
	else if($_GET['todaiSel'] == "first")
		$todaiSel = substr($wordL,0,$_GET['todaiSelN']*2) == substr($source,0,$_GET['todaiSelN']*2);
	else if($_GET['todaiSel'] == "last")
		$todaiSel = substr($wordL,-$_GET['todaiSelN']*2) == substr($source,-$_GET['todaiSelN']*2);

	if($_GET['letterCountSel'] == "all")
 		$letterCountSel = True;
 	else if($_GET['letterCountSel'] == "src")
 		$letterCountSel = ($wordLen == $sourceLen);
 	else if($_GET['letterCountSel'] == "srcGt")
 		$letterCountSel = ($wordLen > $sourceLen);
 	else if($_GET['letterCountSel'] == "srcLs")
 		$letterCountSel = ($wordLen < $sourceLen);
  	else if($_GET['letterCountSel'] == "other")
 		$letterCountSel = ($wordLen == $_GET['letterCountSelN']);

	if($_GET['matraCountSel'] == "all")
 		$matraCountSel = True;
 	else if($_GET['matraCountSel'] == "src")
 		$matraCountSel = ($wordMatra == $sourceMatra);
 	else if($_GET['matraCountSel'] == "srcGt")
 		$matraCountSel = ($wordMatra > $sourceMatra);
 	else if($_GET['matraCountSel'] == "srcLs")
 		$matraCountSel = ($wordMatra < $sourceMatra);
  	else if($_GET['matraCountSel'] == "other")
 		$letterCountSel = ($wordLen == $_GET['matraCountSelN']);

	if($_GET['vaypatuSel'] == "அனைத்தும்")
 		$vaypatuSel = True;
	else if($_GET['vaypatuSel'] == "அதே வாய்ப்பாடு")
 		$vaypatuSel = ($formula == $vaypatu);
 	else
 		$vaypatuSel = ($_GET['vaypatuSel'] == $vaypatu);

 	if($todaiSel && $letterCountSel && $matraCountSel && $vaypatuSel)
		$matchList[] = $word;
 }

$matchListTalai = [];

$bonds = "";

# Check for Linkage

if($_GET['talaiSel'] == "அனைத்தும்")
 	$matchListTalai = $matchList;
else
{
	foreach($matchList as $word)
	{
		@$ptree= new ProsodyParseTree (lat2tam($source)." ".$word, "", "");
		$bond = trim($ptree->WordBond[0]['bond']);
 		$talaiSel = (strpos($bond,$_GET['talaiSel']) !== False);

# 		$bonds .= $bond." ";

	 	if($talaiSel)
			$matchListTalai[] = $word;
	}

}

echo json_encode($matchListTalai, JSON_UNESCAPED_UNICODE);

?>