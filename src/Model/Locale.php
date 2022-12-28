<?php
namespace Orion\Framework\Model;

use Orion\Framework\Helper\LocaleHelper;

/**
 * Class Locale
 *
 * @package Orion\Framework\Model
 */
class Locale
{
    /**
     * @var string
     */
    private string $locale = '';

    /**
     * @var string
     */
    private string $fallbackLocale = '';

    /**
     * @var array
     */
    private array $messages = [];

    /**
     * @var array
     */
    private array $fallbackMessages = [];

    /**
     * Locale constructor.
     *
     * @param LocaleHelper $localeHelper
     */
    public function __construct(
        private readonly LocaleHelper $localeHelper
    ) {}

    /**
     * Takes message and placeholder to translate them in given locale.
     *
     * @param string $key
     * @return string
     */
    public function translate(string $key): string
    {
        $messages = $this->getMessages();

        if (!array_key_exists($key, $messages)) {
            $messages = $this->getFallbackMessages();

            if (!array_key_exists($key, $messages)) {
                return $key;
            } else {
                return $messages[$key];
            }
        }

        return $messages[$key];
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getFallbackLocale(): string
    {
        return $this->fallbackLocale;
    }

    /**
     * @param string $fallbackLocale
     */
    public function setFallbackLocale(string $fallbackLocale): void
    {
        $this->fallbackLocale = $fallbackLocale;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        if ($this->messages) {
            return $this->messages;
        }

        $messages = $this->localeHelper->getMessages($this->getLocale());

        $this->setMessages($messages);

        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return array
     */
    public function getFallbackMessages(): array
    {
        if ($this->fallbackMessages) {
            return $this->fallbackMessages;
        }

        $messages = $this->localeHelper->getMessages($this->getFallbackLocale());

        $this->setFallbackMessages($messages);

        return $this->fallbackMessages;
    }

    /**
     * @param array $fallbackMessages
     */
    public function setFallbackMessages(array $fallbackMessages): void
    {
        $this->fallbackMessages = $fallbackMessages;
    }
}
