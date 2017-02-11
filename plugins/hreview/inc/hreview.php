<?php

function hreview_parse($texte) {

	// on essaie de le faire proprement s'il est encadré de paragraphes
	// sinon tant pis, le HTML sera sauvage
	$pattern = ",(\<p\>)?hreview\:(\d{1})(\</p\>)?,i";

	$seuil = 5; // les hreview c'est de 0 à 5
	if( preg_match($pattern, $texte, $matches)
		&& $matches[2] >= 0
		&& $matches[2] <= $seuil ) {

		$texte = preg_replace_callback($pattern, "hreview_callback", $texte);

	}

	return $texte;

}

function hreview_callback($matches) {

	$note = $matches[2];

	spip_log("HREVIEW : note = " . $note);
	$texte = "<div class='hreview'>";
	$texte .= "<strong>" . _T('hreview:note') . "</strong>" . $note;
	$texte .= "</div>";
	return $texte;

}

?>