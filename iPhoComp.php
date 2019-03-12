<html>
<head>
  <title>iPhocomp - Index of Phonetic Complexity / Indice de complexité phonétique</title>
  <meta name="Description" content="iPhocomp - Index of Phonetic Complexity / Indice de complexité phonétique">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" media="screen" title="Style" href="style.css">
  <link rel="icon" type="image/png" href="favicon.ico" />
  <script type="text/javascript"> 
  function aff(objet){ 
  document.getElementById(objet).style.display = "block";
  } 
  function cache(objet){ 
  document.getElementById(objet).style.display = "none";
  } 
  function cacheTableau(){
  cache('tableau');
  aff('affichage');
  }
  function affTableau(){
  aff('tableau');
  cache('affichage');
  }
  </script> 
</head>

<body style="padding:10px;margin:10px;text-align:justify;font-family:'gill-sans','Gill Sans', 'Gill Sans MT',sans-serif;"> 
<!--
Copyright 2014 - Hyeran Lee-Jean, Philippe Gambette

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

<p style="clear:both;">
<h1>Résultats obtenus</h1>
<?php

$texte = "";

if(isset($_POST['texte'])){
   $texte=$_POST['texte'];
   //echo $texte;
} else {
   $texte="iPhocomp";
}


//regular expression to convert a 2-column CSV file into a PHP array
//(.*)\t(.*)
//"\1" => "\2",

$timeBegin=microtime();


//Creating dictionaries
$dicoPunc=array("'" => "-", "’" => "-", "\"" => "-", "." => "-", "," => "-", "·" => "-", "─" => "…",
":" => "-", ";" => "-", "!" => "-", "?" => "-", "-" => "-", "_" => "-", "—" => "-", "─" => "-",
"\\" => "-", "(" => "-", ")" => "-", "[" => "-", "]" => "-", "{" => "-", "}" => "-",
"/" => "-", "|" => "-", "•" => "-", "«" => "-", "»" => "-", "“" => "-", "”" => "-") ;

include "Words.php";
include 'DicoPhon.php';
include 'iPhocomp_functions.php';

//Phonetic dictionary preprocessing:

$plines = preg_split("/((\r?\n)|(\r\n?))/", $transcriptions);

$nbTrans=0;
foreach($plines as $key => $line){
  if (strlen($line)>0){
  $trans = explode(";", $line);
  $dicoPhon[$trans[0]]=$trans[1];
  $nbTrans++;
  }
}
if($nbTrans==1){
  echo "<i>Une transcription personnalisée prise en compte dans le dictionnaire phonétique.</i><br/>";
}
if($nbTrans>1){
  echo "<i>".$nbTrans." transcriptions personnalisées prises en compte dans le dictionnaire phonétique.</i><br/>";
}


//Text preprocessing:

//$lines = explode("\r\n", $texte);
$lines = preg_split("/((\r?\n)|(\r\n?))/", $texte);

//Count the number of possible problems:
$nbEcaducs=0;
$nbNotRecognized=0;
$nbNonHomophonHomographs=0;

echo "<div id=\"tableau\" style=\"display:none\"><a href=\"javascript:cacheTableau();\"><i>Cacher les résultats détaillés</i></a><br/>";
echo "<!--<small>Ci-dessous, les mots non reconnus dans la base <i>Lexique 3</i>
(et qui nécessitent donc un traitement manuel) sont indiqués sur fond rouge.
Les mots qui nécessitent une vérification de la transcription phonétique
sont indiqués sur fond orange (cas des homographes non homophones)
ou jaune (e caducs).</small>-->";
echo "<table border=1px>";
echo "<tr><th>Mot</th><th>Prononciation</th><th>Param. 1</th><th>Param. 2</th><th>Param. 3</th><th>Param. 4</th><th>Param. 5</th><th>Param. 6</th><th>Param. 7</th><th>Param. 8</th><th>IPC</th></tr>";

$nbWords=0;
$nbRecognizedWords=0;
$notRecognized=array();
$paramSum = array(0,0,0,0,0,0,0,0);

