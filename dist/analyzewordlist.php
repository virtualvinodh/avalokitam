<?php

ignore_user_abort(1); // run script in background even if user closes browser
set_time_limit(1800); // run it for 30 minutes

require_once "transliteration.php";
require_once "parsetreeclass.php";

$file = fopen("wordlist.txt", "r") or exit("Unable to open file!");

$word_array = array();

while(!feof($file))
  {
   $word_array[] =  trim(fgets($file));
  }
fclose($file);

$myfile = fopen("wordlistprocessed.txt", "w") or die("Unable to open file!");

foreach ($word_array as $word) {
   $ptree = new ProsodyParseTree ( $word, 'ta', False );
   $formula = lat2tam($ptree->ParseTreeRoot[0]['pA']['aTi-1']['cI_r-1']['meta']);
   $matracount = $ptree->GetMatraCount(tam2lat($word));
   if (strlen($formula) > 1) {
      fwrite($myfile, $word . ',' . $formula . ',' . $matracount . "\n");
   }
}

fclose($myfile);

echo "complete";

?>