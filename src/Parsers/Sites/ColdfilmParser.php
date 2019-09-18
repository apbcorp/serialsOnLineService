<?php

namespace App\Parsers\Sites;

use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use App\Services\HttpClient;
use App\Services\OutputService;
use App\Structs\Serial\VideoParsingItemStruct;
use App\Structs\Serial\VideoParsingResultStruct;

class ColdfilmParser implements SiteParserInterface
{
    const BASE_URL = 'http://coldfilm.ws';
    const URL = 'http://coldfilm.ws/news/?page%d';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var OutputService
     */
    private $output;

    public function __construct(HttpClient $client, OutputService $outputService)
    {
        $this->httpClient = $client;
        $this->output = $outputService;
    }

    public function getName(): string
    {
        return 'coldfilm';
    }

    /**
     * @return void
     */
    public function afterRegistry()
    {

    }

    public function parse(\DateTime $from): VideoParsingResultStruct
    {
        $this->output->writeLn(sprintf('%s: Start parsing...', $this->getName()));
        $result = new VideoParsingResultStruct($this->getName());
        $this->fillResult($result, $from);
        $this->output->writeLn(sprintf('%s: Finish parsing.', $this->getName()));

        return $result;
    }

    private function fillResult(VideoParsingResultStruct $struct, \DateTime $from)
    {
        $page = 1;

        $url = sprintf(self::URL, $page);
        $html = $this->httpClient->get($url);
        $lastDate = new \DateTime();

        while ($lastDate >= $from) {
            $this->output->writeLn(sprintf('%s: Parsing page %s', $this->getName(), $page));
            $this->parseCatalog($html, $struct, $from);

            $lastItem = $struct->getLastItem();
            $lastDate = $lastItem->getReleaseDate();

            $page++;
        }

        foreach ($struct->getItems() as $key => $item) {
            $this->output->writeLn(
                sprintf('%s: Parsing item %s of %s', $this->getName(), $key + 1, $struct->getItemCount())
            );
            $this->parseItem($item);
        }
    }

    private function parseCatalog(string $html, VideoParsingResultStruct $struct, \DateTime $from)
    {
        $items = explode('kino-item', $html);
        $items[0] = '';

        foreach ($items as $item) {
            if (!$item) {
                continue;
            }

            try {
                $parsingItem = $this->parseCatalogElement($item);

                if (!$parsingItem->isEmpty()) {
                    $struct->addItem($parsingItem);
                }
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @param string $html
     *
     * @return VideoParsingItemStruct
     * @throws \Exception
     */
    private function parseCatalogElement(string $html): VideoParsingItemStruct
    {
        $result = new VideoParsingItemStruct();

        if (!preg_match('/kino-title.*<a.*href="(.*)".*title="(.*)"/Usi', $html, $matches)) {
            return $result;
        }

        $result->setPageLink(self::BASE_URL . $matches[1]);
        $title = $matches[2];

        if (!preg_match('/(.*) (\d+).*(\d+)/Usi', $title, $matches)) {
            return $result;
        }

        $result->setTitle($matches[1]);
        $result->setSeason((int) $matches[2]);
        $result->setEpisode((int) $matches[3]);

        if(!preg_match('/uStarRating.*title="(.*)"/Usi', $html, $matches)) {
            return $result;
        }

        $parts = explode(' ', $matches[1]);
        if (count($parts) < 2) {
            return $result;
        }

        $ratings = explode('/', $parts[1]);

        if (count($ratings) < 2) {
            return $result;
        }

        $result->setFloatRating((float) $ratings[0]);
        $result->setIntRating((int) $ratings[1]);

        if (!preg_match('/kino-date.*(\d{2})\.(\d{2}).(\d{4})/Usi', $html, $matches)
            && !preg_match('/kino-date.*(Сегодня|Вчера)/', $html, $matches)
        ) {
            return $result;
        }

        switch ($matches[1]) {
            case 'Сегодня':
                $result->setReleaseDate(new \DateTime());

                break;
            case 'Вчера':
                $result->setReleaseDate((new \DateTime())->modify('-1 day'));

                break;
            default:
                $dateString = $matches[3] . '-' . $matches[2] . '-' . $matches[1] . ' ' . date('H:m:s');
                $result->setReleaseDate(new \DateTime($dateString));
        }

        $result->setIsEmpty(false);

        return $result;
    }

    private function parseItem(VideoParsingItemStruct $struct)
    {
        $html = $this->httpClient->get($struct->getPageLink());

        if (!preg_match_all('/<iframe.*allowfullscreen.*src="(.*)"/Usi', $html, $matches)) {
            return;
        }

        $struct->setPlaylistLinks($matches[1]);
    }
}