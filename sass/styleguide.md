# Styleguide pour nota-bene.org v7

J'essaie de documenter le design du site proprement avec [Grunt](http://gruntjs.com/creating-tasks) et [KSS](https://github.com/kneath/kss) via [grunt-kss](https://www.npmjs.com/package/grunt-kss).

## Organisation du Styleguide

Au petit bonheur la chance.

Au moment où j'écris ces lignes, il y a des styles par défaut et puis des styles pour le *RWD*.

## Snippets de code

Ils s'insèrent de deux façons :

* soit on les indique dans les commentaires au-dessus d'une fonction :

  	// Titres
  	// 
  	// Titres de rangs `hn`
  	// 
  	// Markup:
  	// <h1>Ceci est un titre 1</h1>
  	// <h2>Ceci est un titre 2</h2>
  	// <h3>Ceci est un titre 3</h3>

* soit on appelle un template `hbs` dans les commentaires :

  	// Headings
  	// 
  	// Tailles de titres en fonction de la longueur
  	// 
  	// .heading-size1	- Taille la plus grande
  	// .heading-size2	- Taille moyenne
  	// .heading-size3	- Taille la plus petite
  	// 
  	// Markup: headings.hbs

Dans ce dernier cas, le `hbs` inclut du code *Handlebar* qui va parcourir les classes :

1.	**Affichage par défaut**
	(autrement dit la balise sans classe)

2.	**Affichage de toutes les classes listées**
	(autrement dit ici les 3 classes listées)

Par example, `headings.hbs` :

	<h2 class="{{modifier_class}}">Titre de rang 2</h2>

… renvoie :

	<div class="kss-modifier__name kss-style">
	  Default styling
	</div>
	<div class="kss-modifier__example">
	  <h2 class="">Titre de rang 2</h2>
	</div>
	<div class="kss-modifier__name kss-style">
	  .heading-size1
	</div>
	<div class="kss-modifier__description kss-style">
	  Taille la plus grande
	</div>
	<div class="kss-modifier__example">
	  <h2 class="heading-size1">Titre de rang 2</h2>
	</div>
	<div class="kss-modifier__name kss-style">
	  .heading-size2
	</div>

(etc.)

---