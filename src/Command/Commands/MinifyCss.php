<?php

namespace Ares\Core\Command\Commands;

use Ares\Core\Command\Minifier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MinifyCss extends Command
{
    /** @var string */
    private const COMMAND_NAME = 'minify:css';

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
        $this->setDescription('Minify all CSS files');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new \Ares\Core\Command\Config\Minifier();
        $minify = new Minifier($config);
        $result = $minify->deploy('css');

        if (!$result) {
            $output->writeln('<error>' . $minify->getError() . '</error>');
            return 1;
        }
        $output->writeln('<info>CSS files were successfully generated</info>');

        return 0;
    }

}