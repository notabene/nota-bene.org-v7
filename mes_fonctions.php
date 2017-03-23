<?php

/**
 * Réglages génériques
 */
$GLOBALS['debut_intertitre'] = "\n<h2>";
$GLOBALS['fin_intertitre'] = "</h2>\n";
// $GLOBALS['puce'] = '<img src="/sites/notabene/v6/img/puce2.png" width="7" height="7" alt="*" />';

/*
	* gestion des abreviations
	* credits : Michel Valdrighi pour l'idee originale
	*           Julien Wajsberg et Gabriel Euzet pour l'aide a la simplification
	* */
function nb_abbr($str) {
	// reste a finir : definitions comme mots entiers
	$array_acronyms = array(
		'/ALA/' => '<abbr title="A List Apart">\\0</abbr>',
		'/ARIA/' => '<abbr title="Accessible Rich Internet Application">\\0</abbr>',
		'/CSS/' => '<abbr title="Cascading Stylesheets">\\0</abbr>',
		'/DOM/' => '<abbr title="Document Object Model">\\0</abbr>',
		'/([^a-z])(etc\.)/' => '\\1<abbr title="et c&aelig;tera">\\2</abbr>',
		'/GIF/' => '<abbr title="Graphic Interchange Format">\\0</abbr>',
		'/([^A-Z])(HTML)/' => '\\1<abbr title="Hypertext Markup Language">\\2</abbr>',
		'/JS/' => '<abbr title="Javascript">\\0</abbr>',
		'/MDC/' => '<abbr title="Mozilla Developer Center">\\0</abbr>',
		'/MSIE/' => '<abbr title="Microsoft Internet Explorer">\\1</abbr>',
		'/([^A-Z])(IE)([^A-Z])/' => '\\1<abbr title="Microsoft Internet Explorer">\\2</abbr>\\3',
		'/([^A-Z])(NN)([^A-Z])/' => '\\1<abbr title="Netscape Navigator">\\2</abbr>\\3',
		'/PDF/' => '<abbr title="Portable Document Format">\\0</abbr>',
		'/PNG/' => '<abbr title="Portable Network Graphics">\\0</abbr>',
		'/RSS/' => '<abbr title="Really Simple Syndication">\\0</abbr>',
		'/SEO/' => '<abbr title="Search Engine Optimization">\\0</abbr>',
		'/W[aA][sS]P/' => '<abbr title="Web Standards Project">WaSP</abbr>',
		'/W3C/' => '<abbr title="World Wide Web Consortium">\\0</abbr>',
		'/WCAG/' => '<abbr title="Web Content Accessibility Guidelines">\\0</abbr>',
		'/wifi/i' => '<abbr title="Wireless network">\\0</abbr>',
		'/XHTML/i' => '<abbr title="eXtensible Hypertext Markup Language">\\0</abbr>',
		'/XML/' => '<abbr title="eXtensible Markup Language">\\0</abbr>',
		'/XSLT/' => '<abbr title="eXtensible Style Language Transformation">\\0</abbr>',
		'/(XSL)([^A-Z])/' => '<abbr title="eXtensible Style Language">\\1</abbr>\\2'
	);

	if( !ereg('<',$str)) { // premier test pour le cas des titres
		$str = preg_replace(array_keys($array_acronyms), array_values($array_acronyms) , $str);
	} else {
		$coll = explode('<',$str);
		$str = '' . $coll[0]; // on prend le premier morceau, qu'il soit vide ou pas
		for($i=1;$i<count($coll);$i++) {
			$coll[$i] = '<' . $coll[$i];
			if(!preg_match('/(<abbr)/i',$coll[$i])) { // si le tag n'est ni abbr ni acronym
				$str .= substr($coll[$i],0,strpos($coll[$i],'>')+1)
					. preg_replace(array_keys($array_acronyms), array_values($array_acronyms) , substr($coll[$i],strpos($coll[$i],'>')+1));
			} else { // sinon passer tel quel et aller au tag suivant
				$str .= $coll[$i];
			}
		}
	}
	return $str;
}

/**
 * nb_FuzzyDate
 * donne des dates plus sympas dans les forums
 * @return
 * @param $date Object
 */
function nb_FuzzyDate($date) {
	if($date!='') {
		// this is now
		$now = date("U");
		// a day is
		$oneday = 3600 * 24;

		// reconstructing a proper date from what's in the database
		$test_date = preg_match_all(",[0-9]*,",$date,$matches);
		$Y = $matches[0][0];
		$M = $matches[0][2];
		$D = $matches[0][4];
		$computed = date("U", mktime(0,0,0,$M,$D,$Y) );

		// $diff is the number of days between $now and $computed
		$diff = floor(($now-$computed)/$oneday);

		// conditionally setting $date
		if($diff < 1) { // then it's today
			$date = _T('fuzzy_today');
		} else if($diff < 2) { // then it's yesterda
			$date = _T('fuzzy_yesterday');
		} else if($diff < 7) { // then it's last {weekday}
			$date = _T('fuzzy_last_w' . date("w",$computed) );
		} else { // too old: resorting to classical affdate display
			$date = affdate($date);
		}
	}
	return $date;
}

