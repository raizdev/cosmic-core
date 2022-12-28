<?php
namespace Orion\Framework\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;

/**
 * Class LoggingServiceProvider
 *
 * @package Orion\Framework\Provider
 */
class LoggingServiceProvider extends AbstractServiceProvider
{
    /**
     * @var string[]
     */
    protected $provides = [
        LoggerInterface::class,
        'logger_settings'
    ];

    /**
     * Registers new service.
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->add(LoggerInterface::class, function () use ($container) {
            $settings       = $container->get('logger_settings');
            $loggerSettings = $settings['logger'];
            $logger         = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();

            $logger->pushProcessor($processor);

            foreach ($loggerSettings['enabled_log_levels'] as $logStreamSettings) {
                $handler = new StreamHandler($logStreamSettings['path'], $logStreamSettings['level'], false);
                $logger->pushHandler($handler);
            }

            return $logger;
        });

        $container->add('logger_settings', function () {
            return [
                'logger' => [
                    'name' => $_ENV['WEB_NAME'] . '-event-log',
                    'enabled_log_levels' => [
                        // DEBUG
                        [
                            'path' => base_dir() . 'tmp/Logs/info.log',
                            'level' => Logger::DEBUG
                        ],
                        // INFO
                        [
                            'path' => base_dir() . 'tmp/Logs/info.log',
                            'level' => Logger::INFO
                        ],
                        // NOTICE
                        [
                            'path' => base_dir() . 'tmp/Logs/info.log',
                            'level' => Logger::NOTICE
                        ],
                        // WARNING
                        [
                            'path' => base_dir() . 'tmp/Logs/warning.log',
                            'level' => Logger::WARNING
                        ],
                        // ERROR
                        [
                            'path' => base_dir() . 'tmp/Logs/error.log',
                            'level' => Logger::ERROR
                        ],
                        // CRITICAL
                        [
                            'path' => base_dir() . 'tmp/Logs/critical.log',
                            'level' => Logger::CRITICAL
                        ],
                        // ALERT
                        [
                            'path' => base_dir() . 'tmp/Logs/critical.log',
                            'level' => Logger::ALERT
                        ],
                        // EMERGENCY
                        [
                            'path' => base_dir() . 'tmp/Logs/critical.log',
                            'level' => Logger::EMERGENCY
                        ],
                    ],
                ]
            ];
        });
    }
}
