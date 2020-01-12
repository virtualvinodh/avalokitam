<?PHP

/*
 * Copyright (C) 2013 Vinodh Rajan vinodh@virtualvinodh.com
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

require_once "transliteration.php";
require_once "translation.php";
require_once "utilityfunctions.php";

error_reporting(0);

/**
 * Avalokitam - Tamil Prosody Analyzer
 *
 * @author Vinodh Rajan, vinodh@virtualviondh.com
 *
 */
class ProsodyParseTree

{
	/**
	 * The Root of the Parse Tree
	 *
	 * @var Iterative String Array
	 */
	public $ParseTreeRoot;

	public $OriginalInput;

	/**
	 * The Input Source Text
	 *
	 * @var String
	 */
	public $InputSourceText;

	// Variables for Metrical Information

	/**
	 * The Metre type of the Prosody Text
	 *
	 * @var String
	 */
	public $MetreType;
	/**
	 * The Total Lines in the Text
	 *
	 * @var Integer
	 */
	public $TotalLines;
	/**
	 * Contains the Talai Informatoin
	 *
	 * @var String Array
	 */
	public $WordBond;
	public $VenLastSyllable;
	public $LetterCount;
	public $LineClass;
	public $VikalpaCount;
	public $Lang;

	public $MetreErrors = array();

	public $VenpaaTypeExpl = "";

	// Reference arrays

	/**
	 * Reference Array of getting the Line Type
	 *
	 * @var array
	 */
	public $LineType = array (
			"",
			"taVi_cco_l",
			"kuRaLaTi",
			"ci_ntaTi",
			"_aLavaTi",
			"neTilaTi",
			"_aRucI_r_k kaZineTilaTi",
			"_eZucI_r_k kaZineTilaTi",
			"_e_NcI_r_k kaZineTilaTi",
			"_o_Vpati_ncI_r_k kaZineTilaTi",
			"pati_VcI_r_k kaZineTilaTi",
			"patiVoru cI_r_k kaZineTilaTi",
			"pa_VViru cI_r_k kaZineTilaTi",
			"patimU_VRu cI_r_k kaZineTilaTi",
			"patiVA_Vku cI_r_k kaZineTilaTi",
			"patiVY_ntu cI_r_k kaZineTilaTi",
			"patiVARu cI_r_k kaZineTilaTi",
			"patiVEZu cI_r_k kaZineTilaTi",
			"patiVe_TTu cI_r_k kaZineTilaTi",
			"pa_tto_Vpatu cI_r_k kaZineTilaTi",
			"_irupatu cI_r_k kaZineTilaTi",
			"_irupa_ttoru cI_r_k kaZineTilaTi",
			"_irupa_ttu _ira_NTu cI_r_k kaZineTilaTi",
			"_irupa_ttu mU_VRu cI_r_k kaZineTilaTi",
			"_irupa_ttu nA_Vku cI_r_k kaZineTilaTi"
	);
	public $SyllableTypes = array (
			"nE_r",
			"nirY"
	);

	/** Tolkaappiyam Line Classes **/

	public $TolLineClass = array (

		4 => "kuRaLaTi",
		5 => "kuRaLaTi",
		6 => "kuRaLaTi",
		7 => "ci_nta_Ti",
		8 => "ci_nta_Ti",
		9 => "ci_nta_Ti",
		10 => "aLavaTi",
		11 => "aLavaTi",
		12 => "aLavaTi",
		13 => "aLavaTi",
		14 => "aLavaTi",
		15 => "neTiLaTi",
		16 => "neTiLaTi",
		17 => "neTiLaTi",
		18 => "kaZineTiLaTi",
		19 => "kaZineTiLaTi",
		20 => "kaZineTiLaTi",
	);

	/**
	 * Reference Array for getting the Word Type
	 *
	 * @var String Array
	 */
	public $WordType = array (

			// Two Asais

			"nE_rnE_r" => "tEmA",
			"nirYnE_r" => "puLimA",
			"nE_rnirY" => "kUviLa_m",
			"nirYnirY" => "karuviLa_m",

			// Three Asais - Kay seers

			"nE_rnE_rnE_r" => "tEmA_GkA_y",
			"nirYnE_rnE_r" => "puLimA_GkA_y",
			"nE_rnirYnE_r" => "kUviLa_GkA_y",
			"nirYnirYnE_r" => "karuviLa_GkA_y",

			// Three Asais - Kani seers

			"nE_rnE_rnirY" => "tEmA_GkaVi",
			"nirYnE_rnirY" => "puLimA_GkaVi",
			"nE_rnirYnirY" => "kUviLa_GkaVi",
			"nirYnirYnirY" => "karuviLa_GkaVi",

			// Four Asais - Tanpuu seers

			"nE_rnE_rnE_rnE_r" => "tEmA_nta_NpU",
			"nirYnE_rnE_rnE_r" => "puLimA_nta_NpU",
			"nE_rnirYnE_rnE_r" => "kUviLa_nta_NpU",
			"nirYnirYnE_rnE_r" => "karuviLa_nta_NpU",

			// Four Asais - naRumpU seers

			"nE_rnE_rnirYnE_r" => "tEmAnaRu_mpU",
			"nirYnE_rnirYnE_r" => "puLimAnaRu_mpU",
			"nE_rnirYnirYnE_r" => "kUviLanaRu_mpU",
			"nirYnirYnirYnE_r" => "karuviLanaRu_mpU",

			// Four Asais - naRunizhal

			"nE_rnE_rnirYnirY" => "tEmAnaRuniZa_l",
			"nirYnE_rnirYnirY" => "puLimAnaRuniZa_l",
			"nE_rnirYnirYnirY" => "kUviLanaRuniZa_l",
			"nirYnirYnirYnirY" => "karuviLanaRuniZa_l",

			// Four Asais- Tannizhal

			"nE_rnE_rnE_rnirY" => "tEmA_nta_NNiZa_l",
			"nirYnE_rnE_rnirY" => "puLimA_nta_NNiZa_l",
			"nE_rnirYnE_rnirY" => "kUviLa_nta_NNiZa_l",
			"nirYnirYnE_rnirY" => "karuviLa_nta_NNiZa_l",

			// Singla ASai - Exceptions

			"nE_r" => "mA",
			"nirY" => "viLa_m"
	);
	public $VenpaaWordClass = array (

			"nE_r" => "nA_L",
			"nirY" => "mala_r",
			"nE_rpu" => "kAcu",
			"nirYpu" => "piRa_ppu"
	);

	/**
	 * Class Constructor
	 *
	 * @param String $ProsodyText
	 * @param String $InterfaceLang
	 */
	function __construct($ProsodyText, $InterfaceLang, $uyirU = False)

	{
		$ProsodyText = $this->splitText(trim ( tam2lat ( $ProsodyText ) ));

		$this->OriginalInput = $ProsodyText;

		if ($uyirU) {

			$ProsodyText = preg_replace ( "/([kcTpR]u)(\s)(_[aAiIuUeEoO])/", '($1)$2$3', $ProsodyText );
			$ProsodyText = preg_replace ( "/([kcTpR]u)(\s*\n)(_[aAiIuUeEoO])/", '($1)$2$3', $ProsodyText );
			$_POST ['ttxt'] = lat2tam ( $ProsodyText );
		}

		$this->InputSourceText = $ProsodyText; // Assigining Source Text to the
		                                       // Variable
		// split


		$this->LetterCount = $this->GetLetterCount ( $ProsodyText );
		$this->ParseTreeRoot [] = $this->GetTextSyllablePattern ( $ProsodyText );
		$this->VikalpaCount = $this->GetVikalpaCount ();
		$this->WordBond = $this->GetWordBond ( $this->ParseTreeRoot );
		$this->LineClass = $this->GetLineClass ( $this->ParseTreeRoot );
		$this->MetreType = $this->GetMetreType ( $this->ParseTreeRoot );

		$this->Lang = $InterfaceLang; // interface language
	}


	public function splitText($ProsodyText)  {
		$lines = explode(PHP_EOL, $ProsodyText);

		$newLines = array();
		$this->breaks = array();

		foreach ($lines as $index=>$line) {
			if (trim($line) == '') {
				$this->breaks[] = $index;
			} else {
				$newLines[] = $line;
			}
		}

		return implode(PHP_EOL, $newLines);
	}

	/**
	 * Returns the Various Count of Letters as an Array
	 *
	 * @param String $ProsodyText
	 * @return Associaative Array
	 */
	public function GetLetterCount($ProsodyText) {
		$TamilText = trim ( lat2tam ( $ProsodyText ) );

		$this->lat = "Vinodh" . $TamilText;

		/* Initialize Variables */

		$VowelCount = 0;
		$ConsonantVowelCount = 0;
		$VowelSignCount = 0;
		$AMeyCount = 0;
		$LetterCount = array ();
		$ShortCount = 0;
		$LongCount = 0;

		$VowelList = array (
				"அ",
				"ஆ",
				"இ",
				"ஈ",
				"உ",
				"ஊ",
				"எ",
				"ஏ",
				"ஐ",
				"ஒ",
				"ஓ",
				"ஔ"
		);
		$VowelSignList = array (
				"ா",
				"ி",
				"ீ",
				"ு",
				"ூ",
				"ெ",
				"ே",
				"ை",
				"ொ",
				"ோ",
				"ௌ"
		);
		$AMeyList = array (
				"க",
				"ங",
				"ச",
				"ஜ",
				"ஞ",
				"ட",
				"ண",
				"த",
				"ந",
				"ன",
				"ப",
				"ம",
				"ய",
				"ர",
				"ற",
				"ல",
				"ள",
				"ழ",
				"வ",
				"ஶ",
				"ஷ",
				"ஸ",
				"ஹ"
		);

		/*
		 * Count Vowels
		 */

		foreach ( $VowelList as $Vowel )
			$VowelCount += substr_count ( $TamilText, $Vowel );

			/*
		 * Count Mey-s
		 */

		$ConsonantCount = substr_count ( $TamilText, "்" );

		/*
		 * Count Aytham
		 */

		$AythamCount = substr_count ( $TamilText, "ஃ" );

		/*
		 * Count A Mey
		 */

		 /* Aikaara-kurukkam and Aukaara-kurukkam with Romamized Text */

		 /* ai - First Syllable - 1.5 Matra (hence Long)
		        - Middle and Final Syllable - 1 Matra (hence Short) */

		$ProsodyText = str_replace ( array (
						"W",
						"Y"
				), array (
						"B",
						"Q"
				), $ProsodyText ); // B-aukarakurukkam
				            // Q-Aikaarakurukkam

		$ProsodyText = preg_replace ( "/(\b.)B(\b.)/", "$1W", $ProsodyText );
		$ProsodyText = preg_replace ( "/(\b.)Q(\b.)/", "$1Y", $ProsodyText );

		$shortVowels = array("a","i","u","e","o","B","Q");
		$longVowels = array("A","I","U","E","O","Y","W");
		$extraLong = array("A_a","I_i","U_u","E_e","O_o","B_i");

		foreach ( $extraLong as $extra )
			{
				$LongCount += substr_count ( $ProsodyText, $extra );
				$ProsodyText = str_replace( $extra,"",$ProsodyText );
			}

		foreach ( $longVowels as $long )
			$LongCount += substr_count ( $ProsodyText, $long );


		foreach ( $shortVowels as $short )
			$ShortCount += substr_count ( $ProsodyText, $short );

		foreach ( $AMeyList as $amy )
			$AMeyCount += substr_count ( $TamilText, $amy );

		$ConsonantVowelCount = $AMeyCount - $ConsonantCount;

		$LetterCount ['Vowel'] = $VowelCount;
		$LetterCount ['Consonant'] = $ConsonantCount;
		$LetterCount ['ConsonantVowel'] = $ConsonantVowelCount;
		$LetterCount ['Aytham'] = $AythamCount;
		$LetterCount ['Short'] = $ShortCount;
		$LetterCount ['Long'] = $LongCount;

		return $LetterCount;
	}

	/* Returns the number of Matras in the word */
	public function GetMatraCount($word) {
		$Count1 = 0;
		$Count1half = 0;
		$Count2 = 0;

		$word = preg_replace("/^(.)Y$/", '*', $word);
		$word = preg_replace("/^(.)W$/", '&', $word);

		$word = preg_replace ( "/^(.)Y/", "$1#$2", $word );
		$word = preg_replace ( "/^(.)W/", "$1%$2", $word );

		$word = preg_replace ( "/(.)Y$/", "$1#$2", $word );
		$word = preg_replace ( "/(.)W$/", "$1%$2", $word );

		$word = preg_replace ( "/(.)Y(.)/", "$1B$1", $word );
		$word = preg_replace ( "/(.)W(.)/", "$1Q$2", $word );

		$matra1 = array("a","i","u","e","o","B","Q");
		$matra1half = array("#","%");
		$matra2 = array("A","I","U","E","O","*","&"); // Complete ai's and au's
		$matrahalf = "_";

		foreach($matra1 as $letter)
		{
			$Count1 += substr_count ( $word, $letter );
			$word = str_replace("_".$letter,"",$word);
		}

		foreach($matra1half as $letter)
		{
			$Count1half += substr_count ( $word, $letter );
			$word = str_replace("_".$letter,"",$word);
		}

		foreach($matra2 as $letter)
		{
			$Count2 += substr_count ( $word, $letter );
			$word = str_replace("_".$letter,"",$word);
		}

		$Counthalf = substr_count($word,$matrahalf);

		$MatraCount = ($Count1 * 1) + ($Count1half * 1.5) + ($Count2 * 2) + ($Counthalf * 0.5);

		return $MatraCount;

	}

	/**
	 * Displays the Count of Letters
	 */
	public function DisplayLetterCount() {
	}

	/** Display count of Tokappiyam Lineclasses **/

	public function DisplayLineClassTol() {
	}

