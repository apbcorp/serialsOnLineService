<?php

namespace App\Command;

use App\Parsers\PlaylistParser;
use App\Parsers\Sites\SiteParserRegistry;
use App\Services\OutputService;
use App\Structs\Serial\VideoParsingResultStruct;
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

    public function __construct(SiteParserRegistry $siteParserRegistry, PlaylistParser $parser, OutputService $output)
    {
        $this->siteParserRegistry = $siteParserRegistry;
        $this->parser = $parser;
        $this->output = $output;

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
    }
}