<?php

error_reporting(0);

require('Splitter.php');
require('Wikipedia.php');

class Search
{
    /**
     * @var Splitter
     */
    private $splitter;

    /**
     * Search constructor.
     *
     * @param Splitter $splitter
     */
    public function Search(Splitter $splitter)
    {
        $this->splitter = $splitter;
    }

    /**
     * Start search process
     *
     * @param string $words
     */
    public function start(string $words)
    {
        $this->echoSearchResult($words);
    }

    /**
     * Echoes search result
     *
     * @param $words
     */
    private function echoSearchResult($words)
    {
        $sentences = $this->splitSentence();
        $words = explode(" ", strtolower($words));

        $result = $this->searchForBestSolution($sentences, $words);

        $result = implode(".", preg_replace('/^\[[0-9]+]$/', '', $result));

        echo html_entity_decode($result) . PHP_EOL;
    }

    /**
     * Returns best search solution
     *
     * @param $sentences
     * @param $words
     * @return array
     */
    private function searchForBestSolution($sentences, $words) : array
    {
        $bestSolution =
            [
                "index" => null,
                "count" => 0
            ];

        foreach ($sentences as $index => $sentence)
        {
            $countOccurences = 0;
            foreach ($words as $word)
            {
                $countOccurences += $this->hasWord($word, $sentence);
            }

            if ($bestSolution["count"] == 0 || $bestSolution["count"] < $countOccurences)
            {
                $bestSolution["count"] = $countOccurences;
                $bestSolution["index"] = $index;
            }
        }

        $sentencesToShow = [];
        for ($x = 2; $x >= 1; $x--)
        {
            array_push($sentencesToShow, $sentences[$bestSolution["index"] - $x]);
        }

        array_push($sentencesToShow, $sentences[$bestSolution["index"]]);

        for ($x = 1; $x <= 2; $x++)
        {
            array_push($sentencesToShow, $sentences[$bestSolution["index"] + $x]);
        }

        return $sentencesToShow;
    }

    /**
     * Returns spited sentence as array
     *
     * @return array
     */
    private function splitSentence() : array
    {
        $data = $this->splitter
            ->exclamation()
            ->question()
            ->get();

        return $data;
    }

    /**
     * Returns 1 or 0 if match is found
     *
     * @param $word
     * @param $txt
     * @return int
     */
    private function hasWord($word, $txt) : int
    {
        $pattern = "/(?:^|[^a-zA-Z])" . preg_quote($word, '/') . "(?:$|[^a-zA-Z])/i";

        return preg_match($pattern, $txt);
    }
}

unset($argv[0]);
$question = implode(" ", $argv);

$searchSentence = "Fastest butterfly swim";

$page = new Wikipedia();
$html = $page->getPage($question);

$splitter = new Splitter($html);
$search = new Search($splitter);
$search->start($searchSentence);