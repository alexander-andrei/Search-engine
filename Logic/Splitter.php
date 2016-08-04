<?php

namespace Logic;

/**
 * Class Splitter
 *
 * @package Logic
 */
class Splitter
{
    /**
     * Split flags
     */
    const DOT = '.';
    const EXCLAMATION = '!';
    const COMMA = ',';
    const QUESTION = '?';

    /**
     * @var string
     */
    private $sentence;

    /**
     * @var array
     */
    private $splitSentence;

    /**
     * Splitter constructor.
     *
     * @param string $sentence
     */
    public function __construct(string $sentence)
    {
        $this->sentence = $sentence;

        $this->dot();
    }

    /**
     * Split strings by dot
     */
    public function dot()
    {
        $this->splitSentence = explode(self::DOT, $this->sentence);
    }

    /**
     * Splits strings by exclamation mark
     *
     * @return Splitter
     */
    public function exclamation() : Splitter
    {
        $this->splitLogic(self::EXCLAMATION);

        return $this;
    }

    /**
     * Splits strings by comma
     *
     * @return Splitter
     */
    public function comma() : Splitter
    {
        $this->splitLogic(self::COMMA);

        return $this;
    }

    /**
     * Splits strings by question mark
     *
     * @return Splitter
     */
    public function question() : Splitter
    {
        $this->splitLogic(self::QUESTION);

        return $this;
    }

    /**
     * Returns all split strings
     *
     * @return array
     */
    public function get() : array
    {
        $this->removeNullElements();
        return $this->splitSentence;
    }

    /**
     * Flattens an multidimensional array
     *
     * @param array $array
     * @return array
     */
    private function flatten(array $array) : array{
        $return = array();

        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });

        return $return;
    }

    /**
     * Basic logic for splitting strings
     *
     * @param string $sign
     */
    private function splitLogic(string $sign)
    {
        $temporaryArray = [];

        foreach ($this->splitSentence as $sentence)
        {
            $temporaryArray[] = explode($sign, $sentence);
        }

        $this->splitSentence = $this->flatten($temporaryArray);
    }

    /**
     * Removes null elements from split strings
     */
    private function removeNullElements()
    {
        foreach ($this->splitSentence as $key => $sentence)
        {
            if ($sentence == "")
            {
                unset($this->splitSentence[$key]);
            }
        }
    }
}