foreach($lines as $key => $line){

  //Got this function from http://php-nlp-tools.com/ 
  $arr = array();
  // for the character classes
  // see http://php.net/manual/en/regexp.reference.unicode.php
  $pat = '/
                    ([\pZ\pC]*)			# match any separator or other
                                        # in sequence
                    (
                        [^\pP\pZ\pC]+ |	# match a sequence of characters
                                        # that are not punctuation,
                                        # separator or other

                        .				# match punctuations one by one
                    )
                    ([\pZ\pC]*)			# match a sequence of separators
                                        # that follows
                /xu';
  preg_match_all($pat,$line,$arr);
  
  $previousWord="";
  $previousWordNotRecognized=false;
  $isOrange=false;
  $phon="";
  $previousPhon;
  
  foreach($arr[2] as $key => $word){
    $word=str_replace(array("œ"), array("oe"), $word);
    
    //Transcribe numbers
    if (intval($word)>0){
      $word=utf8_encode(Numbers_Words::toWords($word,"fr",array()));
    }

    $word = strtolower_utf8($word);
        
    if($previousWordNotRecognized){
      //Is the word a punctuation sign? (then we do nothing)
      
      if(!array_key_exists($word,$dicoPunc)){
        //Try to recognized a multiword expression
        if(array_key_exists($previousWord.$word,$dicoPhon)){
          $word=$previousWord.$word;
          $previousWordNotRecognized=false;
        }
        if(array_key_exists($previousWord." ".$word,$dicoPhon)){
          $word=$previousWord." ".$word;
          $previousWordNotRecognized=false;
        }
        if(array_key_exists($previousWord."-".$word,$dicoPhon)){
          $word=$previousWord."-".$word;
          $previousWordNotRecognized=false;
        }
        if($previousWordNotRecognized){
          //If not recognized, maybe after we add the next word
          $previousWord=$previousWord.$word;
        } else {
          //Multiword expression recognized!
          $previousPhon=$phon;
          $phon=$phon.$dicoPhon[$word];
        }
      }
    } else {
    if(!array_key_exists($word,$dicoPunc)){
      //Is the word in the Lexique phonetic database?
      if(array_key_exists($word,$dicoPhon)){
        //Is there possible amibiguity?        
        if(array_key_exists($word,$dicoHomog)){
          $isOrange=true;
        }
        $previousPhon=$phon;
        $phon=$phon.$dicoPhon[$word];
      } else {
        $WordNotRecognized=true;
        //Try to recognized a multiword expression        
        if(array_key_exists($previousWord.$word,$dicoPhon)){
          $word=$previousWord.$word;
          $WordNotRecognized=false;
        }
        if(array_key_exists($previousWord." ".$word,$dicoPhon)){
          $word=$previousWord." ".$word;
          $WordNotRecognized=false;
        }
        if(array_key_exists($previousWord."-".$word,$dicoPhon)){
          $word=$previousWord."-".$word;
          $WordNotRecognized=false;
        }
        if($WordNotRecognized){
          //If not recognized, maybe after we add the next word
          $previousWordNotRecognized=true;
        } else {
          //Multiword expression recognized!
          $phon=$previousPhon.$dicoPhon[$word];
          $previousWordNotRecognized=false;
        }        
      }
      $previousWord=$word;
    }
    }
  }
  
  $completeWord=str_replace(array("œ"), array("oe"), strtolower_utf8($line));
  $nbWords++;
  
  if ($previousWordNotRecognized){
    echo "<td style=\"background-color:red;\">".$completeWord."</td><td></td>";
    $nbNotRecognized+=1;
    if(!(array_key_exists($completeWord,$notRecognized))){
      $notRecognized[$completeWord]=$line;
    }  
    $phon="";    
  } else {
    $analyzeIt=true;
    $nbRecognizedWords++;
    if ($isOrange){
      echo "<td style=\"background-color:orange;\">".$completeWord."</td><td>".$phon."</td>";
      $nbNonHomophonHomographs+=1;
    } else {
      if(eCaduc($phon)){
        echo "<td style=\"background-color:yellow;\">".$completeWord."</td><td>".$phon."</td>";
       $nbEcaducs+=1;
      } else {
        echo "<td>".$completeWord."</td><td>".$phon."</td>";
      }
    }
  }

  $param = array(0,0,0,0,0,0,0,0);
  
  #Parameter 1
  echo "<td>";
  if($analyzeIt){
    for ($i=0; $i<strlen($phon); $i++) {
      if (($phon[$i]=="k") or ($phon[$i]=="g") or ($phon[$i]=="R") or ($phon[$i]=="G")){
        $param[0]++;
      }
    }
    $paramSum[0]=$paramSum[0]+$param[0];
    echo $param[0];
  }
  echo "</td>";

  #Parameter 2
  echo "<td>";
  if($analyzeIt){
    for ($i=0; $i<strlen($phon); $i++) {
      if (($phon[$i]=="f") or ($phon[$i]=="v") or ($phon[$i]=="s") or ($phon[$i]=="z") or ($phon[$i]=="S") or ($phon[$i]=="Z") or ($phon[$i]=="l") or ($phon[$i]=="R")){
        $param[1]++;
      }    
    }
    $paramSum[1]=$paramSum[1]+$param[1];
    echo $param[1];
  }
  echo "</td>";

  #Parameter 3 - Empty for French
  echo "<td>";
  if($analyzeIt){
    $paramSum[2]=$paramSum[2]+$param[2];
    echo $param[2];
  }
  echo "</td>";
      
  #Parameter 4
  echo "<td>";
  if($analyzeIt){
    if (isConsonant($phon[strlen($phon)-1])){
      $param[3]++;
    }
    $paramSum[3]=$paramSum[3]+$param[3];
    echo $param[3];
  }
  echo "</td>";

  #Parameter 5
  echo "<td>";
  if($analyzeIt){
    for ($i=0; $i<strlen($phon); $i++) {
      if ((mb_substr($phon,$i,1,"utf-8")=="a") or (mb_substr($phon,$i,1,"utf-8")=="2") or (mb_substr($phon,$i,1,"utf-8")=="9")
      or (mb_substr($phon,$i,1,"utf-8")=="e") or (mb_substr($phon,$i,1,"utf-8")=="°")
      or (mb_substr($phon,$i,1,"utf-8")=="E") or (mb_substr($phon,$i,1,"utf-8")=="i") or (mb_substr($phon,$i,1,"utf-8")=="O")
      or (mb_substr($phon,$i,1,"utf-8")=="o") or (mb_substr($phon,$i,1,"utf-8")=="u") or (mb_substr($phon,$i,1,"utf-8")=="y")
      or (mb_substr($phon,$i,1,"utf-8")=="@") or (mb_substr($phon,$i,1,"utf-8")=="5") or (mb_substr($phon,$i,1,"utf-8")=="1")
      or (mb_substr($phon,$i,1,"utf-8")=="§")){       
        $param[4]+=1;
      }
    }
    if($param[4]>2){
      $param[4]=1;
    } else {
      $param[4]=0;
    }
    $paramSum[4]=$paramSum[4]+$param[4];
    echo $param[4];
  }
  echo "</td>";

  #Parameter 6
  $consonantTypes=array(0,0,0,0,0,0,0,0,0);
  echo "<td>";
  if($analyzeIt){
    $previousLetter=-1;
    $newPhon="";
    //Create a new word newPhon keeping only isolated consonants
    for ($i=0; $i<strlen($phon)-1; $i++) {
      if (($previousLetter==-1) and (consonantType($phon[$i])>-1) and (consonantType($phon[$i+1])==-1)){
        $newPhon=$newPhon.$phon[$i];
      }
      $previousLetter=consonantType($phon[$i]);
    }
    if (strlen($phon)>1){
      if ((consonantType($phon[strlen($phon)-2])==-1) and (consonantType($phon[strlen($phon)-1])>-1)){
        $newPhon=$newPhon.$phon[strlen($phon)-1];
      }
    }
    
    for ($i=0; $i<strlen($newPhon); $i++) {
      $consType=consonantType($newPhon[$i]);
      if ($consType>-1){
        $consonantTypes[$consType]++;
      }    
    }
    //count the number of types of consonants
    //which have at least one occurrence in the word:
    $nbConsTypes=0;
    //labiales
    if (($consonantTypes[0]>0) or ($consonantTypes[1]>0)){
      $nbConsTypes++;
    }
    //coronales
    if (($consonantTypes[2]>0) or ($consonantTypes[3]>0) or ($consonantTypes[4]>0) or ($consonantTypes[5]>0) or ($consonantTypes[6]>0)){
      $nbConsTypes++;
    }
    //dorsales
    if (($consonantTypes[7]>0) or ($consonantTypes[8]>0)){
      $nbConsTypes++;
    }
    //add 1 if more than one type of consonant is found
    if($nbConsTypes>1){
      $param[5]=1;
    }else{
      $param[5]=0;
    }
    $paramSum[5]=$paramSum[5]+$param[5];
    echo $param[5];
  }
  echo "</td>";
  
  #Parameter 7
  echo "<td>";
  if($analyzeIt){
    $nbConsecutiveConsonants=0;
    $nbConsonantClusters=0;
    for ($i=0; $i<strlen($phon); $i++) {
      if(isConsonant($phon[$i])){
        $nbConsecutiveConsonants++;
      } else {
        if($nbConsecutiveConsonants>1){
          $nbConsonantClusters++;          
        }
        $nbConsecutiveConsonants=0;
      }
    }
    if($nbConsecutiveConsonants>1){
      $nbConsonantClusters++;
    }
    $param[6]=$nbConsonantClusters;
    $paramSum[6]=$paramSum[6]+$param[6];    
    echo $param[6];
  }
  echo "</td>";
  
  #Parameter 8
  echo "<td>";
  if($analyzeIt){
    $nbConsonantChanges=0;
    $previousConsonantType=-1;
    for ($i=0; $i<strlen($phon); $i++) {
      if(isConsonant($phon[$i])){
        if($previousConsonantType>-1){
          if ($previousConsonantType!=consonantBigType($phon[$i])){
            $nbConsonantChanges+=1;
          }
        }
        $previousConsonantType=consonantBigType($phon[$i]);
      } else {
        $previousConsonantType=-1;
      }
    }
    $param[7]=$nbConsonantChanges;
    $paramSum[7]=$paramSum[7]+$param[7];
    echo $param[7];
  }
  echo "</td>";


  #Index of Phonetic Complexity
  echo "<td>";
  $ipc=0;
  if($analyzeIt){
    for ($i=0; $i<count($param); $i++) {
      $ipc = $ipc+$param[$i];
    }
  echo "<b>".$ipc."</b>";
  }

  echo "</td>";

  
  echo "</tr>\n";
  
}

  echo "<tr><th>TOTAL</th><th></th>
  <th>".(round($paramSum[0]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[1]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[2]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[3]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[4]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[5]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[6]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[7]/$nbRecognizedWords,3))."</th>
  <th>".(round(($paramSum[0]+$paramSum[1]+$paramSum[2]+$paramSum[3]+$paramSum[4]+$paramSum[5]+$paramSum[6]+$paramSum[7])/$nbRecognizedWords,3))."</th></tr>\n";

