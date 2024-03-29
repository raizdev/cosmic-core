<?php declare(strict_types=1);

namespace Cosmic\Core\Mapping\Driver;

use Jgut\Mapping\Driver\AbstractMappingDriver;
use Jgut\Mapping\Driver\Traits\YamlMappingTrait;

/**
 * YAML mapping driver.
 */
class YamlDriver extends AbstractMappingDriver
{
    use YamlMappingTrait;
    use MappingTrait;
}