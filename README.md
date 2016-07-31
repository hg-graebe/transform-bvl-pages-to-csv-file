# Transformator to create CSV file out of pages from Behindertenverband Leipzig e.V.

Based on a clone of https://github.com/AKSW/transform-bvl-pages-to-csv-file 
commit 1b73e78ae672ca7d28e3485ffbe4e5dcd1be28cf as of 2016-07-05

This repository contains a simple PHP script, which transforms pages about
places in Leipzig and their degree of accessibility into a single CSV file (in
German). These pages are being provided by the [Behindertenverband Leipzig
e.V](http://www.le-online.de/).

The following pages are covered currently:

* Education: http://www.le-online.de/bildung.htm
* Services: http://www.le-online.de/dienst.htm
* Restaurants: http://www.le-online.de/gast.htm
* Health: http://www.le-online.de/gesund.htm
* Law: http://www.le-online.de/recht.htm
* Organizations: http://www.le-online.de/verband.htm
* Traffic: http://www.le-online.de/verkehr.htm

## License

Published CSV- and NT file is licensed under the terms of [*Data licence
Germany – attribution – version 2.0*](https://www.govdata.de/dl-de/by-2-0).

Software source code is licensed under the terms of [*GPL
3.0*](http://www.gnu.org/licenses/gpl-3.0.en.html).

# Additional comments (in german)

Bezug zum Projekt spe16 im SWT-Praktikum 2016, Basisvariante

## Eigene Transformation der Daten aus den Quellen. 

### adressen.ttl 

Dazu wurden kleinere Änderungen am Transformationsskript vorgenommen:

* in `functions.php` - URI-Generierung in zwei Funktionen ausgelagert.  Meine
  URIs (ohne Präfix) matchen [\w+].  Uebersetzungstabelle.nt enthält eine
  Zuordnung der einen und der anderen Namen.
* in `create-files.php` - String-Transformation in Funktionen fixTitle($s) und
  fixStreet($s) ausgelagert. Probleme waren:
* " (quote) in Strings waren nicht maskiert.
* Zeilenvorschübe innerhalb von Strings, teilweise ^M (werden durch trim()
  nicht entfernt.
* `postprocess.php` nimmt weitere Fixes vor und packt eine Liste von Präfixen
  für die Transformation nach Turtle vor die ntriples.
* ntriples enthalten Umlaute, womit *rapper* in der Einstellung ntriples nicht
  umgehen kann, deshalb beim Parsen mit *rapper* '-i turtle' verwenden. 

`php create-files.php`                -> erzeugt uhu.nt
`rapper -c -i turtle uhu.nt`          -> prüft die entstandene Datei auf Stringenz
`php postprocess.php >a1.ttl`         -> erzeugt a1.ttl
`rapper -gc a1.ttl`                   -> prüft die entstandene Datei auf Stringenz
`rapper -g a1.ttl -o turtle >a2.ttl`  -> verwandelt das in Turtle

Daraus habe ich dann den adressrelevanten Teil nach *adressen.ttl* extrahiert
(siehe *Queries.txt*). Die Felder *leo:placeName* und *leo:address* wurden dabei
dupliziert, um die Kopie jeweils fixen zu können.

*leo:fixedAddress* wurde dann so weit kuratiert, dass ein Abgleich mit den
LD-Adressen möglich wird. Dazu wurde aus *adressen.ttl* mit *rapper* die
ntriples-Datei *adressen.nt* extrahiert, mit dem Perl-Skript `process.pl`
ld-Adressen entsprechend dem bei LeipzigData verwendeten Namensmuster erzeugt
und als *leo:ldAddress* in *adressen.ttl* angereichert. 

Dateien:

* leo-original-20160707.nt - originale nt-Datei
* leo-version-20160707.nt - nt-Datei wie mit dem modifizierten Skript erzeugt. 
* adressen.ttl - Übernahme von Daten, um die Adressen zu kuratieren und
  Geodaten zu ergänzen. 

adressen.ttl - URI sind HGG-URIs. Semantik der Prädikate:

* leo:address - Adressfeld aus dem Original zusammengebaut 
* leo:fixedAddress - Adressfeld fixiert, aus dem leo:ldAddress erzeugt wird.
* leo:placeName - Name aus dem Original übernommen
* leo:fixedPlaceName - Name fixiert
* leo:ldAddress - (potenzielle) Adress-URI für LeipzigData 
* owl:sameAs - URI nach Konrad Abicht (Stand 5.7.2016)

**Anmerkung:** Der Code von `create-files.php` der Version vom 2016-07-07
scrapte unmittelbar die Webseiten, spätere Versionen nutzen eine andere
Anbindung, die auch private Daten enthält und deshalb nicht mehr durch Dritte
ausgeführt werden kann.  Basis für die weiteren Arbeiten sind also die im
Basisprojekt regelmäßig extrahierten Daten selbst. 

### ld-adressen.ttl

Aus *adressen.ttl* wurden die *leo:ldAddress* Objekte als Instanzen von
*leo:Adresse* in *ld-adressen.ttl* extrahiert, um diese Datei mit weiteren
Informationen aus LeipzigData anzureichern.

Diese Adressen wurden als <http://le-online.de/places/>in den RDF-Store
leipzig-data.de gesteckt und relevante Informationen extrahiert.  Das genau
Vorgehen ist in der Datei `Queries.txt` beschrieben.

ld-adressen.ttl - URI sind Adress-URIs nach LeipzigData, Klasse ist
leo:Adresse.  Semantik der Prädikate:

* ld:inOrtsteil - ein ld:Ortsteil 
* ogcgs:asWKT - Geokoordinaten als WKT-Point 
* rdfs:label - aus LeipzigData extrahierter Label, wenn der fehlt, dann wurde
  kein Adresseintrag gefunden.

## Erzeugen der RDF-Datei aus der CSV-Datei

Verwendet ein allgemeines Skript `csv2rdf.php` auf der Basis der php-Funktion
`fgetcsv(handle, max_Zeilenlänge, delimiter, enclosure)`, um die RDF-Datei aus
der CSV-Datei zu extrahieren.  Dabei werden URIs über ein Autoincrement
vergeben, um die Eindeutigkeit der Datensätze sicherzustellen.

Dateien: 

* leo-original-20160725.nt  - Originaldatei 
* leo-original-20160725.csv   - Originaldatei 
* leo-csvextract-20160731.ttl - aus *leo-original-20160725.csv* mit
  `csv2rdf.php` extrahiert (953 Datensätze wie auch im Original)

```
grep leo:Place leo-csvextract-20160731.ttl |wc -l
grep ontology/place/ns\#titel leo-original-20160725.nt |wc -l
```
