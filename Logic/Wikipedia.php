<?php

namespace Logic;

use \stdClass;

/**
 * Class Wikipedia
 *
 * @package Logic
 */
class Wikipedia
{
    /**
     * @var Parser
     */
    private $_parser;

    /**
     * Wikipedia constructor.
     *
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->_parser = $parser;
    }

    /**
     * @param string $searchKey
     *
     * @return string
     */
    public function getPage(string $searchKey) : string
    {
        $title = $this->getTitle($searchKey);
        $pageURL = $this->getPageUrl($title);
        $wikiPage = $this->_parser->getHtmlPage($pageURL);

        return $wikiPage;
    }

    /**
     * @param string $searchKey
     *
     * @return mixed
     */
    private function curlSearchPage(string $searchKey) : stdClass
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://en.wikipedia.org/w/api.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "format=json&action=query&list=search&srprop=sectionsnippet&srsearch=". urlencode($searchKey)
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return json_decode($server_output);
    }

    /**
     * @param string $searchKey
     *
     * @return mixed
     */
    private function getTitle(string $searchKey) : string
    {
        $wikiData = $this->curlSearchPage($searchKey);

        return reset($wikiData->query->search)->title;
    }

    /**
     * Get the url page for search
     *
     * @param string $title
     *
     * @return string
     */
    private function getPageUrl(string $title) : string
    {
        $splitTitle = explode(" ", $title);

        $pageURL = "https://en.wikipedia.org/wiki/";
        foreach ($splitTitle as $title)
        {
            $pageURL .= $title;

            if (end($splitTitle) != $title)
            {
                $pageURL .= "_";
            }
        }

        return $pageURL;
    }
}