echo "</table><br/></div>\n";

echo "<table border=1px id=\"tableau\">";
echo "<tr><th>Param. 1</th><th>Param. 2</th><th>Param. 3</th><th>Param. 4</th><th>Param. 5</th><th>Param. 6</th><th>Param. 7</th><th>Param. 8</th><th>IPC</th></tr>";
  echo "<tr>
  <th>".(round($paramSum[0]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[1]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[2]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[3]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[4]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[5]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[6]/$nbRecognizedWords,3))."</th>
  <th>".(round($paramSum[7]/$nbRecognizedWords,3))."</th>
  <th>".(round(($paramSum[0]+$paramSum[1]+$paramSum[2]+$paramSum[3]+$paramSum[4]+$paramSum[5]+$paramSum[6]+$paramSum[7])/$nbRecognizedWords,3))."</th></tr>\n";

echo "</table>\n";


echo "<div id=\"affichage\"><a href=\"javascript:affTableau();\"><i>Afficher les résultats détaillés</i></a></div>";

echo "\n";
$timeEnd=microtime();

if($nbEcaducs+$nbNotRecognized+$nbNonHomophonHomographs>0){
  //Display the number of words which could cause problems
  echo "<p>Attention, la liste de mots analysés contient ";
  if($nbEcaducs+$nbNotRecognized+$nbNonHomophonHomographs>1){
    echo ($nbEcaducs+$nbNotRecognized+$nbNonHomophonHomographs)." mots potentiellement problématiques :";
    $previous=0;
    if ($nbNotRecognized>0){
      if ($nbNotRecognized>1){
        echo " ".$nbNotRecognized." <span style=\"background-color:red;\">mots non reconnus donc pas analysés</span>";
        $previous=1;
      } else {
        echo " un <span style=\"background-color:red;\">mot non reconnu donc pas analysé</span>";
        $previous=1;
      }
    }
    if ($nbNonHomophonHomographs>0){
      if ($previous==1){
        echo ",";
      }
      if ($nbNonHomophonHomographs>1){
        echo " ".$nbNonHomophonHomographs." <span style=\"background-color:orange;\">mots avec de possibles homographes non homophones</span>";
        $previous=1;
      } else {
        echo " un <span style=\"background-color:orange;\">mot avec de possibles homographes non homophones</span>";
        $previous=1;
      }
    }
    if ($nbEcaducs>0){
      if ($previous==1){
        echo ",";
      }
      if ($nbEcaducs>1){
        echo " ".$nbEcaducs." <span style=\"background-color:yellow;\">problèmes de e caduc</span>";
        $previous=1;
      } else {
        echo " un <span style=\"background-color:yellow;\">problème de e caduc</span>";
        $previous=1;
      }
    }
    echo ".</p>";

  } else {
    echo " un mot potentiellement problématique,";
    if ($nbEcaducs>0){
      echo " <span style=\"background-color:yellow;\">en raison d'un e caduc</span>.</p>";
    }
    if ($nbNotRecognized>0){
      echo " car <span style=\"background-color:red;\">non reconnu, donc pas analysé</span>.</p>";
    }
    if ($nbNonHomophonHomographs>0){
      echo " <span style=\"background-color:orange;\">en raison de possibles homographes non homophones</span>.</p>";
    }
  }
}

