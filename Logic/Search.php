<?php

namespace Logic;

/**
 * Class Search
 *
 * @package Logic
 */
class Search
{
    /**
     * Search depth
     */
    const SEARCH_RESULT_DEPTH = 2;

    /**
     * @var Splitter
     */
    private $splitter;

    /**
     * Search constructor.
     *
     * @param Splitter $splitter
     */
    public function __construct(Splitter $splitter)
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
     * @param string $words
     */
    private function echoSearchResult(string $words)
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
     * @param array $sentences
     * @param array $words
     *
     * @return array
     */
    private function searchForBestSolution(array $sentences,array $words) : array
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
        for ($x = self::SEARCH_RESULT_DEPTH; $x >= 1; $x--)
        {
            array_push($sentencesToShow, $sentences[$bestSolution["index"] - $x]);
        }

        array_push($sentencesToShow, $sentences[$bestSolution["index"]]);

        for ($x = 1; $x <= self::SEARCH_RESULT_DEPTH; $x++)
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
     * @param string $word
     * @param string $txt
     *
     * @return int
     */
    private function hasWord(string $word, string $txt) : int
    {
        $pattern = "/(?:^|[^a-zA-Z])" . preg_quote($word, '/') . "(?:$|[^a-zA-Z])/i";

        return preg_match($pattern, $txt);
    }
}

