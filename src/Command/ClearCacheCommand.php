<?php
namespace Orion\Core\Command;

use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClearCacheCommand
 *
 * @package Ares\Framework\Command
 */
class ClearCacheCommand extends Command
{
    /** @var string */
    private const COMMAND_NAME = 'ares:clear-cache';

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
        $this->setDescription('Clears the application cache');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->deleteCache();
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            return 1;
        }

        $output->writeln('<info>Application cache successfully cleared</info>');

        return 0;
    }

    /**
     * Deletes the cache recursive
     *
     * @return bool
     */
    private function deleteCache(): bool
    {
        /** @var Client $predisClient */
        $predisClient = container()->get(Client::class);

        if ($_ENV['CACHE_TYPE'] == self::CACHE_TYPE) {
            $predisClient->flushall();
            return true;
        }

        if (is_dir(self::TMP_PATH)) {
            array_map([$this, 'deleteCache'], glob(self::TMP_PATH . DIRECTORY_SEPARATOR . '{,.[!.]}*', GLOB_BRACE));
            return @rmdir(self::TMP_PATH);
        }

        return @unlink(self::TMP_PATH);
    }
}
