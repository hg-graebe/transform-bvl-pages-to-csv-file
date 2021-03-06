<?php

/*  Extract RDF content from CSV file using URIs that are generated by
    autoincrement.  

    Source of csvToArray from 
    http://stackoverflow.com/questions/17761172/php-csv-string-to-array 

*/

$cnt=1000;
$a=csvToArray("leo-original-20160725.csv"); 
echo TurtleEnvelope().join("\n",array_map("getEntry",$a));

function TurtleEnvelope() {
  return '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .
@prefix leo: <http://le-online.de/ontology/place/ns#> .

<http://le-online.de/place/HGG-Datenextrakt/> a owl:Ontology ;
   rdfs:label "RDF als CSV-Extrakt" ;
   rdfs:comment "Extrakt aus der csv-Quelle leo-original-20160725.csv mit dem Skript csv2rdf.php" .

';
}

function getEntry($a) {
  global $cnt;
  $b=array();
  $id=$cnt++; 
  $titel=$a['Titel'];
  $b[]='<http://le-online.de/place/'.$id.'> a leo:Place';
  $b[]=' leo:KAId '.createGoodURIKonrad($titel);
  $b[]=' leo:HGGId '.createGoodURIHGG($titel);
  foreach($a as $key => $value) {
    if (empty($value)) { continue; } // skip empty fields
    // prepare for output
    $value=str_replace('"','\"',$value); // mask quotes
    if (strstr($value,"\n")) { $b[]=" leo:$key \"\"\"$value\"\"\"" ;}  
    else { $b[]=" leo:$key \"$value\"" ; } // multi vs. single line
  }
  return join(";\n  ",$b) . " . \n\n"; 
}

function createGoodURIKonrad($name) { // Originalversion
  $name=strtolower(trim(preg_replace('/\s\s+/', '', $name)));
  $name=str_replace(
        array(
            ' ',     'ß',  'ä',  'ü',  'ö',  'ö',  '<br-/>', '&uuml;', '&auml;', '&ouml;', '"', 'eacute;', '/',
                'ouml;', 'auml;', 'uuml;', ',', "'", '>', '<', '`', '´', '\\'
        ),
        array(
            '-',     'ss', 'ae', 'ue', 'oe', 'oe', '',       'ue',     'ae',     'oe',     '',  'e',       '_',
                'oe',    'ae',    'ue',    '-', '_', '-', '-', '-', '-', ''
	      ),$name);
  return "<http://le-online.de/place/$name>";
}

function createGoodURIHGG($name) { // Originalversion
  $name=strtolower(trim(preg_replace('/\s\s+/', '', $name)));
  $name=str_replace(
        array('ß',  'ä',  'ü',  'ö',  'ö',  '<br-/>', '&uuml;', '&auml;', '&ouml;', 'eacute;',
                'ouml;', 'auml;', 'uuml;', '\\'),
        array('ss', 'ae', 'ue', 'oe', 'oe', '',       'ue',     'ae',     'oe',     'e',
                'oe',    'ae',    'ue' , ''  ),
        $name);
  $name=preg_replace('/\W/', '', $name);
  return "<http://le-online.de/place/$name>";
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