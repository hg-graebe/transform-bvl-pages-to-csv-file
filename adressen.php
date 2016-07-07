<?php

/**
 * Experimental to transform the addresses to the address format used in
 * leipzig-data.de
 */

require 'vendor/autoload.php';

setlocale(LC_CTYPE, 'de_DE.UTF-8');

use Saft\Rdf\NodeFactoryImpl;
use Saft\Rdf\StatementFactoryImpl;
use Saft\Data\ParserFactory;

/* funzt leider nicht: saft/saft-skeleton 0.2.3 requires
    nette-caching-ext/caching dev-master -> satisfiable by
    nette-caching-ext/caching[dev-master] but these conflict with your
    requirements or minimum-stability.x */

$parserFactory = new ParserFactory(new NodeFactoryImpl(), new StatementFactoryImpl());
$parser = $parserFactory->createParserFor('turtle');
// parse a file and transform their content to a StatementIterator
$statementIterator = $parser->parseStreamToIterator(__DIR__ . '/adressen.ttl');
// go through iterator and output the first few statements
$i = 0;
foreach ($statementIterator as $statement) {
    echo (string)$statement->getSubject()
        . ' ' . (string)$statement->getPredicate()
        . ' ' . (string)$statement->getObject()
        . PHP_EOL;
    if ($i++ == 10) { break; }
}