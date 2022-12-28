<?php
namespace Orion\Core\Model\Query;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\DataObject;
use ReflectionClass;

/**
 * Class DataObjectManager
 *
 * @package Orion\Core\Model\Query
 */
class DataObjectManager extends DataBuilder
{
    /**
     * @var string
     */
    private string $entity;

    /**
     * @var array
     */
    private array $relations = [];

    /**
     * Returns models as collection.
     *
     * @param string[] $columns
     * @return Collection
     */
    public function get($columns = ['*']): Collection
    {
        $items = [];

        foreach (parent::get($this->getColumns($columns)) as $item) {
            if(isset($item->aggregate)) {
                $items[] = $item;
                continue;
            }

            $entity = new $this->entity($item);

            $this->resolveRelations($entity);

            $items[] = $entity;
        }

        return accumulate($items);
    }

    /**
     * Returns single model.
     *
     * @param string[] $columns
     * @return DataObject|null
     */
    public function first($columns = ['*']): ?DataObject
    {
        return $this->take(1)->get($columns)->first();
    }

    /**
     * Add relation by key to collection call.
     *
     * @param string $relation
     * @return DataObjectManager
     * @throws DataObjectManagerException
     */
    public function addRelation(string $relation): DataObjectManager
    {
        $relations = $this->entity::RELATIONS;

        if (!isset($relations[$relation])) {
            throw new DataObjectManagerException(
                __('Tried to use undefined relation: "%s"', [$relation]),
                500
            );
        }

        $this->relations[] = $relations[$relation];

        return $this;
    }

    /**
     * Returns models as collection and ignores given fields.
     *
     * @param string[] $columns
     * @return Collection
     */
    public function getExcept(array $columns = []): Collection
    {
        $items = [];

        foreach (parent::get(['*']) as $item) {
            foreach ($columns as $column) {
                unset($item->$column);
            }

            $entity = new $this->entity($item);

            foreach ($this->relations as $relation) {
                $entity->{$relation}();
            }

            $items[] = $entity;
        }

        return accumulate($items);
    }

    /**
     * Sets model.
     *
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * Returns selectable columns.
     *
     * @param array $columns
     * @return array
     */
    private function getColumns(array $columns): array
    {
        if($this->aggregate) {
            return $columns;
        }

        if ($columns !== ['*']) {
            return $columns;
        }

        if ($this->columns) {
            return $this->columns;
        }

        try {
            $reflectionClass = new ReflectionClass($this->entity);
            $constants = $reflectionClass->getConstants();

            unset(
                $constants['TABLE'],
                $constants['HIDDEN'],
                $constants['RELATIONS'],
                $constants['PRIMARY_KEY']
            );

            return array_values($constants);
        } catch (\ReflectionException $e) {
            return $columns;
        }
    }

    /**
     * Resolves simple relations.
     *
     * @param DataObject $entity
     */
    private function resolveRelations(DataObject $entity): void
    {
        foreach ($this->relations as &$relation) {
            $entity->{$relation}();
        }
    }
}
