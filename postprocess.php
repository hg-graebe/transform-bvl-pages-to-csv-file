<?php

/*  Postprocess output of create-files.php Data */

setlocale(LC_CTYPE, 'de_DE.UTF-8');

function prefix() {
  return '
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix foaf: <http://xmlns.com/foaf/0.1/> .
@prefix org: <http://www.w3.org/ns/org#> .
@prefix ld: <http://leipzig-data.de/Data/Model/> .
@prefix ldo: <http://leipzig-data.de/Data/Ort/> .
@prefix cc: <http://creativecommons.org/ns#> .
@prefix geo: <http://www.w3.org/2003/01/geo/wgs84_pos#> .
@prefix geonames: <http://www.geonames.org/ontology#> .
@prefix geosparql: <http://www.opengis.net/ont/geosparql#> .
@prefix leoplace: <http://le-online.de/ontology/place/ns#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .

';
}

$s=file_get_contents("uhu.nt");
//$s=file_get_contents("le-online-extracted-places.nt");
$s=str_replace("\r", " ", $s);
$s=str_replace("&Auml;", "Ä", $s);
$s=str_replace("&Ouml;", "Ö", $s);
$s=str_replace("&Uuml;", "Ü", $s);
$s=str_replace("&eacute;", "é", $s);
$s=str_replace("str.", "straße", $s);
$s=str_replace("Str.", "Straße", $s);

echo prefix().$s; 
