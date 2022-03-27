<?php

namespace Cosmic\Core\Command\Minifier\Commands;

use Cosmic\Core\Command\Minifier\Minifier;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MinifyJs extends Command
{
    /** @var string */
    private const COMMAND_NAME = 'minify:js';

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
        $this->setDescription('Minify all JS files');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new \Cosmic\Core\Command\Minifier\Config\Minifier();
        $minify = new Minifier($config);
        $result = $minify->deploy('js');

        if (!$result) {
            $output->writeln('<error>' . $minify->getError() . '</error>');
            return 1;
        }
        $output->writeln('<info>JS files were successfully generated</info>');

        return 0;
    }

}