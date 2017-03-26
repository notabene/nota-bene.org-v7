
$(document).ready(function(){

	/**
	 * redirect articles : liens vers forums ('repondre a') remplissent un formulaire sur place
	 */

	$('a[data-id-forum]').each(function(){
		$(this).click(function(){
			// trouver a qui on repond
			var idforum = $(this).data('id-forum');

			// ajouter un @-reply en debut de champ texte
			// 1. focus, 2. remplir avec le contenu de span.nom du li parent
			$('div#formulaire_forum legend:first').html( $(this).text() );
			$('div#formulaire_forum textarea#texte')
				.focus()
				.prepend( '&lt;a href="#forum' + idforum + '"&gt;' + $(this).closest('li').find('span.nom').text() + '&lt;/a&gt; : ' );

			return false;
		});
	});

	/**
	 * pullquotes
	 */
	$('span.pullquote').each(function(){
		var t = $(this).text();
		t = t.substring(0,1).toUpperCase() + t.substring(1);
		$(this).closest('p').before('<h3 class="pullquote">' + t + '</h3>');
	});

});