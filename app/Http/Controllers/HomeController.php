<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        $response = Http::withOptions(['verify' => false])
            ->get('https://www.commitstrip.com/en/feed/');

        $simpleXml = simplexml_load_string(data: $response->body(), options: LIBXML_NOCDATA);

        $firstList = [];

        foreach ($simpleXml->channel->item as $item) {
            $itemContent = (string)$item->children("content", true);
            if (
                str_contains($itemContent, 'jpg') ||
                str_contains($itemContent, 'JPG') ||
                str_contains($itemContent, 'GIF') ||
                str_contains($itemContent, 'gif') ||
                str_contains($itemContent, 'PNG') ||
                str_contains($itemContent, 'png')
            ) {
                $firstList[] = (string)$item->link;
            }
        }


        $response = Http::get('https://newsapi.org/v2/top-headlines?country=ma&apiKey=7b8966140fe9405bad22f7072d507072');
        $results = $response->json();

        $secondList = [];

        foreach ($results['articles'] as $article) {
            if ($article['urlToImage']) {
                $secondList[] = $article['url'];
            }
        }


        $uniqueList = array_unique([...$firstList, ...$secondList]);

        // pagination related logic
        $limit = 3;
        $initialPage = (request('page', 1) - 1) * $limit;
        $totalPages = ceil(count($uniqueList) / $limit);

        for ($i = $initialPage; $i < $initialPage + $limit; $i++) {
            if (count($uniqueList) - 1 >= $i) {
                $finalList[] = $uniqueList[$i];
            }
        }

        libxml_use_internal_errors(true);
        $doc = new \DomDocument();

        $images = [];

        foreach ($finalList as $value) {
            if ($value) {
                try {
                    $images[] = $this->getImageInPage($doc, $value);
                } catch (Throwable $ex) {
                    return response(
                        sprintf('unable to load images due to: <br>%s', $ex->getMessage())
                    );
                }
            }
        }

        return view('index', compact('images', 'totalPages'));
    }

    private function getImageInPage($doc, $link)
    {
        $response = Http::withOptions(['verify' => false])
            ->get($link);

        $doc->loadHTML($response->body());
        $xpath = new \DomXpath($doc);

        if (str_contains($link, "commitstrip.com")) {
            $xpathQuery = $xpath->query('//img[contains(@class,"size-full")]/@src');
            $src = $xpathQuery[0]->value;

            return $src;
        }

        $xpathQuery = $xpath->query('//img/@src');
        $src = $xpathQuery[0]->value ?? "";

        return $src;
    }
}