/**
 * nb_commentaires_nofollow
 * ajouter un attribut nofollow aux liens en commentaires
 * @return
 * @param $str Object
 */
function nb_commentaires_nofollow($str) {
	if($str != "") {
		$str = preg_replace("/rel=\".*\"/","",$str);
		$str = preg_replace("/a href=/","a rel=\"nofollow\" href=",$str);
	}

	return $str;
}

/**
 * Code de cerdic pour faire plus generique
 * @param unknown_type $str
 */
function filtre_nofollow_dist($str){
	   if($str) {
			   $liens = extraire_balises($str,'a');
			   foreach($liens as $lien){
					   $rel = extraire_attribut($lien,'rel');
					   $rel = preg_replace("/follow/","",$rel);
					   $rel = ($rel?"$rel ":"")."nofollow";
					   $ln = inserer_attribut($lien,'rel',$rel);
					   $str = str_replace($lien,$ln,$str);
			   }
	   }
	   return $str;
}

/**
	* gravatar pour l'email du mec qui commente
	* */

function nb_gravatar($str,$size=48) {
	if($str!='') {
		//$strtmp = 'http://www.gravatar.com/avatar.php?gravatar_id='.md5($str).'&amp;size='.$size.'&amp;rating=PG&amp;default=' . urlencode("http://www.nota-bene.org/rien.gif") ;
		$str = "gravatar-" . md5($str);
	}
	return $str;
}
/**
 * Retourne la date d'après le champ #EXIF de spip
 * @param unknown_type $array
 */
function nb_dateexif($array) {
	$str = "";
	if(is_array($array)) {
		$str = $array['DateTime'];
		// EXIF 2011:04:21 20:51:05
		// format attendu : 2011-04-21 20:51:05
		$str = substr($str,0,4) . "-" . substr($str,5,2) . "-" . substr($str,8,2) . " 00:00:00";
		$str = affdate($str);
	}
	return $str;
}

/* ajout detecter_langue d'arno mais pas en plugin
	Les sources sont dans squelettes/plugins/detecter_langue histoire de voir a quelle version cela correcpond
	*/

function detecter_langue($texte) {
	include_once("plugins/detecter_langue/inc/detecter_langue.php");
	return _detecter_langue($texte);

}

/**
 * Renvoie une classe en fonction de la longueur de $str
 * @param  $str String en entrée
 * @return String heading-size1|heading-size2|heading-size3
 */

function heading_class($str) {
	// VARIABLES
	$largest = 60;
	$larger = 40;
	$longueur = strlen($str);
	$suffixe = "1";
	$class = "heading-size";

	if($longueur > $largest) {
		$suffixe = "3";
	} else if($longueur > $larger) {
		$suffixe = "2";
	}
	return $class . $suffixe;
}


/**
 * basé sur [(#TEXTE|recherche_extrait{#RECHERCHE})]
 * @return $resultat String
 * @param $str String (#texte)
 * @param $recherche String
 */
function recherche_extrait($str,$recherche) {
	// VARIABLES
	// combien de caractères avant et après la chaîne trouvée
	$combien_autour = 80;
	$resultat = "";

	// on nettoie les chaines
	$str = strip_tags($str);
	$strlowercase = strtolower($str);
	$recherche = strtolower(strip_tags(safehtml($recherche)));

	// position de la chaine trouvée et longueur
	$pos = strpos($strlowercase,$recherche);
	$longueur = strlen($recherche);

	if($pos>0) { // si on a trouvé la chaine dans le texte
		$pos_avant = $pos-$combien_autour;
		$combien_avant = $combien_autour;
		if($pos_avant < 0) {
			// avec substr on repart de la fin du texte si le substr est trop long
			// il faut donc rogner à 0
			$pos_avant = 0;
			$combien_avant = $pos;
		}
		$resultat = "[…]&nbsp;"
			. recherche_propre_before(substr($str,$pos_avant,$combien_avant))
			. "<strong>"
			. substr($str,$pos,$longueur)
			. "</strong>"
			. recherche_propre_after(substr($str,$pos+$longueur,$combien_autour))
			. "&nbsp;[…]";
	}

	return safehtml($resultat);

}

function recherche_propre_before($str) {
	$str = substr($str,strpos($str," ")+1);
	return $str;
}
function recherche_propre_after($str) {
	$str = substr($str,0,strrpos($str," "));
	return $str;
}

/**
 * enlève les retours chariot dans les listitems
 * @param unknown_type $str
 */