echo "<p>Temps passé : ".abs($timeEnd-$timeBegin)." ms.<br/>\n";
echo $nbRecognizedWords." mots reconnus / ".$nbWords." : ".(round($nbRecognizedWords*100/$nbWords,2))."%.</p>\n";

// Display list of non recognized words
$notRecognizedWords=array_keys($notRecognized);
sort($notRecognizedWords);

echo "<h1>Mots non reconnus</h1>";
echo "Les formes en minuscules sont données, la forme originale est indiquée entre parenthèses :\n<ul style=\"margin:0px;\">";
foreach($notRecognizedWords as $key => $word){
  echo "<li>".$word." (".$notRecognized[$word].")</li>\n";
}
echo "</ul>";

// Display text area to manually add a phonetic transcription of unrecognized words
echo "<h1>Transcription des mots non reconnus</h1>";
echo "Vous pouvez, si vous le souhaitez, enregistrer une transcription phonétique des mots non reconnus
pour la charger ensuite lors de l'envoi du texte, afin qu'elle soit prise en compte.
<!--<span style=\"color:red;font-weight:bold;\">(attention, cette fonction n'est actuellement pas disponible !).</span>-->
Une tentative de transcription automatique basique, qui nécessite une relecture attentive, est donnée sur chaque ligne.";
echo "<form name=\"input\" action=\"iPhocomp_addWords.php\" method=\"post\">";
echo "<textarea style=\"font-family:'gill-sans','Gill Sans', 'Gill Sans MT',sans-serif;\" rows=\"20\" cols=\"50\" name=\"newWords\">";
foreach($notRecognizedWords as $key => $word){
  echo $word.";".phonetic($word)."\n";
}
echo "</textarea></form>";
?>
</p>
<p>
<h1>&Agrave; propos de ces résultats</h1>
Le fonctionnement d'iPhocomp et les règles de calcul de l'indice
de complexité phonétique et des 8 paramètres qui le composent sont
détaillés <a href="2014LeeGambette.pdf">dans cet article de présentation</a>.
Pour en savoir plus, vous pouvez contacter
<a href="http://www.praxiling.fr/lee-hyeran.html">Hyeran Lee-Jean</a>.
</p>

</div>
</p>
</body>

</html>