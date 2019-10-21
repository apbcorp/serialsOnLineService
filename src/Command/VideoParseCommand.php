<?php

namespace App\Command;

use App\Entity\Episode;
use App\Entity\Rating;
use App\Entity\Season;
use App\Entity\Serial;
use App\Entity\Stream;
use App\Entity\Synonim;
use App\Parsers\PlaylistParser;
use App\Parsers\Sites\SiteParserRegistry;
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
     * @var EntityRepository
     */
    private $synonimsRepository;

    /**
     * @var EntityRepository
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
        $this->synonimsRepository = $this->entityManager->getRepository(Synonim::class);
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
                $this->output->writeLn(
                    sprintf(
                        'Saving: %s S%sE%s ...',
                        $videoStruct->getTitle(),
                        $videoStruct->getSeason(),
                        $videoStruct->getEpisode()
                    )
                );

                /** @var Synonim|null $synonim */
                $synonim = $this->synonimsRepository->findOneBy(['name' => $videoStruct->getTitle()]);

                if (!$synonim) {
                    $serial = $this->serialRepository->findOneBy(['name' => $videoStruct->getTitle()]);

                    if (!$serial) {
                        $serial = new Serial();
                        $serial->setName($videoStruct->getTitle());
                        $serial->setScreen($videoStruct->getScreen());
                        $this->entityManager->persist($serial);
                        $this->output->writeLn('Add new serial ' . $serial->getName());
                    }

                    $synonim = (new Synonim())
                        ->setName($videoStruct->getTitle())
                        ->setSerial($serial);

                    $this->entityManager->persist($synonim);
                } else {
                    $serial = $synonim->getSerial();
                }

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

                $this->updateRatings($episode, $videoStruct->getRatings());
                $this->updateStreams($episode, $videoStruct->getPlayLists(), $siteResult->getSource());
                $this->entityManager->flush();

                $this->output->writeLn('Saving finished.');
            }
        }
    }

    /**
     * @param Episode $episode
     * @param int[]   $ratings
     */
    private function updateRatings(Episode $episode, array $ratings)
    {
        $ratingArray = [];
        /** @var Rating $rating */
        foreach ($episode->getRatings() as $rating) {
            $ratingArray[$rating->getType()] = $rating;
        }

        foreach ($ratings as $type => $value) {
            if (!isset($ratingArray[$type])) {
                $ratingEntity = (new Rating())
                    ->setType($type);

                $episode->addRating($ratingEntity);
            } else {
                $ratingEntity = $ratingArray[$type];
            }

            $ratingEntity->setValue($value);
        }
    }

    /**
     * @param Episode          $episode
     * @param PlaylistStruct[] $playlists
     * @param string           $translatedBy
     */
    private function updateStreams(Episode $episode, array $playlists, string $translatedBy)
    {
        if (!$playlists) {
            return;
        }

        $streams = [];
        /** @var Stream $stream */
        foreach ($episode->getStreams() as $stream) {
            $streams[$stream->getUrl()] = $stream;
            $stream->setVisible(false);
        }

        foreach ($playlists as $playlist) {
            foreach ($playlist->getM3U8Items() as $m4uItem) {
                if (!$m4uItem->getUrl()) {
                    continue;
                }

                if (!isset($streams[$m4uItem->getUrl()])) {
                    $streamEntity = (new Stream())
                        ->setUrl($m4uItem->getUrl())
                        ->setStreamProvider($m4uItem->getProvider())
                        ->setTranslatedBy($translatedBy)
                        ->setResolution($m4uItem->getResolution());

                    $episode->addStream($streamEntity);
                    $streams[$streamEntity->getUrl()] = $streamEntity;
                } else {
                    $streamEntity = $streams[$m4uItem->getUrl()];
                }

                $streamEntity->setVisible(true);
            }
        }
    }
}