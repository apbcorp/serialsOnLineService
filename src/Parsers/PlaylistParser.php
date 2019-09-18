<?php

namespace App\Parsers;

use App\Parsers\VideoResources\VideoResourceParserRegistry;
use App\Services\OutputService;
use App\Structs\Serial\VideoParsingResultStruct;

class PlaylistParser
{
    /**
     * @var VideoResourceParserRegistry
     */
    private $registry;

    /**
     * @var OutputService
     */
    private $output;

    public function __construct(VideoResourceParserRegistry $registry, OutputService $outputService)
    {
        $this->registry = $registry;
        $this->output = $outputService;
    }

    public function parse(VideoParsingResultStruct $struct)
    {
        $this->output->writeLn('Start parsing playlists...');
        foreach ($struct->getItems() as $key => $item) {
            $this->output->writeLn(sprintf('Parse item %s of %s...', $key + 1, $struct->getItemCount()));
            if (!$item->getPlaylistLinks()) {
                continue;
            }

            foreach ($item->getPlaylistLinks() as $playlistLink) {
                $parser = $this->registry->getByUrl($playlistLink);

                if (!$parser) {
                    $this->output->writeLn('<error>Not found parser for URL ' . $playlistLink . '</error>');

                    continue;
                }

                $this->output->writeLn('Parsing playlist for ' . $parser->getName() . '...');
                try {
                    $playlist = $parser->parse($playlistLink);

                    if ($playlist) {
                        $item->addPlaylist($playlist);
                    }
                } catch (\Exception $e) {
                    $this->output->writeLn(sprintf('<error>%s Error: %s</error>', $parser->getName(), $e->getMessage()));
                }
            }
        }
        $this->output->writeLn('Finish parsing playlists.');
    }
}