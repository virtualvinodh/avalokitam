<?PHP

/*
 * Copyright (C) 2011 Vinodh Rajan vinodh@virtualvinodh.com 
 *
 * This program is free
 * software: you can redistribute it and/or modify it under the terms of the GNU
 * Affero General Public License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version. This program
 * is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Affero General Public License for more details. You
 * should have received a copy of the GNU Affero General Public License along with this
 * program. If not, see <http://www.gnu.org/licenses/>.
 */

require_once "parsetreeclass.php";

error_reporting ( E_ALL ^ E_NOTICE );

/**
 * Avalokitam - Tamil Prosody Analyzer
 *
 * @author Vinodh Rajan, vinodh@virtualviondh.com
 *        
 */
class YapparungalaParseTree extends ProsodyParseTree {
	public $SyllableTypes = array (
			"nE_r",
			"nirY",
			"nE_rpu",
			"nirYpu" 
	);
	public $WordType = array (
			
			"nE_r" => "nE_r",
			"nirY" => "nirY",
			"nE_rpu" => "nE_rpu",
			"nirYpu" => "nirYpu",
			
			// Two Asais
			
			"nE_rnE_r" => "tEmA",
			"nirYnE_r" => "puLimA",
			"nE_rnirY" => "pAtiri",
			"nirYnirY" => "kaNaviri",
			
			"nE_rpunE_r" => "pOtupU",
			"nirYpunE_r" => "viRakutI",
			
			"nE_rnE_rpu" => "pOrERu",
			"nE_rnirYpu" => "pUmarutu",
			"nirYnE_rpu" => "kaTiyARu",
			"nirYnirYpu" => "maZakaLiRu",
			
			"nE_rpunE_rpu" => "vITupERu",
			"nE_rpunirYpu" => "mARukuruku",
			"nirYpunE_rpu" => "varakucORu",
			"nirYpunirYpu" => "muru_TTumarutu",
			
			"nE_rpunirY" => "nITukoTi",
			"nirYpunirY" => "kuZaRupuli",
			
			// Tuumani keZuumani is missing
			
			// Three Asais
			"nE_rnE_rnE_r" => "mAce_lvA_y",
			"nE_rnirYnE_r" => "mApaTuvA_y",
			"nE_rnE_rpunE_r" => "mApOkuvA_y",
			"nE_rnirYpunE_r" => "mAvaZa_GkuvA_y",
			
			"nirYnE_rnE_r" => "pulice_lvA_y",
			"nirYnirYnE_r" => "pulipaTuvA_y",
			"nirYnE_rpunE_r" => "pulipOkuvA_y",
			"nirYnirYpunE_r" => "pulivaZa_GkuvA_y",
			
			"nE_rpunE_rnE_r" => "pA_mpuce_lvA_y",
			"nE_rpunirYnE_r" => "pA_mpupaTuvA_y",
			"nE_rpunE_rpunE_r" => "pA_mpupOkuvA_y",
			"nE_rpunirYpunE_r" => "pA_mpuvaZa_GkuvA_y",
			
			"nirYpunE_rnE_r" => "kaLiRuce_lvA_y",
			"nirYpunirYnE_r" => "kaLiRupaTuvA_y",
			"nirYpunE_rpunE_r" => "kaLiRupOkuvA_y",
			"nirYpunirYpunE_r" => "kaLiRuvaZa_GkuvA_y",
			
			"nE_rnE_rnirY" => "mAce_lcura_m",
			"nE_rnirYnirY" => "mApaTucura_m",
			"nE_rnE_rpunirY" => "mApOkucura_m",
			"nE_rnirYpunirY" => "mAvaZa_Gkucura_m",
			
			"nirYnE_rnirY" => "pulice_lcura_m",
			"nirYnirYnirY" => "pulipaTucura_m",
			"nirYnE_rpunirY" => "pulipOkucura_m",
			"nirYnirYpunirY" => "pulivaZa_Gkucura_m",
			
			"nE_rpunE_rnirY" => "pA_mpuce_lcura_m",
			"nE_rpunirYnirY" => "pA_mpupaTucura_m",
			"nE_rpunE_rpunirY" => "pA_mpupOkucura_m",
			"nE_rpunirYpunirY" => "pA_mpuvaZa_Gkucura_m",
			
			"nirYpunE_rnirY" => "kaLiRuce_lcura_m",
			"nirYpunirYnirY" => "kaLiRupaTucura_m",
			"nirYpunE_rpunirY" => "kaLiRupOkucura_m",
			"nirYpunirYpunirY" => "kaLiRuvaZa_Gkucura_m",
			
			"nE_rnE_rnE_rpu" => "mAce_lkATu",
			"nE_rnirYnE_rpu" => "mApaTukATu",
			"nE_rnE_rpunE_rpu" => "mApOkukATu",
			"nE_rnirYpunE_rpu" => "mAvaZa_GkukATu",
			
			"nirYnE_rnE_rpu" => "pulice_lkATu",
			"nirYnirYnE_rpu" => "pulipaTukATu",
			"nirYnE_rpunE_rpu" => "pulipOkukATu",
			"nirYnirYpunE_rpu" => "pulivaZa_GkukATu",
			
			"nE_rpunE_rnE_rpu" => "pA_mpuce_lkATu",
			"nE_rpunirYnE_rpu" => "pA_mpupaTukATu",
			"nE_rpunE_rpunE_rpu" => "pA_mpupOkukATu",
			"nE_rpunirYpunE_rpu" => "pA_mpuvaZa_GkukATu",
			
			"nirYpunE_rnE_rpu" => "kaLiRuce_lkATu",
			"nirYpunirYnE_rpu" => "kaLiRupaTukATu",
			"nirYpunE_rpunE_rpu" => "kaLiRupOkukATu",
			"nirYpunirYpunE_rpu" => "kaLiRuvaZa_GkukATu",
			
			"nE_rnE_rnirYpu" => "mAce_lkaTaRu",
			"nE_rnirYnirYpu" => "mApaTukaTaRu",
			"nE_rnE_rpunirYpu" => "mApOkukaTaRu",
			"nE_rnirYpunirYpu" => "mAvaZa_GkukaTaRu",
			
			"nirYpunE_rnirYpu" => "pulice_lkaTaRu",
			"nirYpunirYnirYpu" => "pulipaTukaTaRu",
			"nirYpunE_rpunirYpu" => "pulipOkukaTaRu",
			"nirYpunirYpunirYpu" => "pulivaZa_GkukaTaRu",
			
			"nE_rpunE_rnirYpu" => "pA_mpuce_lkaTaRu",
			"nE_rpunirYnirYpu" => "pA_mpupaTukaTaRu",
			"nE_rpunE_rpunirYpu" => "pA_mpupOkukaTaRu",
			"nE_rpunirYpunirYpu" => "pA_mpuvaZa_GkukaTaRu",
			
			"nirYpunE_rnirYpu" => "kaLiRuce_lkaTaRu",
			"nirYpunirYnirYpu" => "kaLiRupaTukaTaRu",
			"nirYpunE_rpunirYpu" => "kaLiRupOkukaTaRu",
			"nirYpunirYpunirYpu" => "kaLiRuvaZa_GkukaTaRu" 
	);
	function CheckVenpaa() {
		return FALSE;
	}
	public function GetTextSyllablePattern($ProsodyText) 