function nb_propre($str) {
	if($str!="") {
		$str = preg_replace("/(\r|\n|\r\n)/"," ",$str);
	}
	return $str;
}

/**
 * ajoute un alt et un longdesc à une image #FICHIER
 * @param unknown_type $str
 * @param unknown_type $alt
 * @param unknown_type $longdesc
 * @param unknown_type $id_document
 */
function alt_et_longdesc($str,$alt,$longdesc='',$id_document='') {
	if($str != '') {
		$str = preg_replace("/img /","img alt='" . $alt . "' ",$str);
		if(strlen($longdesc)>0) {
			$str = preg_replace("/img /","img longdesc='/spip.php?page=longdesc&id_document=" . $id_document . "' ",$str);
		}
	}
	return $str;
}

/**
 * Compares a Hexadecimal color to white/black and returns true if contrasted enough
 * @param  (String) $hexcolor Hexadecimal color
 * @return (Boolean)           If true, contrast is enough towards white
 *
 * Some PHP taken from https://24ways.org/2010/calculating-color-contrast/
 * Calculus in this article came from https://www.w3.org/TR/AERT#color-contrast
 */
function getContrastOnWhite($hexcolor){
	// a good contrast is 5:1
	// https://www.w3.org/TR/2008/WD-UNDERSTANDING-WCAG20-20080430/visual-audio-contrast7.html
	$r = hexdec(substr($hexcolor,0,2));
	$g = hexdec(substr($hexcolor,2,2));
	$b = hexdec(substr($hexcolor,4,2));
	$yiq = (($r*299)+($g*587)+($b*114))/1000;
	$white = 255;
	return (($white+0.05)/($yiq+0.05) > 5);
}
function getContrastOnBlack($hexcolor){
	// a VERY good contrast is 7:1
	// https://www.w3.org/TR/2008/WD-UNDERSTANDING-WCAG20-20080430/visual-audio-contrast7.html
	$r = hexdec(substr($hexcolor,0,2));
	$g = hexdec(substr($hexcolor,2,2));
	$b = hexdec(substr($hexcolor,4,2));
	$yiq = (($r*299)+($g*587)+($b*114))/1000;
	return (($yiq)/(0.05) > 8);
}

/**
 * Takes a Hexadecimal color and contrasts it just enough
 * @param (String) $hexcolor Hexadecimal color
 */
function setContrastedColorOnWhite($hexcolor) {
	while(!getContrastOnWhite($hexcolor)) {
		$hexcolor = darken($hexcolor);
	}
	return $hexcolor;
}
function setContrastedColorOnBlack($hexcolor) {
	$hexcolor = shiftColor($hexcolor,80);
	while(!getContrastOnBlack($hexcolor)) {
		$hexcolor = lighten($hexcolor);
	}
	return $hexcolor;
}


/**
 * Darken/lighten by a step
 * inspired by https://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php#11951022
 * and simplified
 * @param  (String) $hexcolor Hexadecimal color
 * @return (String)           Hexadecimal color
 */
function darken($hexcolor) {
	$steps = -5;
	return shiftColor($hexcolor,$steps);
}
function lighten($hexcolor) {
	$steps = 5;
	return shiftColor($hexcolor,$steps);
}

/**
 * This is where the magic takes place
 * @param  (String) $hexcolor Hexadecimal color
 * @param  (Int) $steps       Steps either up or down to respectively lighten/darken
 * @return (String)           Hexadecimal color
 */
function shiftColor($hexcolor,$steps) {
	$colors = str_split($hexcolor,2);
	$hexcolor = "";
	foreach ($colors as $color) {
		$color = hexdec($color) + $steps;
		if($color < 0) $color = 0;
		if($color > 255) $color = 255;
		$color = dechex($color);
		if(strlen($color) == 1) $color = "0" . $color;
		$hexcolor .= $color;
	}
	return $hexcolor;
}

/**
 * Takes a string and returns a Hexadecimal color string
 * @param  (String) $str Any text
 * @return (String)      Hexadecimal color
 * Haha, this is exactly what tetue wanted to do https://zone.spip.org/trac/spip-zone/browser/_plugins_/numerology/todo.txt#L42
 */
function createHexFromText($str) {
	// we split the string in 3 to have 3 RGB strings
	$chunks = str_split($str, ceil(strlen($str)/3) );
	$hexcolor = "";
	// for each of them, we convert them to a hexadecimal value
	foreach ($chunks as $chunk) {
		$nb = 0;
		for($i=0 ; $i < strlen($chunk) ; $i++) {
			$nb += hexdec(bin2hex($chunk[$i]));
		}
		$nb = ($nb % 255);
		$color = dechex($nb);
		if(strlen($color) == 1) $color = "0" . $color;
		$hexcolor .= $color;
	}
	return $hexcolor;
}
?>