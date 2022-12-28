<?php declare(strict_types=1);

namespace Orion\Core\Transformer;

/**
 * Parameter transformer interface.
 */
interface ParameterTransformer
{
    /**
     * Transform parameters.
     *
     * @param array $parameters
     * @param array $definitions
     *
     * @return array
     */
    public function transform(array $parameters, array $definitions): array;
}