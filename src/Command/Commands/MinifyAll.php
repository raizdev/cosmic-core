<?php

namespace Cosmic\Core\Command\Commands;

use Predis\Client;

use Cosmic\Core\Command\Config\Services;
use Cosmic\Core\Command\Minifier;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MinifyAll extends Command
{
    /** @var string */
    private const COMMAND_NAME = 'minify:all';

    /** @var string */
    private const TMP_PATH = 'tmp';

    /** @var string */
    private const CACHE_TYPE = 'Predis';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Minify JS and CSS files');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new \Cosmic\Core\Command\Config\Minifier();
        $minify = new Minifier($config);
        $result = $minify->deploy();

        if (!$result) {
            $output->writeln('<error>' . $minify->getError() . '</error>');
            return 1;
        }
        $output->writeln('<info>All files were successfully generated</info>');

        return 0;
    }
}