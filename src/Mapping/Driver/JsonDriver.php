<?php declare(strict_types=1);

namespace Cosmic\Core\Mapping\Driver;

use Jgut\Mapping\Driver\AbstractMappingDriver;
use Jgut\Mapping\Driver\Traits\JsonMappingTrait;

/**
 * JSON mapping driver.
 */
class JsonDriver extends AbstractMappingDriver
{
    use JsonMappingTrait;
    use MappingTrait;
}