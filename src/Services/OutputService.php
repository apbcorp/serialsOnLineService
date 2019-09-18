<?php

namespace App\Services;

use Symfony\Component\Console\Output\OutputInterface;

class OutputService
{
    /**
     * @var OutputInterface[]
     */
    private $outputs = [];

    public function addOutput(OutputInterface $output)
    {
        $this->outputs[] = $output;
    }

    public function writeLn(string $text)
    {
        foreach ($this->outputs as $output) {
            $output->writeln($text);
        }
    }
}