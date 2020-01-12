<?PHP

/*

Copyright (C) 2013 Vinodh Rajan vinodh@virtualvinodh.com

This file is a part of Avalokitam.

Avalokitam is free software: you  can redistribute it and/or modify it
under the  terms of the GNU  Affero General Public License as  published by the
Free Software Foundation,  either version 3 of the License,  or (at your
option) any later version.

This  program  is distributed  in  the  hope  that  it will  be  useful,
but  WITHOUT  ANY  WARRANTY;  without   even  the  implied  warranty  of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General
Public License for more details.

You should have received a copy  of the GNU Affero General Public License along
with this program. If not, see <http://www.gnu.org/licenses/>.

*/


if(isset($_REQUEST['txt']))
{
	if($_REQUEST['lang'] == "en")
		echo tam2iso(lat2tam($_REQUEST['txt']));
	else
	{
		$txt = str_replace(" ","+",$_REQUEST['txt']);
		$txt = str_replace("\n","",$txt);
		echo file_get_contents('http://aksharamukha.appspot.com/json?txt='.$txt.'&source=ISO&target=Tamil&preserve=1');
	}
}


function tam2lat($text)
{

$tameng=array(
'அ' => '_a',
'ஆ' => '_A',
'இ' => '_i',
'ஈ' => '_I',
'உ' => '_u',
'ஊ' => '_U',
'எ' => '_e',
'ஏ' => '_E',
'ஐ' => '_Y',
'ஒ' => '_o',
'ஓ' => '_O',
'ஔ' => '_W',
'க' => 'k',
'ங' => 'G',
'ச' => 'c',
'ஜ' => 'j',
'ஞ' => 'J',
'ட' => 'T',
'ண' => 'N',
'த' => 't',
'ந' => 'n',
'ன' => 'V',
'ப' => 'p',
'ம' => 'm',
'ய' => 'y',
'ர' => 'r',
'ற' => 'R',
'ல' => 'l',
'ள'=> 'L',
'ழ' => 'Z',
'வ' => 'v',
'ஶ' => 'F',
'ஷ' => 'S',
'ஸ' => 's',
'ஹ' => 'h',
'ா' => 'A',
'ி' => 'i',
'ீ' => 'I',
'ு' => 'u',
'ூ' => 'U',
'ெ' => 'e',
'ே' => 'E',
'ை' => 'Y',
'ொ' => 'o',
'ோ' => 'O',
'ௌ' => 'W',
'்' => 'X',
'ஃ' => '_K'
);

foreach($tameng as $key=>$value)
{
	$text=str_replace($key,$value,$text); 
}

$text=preg_replace('/([kGcJTNtnpmyrlvZLRVjSsh])X/','_$1',$text);

$text=preg_replace('/(?<!_)([kGcJTNtnpmyrlvZLRVjSsh])(?![aAiIuUeEoOYW])/','$1a',$text);

$text = preg_replace('/(_[kGcJTNtnpmyrlvZLRVjSsh])(_[aAiIuUeEoOYW])/','$1_$2',$text);

return($text);

}

function lat2tam($text)
{

$tameng=array(
'அ' => '_a',
'ஆ' => '_A',
'இ' => '_i',
'ஈ' => '_I',
'உ' => '_u',
'ஊ' => '_U',
'எ' => '_e',
'ஏ' => '_E',
'ஐ' => '_Y',
'ஒ' => '_o',
'ஓ' => '_O',
'ஔ' => '_W',
'க' => 'k',
'ங' => 'G',
'ச' => 'c',
'ஜ' => 'j',
'ஞ' => 'J',
'ட' => 'T',
'ண' => 'N',
'த' => 't',
'ந' => 'n',
'ன' => 'V',
'ப' => 'p',
'ம' => 'm',
'ய' => 'y',
'ர' => 'r',
'ற' => 'R',
'ல' => 'l',
'ள'=> 'L',
'ழ' => 'Z',
'வ' => 'v',
'ஶ' => 'F',
'ஷ' => 'S',
'ஸ' => 's',
'ஹ' => 'h',
'ா' => 'A',
'ி' => 'i',
'ீ' => 'I',
'ு' => 'u',
'ூ' => 'U',
'ெ' => 'e',
'ே' => 'E',
'ை' => 'Y',
'ொ' => 'o',
'ோ' => 'O',
'ௌ' => 'W',
'ஃ' => '_K',
);

//return($text);

$text=preg_replace('/_([kGcJTNtnpmyrlvZLRVjSsh])/','$1்',$text);


foreach($tameng as $key=>$value)
{
	$text=str_replace($value,$key,$text);
}

$text=str_replace(array("a","B","Q"),array("","ௌ","ை"),$text);
$text=str_replace("_","",$text);

return($text);

}

function tam2iso($text)
{

$tameng=array(
'அ' => 'a',
'ஆ' => 'ā',
'இ' => 'i',
'ஈ' => 'ī',
'உ' => 'u',
'ஊ' => 'ū',
'எ' => 'e',
'ஏ' => 'ē',
'ஐ' => 'ai',
'ஒ' => 'o',
'ஓ' => 'ō',
'ஔ' => 'ai',
'க' => 'ka',
'ங' => 'ṅa',
'ச' => 'ca',
'ஜ' => 'ja',
'ஞ' => 'ña',
'ட' => 'ṭa',
'ண' => 'ṇa',
'த' => 'ta',
'ந' => 'na',
'ன' => 'ṉa',
'ப' => 'pa',
'ம' => 'ma',
'ய' => 'ya',
'ர' => 'ra',
'ற' => 'ṟa',
'ல' => 'la',
'ள'=> 'ḷa',
'ழ' => 'ḻa',
'வ' => 'va',
'ஶ' => 'śa',
'ஷ' => 'ṣa',
'ஸ' => 'sa',
'ஹ' => 'ha',
'ா' => '_ā',
'ி' => '_i',
'ீ' => '_ī',
'ு' => '_u',
'ூ' => '_ū',
'ெ' => '_e',
'ே' => '_ē',
'ை' => '_ai',
'ொ' => '_o',
'ோ' => '_ō',
'ௌ' => '_au',
'்' => 'X',
'ஃ' => 'ḵ'
);

foreach($tameng as $key=>$value)
{
	$text=str_replace($key,$value,$text); 
}

$text=str_replace("aX","",$text);
$text=str_replace("a_","",$text);

return($text);

}

function lancon($text,$lang)

{

	if($lang == "en")
		return tam2iso($text);
	else
		return $text;

}

?>