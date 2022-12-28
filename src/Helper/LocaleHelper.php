<?php
namespace Orion\Core\Helper;

/**
 * Class LocaleHelper
 *
 * @package Orion\Core\Helper
 */
class LocaleHelper
{
    /**
     * Takes locale and returns given messages of it.
     *
     * @param string $locale
     * @return array
     */
    public function getMessages(string $locale): array
    {
        $path = $this->getPath();
        $fileName = $this->getFileName($locale);

        $jsonContent = @file_get_contents($path . $fileName);

        if (!$jsonContent) {
            return [];
        }

        return json_decode($jsonContent, true);
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return app_dir() . '/lang/';
    }

    /**
     * @param string $locale
     * @return string
     */
    private function getFileName(string $locale): string
    {
        return $locale . '.json';
    }
}
