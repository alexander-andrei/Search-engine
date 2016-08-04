<?php

namespace Logic;

use \DOMDocument;

class Parser
{
    /**
     * Get html page for parsing
     *
     * @param string $url
     *
     * @return string
     */
    public function getHtmlPage(string $url) : string
    {
        $doc = new DOMDocument();
        $doc->loadHTMLFile($url);

        $data = "";
        foreach ($doc->getElementById("mw-content-text")->getElementsByTagName("p") as $item)
        {
            $data .= $item->textContent;
        }

        return $data;
    }
}