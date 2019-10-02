<?php

namespace App\Command;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Serial;
use App\Entity\Stream;
use App\Parsers\PlaylistParser;
use App\Parsers\Sites\SiteParserRegistry;
use App\Repository\SeasonRepository;
use App\Repository\SerialRepository;
use App\Services\OutputService;
use App\Structs\Serial\PlaylistStruct;
use App\Structs\Serial\VideoParsingResultStruct;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VideoParseCommand extends Command
{
    /**
     * @var SiteParserRegistry
     */
    private $siteParserRegistry;

    /**
     * @var PlaylistParser
     */
    private $parser;

    /**
     * @var OutputService
     */
    private $output;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SerialRepository
     */
    private $serialRepository;

    public function __construct(
        SiteParserRegistry $siteParserRegistry,
        PlaylistParser $parser,
        OutputService $output,
        EntityManagerInterface $entityManager
    ) {
        $this->siteParserRegistry = $siteParserRegistry;
        $this->parser = $parser;
        $this->output = $output;
        $this->entityManager = $entityManager;

        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('parser:video:parse')
            ->setDescription('Parse sites with video')
            ->addArgument('from', InputArgument::OPTIONAL, 'Get update from date')
            ->addArgument('sites', InputArgument::IS_ARRAY, 'List of sites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output->addOutput($output);
        $sites = $input->getArgument('sites');
        $from = $input->getArgument('from');
        $this->serialRepository = $this->entityManager->getRepository(Serial::class);

        try {
            $from = $from
                ? new \DateTime($from)
                : (new \DateTime())->modify('-2 hour');
        } catch (\Exception $e) {
            $from = (new \DateTime())->modify('-2 hour');
        }

        $parsingResults = [];
        foreach ($this->siteParserRegistry->getAll() as $name => $parser) {
            if ($sites && !in_array($name, $sites)) {
                continue;
            }

            $struct = $parser->parse($from);

            $this->parser->parse($struct);
            $parsingResults[] = $struct;
        }

        $this->save($parsingResults);
    }

    /**
     * @param VideoParsingResultStruct[] $result
     */
    private function save(array $result)
    {
        foreach ($result as $siteResult) {
            foreach ($siteResult->getItems() as $videoStruct) {
                $serial = $this->serialRepository->findOneBy(['name' => $videoStruct->getTitle()]);

                if (!$serial) {
                    $serial = new Serial();
                    $this->entityManager->persist($serial);
                }

                $serial->setName($videoStruct->getTitle());

                $season = $serial->getSeasonByNumber($videoStruct->getSeason());

                if (!$season) {
                    $season = (new Season())->setNumber($videoStruct->getSeason());
                    $serial->addSeason($season);
                }

                $episode = $season->getEpisodeByNumber($videoStruct->getEpisode());

                if (!$episode) {
                    $episode = (new Episode())
                        ->setNumber($videoStruct->getEpisode())
                        ->setReleaseDate($videoStruct->getReleaseDate());

                    $season->addEpisode($episode);
                }

                $this->updateStreams($episode, $videoStruct->getPlayLists());
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param Episode          $episode
     * @param PlaylistStruct[] $playlists
     */
    private function updateStreams(Episode $episode, array $playlists)
    {
        $streams = [];
        /** @var Stream $stream */
        foreach ($episode->getStreams() as $stream) {
            $streams[$stream->getUrl()] = $stream;
            $stream->setVisible(false);
        }

        
    }
}