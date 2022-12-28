<?php
namespace Orion\Core\Model;

use JsonSerializable;

/**
 * Class DataObject
 *
 * @package Orion\Core\Model
 */
class DataObject implements JsonSerializable
{
    /** @var string */
    public const PRIMARY_KEY = 'id';

    /** @var string */
    public const TABLE = '';

    /** @var array */
    public const HIDDEN = [];

    /** @var array */
    public const RELATIONS = [];

    /**
     * DataObject constructor.
     *
     * @param mixed $data
     */
    public function __construct(mixed $data = [])
    {
        foreach ($data as $key => &$value) {
            $this->setData($key, $value);
        }
    }

    /**
     * Get data by key.
     *
     * @param string|null $key
     * @return mixed|array|null
     */
    public function getData(mixed $key = null): mixed
    {
        if (!$key) {
            return (array) $this ?? [];
        }

        if (!isset($this->{$key})) {
            return null;
        }

        return $this->{$key};
    }

    /**
     * Set data by key.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setData(string $key, mixed $value): static
    {
        $this->{$key} = $value;
        return $this;
    }

    /**
     * Clears DataObject from relations.
     *
     * @return DataObject
     */
    public function clearRelations(): DataObject
    {
        foreach ($this::RELATIONS as $relationKey => $relation) {
            unset($this->{$relationKey});
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): DataObject
    {
        foreach ($this::HIDDEN as &$hidden) {
            unset($this->{$hidden});
        }

        return $this;
    }
}