	{
		$ProsodyText = preg_replace ( "/\(.*\)/", "", $ProsodyText ); // remov
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
				
				// $Word=str_replace(array("W","Y"),array("B","Q"),$Word); //
				// B-aukarakurukkam Q-Aikaarakurukkam
				
				$Word = preg_replace ( "/(\b.)B/", "$1W", $Word );
				
				$Word = preg_replace ( "/(\b.)Q/", "$1Y", $Word );
				
				/* Get Niraipu Words */
				
				preg_match_all ( '/([kGcJTNtnpmyrlvZLRVjSsh]?_?[aiueoBQ])([kGcJTNtnpmyrlvZLRVjSsh][aAiIuUeEoOYWBQ])(_[KkGcJTNtnpmyrlvZLRVjSsh])*([kGcJTNtnpmyrlvZLRVjSsh]u)/', $Word, $WordClassNiraipu, PREG_OFFSET_CAPTURE );
				
				foreach ( $WordClassNiraipu [0] as $Niraipu ) {
					$WordSyllable [$Niraipu [1]] = array (
							'nirYpu' => $Niraipu [0] 
					);
					
					$chr = "";
					
					for($i = 0; $i < strlen ( $Niraipu [0] ); $i ++)
						$chr = $chr . "^";
					
					$Word = preg_replace ( "/" . $Niraipu [0] . "/", $chr, $Word, 1 );
				}
				
				/* Get Nerpu Words */
				
				preg_match_all ( '/[kGcJTNtnpmyrlvZLRVjSsh]?_?[AIUEOQYBW](_[KkGcJTNtnpmyrlvZLRVjSsh])*([kGcJTNtnpmyrlvZLRVjSsh]u)/', $Word, $WordClassNerpu, PREG_OFFSET_CAPTURE );
				// preg_match_all('/[kGcJTNtnpmyrlvZLRVjSsh]?[aAiIuUeEoOYWBQ](_[KkGcJTNtnpmyrlvZLRVjSsh])*/',$wrd,$ner,PREG_OFFSET_CAPTURE);
				
				if (! empty ( $WordClassNerpu ))
					foreach ( $WordClassNerpu [0] as $Nerpu ) {
						$WordSyllable [$Nerpu [1]] = array (
								'nE_rpu' => $Nerpu [0] 
						);
						
						$chr = "";
						
						for($i = 0; $i < strlen ( $Nerpu [0] ); $i ++)
							$chr = $chr . "^";
						
						$Word = preg_replace ( "/" . $Nerpu [0] . "/", $chr, $Word, 1 );
					}
					
					/* Get Nerpu Words */
				
				preg_match_all ( '/[kGcJTNtnpmyrlvZLRVjSsh]?_?[aAiIuUeEoOQYBW](_[KkGcJTNtnpmyrlvZLRVjSsh])+([kGcJTNtnpmyrlvZLRVjSsh]u)/', $Word, $WordClassNerpu, PREG_OFFSET_CAPTURE );
				// preg_match_all('/[kGcJTNtnpmyrlvZLRVjSsh]?[aAiIuUeEoOYWBQ](_[KkGcJTNtnpmyrlvZLRVjSsh])*/',$wrd,$ner,PREG_OFFSET_CAPTURE);
				
				if (! empty ( $WordClassNerpu ))
					foreach ( $WordClassNerpu [0] as $Nerpu ) {
						$WordSyllable [$Nerpu [1]] = array (
								'nE_rpu' => $Nerpu [0] 
						);
						
						$chr = "";
						
						for($i = 0; $i < strlen ( $Nerpu [0] ); $i ++)
							$chr = $chr . "^";
						
						$Word = preg_replace ( "/" . $Nerpu [0] . "/", $chr, $Word, 1 );
					}
					
					/* Get Nirai Words */
				
				preg_match_all ( '/([kGcJTNtnpmyrlvZLRVjSsh]?_?[aiueoBQ])([kGcJTNtnpmyrlvZLRVjSsh][aAiIuUeEoOYWBQ])(_[KkGcJTNtnpmyrlvZLRVjSsh])*/', $Word, $WordClassNirai, PREG_OFFSET_CAPTURE );
				
				foreach ( $WordClassNirai [0] as $Nirai ) {
					$WordSyllable [$Nirai [1]] = array (
							'nirY' => $Nirai [0] 
					);
					
					$chr = "";
					
					for($i = 0; $i < strlen ( $Nirai [0] ); $i ++)
						$chr = $chr . "^";
					
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
}

?>