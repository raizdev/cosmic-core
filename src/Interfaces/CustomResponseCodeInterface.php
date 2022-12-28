<?php
namespace Orion\Core\Interfaces;

/**
 * Interface CustomResponseCodeInterface
 *
 * @package Orion\Core\Interfaces
 */
interface CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_UNKNOWN_ERROR = 1;

    /** @var int */
    public const RESPONSE_THROTTLE_ERROR = 429;

    /** @var int */
    public const RESPONSE_NOT_ALLOWED = 401;
}
