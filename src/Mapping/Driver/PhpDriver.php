<?php declare(strict_types=1);

namespace Orion\Core\Mapping\Driver;

use Jgut\Mapping\Driver\AbstractMappingDriver;
use Jgut\Mapping\Driver\Traits\PhpMappingTrait;

/**
 * PHP mapping driver.
 */
class PhpDriver extends AbstractMappingDriver
{
    use PhpMappingTrait;
    use MappingTrait;
}