<?php

function hreview_post_propre($texte) {
	include_spip("inc/hreview");
	return hreview_parse($texte);
}

?>