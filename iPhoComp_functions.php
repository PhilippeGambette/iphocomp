<?php
/*
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
*/

function isConsonant($letter){
  if(($letter=="p") or ($letter=="b") or ($letter=="t") or ($letter=="d") or ($letter=="k") or ($letter=="g") or 
   ($letter=="f") or ($letter=="v") or ($letter=="s") or ($letter=="z") or ($letter=="S") or ($letter=="Z") or 
   ($letter=="m") or ($letter=="n") or 
   ($letter=="N") or 
   ($letter=="l") or 
   ($letter=="R") or 
   ($letter=="x") or 
   ($letter=="G")){ 
    return true;
  }else{
    return false;
  }
}

function eCaduc($phon){
  $eCaducFound=false;
  if (strlen($phon)>4){
  for ($i=1; $i<strlen($phon)-1; $i++) {
    if ($phon[$i].$phon[$i+1]=="°"){
      if ($i==1){
         if (isConsonant($phon[0]) and isConsonant($phon[3]) and (!(isConsonant($phon[4])))){
            $eCaducFound=true;
         }
      } else {
         if (!(isConsonant($phon[$i-2])) and isConsonant($phon[$i-1]) and isConsonant($phon[$i+2])){
            $eCaducFound=true;
         }
      }      
    }
  }
  return $eCaducFound;
  }
}


function consonantBigType($letter){
  $result=-1;
  if((consonantType($letter)==0) or (consonantType($letter)==1)){
    //labiale
    $result=0;
  }
  if((consonantType($letter)==2) or (consonantType($letter)==3) or (consonantType($letter)==4) or (consonantType($letter)==5) or (consonantType($letter)==6)){
    //coronale
    $result=1;
  }
  if((consonantType($letter)==7) or (consonantType($letter)==8)){
    //dorsale 
    $result=2;
  }
  return $result;

}

function consonantType($letter){
  $result=-1;
  if(($letter=="p") or ($letter=="b") or ($letter=="m")){
    //bilabiale
    $result=0;
  }
  if(($letter=="f") or ($letter=="v")){
    //labio-dentale
    $result=1;
  }
  if(($letter=="t") or ($letter=="d") or ($letter=="n")){
    //apico-dentale
    $result=2;
  }
  if(($letter=="l")){
    //apico-alvéolaire
    $result=3;
  }
  if(($letter=="s") or ($letter=="z")){
    //alvéolaire
    $result=4;
  }
  if(($letter=="S") or ($letter=="Z")){
    //post-alvéolaire
    $result=5;
  }
  if(($letter=="N")){
    //palatale
    $result=6;
  }
  if(($letter=="G") or ($letter=="k") or ($letter=="g")){
    //vélaire
    $result=7;
  }
  if(($letter=="R")){
    //uvulaire
    $result=8;
  }
  return $result;
}

//function by leha_grobov from http://php.net/manual/fr/function.strtolower.php
function strtolower_utf8($string){ 
  $convert_to = array( 
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", 
    "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", 
    "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý"
  ); 
  $convert_from = array( 
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", 
    "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", 
    "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý"
  ); 

  return str_replace($convert_from, $convert_to, $string); 
} 

