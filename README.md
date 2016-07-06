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

## Mail Konrad Abicht, 2016-07-04

CSV-Datei, welche den extrahierten Datenbestand der Stadtführer-Seiten
repräsentiert: `le-online-extracted-places.csv`

Daraus abgeleitete RDF-Datei: `le-online-extracted-places.nt`

Den Scraper kann man händisch über die Console anstoßen, indem man `php
create-files.php` ausführt. Zum Setup ist ein `composer update` vorher nötig.

Es besteht gerade die Überlegung, die Daten mit der Access-Datenbank des BVL
abzugleichen und entsprechend zu verwenden statt der Web-Extraktion.
