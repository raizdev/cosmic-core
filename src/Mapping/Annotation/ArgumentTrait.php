<?php declare(strict_types=1);

namespace Orion\Core\Mapping\Annotation;

/**
 * Argument annotation trait.
 */
trait ArgumentTrait
{
    /**
     * Arguments.
     *
     * @var mixed[]
     */
    protected $arguments = [];

    /**
     * Get arguments.
     *
     * @return mixed[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Set arguments.
     *
     * @param mixed[] $arguments
     *
     * @return self
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }
}