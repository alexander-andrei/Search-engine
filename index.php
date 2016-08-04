<?php

use Logic\Wikipedia;
use Logic\Splitter;
use Logic\Search;

include ("autoload.php");

//error_reporting(0);

unset($argv[0]);
$question = implode(" ", $argv);

$searchSentence = "Fastest butterfly swim";

$parser = new \Logic\Parser();
$page = new Wikipedia($parser);
$html = $page->getPage($question);

$splitter = new Splitter($html);
$search = new Search($splitter);
$search->start($question);