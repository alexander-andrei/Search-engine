<?php

class Wikipedia
{
    public function getPage($searchKey)
    {
        $title = $this->getTitle($searchKey);
        $pageURL = $this->getPageUrl($title);
        $wikiPage = $this->getHtmlPage($pageURL);

        return $wikiPage;
    }

    private function curlSearchPage($searchKey)
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

    private function getTitle($searchKey)
    {
        $wikiData = $this->curlSearchPage($searchKey);

        return reset($wikiData->query->search)->title;
    }

    private function getPageUrl($title)
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

    private function getHtmlPage($url)
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
