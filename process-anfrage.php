<?php

/* to be processed as 

php csv2rdf.php >a.ttl
rapper -g a.ttl -o turtle > b.ttl

to remove multiple entries. 

 */

$a=csvToArray("anfrage.csv"); 
echo TurtleEnvelope().join("\n",array_map("getEntry",$a));

function TurtleEnvelope() {
  return '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .
@prefix leo: <http://le-online.de/ontology/place/ns#> .

<http://le-online.de/place/KleinesDatenextrakt/> a owl:Ontology ;
   rdfs:label "Transformiertes CSV-Extrakt"  .

';
}

function getEntry($a) {
  $b=array();
  $id=$a['a'];
  $b[]='<http://le-online.de/place/'.$id.'> a leo:Place';
  $b[]=' leo:KAId <'.$a['ka'].'>';
  $b[]=' leo:HGGId <'.$a['hgg'].'>';
  $b[]=' leo:hatOriginalAdresse "'.$a['callret-3'].'"';
  $b[]=' leo:hasAddress <'.fixAddress($a['callret-3']).'>';
  $b[]=' leo:geo "'.$a['callret-4'].'"';
  return join(";\n  ",$b) . " . \n\n"; 
}

function fixAddress($s) {
  preg_match('/Adresse\(\s*(.*)\s*,\s*(.*)\s*,\s*(.*)\s*\)/',$s,$matches);
  // echo $s; print_r($matches);
  $strasse=$matches[1];
  $plz=$matches[2];
  $ort=$matches[3];
  $strasse=preg_replace('/(\d+)$/','.$1',$strasse);
  return fixURI("http://leipzig-data.de/Data/".$plz.".Leipzig.".$strasse);
}

function fixURI($s) {
  $s=str_replace('str.','strasse',$s);
  $s=str_replace('Str.','Strasse',$s);
  $s=str_replace(
		 array('ß',  'ä',  'ü',  'ö',  'é', 'Ä',  'Ö',  'Ü'),
		 array('ss', 'ae', 'ue', 'oe', 'e', 'Ae', 'Oe', 'Ue' ),
        $s);
  $s=preg_replace('/\s+/','',$s);  
  return $s;
}

function csvToArray($file) {
  $rows = array();
  $headers = array();
  if (file_exists($file) && is_readable($file)) {
    $handle = fopen($file, 'r');
    while (!feof($handle)) {
      $row = fgetcsv($handle, 10240, ',', '"');
      if (empty($headers))
	$headers = $row;
      else if (is_array($row)) {
	array_splice($row, count($headers));
	$rows[] = array_combine($headers, $row);
      }
    }
    fclose($handle);
  } else {
    throw new Exception($file . ' doesn\'t exist or is not readable.');
  }
  return $rows;
}