	/**
	 * Displays the analysis as XML
	 */
	public function DisplayXML($venCheck=False) {
		$verseXML = new SimpleXMLElement ( "<?xml version=\"1.0\" encoding=\"utf-8\" ?><verse></verse>" );
		$verseXML->addAttribute ( 'metre', lancon ( lat2tam ( $this->MetreType ), $this->Lang ) );

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $this->ParseTreeRoot ), RecursiveIteratorIterator::SELF_FIRST );

		$feetCount = 0;
		$lineCount = 0;

		$alliterations = array();
		$alliterations['special'] = $this->DisplayTodai ( "CheckMonaiSpecial" );
		$alliterations['varga'] = $this->DisplayTodai ( "CheckMonaiVarga" );
		$alliterations['inam'] = $this->DisplayTodai ( "CheckMonaiInam" );
		$alliterations['nedil'] = $this->DisplayTodai ( "CheckMonaiNedil" );

		$rhymes = array();
		$rhymes['special'] = $this->DisplayTodai ( "CheckEtukai" );
		$rhymes['varga'] = $this->DisplayTodai ( "CheckEtukaiVarga" );
		$rhymes['acitai'] = $this->DisplayTodai ( "CheckEtukaiAcitai" );
		$rhymes['inam'] = $this->DisplayTodai ( "CheckEtukaiInam" );
		$rhymes['uyir'] = $this->DisplayTodai ( "CheckEtukaiUyir" );
		$rhymes['nedil'] = $this->DisplayTodai ( "CheckEtukaiNedil" );

		$rhyme_ultima = $this->DisplayTodai ( "CheckIyaipu" );

		$phone = $verseXML->addChild ( 'Letter' );
		$phone->addAttribute ( "InitialVowels", $this->LetterCount ['Vowel'] );
		$phone->addAttribute ( "PureConsonants", $this->LetterCount ['Consonant'] );
		$phone->addAttribute ( "ConsonantVowels", $this->LetterCount ['ConsonantVowel'] );
		$phone->addAttribute ( "Aytham", $this->LetterCount ['Aytham'] );
		$phone->addAttribute ( "Short", $this->LetterCount ['Short'] );
		$phone->addAttribute ( "Long", $this->LetterCount ['Long'] );

		$VenpaaIndicator = strpos ( $this->MetreType, "ve_NpA" ) != FALSE;

		foreach ( $rit as $key => $value ) {

			if ($rit->getDepth () > 1) {

				if (substr ( $key, 0, 3 ) == "aTi") {
					$line = $verseXML->addChild ( 'MetricalLine' );
				}

				if (substr ( $key, 0, 4 ) == "cI_r") {
					$feet = $line->addChild ( 'MetricalFoot' );
					$metremeCount = 0;
				}

				if (substr ( $key, 0, 3 ) == "acY") {
					$metremeCount ++;

					if (isset ( $value ['nE_r'] )) {
						$metreme = $feet->addChild ( "Metreme", lancon ( lat2tam ( $value ['nE_r'] ), $this->Lang ) );

						if (($lineCount == $this->TotalLines - 1 && $lineFeetCount == 3 && $metremeCount == 2 && !isset($this->VenpaError ['final'] [1])) && ($VenpaaIndicator || $venCheck))
							$metreme->addAttribute ( 'type', lancon ( lat2tam ( 'pu' ), $this->Lang ) );
						else
							$metreme->addAttribute ( 'type', lancon ( lat2tam ( 'nE_r' ), $this->Lang ) );
					}

					if (isset ( $value ['nirY'] )) {
						$metreme = $feet->addChild ( "Metreme", lancon ( lat2tam ( $value ['nirY'] ), $this->Lang ) );
						$metreme->addAttribute ( 'type', lancon ( lat2tam ( 'nirY' ), $this->Lang ) );
					}
				}

				if ($key == "meta") {
					if (($lineCount == $this->TotalLines - 1 && $lineFeetCount == 3 && !isset($this->VenpaError ['final'] [1]) ) && ( $VenpaaIndicator || $venCheck))
						$feet->addAttribute ( 'class', lancon ( lat2tam ( $this->VenpaaWordClass [$this->VenLastSyllable] ), $this->Lang ) );
					else
						$feet->addAttribute ( 'class', lancon ( lat2tam ( $value ), $this->Lang ) );

					if ($feetCount > 0)
						$feet->addAttribute ( 'linkage', lancon ( lat2tam ( $this->WordBond [$feetCount - 1] ['bond'] ), $this->Lang ) );

					$feetCount += 1;
					$lineFeetCount += 1;
				}

				if ($key == "smeta") {
					$lineFeetCount = 1;

					$line->addAttribute ( 'type', lancon ( lat2tam ( $this->LineType [$value] ), $this->Lang ) );

					$alliterationCheck = count ( $alliterations ['special'] [0] [$lineCount] ) > 0 || count ( $alliterations ['varga'] [0] [$lineCount] ) > 0 || count ( $alliterations ['inam'] [0] [$lineCount] ) > 0 || count ( $alliterations ['nedil'] [0] [$lineCount] ) > 0;

					$rhymeCheck = count ( $rhymes ['special'] [0] [$lineCount] ) > 0 || count ( $rhymes ['varga'] [0] [$lineCount] ) > 0 || count ( $rhymes ['uyir'] [0] [$lineCount] ) > 0 || count ( $rhymes ['nedil'][0][$lineCount] ) > 0 || count ( $rhymes ['inam'][0][$lineCount] ) > 0 || count ( $rhymes ['acitai'][0][$lineCount] ) > 0;

					if ($alliterationCheck || $rhymeCheck || count ( $rhyme_ultima [0] [$lineCount] ) > 0)

					{
						$ornament = $line->addChild ( "Ornamentation" );

						foreach ($alliterations as $class => $alliteration) {
							if (count ( $alliteration [0] [$lineCount] ) > 0) {
								$alliterationX = $ornament->addChild ( "Alliteration" );

								foreach ( $alliteration [0] [$lineCount] [0] as $ind => $match ) {
									$match = $alliterationX->addChild ( "Match", $match );
									$match->addAttribute ( "foot", $ind + 1 );
								}
								$alliterationX->addAttribute ( 'type', $alliteration [0] [$lineCount] [1] );
								$alliterationX->addAttribute ( 'class', $class );
							}
						}

						foreach ($rhymes as $class => $rhyme) {
							if (count ( $rhyme [0] [$lineCount] ) > 0) {
								$rhymeX = $ornament->addChild ( "Rhyme" );
								foreach ( $rhyme [0] [$lineCount] [0] as $ind => $match ) {
									$match = $rhymeX->addChild ( "Match", $match );
									$match->addAttribute ( "foot", $ind + 1 );
								}

								$rhymeX->addAttribute ( 'type', $rhyme [0] [$lineCount] [1] );
								$rhymeX->addAttribute ( 'class', $class );
							}
						}


						if (count ( $rhyme_ultima [0] [$lineCount] ) > 0) {
							$rhymeX_ultima = $ornament->addChild ( "Ultima-Rhyme" );

							foreach ( $rhyme_ultima [0] [$lineCount] [0] as $ind => $match ) {
								$match = $rhymeX_ultima->addChild ( "Match", $match );
								$match->addAttribute ( "foot", $ind + 1 );
							}

							$rhymeX_ultima->addAttribute ( 'type', $rhyme_ultima [0] [$lineCount] [1] );
						}

					}

					$lineCount += 1;
				}
			}
		};

		$rhymeCheckLine = count ( $rhymes ['special'] [1]) > 0 || count ( $rhymes ['varga'] [1]) > 0 || count ( $rhymes ['uyir'] [1]) > 0 || count ( $rhymes ['nedil'][1]) > 0 || count ( $rhymes ['inam'][1]) > 0 || count ( $rhymes ['acitai'][1]) > 0;

		if (count ( $alliteration [1] ) > 0 || $rhymeCheckLine || count ( $rhyme_ultima [1] ) > 0) {
			$ornament = $verseXML->addChild ( "Ornamentation" );

			foreach ($alliterations as $class => $alliteration) {
				if (count ( $alliteration [1] ) > 0) {
					$alliterationX = $ornament->addChild ( "Alliteration" );

					foreach ( $alliteration [1] [0] [0] as $ind => $match ) {
						$match = $alliterationX->addChild ( "Match", $match );
						$match->addAttribute ( "line", $ind + 1 );
					}

						$alliterationX->addAttribute ( 'class', $class );

				}
			}

			foreach ($rhymes as $class => $rhyme) {
				if (count ( $rhyme [1] ) > 0) {
					$rhymeX = $ornament->addChild ( "Rhyme" );

					foreach ( $rhyme [1] [0] [0] as $ind => $match ) {
						$match = $rhymeX->addChild ( "Match", $match );
						$match->addAttribute ( "line", $ind + 1 );
					}

					$rhymeX->addAttribute ( 'class', $class );

					#print_r($rhymeX);
				}
			}

			if (count ( $rhyme_ultima [1] ) > 0) {
				$rhymeX_ultima = $ornament->addChild ( "Ultima-Rhyme" );

				foreach ( $rhyme_ultima [1] [0] [0] as $ind => $match ) {
					$match = $rhymeX_ultima->addChild ( "Match", $match );
					$match->addAttribute ( "line", $ind + 1 );
				}
			}

		}

		foreach ($this->MetreErrors as $errorName => $errors) {
			$venpaError = $verseXML->addChild ( str_replace(' ', '_', $errorName) );

			foreach ($errors as $error) {
				$venpaError->addChild ("Rule", $error[0]);
				$venpaError->addChild ("Result", $error[1]);
			}
		}

		$venpaError = $verseXML->addChild ( 'ActiveRules' );

			foreach ($this->ActiveRules as $error) {
				$venpaError->addChild ("Rule", $error[0]);
				$venpaError->addChild ("Result", $error[1]);
			}

		$MetreExplanatation = $verseXML->addChild ( "Explanation", $this->VenpaaTypeExpl);


		if ($this->VenLastWordCheck) {
			$VenpaLastWordClass = $verseXML->addChild ( "VenpaLastWordClass", lancon ( lat2tam ( $this->VenpaaWordClass [$this->VenLastSyllable]), $this->Lang));
		} else {
			$VenpaLastWordClass = $verseXML->addChild ( "VenpaLastWordClass", 'None');
		}

		$nonSpecialLineEtukai = $verseXML->addChild ( "NonSpecialLineEtukai", $this->nonSpecialLineEtukai);

		$dom = new DOMDocument ( '1.0' );
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML ( $verseXML->asXML () );

		return html_entity_decode ( $dom->saveXML () );
	}

	/**
	 * Returns the Class of each Prosody Line as an Array .
	 *
	 * ..
	 *
	 * @return multitype:String
	 *
	 */
	public function GetLineClass($ParseTreeRoot)

	{
		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $ParseTreeRoot ), RecursiveIteratorIterator::SELF_FIRST );

		foreach ( $rit as $key => $value )
			if ($rit->hasChildren () === FALSE)
				if ($key == "smeta")
					$LineClass [] = $this->LineType [$value];

		return $LineClass;
	}

	/**
	 * Displays the Line class
	 */
	public function DisplayLineClass() {
	}

	/**
	 * Displays the Vaypaadu, Asai & Seer information as a Table
	 */
	public function DisplaySyllableWordClass()
	{
	}

	/**
	 * Returns the Talai information as an Associative Array
	 *
	 * @param ParseTree $root
	 * @return multitype:string
	 */
	public function GetWordBond($root)

	{
		$SyllableBond = array ();

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::LEAVES_ONLY );

		$WordClass = array ();
		$Syllable = "";
		$Word = array ();
		$SyllableClass = array ();
		$FirstSyllable = TRUE;

		/*
		 * Push the Word with Divides, WordClass (Tema, Pulima, etc) & First
		 * Syllable Class (ner nirai) into two Arrays to calculate the Talai.
		 */

		foreach ( $rit as $key => $value ) {

			if ($key == "nE_r" || $key == "nirY") {

				$Syllable = $Syllable . $value . "/";

				if ($FirstSyllable) {
					array_push ( $SyllableClass, $key );
					$FirstSyllable = FALSE;
				}
			}

			if ($key == "meta") {
				array_push ( $WordClass, $value );
				array_push ( $Word, $Syllable );
				$Syllable = "";
				$FirstSyllable = TRUE;
			}
		}

		$Bond = "";

		/*
		 * Compare the Word Class & Syllable Class to calculate the Talai
		 */

		for($i = 0; $i < count ( $Word ) - 1; $i ++)

		{

			$Bond ['word1'] = $Word [$i];
			$Bond ['word2'] = $Word [$i + 1];
			$Bond ['class1'] = $WordClass [$i];
			$Bond ['class2'] = $SyllableClass [$i + 1];

			// $tl[$seer[$i]]=$vayp[$i];
			// $tl[$seer[$i+1]]=$asai[$i+1];

			if (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 2 ) == "mA" && ($SyllableClass [$i + 1] == "nE_r"))

			{
				$Bond ['bond'] = "நேரொன்றிய ஆசிரியத்தளை";
			}

			else if (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 6 ) == "viLa_m" && ($SyllableClass [$i + 1] == "nirY"))

			{

				$Bond ['bond'] = "நிரையொன்றிய ஆசிரியத்தளை";
			}

			else if ((substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 2 ) == "mA" && ($SyllableClass [$i + 1] == "nirY")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 6 ) == "viLa_m" && ($SyllableClass [$i + 1] == "nE_r")))

			{
				$Bond ['bond'] = "இயற்சீர் வெண்டளை";
			}

			else if ((substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 4 ) == "kA_y" && ($SyllableClass [$i + 1] == "nE_r")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 2 ) == "pU" && ($SyllableClass [$i + 1] == "nE_r")))

			{

				$Bond ['bond'] = "வெண்சீர் வெண்டளை";
			}

			else if ((substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 4 ) == "kaVi" && ($SyllableClass [$i + 1] == "nirY")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 6 ) == "niZa_l" && ($SyllableClass [$i + 1] == "nirY")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 6 ) == "NiZa_l" && ($SyllableClass [$i + 1] == "nirY")))

			{
				$Bond ['bond'] = "ஒன்றிய வஞ்சித்தளை";
			}

			else if ((substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 4 ) == "kaVi" && ($SyllableClass [$i + 1] == "nE_r")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 6 ) == "niZa_l" && ($SyllableClass [$i + 1] == "nE_r")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 6 ) == "NiZa_l" && ($SyllableClass [$i + 1] == "nE_r")))

			{
				$Bond ['bond'] = "ஒன்றா வஞ்சித்தளை";
			}

			else if ((substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 4 ) == "kA_y" && ($SyllableClass [$i + 1] == "nirY")) || (substr ( $WordClass [$i], strlen ( $WordClass [$i] ) - 2 ) == "pU" && ($SyllableClass [$i + 1] == "nirY")))

			{
				$Bond ['bond'] = "கலித்தளை";
			}

			$SyllableBond [] = $Bond;

			$Bond = "";
		}

		return $SyllableBond;
	}

	/**
	 * Displays Talai Information as a Table
	 */
	public function DisplayWordBond()
	{

	}

	/**
	 * Return the Metre of the Prosody
	 *
	 * @param unknown_type $root
	 * @return Ambigous <NULL, string>
	 */
	public function GetMetreType($root)

	{
		$Venpaa = $this->CheckVenpaa ();
		$Asiriyappaa = $this->CheckAsiyaripaa ();
		$Kalippaa = $this->CheckKalippaa ();
		$VenKalippaa = $this->CheckVenKalippaa ();
		$Vanjippaa = $this->CheckVanjippaa ();
		$VenInam = $this->CheckVenInam ();
		$AsiriyaInam = $this->CheckAsiriyaInam ();
		$VanjiInam = $this->CheckVanjiInam ();
		$KaliInam = $this->CheckKaliInam ();

		if ($Venpaa != NULL) {
			$MetreType = $Venpaa;
			$this->ActiveRules = $this->MetreErrors['venpaa'];
		}
		else if ($Asiriyappaa != NULL) {
			$MetreType = $Asiriyappaa;
			$this->ActiveRules = $this->MetreErrors['aciriyappaa'];
		}
		else if ($Kalippaa != NULL) {
			$MetreType = $Kalippaa;
			$this->ActiveRules = $this->MetreErrors['kalippaa'];
		}
		else if ($VenKalippaa != NULL) {
			$MetreType = $VenKalippaa;
			$this->ActiveRules = $this->MetreErrors['venkalippaa'];
		}
		else if ($Vanjippaa != NULL) {
			$MetreType = $Vanjippaa;
			$this->ActiveRules = $this->MetreErrors['vanjippaa'];
		}
		else if ($VenInam != NULL) {
			$MetreType = $VenInam;
			$this->ActiveRules = $this->MetreErrors[$VenInam];
		}
		else if ($AsiriyaInam != NULL) {
			$MetreType = $AsiriyaInam;
			if (strpos($MetreType, '_Aciriyaviru_tta_m'))
				$this->ActiveRules = $this->MetreErrors['_Aciriyaviru_tta_m'];
			else
				$this->ActiveRules = $this->MetreErrors[$MetreType];
		}
		else if ($KaliInam != NULL) {
			$MetreType = $KaliInam;
			$this->ActiveRules = $this->MetreErrors[$KaliInam];
		}
		else if ($VanjiInam != NULL) {
			$MetreType = $VanjiInam;
			$this->ActiveRules = $this->MetreErrors[$VanjiInam];
		}
		else {
			$MetreType = NULL;
			$this->ActiveRules = array();
			$this->ActiveRules[0][0] = 'யாதொரு பாவகையின் விதிகளும் பாவினத்தின் விதிகளும் பொருந்தவில்லை';
			$this->ActiveRules[0][1] = TRUE;

			$this->ActiveRules[1][0] = 'பொருத்தம்';
			$this->ActiveRules[1][1] = TRUE;
		}

		return $MetreType;
	}

	/**
	 * Check if the Prosody Metre is Venpaa - If Venpaa return the exact type,
	 * else return NULL
	 *
	 * @return string NULL
	 */
	public function CheckVenpaa() {

		// check and remove the eetrasai class variable
		$root = $this->ParseTreeRoot;

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$LineClassCheck = TRUE;
		$WordClassCheck = TRUE;

		$AllowedWordClass = array (
				"tEmA",
				"puLimA",
				"kUviLa_m",
				"karuviLa_m",
				"tEmA_GkA_y",
				"puLimA_GkA_y",
				"kUviLa_GkA_y",
				"karuviLa_GkA_y"
		); // ,"mA","viLa_m");

		/* Word Count */

		$cirs = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$wordCount = - 1;

		foreach ( $cirs as $key => $value )
			if ($key == 'meta')
				$wordCount += 1;

			/* Check for Allowed Seers, Line Count */

		$wrongWordClasses = array ();

		$wordIndex == 0;

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {

				if ($Line == 'meta') {
					$wordIndex += 1;

					/* Check for Allowed Seers */

					if ($wordIndex != $wordCount && ! in_array ( $Words, $AllowedWordClass )) {
						$WordClassCheck = FALSE;
						$wrongWordClasses [] = $Words;
					}
				}
			}

			# Check number of lines
			if($this->TotalLines < 2)
				$LineClassCheck = False;

			if ($rit->getDepth () == 2) {

				/* Check for Allowed Line Classes */

				if ($Line != ("aTi-" . $this->TotalLines) && $Words ['smeta'] != 4)
					$LineClassCheck = FALSE;

				if ($Line == ("aTi-" . ($this->TotalLines)) && $Words ['smeta'] != 3)
					$LineClassCheck = FALSE;

				if ($Line == ("aTi-" . $this->TotalLines))
					$LastLine = $Words;
			}
		}

		/*
		 * Getting Last Syllable to Check for Naal,Kaasu, Malar, Pirappu
		 * patterns
		 */
		foreach ( $LastLine as $key => $value )
			if ($key != "smeta")
				$LastWord = $value;

		$LastWord = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $LastWord ), RecursiveIteratorIterator::LEAVES_ONLY );

		$LastSyllable = array ();
		$LastSyllableClass = array ();

		foreach ( $LastWord as $key => $value ) {

			$LastSyllableClass [] = $key;
			$LastSyllable [] = $value;
		}

		$FinalSyllableClassCheck = FALSE;

		$this->VenLastWordCheck = TRUE;

		if (empty ( $LastSyllable [2] )) {

			$FinalSyllableClassCheck = TRUE;
		}

		else if (strlen ( $LastSyllable [1] ) == 2 && substr ( $LastSyllable [1], 1 ) == 'u')

		{

			$FinalSyllableClassCheck = TRUE;
			$LastSyllableClass [0] .= 'pu';
		} else {
			$this->VenLastWordCheck = FALSE;
		}

		$this->VenLastSyllable = $LastSyllableClass [0];

		/* Check for Metre Specific Talais */

		$WordBondClassCheck = TRUE;
		$Bond = array ();

		$wrongBonds = array ();

		foreach ( $this->WordBond as $Bond ) {
			$BondType = $Bond ['bond'];

			if (substr ( $BondType, strlen ( $BondType ) - 21 ) != "வெண்டளை") {
				$WordBondClassCheck = FALSE;
				$wrongBonds [] = $BondType;
			}
		}

		/* Classify the Metre */

		$this->VenpaError = "";

		// Logging and Classifying Errors

		$this->VenpaError [0] [0] = 'ஈற்றடியின் ஈற்றுச்சீரைத் தவிர்த்து ஈரசைச்சீர்களும் காய்ச்சீர்களும் மட்டுமே பயின்று வருதல் வேண்டும்';
		$this->VenpaError [1] [0] = 'வெண்டளைகள் மட்டுமே பயின்று வருதல் வேண்டும்';
		$this->VenpaError [2] [0] = 'ஈற்றடி மூன்று சீர்களும் ஏனைய அடிகள் நான்கு சீர்களும் கொண்டிருத்தல் வேண்டும்';
		$this->VenpaError [3] [0] = 'ஈற்றடியின் ஈற்றுச்சீர் நாள், மலர், காசு, பிறப்பு ஆகியவற்றுள் இருத்தல் வேண்டும்';

		// Bonds
		if (! $WordBondClassCheck) {
			$wrongBondsList = join ( ",", array_unique ( $wrongBonds ) );
			$this->VenpaError [1] [1] = 'பாவினுள் வெண்டளை அல்லாத ' . $wrongBondsList . ' பயின்று வந்துள்ளது(ன)';
		} else {
			$this->VenpaError [1] [1] = TRUE;
		}

		// WordClass
		if (! $WordClassCheck) {
			$wrongWordClassList = lat2tam ( join ( ",", array_unique ( $wrongWordClasses ) ) );
			$this->VenpaError [0] [1] = $wrongWordClassList . ' ஆகிய வாய்ப்பாடு(கள்) பயின்றுள்ளது(ன)';
		} else {
			$this->VenpaError [0] [1] = TRUE;
		}

		// LineClass
		$this->VenpaError [2] [1] = $LineClassCheck;

		// Final Syllable
		$this->VenpaError [3] [1] = $FinalSyllableClassCheck;

		$this->VenpaError [4] [0] = 'நேரிசையாகின் ஒருவிகற்பமோ அல்லது இருவிகற்பமோ கொண்டு எதுகை அமைந்த தனிச்சொல் பெற்று வருதல் வேண்டும். நேரிசை விதிகள் பொருந்தாத அனைத்தும் இன்னிசை ஆகும்.';;
		$this->VenpaError [4] [1] = 'info';

		$this->VenpaError [5] [0] = 'பொருத்தம்';;
		$this->VenpaError [5] [1] = $WordBondClassCheck && $FinalSyllableClassCheck && $LineClassCheck && $WordClassCheck;

		$this->MetreErrors['venpaa'] = $this->VenpaError;

		if ($WordBondClassCheck && $FinalSyllableClassCheck && $LineClassCheck && $WordClassCheck)

		{
			$Vikalpa = $this->VikalpaCount;

			$TaniccolExists = $this->CheckTaniccol ( $this->InputSourceText, 2, TRUE );

			$this->VenpaaTypeExpl = ( string ) $this->TotalLines . " அடிகளுடன் ";

			if ($Vikalpa == 1)
				$this->VenpaaTypeExpl .= ( string ) $Vikalpa . " விகற்பம் கொண்டு ";
			else
				$this->VenpaaTypeExpl .= ( string ) $Vikalpa . " விகற்பங்கள் கொண்டு ";

			if ($this->TotalLines == 3 || $this->TotalLines == 4) {
				if ($TaniccolExists)
					$this->VenpaaTypeExpl .= "எதுகை அமைந்த தனிச்சொல் பெற்று ";
				else
					$this->VenpaaTypeExpl .= "எதுகை அமைந்த தனிச்சொல் பெறாது ";
			}

			if ($this->TotalLines == 4 && $Vikalpa == 1 && $TaniccolExists == TRUE)
				$MetreType = "_oru vika_Rpa nEricY ve_NpA";
			else if ($this->TotalLines == 4 && $Vikalpa == 2 && $TaniccolExists == TRUE)
				$MetreType = "_iru vika_Rpa nEricY ve_NpA";
			else

			{
				$MetreType = "_i_VVicY ve_NpA";

				if ($Vikalpa == 1)
					$MetreType = "_oru vika_Rpa " . $MetreType;
				else
					$MetreType = "pala vika_Rpa " . $MetreType;
			}

			if ($this->TotalLines == 2 && $Vikalpa == 1)
				$MetreType = "_oru vika_Rpa_k kuRa_L ve_NpA";
			else if ($this->TotalLines == 2 && $Vikalpa == 2)
				$MetreType = "_iru vika_Rpa_k kuRa_L ve_NpA";
			else if ($this->TotalLines == 3 && $TaniccolExists == TRUE && $Vikalpa == 1)
				$MetreType = "_oru vika_Rpa nEricY ci_ntiya_l ve_NpA";
			else if ($this->TotalLines == 3 && $TaniccolExists == TRUE && $Vikalpa == 2)
				$MetreType = "_iru vika_Rpa nEricY ci_ntiya_l ve_NpA";
			else if ($this->TotalLines == 3 && $TaniccolExists == FALSE && $Vikalpa == 1)
				$MetreType = "_oru vika_Rpa _i_VVicY ci_ntiya_l ve_NpA";
			else if ($this->TotalLines == 3 && $TaniccolExists == FALSE && $Vikalpa > 1)
				$MetreType = "pala vika_Rpa _i_VVicY ci_ntiya_l ve_NpA";
			else if ($this->TotalLines > 4 && $this->TotalLines <= 12 && $Vikalpa == 1)
				$MetreType = "_oru vika_Rpa pa_KRoTY ve_NpA";
			else if ($this->TotalLines > 4 && $this->TotalLines <= 12 && $Vikalpa > 1)
				$MetreType = "pala vika_Rpa pa_KRoTY ve_NpA";
			else if ($this->TotalLines > 12) {
				$MetreType = "kalive_NpA";
				$this->VenpaaTypeExpl = "வெண்பா விதிகள் பொருந்தினாலும் 12 அடிகளுக்கு மேற்பட்டு ";
			}

			$this->VenpaaTypeExpl .= "வந்ததால் " . lancon ( lat2tam ( $MetreType ), $this->lang ) . " ஆயிற்று";

			return $MetreType;
		}

		else
			return NULL;
	}

		public function CheckVenpaaCheck($verse) {

		// check and remove the eetrasai class variable
		$TotalLines = 3;
		$ParseTreeRoot [] = $this->GetTextSyllablePattern ( $verse );
		$WordBond = $this->GetWordBond ( $ParseTreeRoot );

		$root = $ParseTreeRoot;

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$LineClassCheck = TRUE;
		$WordClassCheck = TRUE;

		$AllowedWordClass = array (
				"tEmA",
				"puLimA",
				"kUviLa_m",
				"karuviLa_m",
				"tEmA_GkA_y",
				"puLimA_GkA_y",
				"kUviLa_GkA_y",
				"karuviLa_GkA_y"
		); // ,"mA","viLa_m");

		/* Word Count */

		$cirs = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$wordCount = - 1;

		foreach ( $cirs as $key => $value )
			if ($key == 'meta')
				$wordCount += 1;

			/* Check for Allowed Seers, Line Count */

		$wrongWordClasses = array ();

		$wordIndex == 0;

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {

				if ($Line == 'meta') {
					$wordIndex += 1;

					/* Check for Allowed Seers */

					if ($wordIndex != $wordCount && ! in_array ( $Words, $AllowedWordClass )) {
						$WordClassCheck = FALSE;
						$wrongWordClasses [] = $Words;
					}
				}
			}

			# Check number of lines
			if($TotalLines < 2)
				$LineClassCheck = False;

			if ($rit->getDepth () == 2) {

				/* Check for Allowed Line Classes */

				if ($Line != ("aTi-" . $TotalLines) && $Words ['smeta'] != 4)
					$LineClassCheck = FALSE;

				if ($Line == ("aTi-" . ($TotalLines)) && $Words ['smeta'] != 3)
					$LineClassCheck = FALSE;

				if ($Line == ("aTi-" . $TotalLines))
					$LastLine = $Words;
			}
		}

		/*
		 * Getting Last Syllable to Check for Naal,Kaasu, Malar, Pirappu
		 * patterns
		 */
		foreach ( $LastLine as $key => $value )
			if ($key != "smeta")
				$LastWord = $value;

		$LastWord = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $LastWord ), RecursiveIteratorIterator::LEAVES_ONLY );

		$LastSyllable = array ();
		$LastSyllableClass = array ();

		foreach ( $LastWord as $key => $value ) {

			$LastSyllableClass [] = $key;
			$LastSyllable [] = $value;
		}

		$FinalSyllableClassCheck = FALSE;

		if (empty ( $LastSyllable [2] )) {

			$FinalSyllableClassCheck = TRUE;
		}

		else if (strlen ( $LastSyllable [1] ) == 2 && substr ( $LastSyllable [1], 1 ) == 'u')

		{
			$FinalSyllableClassCheck = TRUE;
		}

		/* Check for Metre Specific Talais */

		$WordBondClassCheck = TRUE;
		$Bond = array ();

		$wrongBonds = array ();

		foreach ( $WordBond as $Bond ) {
			$BondType = $Bond ['bond'];

			if (substr ( $BondType, strlen ( $BondType ) - 21 ) != "வெண்டளை") {
				$WordBondClassCheck = FALSE;
				$wrongBonds [] = $BondType;
			}
		}

		return $WordBondClassCheck && $FinalSyllableClassCheck && $LineClassCheck && $WordClassCheck;
	}

	// # Displaying Errors occured on Checking with Venpa Rules
	public function displayError($verseError) {
	}

	/**
	 * Check if the Prosody Metre is Asiriyapaa - If Asiriyappaa return the
	 * exact type, else return NULL
	 *
	 * @return string NULL
	 */
	public function CheckAsiyaripaa() {
		$root = $this->ParseTreeRoot;

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$WordClassCheck = TRUE;
		$FinalLastSyllableClassCheck = FALSE;
		$LineClassCheck = FALSE;

		$AllowedWordClassCount = 0;
		$TotalWordCount = 0;

		$DisAllowedWordClass = array (
				"karuviLa_GkaVi",
				"kUviLa_GkaVi"
		);
		$AllowedWordClass = array (
				"tEmA",
				"puLimA",
				"kUviLa_m",
				"karuviLa_m"
		);

		$AllowedMonoFinalLetters = array (
				"E",
				"O",
				"I",
				"Y",
				"Q"
		);
		$AllowedTriFinalLetters = array (
				"A_y",
				"e_V"
		);

		$LineWordCount = array ();

		/* Check for Allowed Word Class and DisAllowed Word Class */

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {

				if ($Line == 'meta') {

					$TotalWordCount ++;

					if (in_array ( $Words, $DisAllowedWordClass ))
						$WordClassCheck = FALSE;
					if (in_array ( $Words, $AllowedWordClass ))
						$AllowedWordClassCount ++;
				}
			}

			if ($rit->getDepth () == 2) {

				$LineWordCount [] = $Words ['smeta'];
			}
		}

		$this->AsiriyappaaError = array();

		/*
		 * Check for Final Syllable ending
		 */

		$FinalLettersMono = substr ( $this->InputSourceText, - 1 );
		$FinalLettersTri = substr ( $this->InputSourceText, - 3 );

		if (in_array ( $FinalLettersMono, $AllowedMonoFinalLetters ) || in_array ( $FinalLettersTri, $AllowedTriFinalLetters ))
			$FinalLastSyllableClassCheck = TRUE;

			/*
		 * Check for Line Count
		 */

		if ($this->TotalLines > 2)
			$LineClassCheck = TRUE;

			/*
		 * Check if Agavarseers are Majority
		 */
		$WordSyllableCheck = FALSE;

		if (($AllowedWordClassCount / $TotalWordCount) > 0.4)
			$WordSyllableCheck = TRUE;

		# Check if Asiriyattalai are Majority

		$totalBondCount = 0;
		$AciriBondCount = 0;

		foreach ( $this->WordBond as $Bond ) {
			$BondType = $Bond ['bond'];

			if (strpos ( $BondType, "ஆசிரியத்தளை") !== FALSE) {
				$AciriBondCount = $AciriBondCount + 1;
			}

			$totalBondCount = $totalBondCount + 1;

		}

		$TalaiCheck = ($AciriBondCount/$totalBondCount) > 0.4;

		$this->AsiriyappaaError[1][0] = 'குறைந்த பட்சம் மூன்றடிகள் பெற்றிருத்தல் வேண்டும்';
		$this->AsiriyappaaError[1][1] = $LineClassCheck;

		$this->AsiriyappaaError[2][0] = 'இயற்சீர்கள் மிகுந்து வருதல் வேண்டும் (அவலோகிதம் குறைந்தபட்சம் 40% எதிர்பார்க்கிறது)';
		$this->AsiriyappaaError[2][1] = $WordSyllableCheck;

		$this->AsiriyappaaError[3][0] = 'ஆசிரியத்தளை மிகுந்து வருதல் வேண்டும் (அவலோகிதம் குறைந்தபட்சம் 40% எதிர்பார்க்கிறது)';
		$this->AsiriyappaaError[3][1] = $TalaiCheck;

		$this->AsiriyappaaError[4][0] = 'ஈற்றடியின் ஈற்றசை ஏ, ஓ, ஈ, ஆய், ஐ அல்லது என் என்று முடிதல் வேண்டும்';
		$this->AsiriyappaaError[4][1] = $FinalLastSyllableClassCheck;

		$this->AsiriyappaaError[5][0] = 'கருவிளங்கனி, கூவிளங்கனி ஆகிய வாய்ப்படுகள் வருதல் கூடாது';
		$this->AsiriyappaaError[5][1] = $WordClassCheck;

			/*
		 * Find the Type of Asiriyappaa
		 */

		$AlavadiClass = TRUE;
		$NonAlavadiClassCount = array ();

		/*
		 * Validate the count of Words in Each line, to decide the type of
		 * Asiriyappa
		 */

		$Vikalpa = $this->VikalpaCount;
		$alavadiCheck = TRUE;
		$lineDistributionCheck = TRUE;
		$taniccolCheck = TRUE;

		for($LineIndex = 0; $LineIndex < count ( $LineWordCount ); $LineIndex ++) {
			if ($LineWordCount [$LineIndex] < 4) {
				$AlavadiClass = FALSE;
				$NonAlavadiClassCount [] = $LineIndex + 1;

				if ($LineIndex == 0 || $LineIndex == (count ( $LineWordCount ) - 1))
					$lineDistributionCheck = FALSE;
			}

			if ($LineWordCount [$LineIndex] > 4)
				$alavadiCheck = FALSE;

			if ($LineWordCount [$LineIndex] < 2)
				$taniccolCheck = FALSE;
		}

			/*
		 * Check if all Conditions are TRUE
		 */

		if ($WordClassCheck && $FinalLastSyllableClassCheck && $LineClassCheck && $WordSyllableCheck && $TalaiCheck && $alavadiCheck && $lineDistributionCheck && $taniccolCheck)
			$AsiriyappaaCheck = TRUE;
		else
			$AsiriyappaaCheck = FALSE;

		$this->AsiriyappaaError[6][0] = 'அளவடியை விட அதிகமாக சீர்கள் கொண்ட அடிகள் வருதல் கூடாது';
		$this->AsiriyappaaError[6][1] = $alavadiCheck;

		$this->AsiriyappaaError[7][0] = 'முதலடியும் ஈற்றடியும் அளவடியை விட குறைந்து வருதல் கூடாது. (இடையே அடிகள் குறைந்து வரலாம்)';
		$this->AsiriyappaaError[7][1] = $lineDistributionCheck;

		$this->AsiriyappaaError [8] [0] = 'இடையே அடிகள் குறைந்தால் சிந்தடி மற்றும் குறளடிகள் மட்டும் வருதல் வேண்டும்';
		$this->AsiriyappaaError [8] [1] =  $taniccolCheck;

		$this->AsiriyappaaError[9][0] = 'பொருத்தம்';
		$this->AsiriyappaaError[9][1] = $AsiriyappaaCheck;

		$this->MetreErrors['aciriyappaa'] = $this->AsiriyappaaError;

		if ($AsiriyappaaCheck)

		{

			/*
			 * If Vikalpa = 1 the it must be considered as Kali Vritta rather
			 * than Asiriyappaa
			 */

			if ($AlavadiClass) {
				if ($Vikalpa == 1 && count($LineWordCount) == 4)
					$MetreType = NULL;
				else {
					$MetreType = "nilYma_NTila _Aciriya_ppA";
					$this->VenpaaTypeExpl = "அனைத்து அடிகளும் அளவடிகளாக வந்ததினால் நிலைமண்டில ஆசிரியப்பா ஆயிற்று";
				}
			}

			else {
				if (count ( $NonAlavadiClassCount ) == 1 && $NonAlavadiClassCount [0] == ($this->TotalLines - 1)) {

					$MetreType = "nEricY _Aciriya_ppA";
					$this->VenpaaTypeExpl = "ஈற்றயலடி மட்டும் குறைந்து வந்ததினால் நேரிசை ஆசிரியப்பா ஆயிற்று";

				}

				else {

					$MetreType = "_iNY_kkuRa_L _Aciriya_ppA";
					$this->VenpaaTypeExpl = "ஈற்றயலடி தவிர்த்த பிற அடி(கள்) குறைந்து வந்ததினால் இணைக்குறள் ஆசிரியப்பா ஆயிற்று";

				}
			}

			return $MetreType;
		}

		else

			return NULL;
	}

	/**
	 * Check if the Prosody Metre is Kalipaa - If the metre matches return the
	 * exact type, else return NULL
	 *
	 * @return string NULL
	 */
	public function CheckKalippaa()

	{
		$root = $this->ParseTreeRoot;

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$LineClassCheck = TRUE;
		$WordClassCheck = TRUE;
		$WordSyllableClassCheck = FALSE;
		$LineCountCheck = FALSE;

		$DisAllowedWordClass = array (
				"karuviLa_GkaVi",
				"kUviLa_GkaVi",
				"tEmA",
				"puLimA"
		);
		$AllowedWordClass = array (
				"tEmA_GkA_y",
				"puLimA_GkA_y",
				"kUviLa_GkA_y",
				"karuviLa_GkA_y"
		);

		$TotalWordCount = 0;

		$AllowedWordClassCount = 0;

		/*
		 * Check for Line Count
		 */

		if ($this->TotalLines > 3)
			$LineCountCheck = TRUE;

			/*
		 * Check for Allowed Word Class
		 */

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {

				if ($Line == 'meta') {

					$TotalWordCount ++;

					if (in_array ( $Words, $DisAllowedWordClass ))
						$WordClassCheck = FALSE;
					if (in_array ( $Words, $AllowedWordClass ))
						$AllowedWordClassCount ++;
				}
			}

			if ($rit->getDepth () == 2) {

				if ($Words ['smeta'] != 4)
					$LineClassCheck = FALSE;
			}
		}

		$totalBondCount = 0;
		$KaliBondCount = 0;

		foreach ( $this->WordBond as $Bond ) {
			$BondType = $Bond ['bond'];

			if (strpos ( $BondType, "கலித்தளை") !== FALSE) {
				$KaliBondCount = $KaliBondCount + 1;
			}

			$totalBondCount = $totalBondCount + 1;

		}

		$TalaiCheck = $KaliBondCount/$totalBondCount > 0.4;


		$kalippaCheck = $WordClassCheck && $LineClassCheck && $TalaiCheck && $LineCountCheck;

		$this->kalippaError = array();

		$this->kalippaError[1][0] = 'குறைந்தபட்சம் நான்கடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->kalippaError[1][1] = $LineCountCheck;

		$this->kalippaError[2][0] = 'அளவடிகள் மட்டும் வருதல் வேண்டும்';
		$this->kalippaError[2][1] = $LineClassCheck;

		$this->kalippaError[3][0] = 'மாச்சீரும் விளங்கனிச்சீரும் வருதல் கூடாது';
		$this->kalippaError[3][1] = $WordClassCheck;

		$this->kalippaError[4][0] = 'கலித்தளை மிகுந்து வருதல் வேண்டும் (அவலோகிதம் குறைந்தபட்சம் 40% எதிர்பார்க்கிறது)';
		$this->kalippaError[4][1] = $TalaiCheck;

		$this->kalippaError[5][0] = 'பொருத்தம்';
		$this->kalippaError[5][1] = $kalippaCheck;

		$this->MetreErrors['kalippaa'] = $this->kalippaError;

		if ($kalippaCheck ) {
			$this->VenpaaTypeExpl .= "பிற உறுப்புகள் ஏதும் இன்றி ஒரு தரவு மட்டும் தனித்து வந்ததினால் தரவு கொச்சகக் கலிப்பா ஆயிற்று";
			return "taravu ko_ccaka_k kali_ppA";
		}
		else
			return NULL;
	}

	/**
	 * Check if the Prosody Metre is Kalivenpaa - If the metre matches return
	 * the exact type, else return NULL
	 *
	 * @return string NULL
	 */
	public function CheckVenKalippaa()

	{
		$root = $this->ParseTreeRoot;

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$LineClassCheck = TRUE;
		$WordClassCheck = TRUE;
		$WordSyllableClassCheck = FALSE;
		$VenkalippaaEndCheck = FALSE;
		$LineCountCheck = FALSE;

		$DisAllowedWordClass = array (
				"karuviLa_GkaVi",
				"kUviLa_GkaVi",
				"tEmA",
				"puLimA"
		);
		$NonFinalWordClass = array (
				"karuviLa_GkaVi",
				"kUviLa_GkaVi"
		);
		$AllowedWordClass = array (
				"tEmA_GkA_y",
				"puLimA_GkA_y",
				"kUviLa_GkA_y",
				"karuviLa_GkA_y"
		);

		$TotalWordCount = 0;
		$AllowedWordClassCount = 0;
		$FinalWords = array ();

		$LineIndex = 0;

		/*
		 * Check for Line Count
		 */

		if ($this->TotalLines > 3)
			$LineCountCheck = TRUE;

			/*
		 * Check for Allowed Word Classes
		 */

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {

				if ($Line == 'meta' && $LineIndex != $this->TotalLines) {

					if (in_array ( $Words, $DisAllowedWordClass ))
						$WordClassCheck = FALSE;
				}
			}

			if ($rit->getDepth () == 2) {

				$LineIndex ++;

				if ($Words ['smeta'] != 4 && $Line != ("aTi-" . $this->TotalLines)) {
					$LineClassCheck = FALSE;
				}
				if ($Line == ("aTi-" . $this->TotalLines)) {

					$FinalWordsCount = $Words ['smeta'];
				}
			}
		}

		$totalBondCount = 0;
		$KaliBondCount = 0;
		$VenBondCount = 0;

		foreach ( $this->WordBond as $Bond ) {
			$BondType = $Bond ['bond'];

			if (strpos ( $BondType, "கலித்தளை") !== FALSE) {
				$KaliBondCount = $KaliBondCount + 1;
			}

			if (strpos ( $BondType, "வெண்டளை") !== FALSE) {
				$VenBondCount = $VenBondCount + 1;
			}

			$totalBondCount = $totalBondCount + 1;

		}

		$TalaiCheck = ($KaliBondCount + $VenBondCount)/$totalBondCount > 0.5 && $KaliBondCount/$totalBondCount > 0.25;

		/*
		 * Check if Final Line Word Coutn is 3 and prsence of DisallowedWord
		 * Class
		 */
		if ($FinalWordsCount == 3) {
			$VenkalippaaEndCheck = TRUE;
		}

		$VenKalippaWordCheck = $LineClassCheck && $VenkalippaaEndCheck;

		$VenkLaippaCheck = $VenKalippaWordCheck & $TalaiCheck & $LineCountCheck && $WordClassCheck;

		$this->VenkalippaError = array();

		$this->VenkalippaError[1][0] = 'குறைந்தபட்சம் நான்கடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->VenkalippaError[1][1] = $LineCountCheck;

		$this->VenkalippaError[2][0] = 'ஈற்றடி தவிர்த்த பிற அடிகள் நான்கு சீர்கள் கொண்டிருத்தல் வேண்டும்';
		$this->VenkalippaError[2][1] = $LineClassCheck;

		$this->VenkalippaError[3][0] = 'ஈற்றடி மூன்று சீர்கள் கொண்டிருத்தல் வேண்டும்';
		$this->VenkalippaError[3][1] = $VenkalippaaEndCheck;

		$this->VenkalippaError[4][0] = '(ஈற்றடி தவிர்த்து) மாச்சீரும் விளங்கனிச்சீரும் வருதல் கூடாது';
		$this->VenkalippaError[4][1] = $WordClassCheck;

		$this->VenkalippaError[5][0] = 'கலித்தளையும் வெண்டளையும் மிகுந்து வருதல் வருதல் வேண்டும் (அவலோகிதம் இவ்விருதளைகளும் இணைந்து குறைந்தபட்சம் 50% எதிர்பார்க்கிறது. கலித்தளை மட்டும் குறைந்தபட்சம் 25% எதிர்பார்க்கிறது)';
		$this->VenkalippaError[5][1] = $TalaiCheck;

		$this->VenkalippaError[6][0] = 'பொருத்தம்';
		$this->VenkalippaError[6][1] = $VenkLaippaCheck;

		$this->MetreErrors['venkalippaa'] = $this->VenkalippaError;

		if ($VenkLaippaCheck)
			return "ve_Nkali_ppA";
		else
			return NULL;
	}

	/**
	 * Check if the Prosody Metre is Vanjipaa - If the metre matches return the
	 * exact type, else return NULL
	 *
	 * @return string NULL
	 */
	public function CheckVanjippaa()

	{
		$root = $this->ParseTreeRoot;

		$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

		$WordSyllableClassCheck = FALSE;
		$LineClassCheck = TRUE;
		$LoneWord = FALSE;
		$LineWordCount = array ();
		$AllowedWordClass = array (
				"tEmA",
				"puLimA",
				"kUviLa_m",
				"karuviLa_m"
		);
		$AllowedWordClassCount = 0;
		$TotalWordCount = 0;

		$AllowedWordClassVanji = array (
			"tEmA_GkaVi",
			"puLimA_GkaVi",
			"kUviLa_GkaVi",
			"karuviLa_GkaVi"
		);

		$AllowedMonoFinalLetters = array (
				"E",
				"O",
				"I",
				"Y",
				"Q"
		);
		$AllowedTriFinalLetters = array (
				"A_y",
				"e_V"
		);

		$this->VanjippaaError = array();

		// Check for Vanjiceers are Majority

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {
				if ($Line == 'meta' && !$LoneWord) {
					$TotalWordCount ++;

					if (in_array ( $Words, $AllowedWordClassVanji ))
						$AllowedWordClassCount ++;
				}
			}

			if ($rit->getDepth () == 2) {

				if ($Words ['smeta'] == 1)
					$LoneWord = TRUE;

			}
		}

		if ($TotalWordCount != 0)
			$WordSyllableCheck = ($AllowedWordClassCount / $TotalWordCount) > 0.35;
		else
			$WordSyllableCheck = FALSE;

		// Check for Vanjittalai are Majority

		$LoneWord = FALSE;

		$totalBondCount = 0;
		$VanjiBondCount = 0;

		foreach ( $this->WordBond as $Bond ) {
			if ($totalBondCount < $TotalWordCount) {

				$BondType = $Bond ['bond'];

				if (strpos ( $BondType, "வஞ்சித்தளை") !== FALSE) {
					$VanjiBondCount = $VanjiBondCount + 1;
				}

			}

			$totalBondCount = $totalBondCount + 1;
		}

		$TalaiCheck = ($VanjiBondCount/($TotalWordCount - 1)) > 0.4;

		/*
		 * Check for Taniccol, Allowed Word Class in Asiriya Surithakam
		 */

		$TotalWordCountSuridhagam = 0;
		$AllWordCount = 0;

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {
				if ($Line == 'meta')
					$AllWordCount++;
				if ($Line == 'meta' && $LoneWord) {
					$TotalWordCountSuridhagam ++;

					if (in_array ( $Words, $AllowedWordClass ))
						$AllowedWordClassCount ++;
				}
			}

			if ($rit->getDepth () == 2) {
				if ($Words ['smeta'] == 1)
					$LoneWord = TRUE;

				$LineWordCount [] = $Words ['smeta'];
			}
		}

		$TotalWordCountSuridhagam = $TotalWordCountSuridhagam - 1;

		/*
		 * Check if Allowed Word Class is Majority in Asiriyasurithakam
		 */
		$WordSyllableClassCheckSuridhagam = $AllowedWordClassCount / $TotalWordCountSuridhagam > 0.4;

    // Check Aciriyattalai in Aciriyaccurithakam

		$totalBondCount = 0;
		$AciriBondCount = 0;

		foreach ( $this->WordBond as $Bond ) {
			if ($totalBondCount > $TotalWordCount) {
				$BondType = $Bond ['bond'];

				if (strpos ( $BondType, "ஆசிரியத்தளை") !== FALSE) {
					$AciriBondCount = $AciriBondCount + 1;
				}
			}

			$totalBondCount = $totalBondCount + 1;

		}

		if ($TotalWordCountSuridhagam - 1 != 0)
			$TalaiCheckSuridhagam = ($AciriBondCount/($TotalWordCountSuridhagam - 1)) > 0.4;
		else
			$TalaiCheckSuridhagam = FALSE;

		// Check Aciriyappaa Ending

		$FinalLettersMono = substr ( $this->InputSourceText, - 1 );
		$FinalLettersTri = substr ( $this->InputSourceText, - 3 );

		$FinalLastSyllableClassCheck = in_array ( $FinalLettersMono, $AllowedMonoFinalLetters ) || in_array ( $FinalLettersTri, $AllowedTriFinalLetters );

			/*
		 * Validate the Words in Each line
		 */

		for($LineIndex = 0; $LineIndex < count ( $LineWordCount ); $LineIndex ++) {

			if ($LineWordCount [$LineIndex] != $LineWordCount [$LineIndex + 1])
				$LineClassCheck = FALSE;

			if ($LineWordCount [$LineIndex + 2] == 1)
				break;
		}

		$LineClassVanjiCheck = $LineClassCheck && ($LineWordCount [0] == 2 || $LineWordCount [0] == 3);

		$LineCountCheck = $LineIndex >= 1;

		$LoneWordCheck = $LineWordCount [$LineIndex + 2] == 1;

		$NextLineIndex = $LineIndex + 3;

		$SuridhagamCheck = ($LineWordCount [$NextLineIndex] == 3  || $LineWordCount [$NextLineIndex] == 4) && ($LineWordCount [$NextLineIndex + 1] == 4);

		$SuridhagamWordCount = ((count ( $LineWordCount )) - ($LineIndex + 3));

		## Check Suridhagam

		if ($SuridhagamWordCount != 2)
			$SuridhagamCheck = FALSE;

		$vanjiCheck = $WordSyllableCheck && $TalaiCheck && $LineClassVanjiCheck && $LoneWordCheck && $SuridhagamCheck && $WordSyllableClassCheckSuridhagam && $TalaiCheckSuridhagam && $FinalLastSyllableClassCheck && $LineCountCheck;


		$this->VanjippaaError[1][0] = 'குறைந்தபட்சம் மூன்று வஞ்சி அடிகள் வருதல் வேண்டும்';
		$this->VanjippaaError[1][1] = $LineCountCheck;

		$this->VanjippaaError[2][0] = 'வஞ்சி அடிகள் குறளடிகளாலோ சிந்தடிகளாலோ அமைதல் வேண்டும்';
		$this->VanjippaaError[2][1] = $LineClassVanjiCheck;

		$this->VanjippaaError[3][0] = 'வஞ்சி அடிகளில் கனிச்சீர்கள் நிறைந்து வருதல் வேண்டும் (அவலோகிதம் குறைந்தபட்சம் 40% எதிர்பார்க்கிறது)';
		$this->VanjippaaError[3][1] = $WordSyllableCheck;

		$this->VanjippaaError[4][0] = 'வஞ்சி அடிகளில் வஞ்சித்தளைகள் நிறைந்து வருதல் வேண்டும் (அவலோகிதம் குறைந்தபட்சம் 40% எதிர்பார்க்கிறது)';
		$this->VanjippaaError[4][1] = $TalaiCheck;

		$this->VanjippaaError[5][0] = 'வஞ்சி அடிகளை அடுத்து தனிச்சொல் வருதல் வேண்டும்';
		$this->VanjippaaError[5][1] = $LoneWordCheck;

		$this->VanjippaaError[6][0] = 'தனிச்சொல்லை அடுத்து சுரிதகம் வருதல் வேண்டும்  (அவலோகிதம் 2 அடிகள் எதிர்பார்க்கிறது)';
		$this->VanjippaaError[6][1] = $SuridhagamCheck;

		$this->VanjippaaError[7][0] = 'சுரிதகம் ஆசிரிய ஓசையை கொண்டிருத்தல் வேணடும்';
		$this->VanjippaaError[7][1] = $SuridhagamCheck && $WordSyllableClassCheckSuridhagam && $TalaiCheckSuridhagam && $FinalLastSyllableClassCheck;

		$this->VanjippaaError[8][0] = 'பொருத்தம்';
		$this->VanjippaaError[8][1] = $vanjiCheck;

		$this->MetreErrors['vanjippaa'] = $this->VanjippaaError;

			/*
		 * Check the Type of Vanjippa
		 */

		if ($vanjiCheck) {
			 $lineTypeClassification = "";
			if ($LineWordCount [0] == 2) {
				$MetreType = "kuRaLaTi va_Jci_ppA";
				$lineTypeClassification = "குறளடிகள்";
			}
			if ($LineWordCount [0] == 3) {
				$MetreType = "ci_ntaTi va_Jci_ppA";
				$lineTypeClassification = "சிந்தடிகள்";
			}

			$this->VenpaaTypeExpl = lat2tam($this->LineClass[0]) . " கொண்ட வஞ்சி அடிகள் வந்ததினால் " . lat2tam($MetreType). " ஆயிற்று";

			return $MetreType;
		}

		else
			return NULL;
	}

	/**
	 * Check if the Prosody Metre is VenpaaInam - If the metre matches return
	 * the exact type, else return NULL
	 *
	 * @return Ambigous <string, NULL>
	 */
	public function CheckVenInam() {
		$KuralTaazhisaiCheck = FALSE;
		$KuralTuraiCheck = FALSE;

		$TaazhisaiCheck = TRUE;
		$TuraiCheck = TRUE;
		$ViruttamCheck = TRUE;

		$atiEtukai = $this->CheckAtiEtukai();

		$LineTypeReverse = array_flip ( $this->LineType );

		/*
		 * Check Kural Turai & Thaazhisai
		 */

		$LineCheckKural = $this->TotalLines == 2;

		if ($LineTypeReverse [$this->LineClass [0]] == $LineTypeReverse [$this->LineClass [1]])
			$KuralTuraiCountCheck = TRUE;

		if ($LineTypeReverse [$this->LineClass [0]] > $LineTypeReverse [$this->LineClass [1]])
			$KuralTaazhisaiCountCheck = TRUE;

		$FirstLineCount = $LineTypeReverse [$this->LineClass [0]] >= 4;

		$KuralTuraiCheck = $LineCheckKural && $KuralTuraiCountCheck;
		$KuralTaazhisaiCheck = $LineCheckKural && $KuralTaazhisaiCountCheck && $FirstLineCount;

		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'] = array();

		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][0][0] = 'இரு அடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][0][1] = $LineCheckKural;

		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][1][0] = 'இரு அடிகளும் ஒரே அளவில் இருத்தல் வேண்டும்';
		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][1][1] = $KuralTuraiCountCheck && $LineCheckKural;

		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][2][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][2][1] = 'info';

		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][3][0] = 'பொருத்தம்';
		$this->MetreErrors['kuRa_Lve_Nce_ntuRY'][3][1] = $KuralTuraiCheck;

		$this->MetreErrors['kuRa_TTAZicY'] = array();

		$this->MetreErrors['kuRa_TTAZicY'][0][0] = 'இரு அடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['kuRa_TTAZicY'][0][1] = $LineCheckKural;

		$this->MetreErrors['kuRa_TTAZicY'][1][0] = 'முதலடி குறைந்தபட்சம் நான்கு சீர்கள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['kuRa_TTAZicY'][1][1] = $FirstLineCount;

		$this->MetreErrors['kuRa_TTAZicY'][2][0] = 'இரண்டாம் அடி முதலடியை விட குறைவாக இருத்தல் வேண்டும்';
		$this->MetreErrors['kuRa_TTAZicY'][2][1] = $KuralTaazhisaiCountCheck;

		$this->MetreErrors['kuRa_TTAZicY'][3][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['kuRa_TTAZicY'][3][1] = 'info';

		$this->MetreErrors['kuRa_TTAZicY'][4][0] = 'பொருத்தம்';
		$this->MetreErrors['kuRa_TTAZicY'][4][1] = $KuralTaazhisaiCheck;

		/*
		 * Check for Ven Thaazhisai
		 */

		$LineThazhisaiCheck = $this->TotalLines == 3;

		$TaazhisaiCountCheck = ($this->LineClass [0] == "_aLavaTi") && ($this->LineClass [1] == "_aLavaTi") && ($this->LineClass [2] == "ci_ntaTi");

		$TaazhisaiCheck = $LineThazhisaiCheck && $TaazhisaiCountCheck;

		$this->MetreErrors['ve_NTAZicY'][0][0] = 'மூன்று அடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['ve_NTAZicY'][0][1] = $LineThazhisaiCheck;

		$this->MetreErrors['ve_NTAZicY'][1][0] = 'முதல் இருஅடிகள் அளவடியாகவும் ஈற்றடி சிந்தடியாக இருத்தல் வேண்டும்';
		$this->MetreErrors['ve_NTAZicY'][1][1] = $TaazhisaiCountCheck;

		$this->MetreErrors['ve_NTAZicY'][2][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['ve_NTAZicY'][2][1] = 'info';

		$this->MetreErrors['ve_NTAZicY'][3][0] = 'பொருத்தம்';
		$this->MetreErrors['ve_NTAZicY'][3][1] = $TaazhisaiCheck;

			/*
		 * Check for Ven Thurai
		 */

		$ProsodyLineTypes = $this->LineClass;
		$LinesCount = array ();

		foreach ( $ProsodyLineTypes as $key => $value ) {
			$LinesCount [] = ($LineTypeReverse [$value]);
		}

		$LastLine = $LinesCount [count($LinesCount) - 1];

		## Check Last line is less than the first line.

		$LastFirstLineCheck = $LinesCount [count($LinesCount) - 1] < $LinesCount [0];

		$reducedLineCheck = $LastFirstLineCheck;

		## Check if the reduced count is consistent
		## eg. 6 6 4 4 4 and not 6 6 6 4 3 4 etc.

		$lineDecrease = TRUE;

		for($i = 0; $i < count($LinesCount); $i++) {
			if ($LinesCount[$i + 1] < $LinesCount[$i]) {
				$LineChange = $i + 1;
				break;
			}

			if ($LinesCount[$i + 1] > $LinesCount[$i]) {
				$lineDecrease = FALSE;
				break;
			}

		}

		for ($i = $LineChange + 1; $i < count($LinesCount); $i++) {
			if ($LinesCount[$i] != $LinesCount[$LineChange])
				$reducedLineCheck = FALSE;
		}

		$LineCountCheckTurai = $this->TotalLines >= 3 && $this->TotalLines <= 7;

		$TuraiCheck = $LineCountCheckTurai && $reducedLineCheck && $LastFirstLineCheck && $lineDecrease;

		$this->MetreErrors['ve_NTuRY'][0][0] = 'மூன்று அடிகள் முதல் ஏழடிகள் வரை கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['ve_NTuRY'][0][1] = $LineCountCheckTurai;

		$this->MetreErrors['ve_NTuRY'][1][0] = 'முதல் சில அடிகள் ஒரே அளவையும், பின்வரும் அடிகள் நிலையாக சீர் குறைந்து ஒரே அளவில் வருதல் வேண்டும்.';
		$this->MetreErrors['ve_NTuRY'][1][1] = $reducedLineCheck && $LastFirstLineCheck && $lineDecrease;

		$this->MetreErrors['ve_NTuRY'][2][0] = 'பிற அடிகள் குறையாது ஈற்றடி மட்டும் குறைந்தும் வருதலும் உண்டு.';
		$this->MetreErrors['ve_NTuRY'][2][1] = 'info';

		$this->MetreErrors['ve_NTuRY'][3][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['ve_NTuRY'][3][1] = 'info';

		$this->MetreErrors['ve_NTuRY'][4][0] = 'பொருத்தம்';
		$this->MetreErrors['ve_NTuRY'][4][1] = $TuraiCheck;

			/*
		 * Check for Veli Viruttam
		 */

		$TaniccolCheck = TRUE;

		for($LineIndex = 1; $LineIndex <= $this->TotalLines; $LineIndex ++) {
			$TaniccolCheck = $TaniccolCheck && $this->CheckTaniccol ( $this->InputSourceText, $LineIndex, FALSE );
		}

		$LineClassCheck = $this->CheckLineWordCount ( $this->TotalLines, 5 ) && ($this->TotalLines == 3 || $this->TotalLines == 4);

		$TaniccolEqual = TRUE;

		if($TaniccolCheck) {
			$TaniccolWord = $this->ReturnTaniccol ($this->InputSourceText, 1);

			for($LineIndex = 1; $LineIndex <= $this->TotalLines; $LineIndex ++) {
				$TaniccolEqual = $TaniccolEqual && $this->ReturnTaniccol ($this->InputSourceText, $LineIndex) == $TaniccolWord;
			}
		}

		$ViruttamCheck = $LineClassCheck && $TaniccolCheck && $TaniccolEqual && $atiEtukai;

		$this->MetreErrors['veLiviru_tta_m'][0][0] = 'மூன்று அல்லது நான்கு நெடிலடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['veLiviru_tta_m'][0][1] = $LineClassCheck;

		$this->MetreErrors['veLiviru_tta_m'][1][0] = 'ஒவ்வொரு அடியிலும் தனிச்சொல் பெற்றிருத்தல் வேண்டும்';
		$this->MetreErrors['veLiviru_tta_m'][1][1] = $TaniccolCheck;

		$this->MetreErrors['veLiviru_tta_m'][2][0] = 'தனிச்சொற்கள் அனைத்தும் ஒரே சொல்லாக இருத்தல் வேண்டும்.';
		$this->MetreErrors['veLiviru_tta_m'][2][1] = $TaniccolCheck && $TaniccolEqual;

		$this->MetreErrors['veLiviru_tta_m'][3][0] = 'ஒவ்வொரு அடியிலும் ஒரே அடி எதுகை அமைதல் வேண்டும்';
		$this->MetreErrors['veLiviru_tta_m'][3][1] = $atiEtukai;

		$this->MetreErrors['veLiviru_tta_m'][4][0] = 'பொருத்தம்';
		$this->MetreErrors['veLiviru_tta_m'][4][1] = $ViruttamCheck;

		/** Check Vellaithaazhisai **/

		$VellaithaazhisaiCheck = FALSE;

		if (count($this->breaks) == 2) {
			$Lines = explode ( PHP_EOL, trim ( $this->InputSourceText ) );

			$Venpaverse1 = implode(PHP_EOL, array_slice(($Lines), 0, 3));
			$Venpaverse2 = implode(PHP_EOL, array_slice(($Lines), 3, 3));
			$Venpaverse3 = implode(PHP_EOL, array_slice(($Lines), 6, 3));

			$VellaithaazhisaiCheck = $this->breaks[0] == 3 && $this->breaks[1] == 7 && $this->CheckVenpaaCheck($Venpaverse1) && $this->CheckVenpaaCheck($Venpaverse2) && $this->CheckVenpaaCheck($Venpaverse3);
		}

		$this->MetreErrors['ve_LLo_ttAZicY'][0][0] = 'சிந்தடி வெண்பாக்கள் மூன்றடுக்கி வருதல் வேண்டும்';
		$this->MetreErrors['ve_LLo_ttAZicY'][0][1] = $VellaithaazhisaiCheck;

		$this->MetreErrors['ve_LLo_ttAZicY'][1][0] = 'பொருத்தம்';
		$this->MetreErrors['ve_LLo_ttAZicY'][1][1] = $VellaithaazhisaiCheck;


		if ($KuralTaazhisaiCheck)
			$MetreType = "kuRa_TTAZicY";
		else if ($KuralTuraiCheck)
			$MetreType = "kuRa_Lve_Nce_ntuRY";
		else if ($TaazhisaiCheck)
			$MetreType = "ve_NTAZicY";
		else if ($TuraiCheck)
			$MetreType = "ve_NTuRY";
		else if ($ViruttamCheck && $atiEtukai)
			$MetreType = "veLiviru_tta_m";
		else if ($VellaithaazhisaiCheck)
			$MetreType = "ve_LLo_ttAZicY";
		else
			$MetreType = NULL;

		return $MetreType;
	}

	/**
	 * Check if the Prosody Metre is Aasiriyapaainam - If the metre matches
	 * return the exact type, else return NULL
	 *
	 * @return Ambigous <string, NULL>
	 */
	public function CheckAsiriyaInam() {
		$TaazhisaiCheck = FALSE;
		$TuraiCheck = FALSE;
		$ViruttamCheck = FALSE;

		$Lines = explode ( PHP_EOL, trim ( $this->InputSourceText ) );

		$atiEtukai = $this->CheckAtiEtukai();


		for($LineIndex = 6; $LineIndex <= 24; $LineIndex ++)

		{
			$ViruttamCheck = $this->CheckLineWordCount ( 4, $LineIndex );

			if ($ViruttamCheck)
				break;
		}

		$this->MetreErrors['_Aciriyaviru_tta_m'] = array();

		$this->MetreErrors['_Aciriyaviru_tta_m'][0][0] = 'கழிநெடிலடிகள் நான்கு கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['_Aciriyaviru_tta_m'][0][1] = $ViruttamCheck;

		$this->MetreErrors['_Aciriyaviru_tta_m'][1][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் வேண்டும்';
		$this->MetreErrors['_Aciriyaviru_tta_m'][1][1] = $atiEtukai;

		$this->MetreErrors['_Aciriyaviru_tta_m'][2][0] = 'பொருத்தம்';
		$this->MetreErrors['_Aciriyaviru_tta_m'][2][1] = $ViruttamCheck && $atiEtukai;

		if ($ViruttamCheck && $atiEtukai) {
			$this->VenpaaTypeExpl = lat2tam($this->LineClass[0]) . " நான்கு கொண்டிருப்பதால் " . lat2tam($this->LineClass[0]). " ஆசிரியவிருத்தம் ஆயிற்று";
		}

		/*
		 * Check for Thaazhisai
		 */

		for($WordCount = 2; $WordCount <= 9; $WordCount ++)
			$TaazhisaiCheck = $TaazhisaiCheck || $this->CheckLineWordCount ( 3, $WordCount );

		$TaazhisaiCheck = $TaazhisaiCheck && strpos($Lines[0], '-') === false && strpos($Lines[1], '-') === false && strpos($Lines[2], '-') === false;

		if (count($this->breaks) == 2) {
			$countIndex = 0;
			$lineSplit = array();

			$lineSplit[0] = array();
			$lineSplit[1] = array();
			$lineSplit[2] = array();

			foreach ($Lines as $index => $line) {
				if ($index <= $this->breaks[0] - 1) {
					$lineSplit[0][] = count(explode(' ', trim($line)));
				}
				else if ($index >= $this->breaks[0] && $index <= $this->breaks[1] - 2) {
					$lineSplit[1][] = count(explode(' ', trim($line)));
				}	else {
					$lineSplit[2][] = count(explode(' ', trim($line)));
				}
			}

			$TaazhisaiCheckTriple = max($lineSplit[0]) == min($lineSplit[0]) && max($lineSplit[1]) == min($lineSplit[1]) && max($lineSplit[2]) == min($lineSplit[2]) && $this->breaks[0] == 3 && $this->breaks[1] == 7;
		}

			$this->MetreErrors['_Aciriya_ttAZicY'] = array();

			$this->MetreErrors['_Aciriya_ttAZicY'][0][0] = 'ஒரே அளவு கொண்ட மூன்று அடிகள் கொண்டிருத்தல் வேண்டும்';
			$this->MetreErrors['_Aciriya_ttAZicY'][0][1] = $TaazhisaiCheck || $TaazhisaiCheckTriple;

			$this->MetreErrors['_Aciriya_ttAZicY'][1][0] = 'இது மூன்றடுக்காகவும் வரலாம். மூன்றடுக்கிவரின் இது ஆசிரிய ஒத்தாழிசை ஆகும்.';
			$this->MetreErrors['_Aciriya_ttAZicY'][1][1] = 'info';

			if ($TaazhisaiCheck) {
				$this->MetreErrors['_Aciriya_ttAZicY'][2][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
			}

			else {
				$this->MetreErrors['_Aciriya_ttAZicY'][2][0] = 'ஒவ்வொரு அடுக்கிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
			}

			$this->MetreErrors['_Aciriya_ttAZicY'][2][1] = 'info';

			$this->MetreErrors['_Aciriya_ttAZicY'][3][0] = 'பொருத்தம்';
			$this->MetreErrors['_Aciriya_ttAZicY'][3][1] = $TaazhisaiCheck || $TaazhisaiCheckTriple;

			/*
		 * Check for Turai
		 */

		$TuraiCheck = TRUE;

		$LineLevelCheck = FALSE;

		$LineCountCheck = $this->TotalLines == 4;

		for($WordCount = 2; $WordCount <= 24; $WordCount ++)
			$LineLevelCheck = $LineLevelCheck || $this->CheckLineWordCount ( 4, $WordCount );

		$ProsodyLineTypes = $this->LineClass;
		$LineTypeReverse = array_flip ( $this->LineType );

		/* Check if it isn't a Kalittaazhisai */

		foreach ( $ProsodyLineTypes as $key => $value ) {
			if (($key + 1) != $this->TotalLines)
				$LineWordCount [] = ($LineTypeReverse [$value]);
			else
				$FinalLineCount = ($LineTypeReverse [$value]);
		}

		try {
			$LineIrregularCheck = ! $LineLevelCheck && $FinalLineCount == @max ( $LineWordCount );
		} catch (Exception $e) {
			$LineIrregularCheck = FALSE;
		}

		try {
			$LineLessCheck = ! $LineLevelCheck && $FinalLineCount > @min ( $LineWordCount );
		} catch (Exception $e) {
			$LineLessCheck = FALSE;
		}

		$TuraiCheck = $LineCountCheck && $LineIrregularCheck;

		$this->MetreErrors['_Aciriya_ttuRY'] = array();

		$this->MetreErrors['_Aciriya_ttuRY'][0][0] = 'நான்கு அடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['_Aciriya_ttuRY'][0][1] = $LineCountCheck;


		$this->MetreErrors['_Aciriya_ttuRY'][1][0] = 'குறைந்தபட்சம் ஒரு அடி ஈற்றடியின் நீளத்தை விட குறைவாக வருதல் வேண்டும்';
		$this->MetreErrors['_Aciriya_ttuRY'][1][1] =  $LineLessCheck;

		$this->MetreErrors['_Aciriya_ttuRY'][2][0] = 'குறைவாக வராத அடிகள், ஈற்றடியின் அளவை பெற்றிருத்தல் வேண்டும்';

		try {
			$this->MetreErrors['_Aciriya_ttuRY'][2][1] = $LineIrregularCheck;
		} catch (Exception $e) {
			$this->MetreErrors['_Aciriya_ttuRY'][2][1] = FALSE;
		}

		$this->MetreErrors['_Aciriya_ttuRY'][3][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['_Aciriya_ttuRY'][3][1] = 'info';

		$this->MetreErrors['_Aciriya_ttuRY'][4][0] = 'பொருத்தம்';
		$this->MetreErrors['_Aciriya_ttuRY'][4][1] = $TuraiCheck;

		if ($TaazhisaiCheck || $TaazhisaiCheckTriple) {
			$MetreType = "_Aciriya_ttAZicY";

			if ($TaazhisaiCheck && $this->VenpaaTypeExpl == '') {
				$this->VenpaaTypeExpl = 'இது தனித்து வந்த ஆசிரியத்தாழிசை';
			}

			if ($TaazhisaiCheckTriple && $this->VenpaaTypeExpl == '') {
				$this->VenpaaTypeExpl = 'இது மூன்றடுக்கி வந்த ஆசிரிய ஒத்தாழிசை';
			}
		}
		else if ($TuraiCheck)
			$MetreType = "_Aciriya_ttuRY";
		else if ($ViruttamCheck && $atiEtukai)
			$MetreType = $this->LineType [$LineIndex] . " _Aciriyaviru_tta_m";
		else
			$MetreType = NULL;

		return $MetreType;
	}

	/**
	 * Check if the Prosody Metre is KalipaaInam - If the metre matches return
	 * the exact type, else return NULL
	 *
	 * @return Ambigous <string, NULL>
	 */
	public function CheckKaliInam() {
		$TaazhisaiCheck = TRUE;
		$KattalaiCheck = FALSE;
		$TuraiCheck = $this->CheckLineWordCount ( 4, 5 );
		$ViruttamCheck = $this->CheckLineWordCount ( 4, 4 );

		$atiEtukai = $this->CheckAtiEtukai();


		/* Check for Kattalai Kaliththurai */

		if (TRUE)

		{

			/*
			 * Check for Letter count in Each line
			 */

			$Lines = explode ( PHP_EOL, trim ( $this->InputSourceText ) );
			$LinesMetre = array ();

			$root = $this->ParseTreeRoot;

			$rit = new RecursiveIteratorIterator ( new RecursiveArrayIterator ( $root ), RecursiveIteratorIterator::SELF_FIRST );

			$NewSentence = TRUE;

			foreach ( $rit as $key => $value ) {

				if ($rit->hasChildren () === FALSE) {

					if ($NewSentence)
						if ($key == "nE_r" || $key == "nirY") {

							$LinesMetre [] = $key;
							$NewSentence = FALSE;
						}
				}

				else {
					if ($rit->getDepth () == 2)
						$NewSentence = TRUE;
				}
			}

			$WordCountCheck = TRUE;

			for($LineIndex = 0; $LineIndex < count ( $Lines ); $LineIndex ++) {

				$LetterCount = $this->GetLetterCount ( $Lines [$LineIndex] );
				$KattalaiCount = $LetterCount ['Vowel'] + $LetterCount ['ConsonantVowel'];

				// echo $KattalaiCount;

				if ($LinesMetre [$LineIndex] == "nE_r" && $KattalaiCount != 16)
					$WordCountCheck = FALSE;
				if ($LinesMetre [$LineIndex] == "nirY" && $KattalaiCount != 17)
					$WordCountCheck = FALSE;
			}

			/*
			 * Check for Vendalai in each line.. but not inbetween lines
			 */

			$WordBondClassCheck = TRUE;
			$WCount = 1;

			foreach ( $this->WordBond as $Bond ) {
				$BondType = $Bond ['bond'];

				if ($WCount % 5 != 0)
					if (substr ( $BondType, strlen ( $BondType ) - 21 ) != "வெண்டளை")
						$WordBondClassCheck = FALSE;

				$WCount ++;
			}


			/* Check if karuviLangkAy in the end */

			$karuviLangkAyCheckFinal = TRUE;
			$karuviLangkAyCheckOthers = TRUE;

		$AllowedWordClass1 = 	["kUviLa_GkA_y", "karuviLa_GkA_y"];

		$AllowedWordClass2 = [
			"kUviLa_GkA_y",
			"karuviLa_GkA_y",
			"tEmA_GkaVi",
			"puLimA_GkaVi",

			// Four Asais - Tanpuu seers

			"tEmA_nta_NpU",
			"puLimA_nta_NpU",
			"kUviLa_nta_NpU",
			"karuviLa_nta_NpU",

			];

		$wordCount = 0;

		foreach ( $rit as $Line => $Words ) {

			if ($rit->hasChildren () == FALSE) {

				if ($Line == 'meta') {

					if (($wordCount+1)%5 == 0 ) {
						if (!in_array ( $Words, $AllowedWordClass2 )) {
							$karuviLangkAyCheckFinal = FALSE;
						}
					}
					else {
						if (in_array ( $Words, $AllowedWordClass1 ))
							$karuviLangkAyCheckOthers = FALSE;
					}

					$wordCount += 1;
				}
			}

		}


			/*
			 * Check if LastSyllableEnds with E
			 */

			$LastSyllableCheck = TRUE;
			$LastSyllable = substr ( $this->InputSourceText, - 1 );

			if ($LastSyllable != "E")
				$LastSyllableCheck = FALSE;

			if ($WordBondClassCheck && $WordCountCheck && $LastSyllableCheck && $karuviLangkAyCheckFinal && $karuviLangkAyCheckOthers && $TuraiCheck && $atiEtukai)
				$KattalaiCheck = TRUE;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'] = array();

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][0][0] = 'நெடிலடிகள் நான்கு கொண்டிருத்தல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][0][1] = $TuraiCheck;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][1][0] = 'ஒவ்வொரு அடிக்குள்ளும் வெண்டளை வருதல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][1][1] = $WordBondClassCheck;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][2][0] = 'அடிக்குள் ஐந்தாம் சீர் விளங்காய்ச்சீராக இருத்தல் வேண்டும். அருகி விளங்காய்ச் சீருக்குப் பதிலாக மாங்கனி அல்லது மாந்தண்பூவும் வரலாம்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][2][1] = $karuviLangkAyCheckFinal;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][3][0] = 'அடிக்குள் ஐந்தாம் சீர் தவிர்த்த பிறசீர்களில் விளங்காய் வருதல் கூடாது';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][3][1] = $karuviLangkAyCheckOthers;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][4][0] = 'அடி நேரசையில் தொடங்கினால், மெய்யொழித்து 16 எழுத்துக்களும், நிரையசையானால் 17 எழுத்துக்களும் கொண்டிருத்தல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][4][1] = $WordCountCheck;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][5][0] = 'கடைசி அசை ஏகாரத்தில் முடிதல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][5][1] = $LastSyllableCheck;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][6][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][6][1] = $atiEtukai;

			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][7][0] = 'பொருத்தம்';
			$this->MetreErrors['ka_TTaLY_kkali_ttuRY'][7][1] = $KattalaiCheck;

		}

		/*
		 * Check for Kalittaazhisai
		 */

		$TaazhisaiCountCheck = TRUE;
		$TaazhisaiLineCheck = TRUE;

		if ($this->TotalLines < 2)
			$TaazhisaiCountCheck = FALSE;

		$LineTypeReverse = array_flip ( $this->LineType );
		$ProsodyLineTypes = $this->LineClass;

		$LineWordCount = array ();

		foreach ( $ProsodyLineTypes as $key => $value ) {
			if (($key + 1) != $this->TotalLines)
				$LineWordCount [] = ($LineTypeReverse [$value]);
			else
				$FinalLineCount = ($LineTypeReverse [$value]);
		}

		if (count ( $LineWordCount ) > 0)
			if (max ( $LineWordCount ) >= $FinalLineCount)
				$TaazhisaiLineCheck = FALSE;

		$TaazhisaiCheckSingle = $TaazhisaiLineCheck && $TaazhisaiCountCheck;

		## Check Triple Thaazhisai

		if (count($this->breaks) == 2) {
			$countIndex = 0;
			$lineSplit = array();

			$lineSplit[0] = array();
			$lineSplit[1] = array();
			$lineSplit[2] = array();

			foreach ($Lines as $index => $line) {
				if ($index <= $this->breaks[0] - 1) {
					$lineSplit[0][] = count(explode(' ', trim($line)));
				}
				else if ($index >= $this->breaks[0] && $index <= $this->breaks[1] - 2) {
					$lineSplit[1][] = count(explode(' ', trim($line)));
				}	else {
					$lineSplit[2][] = count(explode(' ', trim($line)));
				}
			}

			$last1 = array_pop($lineSplit[0]);
			$last2 = array_pop($lineSplit[1]);
			$last3 = array_pop($lineSplit[2]);

			$TaazhisaiCheckTriple = max($lineSplit[0]) < $last1 && max($lineSplit[1]) < $last2 && max($lineSplit[2]) < $last3;
		}


		//////////////////////////////////

		// KAttalai Kalippaa

		if (TRUE)

		{

			$LineCheckKK = $this->CheckLineWordCount ( 8, 4 );

			/*
			 * Check for Letter count in Each line
			 */

			$WordCountCheckKK = TRUE;

			for($LineIndex = 0; $LineIndex < count ( $Lines ); $LineIndex ++) {

				$LetterCount = $this->GetLetterCount ( $Lines [$LineIndex] );
				$KattalaiCount = $LetterCount ['Vowel'] + $LetterCount ['ConsonantVowel'];

				// echo $KattalaiCount;

				if ($LinesMetre [$LineIndex] == "nE_r" && $KattalaiCount != 11)
					$WordCountCheckKK = FALSE;
				if ($LinesMetre [$LineIndex] == "nirY" && $KattalaiCount != 12)
					$WordCountCheckKK = FALSE;
			}

			/*
			 * Check for Vendalai in each line.. but not inbetween lines
			 */

			$WordBondClassCheckKK = TRUE;
			$WCount = 1;

			foreach ( $this->WordBond as $Bond ) {
				$BondType = $Bond ['bond'];


				if (($WCount % 4) == 1) {

					$WordBondClassCheckKK = strpos($BondType,"ஆசிரியத்தளை") !== FALSE;
				}
				if (($WCount % 4) > 1) {

					$WordBondClassCheckKK = strpos($BondType,"வெண்டளை") !== FALSE;
				}

				$WCount ++;
			}


			$KattalaiCheckKK = $LineCheckKK && $WordCountCheckKK && $WordBondClassCheckKK;

			$this->MetreErrors['ka_TTaLY_kkali_ppA'] = array();

			$this->MetreErrors['ka_TTaLY_kkali_ppA'][0][0] = 'நான்கு சீர்கள் கொண்ட அரையடிகள் 8 வருதல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ppA'][0][1] = $LineCheckKK;

			$this->MetreErrors['ka_TTaLY_kkali_ppA'][1][0] = 'அரையடி நேரசையில் தொடங்கினால், மெய்யொழித்து 11 எழுத்துக்களும், நிரையசையானால் 12 எழுத்துக்களும் கொண்டிருத்தல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ppA'][1][1] = $WordCountCheckKK;

			$this->MetreErrors['ka_TTaLY_kkali_ppA'][2][0] = 'அரையடியில் முதல் இரண்டு சீர்க்களுக்கு இடையே ஆசிரியத்தளையும், பிற் சீர்களுக்கு இடையே வெண்டளையும் வருதல் வேண்டும்';
			$this->MetreErrors['ka_TTaLY_kkali_ppA'][2][1] = $WordBondClassCheckKK;

			$this->MetreErrors['ka_TTaLY_kkali_ppA'][3][0] = 'பொருத்தம்';
			$this->MetreErrors['ka_TTaLY_kkali_ppA'][3][1] = $KattalaiCheckKK;

		}

		////////////////////////////////////


		if ($TaazhisaiCheckSingle || $TaazhisaiCheckTriple) {
			$MetreType = "kali_ttAZicY";

			if ($TaazhisaiCheckSingle && $this->VenpaaTypeExpl == '') {
				$this->VenpaaTypeExpl = 'இது தனித்து வந்த கலித்தாழிசை';
			}

			if ($TaazhisaiCheckTriple && $this->VenpaaTypeExpl == '') {
				$this->VenpaaTypeExpl = 'இது மூன்றடுக்கி வந்த கலித்தாழிசை';
			}
		}
		else if ($KattalaiCheck)
			$MetreType = "ka_TTaLY_kkali_ttuRY";
		else if ($KattalaiCheckKK)
			$MetreType = "ka_TTaLY_kkali_ppA";
		else if ($TuraiCheck && $atiEtukai)
			$MetreType = "kali_ttuRY";
		else if ($ViruttamCheck && $atiEtukai)
			$MetreType = "kaliviru_tta_m";
		else
			$MetreType = NULL;

		$this->MetreErrors['kali_ttuRY'] = array();

		$this->MetreErrors['kali_ttuRY'][0][0] = 'நெடிலடிகள் நான்கு கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['kali_ttuRY'][0][1] = $TuraiCheck;
		$this->MetreErrors['kali_ttuRY'][1][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் வேண்டும்';
		$this->MetreErrors['kali_ttuRY'][1][1] = $atiEtukai;
		$this->MetreErrors['kali_ttuRY'][2][0] = 'பொருத்தம்';
		$this->MetreErrors['kali_ttuRY'][2][1] = $TuraiCheck && $atiEtukai;

		$this->MetreErrors['kaliviru_tta_m'] = array();

		$this->MetreErrors['kaliviru_tta_m'][0][0] = 'அளவடிகள் நான்கு கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['kaliviru_tta_m'][0][1] = $ViruttamCheck;
		$this->MetreErrors['kaliviru_tta_m'][1][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் வேண்டும்';
		$this->MetreErrors['kaliviru_tta_m'][1][1] = $atiEtukai;
		$this->MetreErrors['kaliviru_tta_m'][2][0] = 'பொருத்தம்';
		$this->MetreErrors['kaliviru_tta_m'][2][1] = $ViruttamCheck && $atiEtukai;

		$this->MetreErrors['kali_ttAZicY'] = array();

		$this->MetreErrors['kali_ttAZicY'][0][0] = 'ஒன்றுக்கும் மேற்பட்ட அடிகள் கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['kali_ttAZicY'][0][1] = $TaazhisaiCountCheck;
		$this->MetreErrors['kali_ttAZicY'][1][0] = 'ஈற்றடி பிற அடிகளைவிட நீளமாக இருத்தல் வேண்டும்';
		$this->MetreErrors['kali_ttAZicY'][1][1] = $TaazhisaiLineCheck || $TaazhisaiCheckTriple;
		$this->MetreErrors['kali_ttAZicY'][2][0] = 'இது மூன்றடுக்காகவும் வரலாம்';
		$this->MetreErrors['kali_ttAZicY'][2][1] = 'info';

		if ($TaazhisaiCheckSingle) {
			$this->MetreErrors['kali_ttAZicY'][3][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		}

		else {
			$this->MetreErrors['kali_ttAZicY'][3][0] = 'ஒவ்வொரு அடுக்கிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		}

		$this->MetreErrors['kali_ttAZicY'][3][1] = 'info';
		$this->MetreErrors['kali_ttAZicY'][4][0] = 'பொருத்தம்';
		$this->MetreErrors['kali_ttAZicY'][4][1] = $TaazhisaiCheckSingle || $TaazhisaiCheckTriple;

		return $MetreType;
	}

	/**
	 * Check if the Prosody Metre is VanjippaaInam - If the metre matches return
	 * the exact type, else return NULL
	 *
	 * @return Ambigous <string, NULL>
	 */
	public function CheckVanjiInam() {
		$atiEtukai = $this->CheckAtiEtukai();

		$TaazhisaiCheck = $this->CheckLineWordCount ( 12, 2 );
		$TuraiCheck = $this->CheckLineWordCount ( 4, 2 );
		$ViruttamCheck = $this->CheckLineWordCount ( 4, 3 );

		if ($TaazhisaiCheck) {
			$Lines = explode ( PHP_EOL, trim ( RemovePunctuation($this->InputSourceText) ) );

			$adukkuCountCheck = count($this->breaks) == 2;

			$adukkuLineCheck = $this->breaks[0] == 4 && $this->breaks[1] == 9;
		}

		if ($TaazhisaiCheck && $adukkuCountCheck && $adukkuLineCheck)
			$MetreType = "va_Jci_ttAZicY";
		else if ($TuraiCheck)
			$MetreType = "va_Jci_ttuRY";
		else if ($ViruttamCheck && $atiEtukai)
			$MetreType = "va_Jciviru_tta_m";
		else
			$MetreType = NULL;

		$this->MetreErrors['va_Jci_ttAZicY'] = array();

		$this->MetreErrors['va_Jci_ttAZicY'][0][0] = 'பன்னிரண்டு அளவடிகள் தலா நான்கடிகள் கொண்ட மூன்று அடுக்காக வருதல் வேண்டும்';
		$this->MetreErrors['va_Jci_ttAZicY'][0][1] = $TaazhisaiCheck && $adukkuCountCheck && $adukkuLineCheck;
		$this->MetreErrors['va_Jci_ttAZicY'][1][0] = 'ஒவ்வொரு அடுக்கிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['va_Jci_ttAZicY'][1][1] = 'info';
		$this->MetreErrors['va_Jci_ttAZicY'][2][0] = 'பொருத்தம்';
		$this->MetreErrors['va_Jci_ttAZicY'][2][1] = $TaazhisaiCheck && $adukkuCountCheck && $adukkuLineCheck;

		$this->MetreErrors['va_Jci_ttuRY'] = array();

		$this->MetreErrors['va_Jci_ttuRY'][0][0] = 'குறளடிகள் நான்கு கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['va_Jci_ttuRY'][0][1] = $TuraiCheck;
		$this->MetreErrors['va_Jci_ttuRY'][1][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் சிறப்பு';
		$this->MetreErrors['va_Jci_ttuRY'][1][1] = 'info';
		$this->MetreErrors['va_Jci_ttuRY'][2][0] = 'பொருத்தம்';
		$this->MetreErrors['va_Jci_ttuRY'][2][1] = $TuraiCheck;

		$this->MetreErrors['va_Jciviru_tta_m'] = array();

		$this->MetreErrors['va_Jciviru_tta_m'][0][0] = 'சிந்தடிகள் நான்கு கொண்டிருத்தல் வேண்டும்';
		$this->MetreErrors['va_Jciviru_tta_m'][0][1] = $ViruttamCheck;
		$this->MetreErrors['va_Jciviru_tta_m'][1][0] = 'அனைத்து அடிகளிலும் ஒரே அடி எதுகை அமைதல் வேண்டும்';
		$this->MetreErrors['va_Jciviru_tta_m'][1][1] = $atiEtukai;
		$this->MetreErrors['va_Jciviru_tta_m'][2][0] = 'பொருத்தம்';
		$this->MetreErrors['va_Jciviru_tta_m'][2][1] = $ViruttamCheck && $atiEtukai;

		$this->ActiveRules = $this->MetreErrors[$MetreType];

		return $MetreType;
	}

	/**
	 * Parses the Text and constructs the Parse Tree with the Information on
	 * Sentence, WordClass, Syllable Class
	 *
	 * @param String $ProsodyText
	 * @return AssociativeArray ParseTree
	 */
	public function GetTextSyllablePattern($ProsodyText)

	{
		$ProsodyText = preg_replace ( "/\(.{1,2}\)/", "", $ProsodyText ); // remov
		                                                                  // paranthesized
		                                                                  // words

		$ProsodyText = RemovePunctuation ( $ProsodyText ); // Removing Punctuation
		                                                   // and
		                                                   // reformatting the
		                                                   // text.

		$Lines = explode ( PHP_EOL, trim ( $ProsodyText ) ); // Seperating the
		                                                     // lines of the
		                                                     // text.

		$Lines = preg_replace ( "/\s$/", "", $Lines ); // remove unnecessary
		                                               // spaces

		$LineList = array ();
		$LineCount = 1;

		foreach ( $Lines as $Line ) {
			$Words = explode ( " ", trim ( $Line ) );

			$WordList = array ();
			$WordCount = 1;

			foreach ( $Words as $Word ) {
				$WordSyllable = array ();

				$Word = str_replace ( array (
						"W",
						"Y"
				), array (
						"B",
						"Q"
				), $Word ); // B-aukarakurukkam
				            // Q-Aikaarakurukkam

				$Word = preg_replace ( "/(\b.)B/", "$1W", $Word );

				$Word = preg_replace ( "/(\b.)Q/", "$1Y", $Word );

				/* Get Nirai Words */

				preg_match_all ( '/([kGcJTNtnpmyrlvZLRVjSsh]?_?[aiueoBQ])([kGcJTNtnpmyrlvZLRVjSsh][aAiIuUeEoOYWBQ])(_[KkGcJTNtnpmyrlvZLRVjSsh])*/', $Word, $WordClassNirai, PREG_OFFSET_CAPTURE );

				foreach ( $WordClassNirai [0] as $Nirai ) {
					$WordSyllable [$Nirai [1]] = array (
							'nirY' => $Nirai [0]
					);

					$chr = "";

					for($i = 0; $i < strlen ( $Nirai [0] ); $i ++)
						$chr = $chr . "^";

						// $Word=str_replace($Nirai,$chr,$Word);
					$Word = preg_replace ( "/" . $Nirai [0] . "/", $chr, $Word, 1 );
				}

				/* Get Ner Words */

				preg_match_all ( '/[kGcJTNtnpmyrlvZLRVjSsh]?_?[aAiIuUeEoOQYBW](_[KkGcJTNtnpmyrlvZLRVjSsh])*/', $Word, $WordClassNer, PREG_OFFSET_CAPTURE );
				// preg_match_all('/[kGcJTNtnpmyrlvZLRVjSsh]?[aAiIuUeEoOYWBQ](_[KkGcJTNtnpmyrlvZLRVjSsh])*/',$wrd,$ner,PREG_OFFSET_CAPTURE);

				if (! empty ( $WordClassNer ))
					foreach ( $WordClassNer [0] as $Ner ) {
						$WordSyllable [$Ner [1]] = array (
								'nE_r' => $Ner [0]
						);
					}

				ksort ( $WordSyllable );

				$Syllable = array ();
				$SyllableCount = 1;
				$WordPattern = "";

				foreach ( $WordSyllable as $key => $value ) {
					$Syllable ["acY-" . $SyllableCount ++] = $value;

					foreach ( $value as $Class => $ClassWord )
						$WordPattern = $WordPattern . $Class;
				}

				if (! empty ( $WordPattern ))
					$Syllable ["meta"] = $this->WordType [$WordPattern];
				else
					$WordCount --;

				$WordList ["cI_r-" . $WordCount ++] = $Syllable;
			}

			$WordList ["smeta"] = -- $WordCount;

			$LineList ["aTi-" . $LineCount ++] = $WordList;
		}

		$this->TotalLines = -- $LineCount;

		return array (
				"pA" => $LineList
		);
	}

	/**
	 * Calculates the number of Vikalpa (Adi Etukai) in the Text
	 *
	 * @return number
	 */
	public function GetVikalpaCount()

	{
		$ProsodyText = RemovePunctuation ( $this->InputSourceText ); // Removing
		                                                             // Punctuation
		                                                             // and
		                                                             // reformatting
		                                                             // the
		                                                             // text.
		$Lines = explode ( PHP_EOL, trim ( $ProsodyText ) );

		$FeetWords = array ();
		$VikalpaCount = 1;

		foreach ( $Lines as $Line ) {
			$Words = explode ( " ", $Line );
			$FeetWords [] = $Words [0];
		}

		for($WordIndex = 0; $WordIndex < count ( $FeetWords ) - 1; $WordIndex ++)
			if (! $this->CheckEtukaiSpecialVarga ( $FeetWords [$WordIndex], $FeetWords [$WordIndex + 1] ))
				$VikalpaCount ++;

		return $VikalpaCount;


	}

	public function CheckAtiEtukai() {
		$ProsodyText = RemovePunctuation ( $this->InputSourceText ); // Removing
		                                                             // Punctuation
		                                                             // and
		                                                             // reformatting
		                                                             // the
		                                                             // text.

		return $this->CheckAtiEtukaiVerse($ProsodyText);
	}

	public function CheckAtiEtukaiVerse($ProsodyText)

	{

		$Lines = explode ( PHP_EOL, trim ( $ProsodyText ) );

		$FeetWords = array ();

		foreach ( $Lines as $Line ) {
			$Words = explode ( " ", $Line );
			$FeetWords [] = $Words [0];
		}

		$VikalpaCountSpecialVarga = 1;

		for($WordIndex = 0; $WordIndex < count ( $FeetWords ) - 1; $WordIndex ++)
			if (! $this->CheckEtukaiSpecialVarga ( $FeetWords [$WordIndex], $FeetWords [$WordIndex + 1] ))
				$VikalpaCountSpecialVarga ++;

		$VikalpaCountAcitai = 1;

		for($WordIndex = 0; $WordIndex < count ( $FeetWords ) - 1; $WordIndex ++)
			if (! $this->CheckEtukaiAcitai ( $FeetWords [$WordIndex], $FeetWords [$WordIndex + 1] ))
				$VikalpaCountAcitai ++;

		$VikalpaCountInam = 1;

		for($WordIndex = 0; $WordIndex < count ( $FeetWords ) - 1; $WordIndex ++)
			if (! $this->CheckEtukaiInam ( $FeetWords [$WordIndex], $FeetWords [$WordIndex + 1] ))
				$VikalpaCountInam ++;

		$VikalpaCountUyir = 1;

		for($WordIndex = 0; $WordIndex < count ( $FeetWords ) - 1; $WordIndex ++)
			if (! $this->CheckEtukaiUyir ( $FeetWords [$WordIndex], $FeetWords [$WordIndex + 1] ))
				$VikalpaCountUyir ++;

		$VikalpaCountNedil = 1;

		for($WordIndex = 0; $WordIndex < count ( $FeetWords ) - 1; $WordIndex ++)
			if (! $this->CheckEtukaiNedil ( $FeetWords [$WordIndex], $FeetWords [$WordIndex + 1] ))
				$VikalpaCountNedil ++;

		$atiethukaiCheck = $VikalpaCountSpecialVarga == 1 || $VikalpaCountAcitai == 1 || $VikalpaCountUyir == 1 || $VikalpaCountInam == 1 || $VikalpaCountNedil == 1;

		$this->nonSpecialLineEtukai = ($VikalpaCountAcitai == 1 || $VikalpaCountUyir == 1 || $VikalpaCountInam == 1 || $VikalpaCountNedil == 1) && !($VikalpaCountSpecialVarga == 1);

		# echo $VikalpaCountSpecialVarga . " " . $VikalpaCountAcitai . " " . $VikalpaCountInam . " ". $VikalpaCountUyir. " ".$VikalpaCountNedil;

		return $atiethukaiCheck;
	}

	public function ReturnTaniccol($SourceText, $LineIndex)

	{
		$SourceText = str_replace ( "--", "-", $SourceText );
		$SourceText = str_replace ( "–", "-", $SourceText );

		$Lines = explode ( PHP_EOL, trim ( $SourceText ) );
		$Words = explode ( "-", $Lines [$LineIndex - 1] );

		if (count ( $Words ) != 2)
			return FALSE;
		else
			return $Words[1];

	}

	/**
	 * Checks if the Taniccol is present in a give Line, and whether it should
	 * rhyme (etukai) with the line
	 *
	 * @param String $SourceText
	 * @param Number $LineIndex
	 * @param Boolean $RhymeCheck
	 * @return boolean
	 */
	public function CheckTaniccol($SourceText, $LineIndex, $RhymeCheck)

	{
		$SourceText = str_replace ( "--", "-", $SourceText );
		$SourceText = str_replace ( "–", "-", $SourceText );

		$Lines = explode ( PHP_EOL, trim ( $SourceText ) );
		$Words = explode ( "-", $Lines [$LineIndex - 1] );

		$TaniccolExists = TRUE;
		$TaniccolVikalpaExists = TRUE;

		// echo $words[1];

		if (count ( $Words ) != 2)
			$TaniccolExists = FALSE;

		$LongVowels = array (
				"A",
				"I",
				"U",
				"E",
				"O",
				"W",
				"Y"
		);
		$ShortVowels = array (
				"a",
				"i",
				"u",
				"e",
				"o"
		);

		if (! $this->CheckEtukaiSpecialVarga ( trim ( $Words [0] ), trim ( $Words [1] ) ))
			$TaniccolVikalpaExists = FALSE;

		if (! $RhymeCheck)
			$TaniccolVikalpaExists = TRUE;

		if ($TaniccolExists && $TaniccolVikalpaExists)
			return TRUE;
		else
			return FALSE;
	}
	public function CheckLineWordCount($LineCount, $WordCount) {
		$ProsodyLineTypes = $this->LineClass;

		$LineClassCheck = TRUE;

		foreach ( $ProsodyLineTypes as $ProsodyLineClass )
			if ($ProsodyLineClass != $this->LineType [$WordCount])
				$LineClassCheck = FALSE;

		if ($this->TotalLines != $LineCount)
			$LineClassCheck = FALSE;

		return $LineClassCheck;
	}
	public function DisplayTodai($TodaiType) {
		$MonaiArray = $this->GetTodai ( $this->InputSourceText, $TodaiType );
		$ProsodyText = RemovePunctuation ( $this->InputSourceText ); // Removing
		                                                             // Punctuation
		                                                             // and
		                                                             // reformatting
		                                                             // the
		                                                             // text.
		$Lines = explode ( PHP_EOL, trim ( $ProsodyText ) );

		$MonaiExists = FALSE;

		$Ornament [] = $this->DisplayTodaiElements ( $MonaiArray, $Lines, $TodaiType, "Line" );

		$FeetLine = "";

		foreach ( $Lines as $Line ) {
			$FeetWords = explode ( " ", $Line );

			if ($TodaiType != "CheckIyaipu")
				$FeetLine .= $FeetWords [0] . " ";
			else
				$FeetLine .= end($FeetWords) . " ";
		}

		$FeetTodai = $this->GetTodai ( $FeetLine, $TodaiType );

		$Ornament [] = $this->DisplayTodaiElements ( $FeetTodai, $Lines, $TodaiType, NULL );

		return $Ornament;
	}
	public function DisplayTodaiElements($TodaiArray, $Lines, $TodaiType, $TodaiClass) {
		$TodadiPatternName = array (
				"12" => "_iNY",
				"13" => "poZi_ppu",
				"14" => "_orU_u",
				"123" => "kUZY",
				"134" => "mE_RkatuvA_y",
				"124" => "kI_Z_kkatuvA_y",
				"1234" => "mu_RRu"
		);

		if ($TodaiType == 'CheckIyaipu') {
			$TodadiPatternName = array (
					"43" => "_iNY",
					"42" => "poZi_ppu",
					"41" => "_orU_u",
					"432" => "kUZY",
					"421" => "mE_RkatuvA_y",
					"431" => "kI_Z_kkatuvA_y",
					"4321" => "mu_RRu"
			);
		}

		$TodaiExists = FALSE;

		$Ornament = array ();

		for($LineIndex = 0; $LineIndex < count ( $TodaiArray ); $LineIndex ++) {

			$TodaiLine = $TodaiArray [$LineIndex];

			$LineWordCount = count ( explode ( " ", trim ( $Lines [$LineIndex] ) ) );

			foreach ( $TodaiLine as $TodaiSeer ) {
				if (count ( $TodaiSeer ) > 1) {
					$TodaiExists = true;

					for($TodaiIndex = 0; $TodaiIndex < count ( $TodaiSeer ); $TodaiIndex ++) {
						foreach ( $TodaiSeer [$TodaiIndex] as $Index => $Word )
							;
						{

							$TodaiPattern = $TodaiPattern . ($Index + 1);

							if (strpos($TodaiType, "CheckMonai") !== false) {

								$Ornament [$LineIndex] [0] [$Index] = lancon ( lat2tam ( $Word ), $this->Lang );

							}

							if (strpos($TodaiType, "CheckEtukai") !== false ) {

								$Ornament [$LineIndex] [0] [$Index] = lancon ( lat2tam ( $Word ), $this->Lang );

							}

							if ($TodaiType == "CheckIyaipu") {
								$Ornament [$LineIndex] [0] [$Index] = lancon ( lat2tam ( $Word ), $this->Lang );

								$Word = trim($Word);

							}

						}
					}

					if ($TodadiPatternName [$TodaiPattern] != "" && $TodaiClass == "Line" && $LineWordCount == 4) {
						$Ornament [$LineIndex] [1] = lancon ( lat2tam ( $TodadiPatternName [$TodaiPattern] ), $this->Lang );
					}

					if ($TodaiClass == NULL && ($TodaiType == "CheckEtukai" || $TodaiType == "CheckEtukaiVarga")) {
						$EtukaiPattern = str_split($TodaiPattern);
						$odd = TRUE;

						foreach($EtukaiPattern as $patt) {
							$odd = intval($patt) % 2 != 0;
						}

						if ($odd) {
							$Ornament [$LineIndex] [1] = 'இடையிட்டெதுகை';
						}
					}

					$TodaiPattern = "";
				}
			}

			$TodaiExists = FALSE;
		}
		return $Ornament;
	}

	public function GetTodai($ProsodyText, $TodaiType) {
		$ProsodyText = RemovePunctuation ( $ProsodyText ); // Removing Punctuation
		                                                   // and
		                                                   // reformatting the
		                                                   // text.
		$Lines = explode ( PHP_EOL, trim ( $ProsodyText ) ); // Seperating the
		                                                     // lines of the
		                                                     // text.

		$TodaiLineIndex = array ();

		$TodaiList = array ();

		/*
		 * Compare each word in a line, with the rest of the words if words
		 * match, place them in an array iterate again with the next word skip
		 * already matched words
		 */

		foreach ( $Lines as $Line ) {
			$Words = explode ( " ", $Line );

			$TodaiWordIndex = array ();

			$TodaiIndex = array ();

			if($TodaiType != "CheckIyaipu") {
				$TodaiIndex [] = array (
						0 => $Words [0]
				);
			} else {
				$TodaiIndex [] = array (
						(count($Words)-1) => end($Words)
				);
			}

			for($NewIndex = 1; $NewIndex < count ( $Words ); $NewIndex ++) {
				  if($TodaiType !== "CheckIyaipu") {
						$TodaiCheck = $this->$TodaiType ( $Words [0], $Words [$NewIndex] );
				  }
					else {
						$TodaiCheck = $this->$TodaiType ( end($Words) , $Words [count ( $Words ) - $NewIndex - 1] );
					}

				if ($TodaiCheck) {
					if($TodaiType != "CheckIyaipu") {
						$TodaiIndex [] = array (
								$NewIndex => $Words [$NewIndex]
						);
						$TodaiList [] = $NewIndex;
					} else {
						$Iyaipuindex = count ( $Words ) - $NewIndex - 1;
						$TodaiIndex [] = array (
								$Iyaipuindex => $Words [$Iyaipuindex]
						);
						$TodaiList [] = $Iyaipuindex;
					}
				}
			}

			$TodaiWordIndex [] = $TodaiIndex;

			$TodaiLineIndex [] = $TodaiWordIndex;
			$TodaiList = array ();
		}

		return $TodaiLineIndex;
	}

	public function CheckMonaiSpecial($FirstWord, $SecondWord)

	{
		$MonaiAksharaVow = array ();

		// Monai Equivalents

		$MonaiAksharaVow [] = array (
				"a",
				"A",
				"Y",
				"W"
		);
		$MonaiAksharaVow [] = array (
				"i",
				"I",
				"e",
				"E"
		);
		$MonaiAksharaVow [] = array (
				"u",
				"U",
				"o",
				"O"
		);

		$MonaiAksharaCons [] = array (
				"J",
				"n"
		);
		$MonaiAksharaCons [] = array (
				"m",
				"v"
		);
		$MonaiAksharaCons [] = array (
				"t",
				"c"
		);

		$MonaiFirstLtr = FALSE;
		$MonaiSecondLtr = FALSE;

		// Compare First Letter

		if (substr ( $FirstWord, 0, 1 ) == substr ( $SecondWord, 0, 1 ))

		{
			$MonaiFirstLtr = TRUE;
		}

		else {
			foreach ( $MonaiAksharaCons as $Monai ) {
				if (in_array ( substr ( $FirstWord, 0, 1 ), $Monai ) && in_array ( substr ( $SecondWord, 0, 1 ), $Monai ))
					$MonaiFirstLtr = TRUE;
			}
		}

		// if first letter matches, then compare second letter

		if ($MonaiFirstLtr) {
			foreach ( $MonaiAksharaVow as $Monai ) {
				if (in_array ( substr ( $FirstWord, 1, 1 ), $Monai ) && in_array ( substr ( $SecondWord, 1, 1 ), $Monai )) {
					$MonaiSecondLtr = TRUE;
				}
			}
		}

		return $MonaiSecondLtr;
	}

	public function CheckMonaiVarga($FirstWord, $SecondWord)

	{
		$MonaiAksharaVow = array ();

		$MonaiAksharaCons [] = array (
				"J",
				"n"
		);
		$MonaiAksharaCons [] = array (
				"m",
				"v"
		);
		$MonaiAksharaCons [] = array (
				"t",
				"c"
		);

		$MonaiFirstLtr = FALSE;
		$MonaiSecondLtr = FALSE;

		// Compare First Letter

		if (substr ( $FirstWord, 0, 1 ) == substr ( $SecondWord, 0, 1 ) && substr ( $FirstWord, 0, 1 ) != '_')

		{
			$MonaiFirstLtr = TRUE;
		}

		else {
			foreach ( $MonaiAksharaCons as $Monai ) {
				if (in_array ( substr ( $FirstWord, 0, 1 ), $Monai ) && in_array ( substr ( $SecondWord, 0, 1 ), $Monai ))
					$MonaiFirstLtr = TRUE;
			}
		}

		return $MonaiFirstLtr;
	}

	public function CheckMonaiInam($FirstWord, $SecondWord)

	{
		$MonaiAksharaVow = array ();

		// Monai Equivalents

		$MonaiAksharaCons [] = array (
				"k",
				"c",
				"T",
				"t",
				"p",
				"R"
		);
		$MonaiAksharaCons [] = array (
				"y",
				"r",
				"l",
				"v",
				"Z",
				"L"
		);
		$MonaiAksharaCons [] = array (
				"G",
				"J",
				"N",
				"n",
				"m",
				"V"
		);

		$MonaiFirstLtr = FALSE;
		$MonaiSecondLtr = FALSE;

		// Compare First Letter

		if (substr ( $FirstWord, 0, 1 ) == substr ( $SecondWord, 0, 1 ) && substr ( $FirstWord, 0, 1 ) != '_')

		{
			$MonaiFirstLtr = TRUE;
		}

		else {
			foreach ( $MonaiAksharaCons as $Monai ) {
				if (in_array ( substr ( $FirstWord, 0, 1 ), $Monai ) && in_array ( substr ( $SecondWord, 0, 1 ), $Monai ))
					$MonaiFirstLtr = TRUE;
			}
		}

		return $MonaiFirstLtr;
	}

	public function CheckMonaiNedil($FirstWord, $SecondWord)

	{
		$LongVowels = array (
				"A",
				"I",
				"U",
				"E",
				"O"
		);
		$ShortVowels = array (
				"a",
				"i",
				"u",
				"e",
				"o",
				"W",
				"Y"
		);

		$FirstWordInit = substr ( $FirstWord, 1, 1 );
		$SecondWordInit = substr ( $SecondWord, 1, 1 );

		$InLongVowels = (in_array ( $FirstWordInit, $LongVowels ) && in_array ( $SecondWordInit, $LongVowels ));

		return $InLongVowels;
	}

	public function CheckEtukaiVowelLength($FirstWord, $SecondWord) {
		$LongVowels = array (
				"A",
				"I",
				"U",
				"E",
				"O"
		);
		$ShortVowels = array (
				"a",
				"i",
				"u",
				"e",
				"o",
				"W",
				"Y"
		);

		$FirstWordInit = substr ( $FirstWord, 1, 1 );
		$SecondWordInit = substr ( $SecondWord, 1, 1 );

		$InLongVowels = (in_array ( $FirstWordInit, $LongVowels ) && in_array ( $SecondWordInit, $LongVowels ));
		$InShortVowels = (in_array ( $FirstWordInit, $ShortVowels ) && in_array ( $SecondWordInit, $ShortVowels ));

		$VowelLengthCheck = ($InLongVowels || $InShortVowels) && strlen($FirstWord) > 2 && strlen($SecondWord) > 2;

		return $VowelLengthCheck;
	}

	public function CheckEtukaiInam($FirstWord, $SecondWord) {
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$Vallinam = array (
				"k",
				"c",
				"T",
				"t",
				"p",
				"R"
		);

		$Itaiyinam = array (
				"y",
				"r",
				"l",
				"v",
				"Z",
				"L"
		);

		$Mellinam = array (
				"G",
				"J",
				"N",
				"n",
				"m",
				"V"
		);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		if (substr ( $FirstWordSuff, 0, 1 ) == "_" || substr ( $SecondWordSuff, 0, 1 ) == "_") {

			$FirstWordSuffInit = substr ( $FirstWordSuff, 1, 1 );
			$SecondWordSuffInit = substr ( $SecondWordSuff, 1, 1 );
		}
		else
		{

			$FirstWordSuffInit = substr ( $FirstWordSuff, 0, 1 );
			$SecondWordSuffInit = substr ( $SecondWordSuff, 0, 1 );
		}

		 $InVallinam = (in_array ( $FirstWordSuffInit, $Vallinam ) && in_array ( $SecondWordSuffInit, $Vallinam ));
		 $InItaiyinam = (in_array ( $FirstWordSuffInit, $Itaiyinam ) && in_array ( $SecondWordSuffInit, $Itaiyinam ));
		 $InMellinam = (in_array ( $FirstWordSuffInit, $Mellinam ) && in_array ( $SecondWordSuffInit, $Mellinam ));

		$EtukaiLetterCheck = $InVallinam || $InItaiyinam || $InMellinam;

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckEtukaiAcitai($FirstWord, $SecondWord)
	{
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		$Acu = array ("_r", "_l", "_Z", "_y");

		if (in_array($FirstWordSuff, $Acu)) {
			$FirstWord = str_replace($FirstWordSuff, '', $FirstWord);
		}

		if (in_array($SecondWordSuff, $Acu)) {
			$SecondWord = str_replace($SecondWordSuff, '', $SecondWord);
		}

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		if (substr ( $FirstWordSuff, 0, 1 ) == "_" || substr ( $SecondWordSuff, 0, 1 ) == "_") {

			if ($FirstWordSuff == $SecondWordSuff) {
				$EtukaiLetterCheck = TRUE;
			}
		}
		else
		{
			if (substr ( $FirstWordSuff, 0, 1 ) == substr ( $SecondWordSuff, 0, 1 ))
				$EtukaiLetterCheck = TRUE;
		}

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckEtukai($FirstWord, $SecondWord)

	{
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

			if (substr ( $FirstWordSuff, 0, 2 ) == substr ( $SecondWordSuff, 0, 2 ))
				$EtukaiLetterCheck = TRUE;

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckEtukaiSpecialVarga($FirstWord, $SecondWord)
	{
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		if (substr ( $FirstWordSuff, 0, 1 ) == "_" || substr ( $SecondWordSuff, 0, 1 ) == "_") {

			if ($FirstWordSuff == $SecondWordSuff) {
				$EtukaiLetterCheck = TRUE;
			}
		}
		else
		{
			if (substr ( $FirstWordSuff, 0, 1 ) == substr ( $SecondWordSuff, 0, 1 ))
				$EtukaiLetterCheck = TRUE;
		}

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckEtukaiVarga($FirstWord, $SecondWord)

	{
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		if (substr ( $FirstWordSuff, 0, 1 ) == "_" || substr ( $SecondWordSuff, 0, 1 ) == "_") {
		}

		else

		{
			if (substr ( $FirstWordSuff, 0, 1 ) == substr ( $SecondWordSuff, 0, 1 ))
				$EtukaiLetterCheck = TRUE;
		}

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckEtukaiUyir($FirstWord, $SecondWord)

	{
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		if (substr ( $FirstWordSuff, 0, 1 ) == "_" || substr ( $SecondWordSuff, 0, 1 ) == "_") {
		}

		else

		{
			if (substr ( $FirstWordSuff, 1, 1 ) == substr ( $SecondWordSuff, 1, 1 ))
				$EtukaiLetterCheck = TRUE;
		}

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckEtukaiNedil($FirstWord, $SecondWord)

	{
		$EtukaiLetterCheck = FALSE;

		$VowelLengthCheck = $this->CheckEtukaiVowelLength($FirstWord, $SecondWord);

		$FirstWordSuff = substr ( $FirstWord, 2, 2 );
		$SecondWordSuff = substr ( $SecondWord, 2, 2 );

		$LongVowels = array (
				"A",
				"I",
				"U",
				"E",
				"O"
		);

		if (substr ( $FirstWordSuff, 0, 1 ) == "_" || substr ( $SecondWordSuff, 0, 1 ) == "_") {
		}

		else

		{
			if (in_array ( substr ( $FirstWordSuff, 1, 1 ), $LongVowels ) &&  in_array ( substr ( $SecondWordSuff, 1, 1 ), $LongVowels ))
				$EtukaiLetterCheck = TRUE;
		}

		$EtukaiCheck = $VowelLengthCheck && $EtukaiLetterCheck;

		return $EtukaiCheck;
	}

	public function CheckIyaipu($FirstWord, $SecondWord)

	{

	if(substr ( trim($FirstWord), -2) == substr ( trim($SecondWord), -2 ))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}

}

// Class declaration over

?>