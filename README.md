# Transformator to create CSV file out of pages from Behindertenverband Leipzig e.V.

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

Clone des Repos https://github.com/AKSW/transform-bvl-pages-to-csv-file

## HGG, 2016-07-06

Änderungen: 

In `functions.php` - URI-Generierung in zwei Funtkionen ausgelagert.  Meine
URIs (ohne Präfix) matchen [\w+]

In `create-files.php` - String-Transformation in Funktionen fixTitle($s) und
fixStreet($s) ausgelagert. Probleme waren: 

* " (quote) in Strings waren nicht maskiert.
* Zeilenvorschübe innerhalb von Strings, teilweise ^M (werden durch trim()
  nicht entfernt.

`postprocess.php` nimmt weitere Fixes vor (die man auch in `create-files.php`
unterbringen kann) und packt eine Liste von Präfixen für die Transformation
nach Turtle vor die ntriples.

php create-files.php                -> erzeugt uhu.nt
php postprocess.php >a1.nt          -> erzeugt a1.nt
rapper -gc a1.nt                    -> prüft die entstandene Datei auf Stringenz
rapper -g a1.nt -o turtle >a.ttl    -> verwandelt das in Turtle

Das Ganze habe ich dann in ein Ontowiki@localhost gepackt, daraus den
adressrelevanten Teil extrahiert (siehe `Queries.txt`) und in `adressen.ttl`
gepackt. Die Felder leoplace:placeName und leoplace:address wurden dabei
dupliziert, um die Kopie jeweils fixen zu können.

`leoplace:fixedAddress` wurde dann so weit kuratiert, dass ein Abgleich mit den
LD-Adressen möglich wird.

