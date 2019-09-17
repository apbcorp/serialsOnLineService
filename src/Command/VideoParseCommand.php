<?php

namespace App\Command;

use App\Parsers\ParserRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VideoParseCommand extends Command
{
    /**
     * @var ParserRegistry
     */
    private $registry;

    public function __construct(ParserRegistry $registry)
    {
        $this->registry = $registry;

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
        $sites = $input->getArgument('sites');
        $from = $input->getArgument('from');

        try {
            $from = $from
                ? new \DateTime($from)
                : (new \DateTime())->modify('-2 hour');
        } catch (\Exception $e) {
            $from = (new \DateTime())->modify('-2 hour');
        }

        foreach ($this->registry->getAll() as $name => $parser) {
            if ($sites && !in_array($name, $sites)) {
                continue;
            }

            $result = $parser->parse($from);
        }
    }
}