function phonetic($string){ 
  $string = str_replace(array("-"), array(""), $string);
  $string = str_replace(array("j"), array("Z"), $string);

  $string = str_replace(array("ain", "ein"), array("5", "5"), $string);

  $string = str_replace(array("in","an","on"), array("5","@","§"), $string);

  $string = str_replace(array("5a", "5e", "5é", "5è", "5i", "5o", "5u"), array("ina", "ine", "iné", "inè", "ini", "ino", "inu"), $string);
  $string = str_replace(array("@a", "@e", "@é", "@è", "@i", "@o", "@u"), array("ana", "ane", "ané", "anè", "ani", "ano", "anu"), $string);
  $string = str_replace(array("§a", "§e", "§é", "§è", "§i", "§o", "§u"), array("ona", "one", "oné", "onè", "oni", "ono", "onu"), $string);


  $string = str_replace(array("ai"), array("è"), $string);

  $string = str_replace(array("eu", "qu"), array("2", "k"), $string);
  
  $string = str_replace(array("ge", "gè", "gé", "gê", "gë", "gi", "gî", "gy"),
  array("Ze", "ZE", "Zé", "ZE", "ZE", "Zi", "Zi", "Zi"), $string);
  
  $convert_from = array( 
    "asa", "ase", "asi", "aso", "asu", "esa", "ese", "esi", "eso", "esu", "isa", "ise", "isi", "iso", "isu",
    "osa", "ose", "osi", "oso", "osu", "usa", "use", "usi", "uso", "usu"
  ); 
  $convert_to = array( 
    "aza", "aze", "azi", "azo", "azu", "eza", "eze", "ezi", "ezo", "ezu", "iza", "ize", "izi", "izo", "izu",
    "oza", "oze", "ozi", "ozo", "ozu", "uza", "uze", "uzi", "uzo", "uzu"
  ); 
  $string = str_replace($convert_from, $convert_to, $string);
  
  $string = str_replace(array("acce", "ucci", "uccè", "uccé", "ucce", ), array("akse", "utSi", "yksE", "yksé", "ykse"), $string);

  $string = str_replace(array("aina", "aine", "aini", "aino", "ainu", ), array("èna", "ène", "èni", "èno", "ènu"), $string);

  $string = str_replace(array("ce", "ci"), array("se", "si"), $string);

  
  $string = str_replace(array("emment"), array("am@"), $string);

  $string = str_replace(array("ent"), array("@t"), $string);
  
  $string = str_replace(array("ecc", "eff", "elf", "ell", "elk", "elt", "elv", "emm", "enn", "epp", "err", "ess", "est", "ett"),
  array("Ek", "Ef", "Elf", "El", "Elk", "Elt", "Elv", "@m", "En", "Ep", "Er", "Es", "Est", "Et"), $string);
  
  $string = str_replace(array("ien", "oin"), array("j5", "w5"), $string);
  
  $string = str_replace(array("w5a", "w5e", "w5i", "w5o", "w5u"), array("wana", "wane", "wani", "wano", "wanu"), $string);

  $string = str_replace(array("ck"), array("k"), $string);
  
  $string = str_replace(array("gn"), array("N"), $string);

  $string = str_replace(array("ch","ph"), array("S","f"), $string);
  
  $string = str_replace(array("ez"), array("é"), $string);

  $string = str_replace(array("ee"), array("i"), $string);

  $string = str_replace(array("cc", "ff", "ll", "mm", "nn", "pp", "rr", "ss", "tt"),
  array("k", "f", "l", "m", "n", "p", "r", "s", "t"), $string);

  $string = str_replace(array("au", "eau"), array("o", "o"), $string);

  $string = str_replace(array("aill", "eill", "ouill"), array("aj", "Ej", "ouj"), $string);
  
  $string = str_replace(array("uill"), array("8ij"), $string);

  $string = str_replace(array("uy", "oy"), array("8ij", "waj"), $string);

  $string = str_replace(array("e"), array("°"), $string);
  $string = str_replace(array("y"), array("i"), $string);

  $convert_from = array( 
    "a", "b", "c", "d", "f", "g", "h", "i", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", 
    "v", "w", "x", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", 
    "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý"
  ); 
  $convert_to = array( 
    "a", "b", "k", "d", "f", "g", "", "i", "k", "l", "m", "n", "o", "p", "k", "r", "s", "t", "y", 
    "v", "w", "ks", "z", "a", "a", "a", "a", "a", "a", "a", "s", "E", "e", "E", "E", "i", "i", "i", "i", 
    "N", "o", "o", "o", "o", "o", "o", "y", "y", "y", "y", "i"
  );

  $string = str_replace($convert_from, $convert_to, $string);
  
  $string = str_replace(array("oy"), array("u"), $string);

  // Fin du mot :
  if((mb_substr($string,mb_strlen($string,'UTF-8')-2,1,'UTF-8')=="@") and ((substr($string,strlen($string)-1,1)=="t")
  or (substr($string,strlen($string)-1,1)=="s"))){
    $string=mb_substr($string,0,-1,'UTF-8');
  }

  if((mb_substr($string,mb_strlen($string,'UTF-8')-3,1,'UTF-8')=="@") and (substr($string,strlen($string)-2,1)=="t")
  and (substr($string,strlen($string)-1,1)=="s")){
    $string=mb_substr($string,0,-2,'UTF-8');
  }
  
  if(mb_substr($string,mb_strlen($string,'UTF-8')-1,1,'UTF-8')=="°"){
    $string=mb_substr($string,0,-1,'UTF-8');
  }

  if((mb_substr($string,mb_strlen($string,'UTF-8')-2,1,'UTF-8')=="°") and (substr($string,strlen($string)-1,1)=="s")){
    $string=mb_substr($string,0,-2,'UTF-8');
  }
  
  if((mb_substr($string,mb_strlen($string,'UTF-8')-2,1,'UTF-8')=="E") and (substr($string,strlen($string)-1,1)=="s")){
    $string=mb_substr($string,0,-1,'UTF-8');
  }
    
  return $string; 
} 

?>
