<html>
<head>
  <title>iPhocomp - Index of Phonetic Complexity / Indice de complexité phonétique</title>
  <meta name="Description" content="iPhocomp - Index of Phonetic Complexity / Indice de complexité phonétique">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" media="screen" title="Style" href="style.css">
  <link rel="icon" type="image/png" href="favicon.ico" />
</head>

<body style="padding:10px;margin:10px;text-align:justify;"> 
<!--
Copyright 2014-2019 - Hyeran Lee-Jean, Philippe Gambette

This file is part of iPhocomp.

    iPhocomp is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    iPhocomp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with iPhocomp.  If not, see <http://www.gnu.org/licenses/>.
-->

<div id="container">

<div id="topLogo">
<a href="http://igm.univ-mlv.fr/~gambette/iPhocomp"><img src="Logo-iPhocomp.png" style="margin:10px;" alt="iPhocomp"></a>
</div>
<div id="topBar">&nbsp;</div>
<div id="topTitle">Index of Phonetic Complexity</div>


<div style="clear:both;position:relative;width:48%;margin:0px;padding-right:10px;float:left;">
<p>iPhocomp estime l'<i>indice de complexité phonétique</i> d'une
liste de mots en français. Il est adapté de l'<i>Index of Phonetic
Complexity</i> de Kathy J. Jakielski pour l'anglais [1].
</p>
<p>Il utilise les transcriptions phonétiques extraites de la base de données
<i><a href="http://www.lexique.org">Lexique 3.80</a></i> [2].
</p>
</div>
<div style="position:relative;width:48%;margin:0px;padding-left:10px;float:right;">
<p>iPhocomp estimates the <i>Index of Phonetic Complexity</i> of a 
list of words in French, adapted from the Index of Phonetic Complexity 
for English developed by Kathy J. Jakielski [1].
</p>
<p>It uses phonetic transcriptions extracted from the
<i><a href="http://www.lexique.org">Lexique 3.80</a></i> database [2].
</p>
</div>

<div style="clear:both;background-color:#CCCCCC;padding:8px;padding-top:1px;padding-bottom:1px;">
<p>
<form name="input" action="iPhocomp.php" method="post">
Entrez votre liste de mots ici (un mot par ligne) :<br/>
<textarea rows="12" cols="95" name="texte">
pêche
agenda
samedi
vis
JEP</textarea><br/>
<INPUT type="submit" value="Calculer l'indice de complexité phonétique !" style="font-size:16px;font-family:'calibri', 'gill-sans','Gill Sans', 'Gill Sans MT',sans-serif;">
<br/>
<i>Si vous souhaitez utiliser des <b>transcriptions phonétiques personnalisées</b>,
merci de les ajouter dans le cadre de texte ci-dessous (en utilisant les
<a href="http://www.lexique.org/outils/Manuel_Lexique.htm#_Toc108519023">codes phonémiques de Lexique 3</a>),
elles seront <b>prioritaires</b> par rapport aux transcriptions de la base de données utilisée par iPhocomp :</i><br/>
<textarea rows="6" cols="95" name="transcriptions">
iphocomp;ifok§p
vis;vis
</textarea><br/>
</form>
</p>
</div>
<div style="clear:both;background-color:white;padding:0px;padding-top:10px;padding-bottom:1px;">
<p>
<h1>&Agrave; propos d'iPhocomp</h1>
Si vous utilisez iPhocomp (version 1.1 du 12/03/2019), merci de citer cette référence : <br/>
<span style="background-color:#CCCCCC;">Lee H.-R., Gambette P., Barkat-Defradas M. (2014), <a href="https://hal.archives-ouvertes.fr/hal-01277047">iPhocomp :
calcul automatique de l’indice de complexité phonétique de Jakielski</a>, session posters des XXXèmes Journées d'Etudes de la Parole (JEP 2014).</span>
<br/>
iPhocomp est un logiciel libre selon la licence GPLv3. Vous pouvez le télécharger
<a href="https://github.com/PhilippeGambette/iphocomp">ici</a>. Si vous le réutilisez,
nous vous invitons à faire un lien vers ce site web à l'endroit de l'interface
où vous évoquez la propriété intellectuelle.
</p>
<p>
<h1>Références</h1>
<ul style="margin:0px">
<li>[1] Jakielski K.J. (1998) <i>Motor organization
in the acquisition of consonant clusters</i>,
PhD thesis, University of Texas at Austin.</li>
<li>[2] New B., Pallier C., Ferrand L. &amp; Matos R. (2001)
Une base de données lexicales du français contemporain sur
internet: LEXIQUE, <i>L'Année Psychologique</i> 101:447-462,
<a href="http://www.lexique.org">www.lexique.org</a>.</li>
</ul>
iPhocomp utilise le script de découpage de mots
<a href="http://php-nlp-tools.com/documentation/api/NlpTools/Tokenizers/WhitespaceAndPunctuationTokenizer.html">WhitespaceAndPunctuationTokenizer</a>
de <a href="http://php-nlp-tools.com/">PHP NlpTools</a>.
Il utilise également le package PHP <a href="http://pear.php.net/package/Numbers_Words/redirected">Number_Words</a>
pour transcrire en français les nombres écrits sous forme de chiffres.
</p>
</div>
<div style="clear:both;text-align:center;margin-top:20px;"><small>&copy; 2014-2019 - <a href="https://www.traduction-coreen.com/">Hyeran Lee-Jean</a>, <a href="http://igm.univ-mlv.fr/~gambette/">Philippe Gambette</a> - Logo by Pierre Jean</small></div>
</div>
</body>
</html>