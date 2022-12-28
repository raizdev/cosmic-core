<?php
namespace Ares\Framework\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Factory\DataObjectManagerFactory;
use Orion\Core\Model\DataObject;
use Orion\Core\Model\Query\DataObjectManager;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Service\CacheService;
use Orion\Core\Model\Query\Collection;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class BaseRepository
 *
 * @package Orion\Core\Repository
 */
abstract class BaseRepository
{
    /** @var string */
    private const COLUMN_ID = 'id';

    /** @var int */
    protected int $paginationLimit = 50;

    /**
     * @var string
     */
    protected string $entity;

    /**
     * @var string
     */
    protected string $cachePrefix;

    /**
     * @var string
     */
    protected string $cacheCollectionPrefix;

    /**
     * BaseRepository constructor.
     *
     * @param DataObjectManagerFactory $dataObjectManagerFactory
     * @param CacheService             $cacheService
     */
    public function __construct(
        private readonly DataObjectManagerFactory $dataObjectManagerFactory,
        private readonly CacheService             $cacheService
    ) {}

    /**
     * Get DataObject by id or by given field value pair.
     *
     * @param mixed  $value
     * @param string $column
     * @param bool   $allowFail
     * @param bool   $isCached
     *
     * @return DataObject|null
     * @throws NoSuchEntityException
     */
    public function get(
        mixed $value,
        string $column = self::COLUMN_ID,
        bool $allowFail = false,
        bool $isCached = true
    ): ?DataObject {
        $entity = $this->cacheService->get($this->cachePrefix . $value);

        if ($entity && $isCached) {
            return unserialize($entity);
        }

        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);
        $entity = $dataObjectManager->where($column, $value)->first();

        if (!$entity && !$allowFail) {
            throw new NoSuchEntityException(__('Entity not found'), 404);
        }

        $this->cacheService->set($this->cachePrefix . $value, serialize($entity));

        return $entity;
    }

    /**
     * Get one DataObject through a certain SearchCriteria.
     *
     * @param DataObjectManager $dataObjectManager
     * @param bool              $allowFail
     * @param bool              $isCached
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getOneBy(
        DataObjectManager $dataObjectManager,
        bool $allowFail = false,
        bool $isCached = true
    ): mixed {
        $cacheKey = $this->getCacheKey($dataObjectManager);

        $entity = $this->cacheService->get($this->cachePrefix . $cacheKey);

        if ($entity && $isCached) {
            return unserialize($entity);
        }

        $entity = $dataObjectManager->limit(1)->first();

        if (!$entity && !$allowFail) {
            throw new NoSuchEntityException(__('Entity not found'), 404);
        }

        $this->cacheService->set($this->cachePrefix . $cacheKey, serialize($entity));

        return $entity;
    }

    /**
     * Get list of data objects by build search.
     *
     * @param DataObjectManager $dataObjectManager
     * @param bool              $isCached
     * @return Collection
     */
    public function getList(DataObjectManager $dataObjectManager, bool $isCached = true): Collection
    {
        $cacheKey = $this->getCacheKey($dataObjectManager);

        $collection = $this->cacheService->get($this->cacheCollectionPrefix . $cacheKey);

        if ($collection && $isCached) {
            return unserialize($collection);
        }

        $collection = $dataObjectManager->get();

        $this->cacheCollection($cacheKey, $collection);

        return $collection;
    }

    /**
     * Get paginated list of data objects by build search.
     *
     * @param DataObjectManager $dataObjectManager
     * @param int               $pageNumber
     * @param int               $limit
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException|BindingResolutionException
     */
    public function getPaginatedList(
        DataObjectManager $dataObjectManager,
        int $pageNumber,
        int $limit
    ): PaginatedCollection {
        if ($limit > $this->paginationLimit) {
            throw new DataObjectManagerException(
                __('You cant exceed the Limit of %s', [$this->paginationLimit])
            );
        }

        $cacheKey = $this->getCacheKey($dataObjectManager, (string)$pageNumber, (string)$limit);

        $collection = $this->cacheService->get($this->cacheCollectionPrefix . $cacheKey);

        if ($collection) {
            return unserialize($collection);
        }

        $collection = $dataObjectManager->paginate($limit, ['*'], 'page', $pageNumber);

        $this->cacheCollection($cacheKey, $collection);

        return $collection;
    }

    /**
     * Saves or updates given entity.
     *
     * @param DataObject $entity
     * @return DataObject
     * @throws DataObjectManagerException
     */
    public function save(DataObject $entity): DataObject
    {
        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);

        $id = $entity->getData(self::COLUMN_ID);

        try {
            /** @TODO rework this so it works with relations attached */
            $entity->clearRelations();
            if ($id) {
                $dataObjectManager
                    ->where(self::COLUMN_ID, $id)
                    ->update($entity->getData());

                $this->cacheService->deleteByTag($id);

                return $this->get($entity->getId(), $entity::PRIMARY_KEY, true, false) ?? $entity;
            }

            $newId = $dataObjectManager->insertGetId($entity->getData(), $entity::PRIMARY_KEY);

            $this->cacheService->deleteByTag($this->cacheCollectionPrefix);
            return $this->get($newId, $entity::PRIMARY_KEY, true, false) ?? $entity;
        } catch (\Exception $exception) {
            throw new DataObjectManagerException(
                $exception->getMessage(),
                500,
                $exception
            );
        }
    }

    /**
     * Delete by id.
     *
     * @param int $id
     * @return bool
     * @throws DataObjectManagerException
     */
    public function delete(int $id): bool
    {
        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);

        try {
            $deleted = (bool)$dataObjectManager->delete($id);

            if (!$deleted) {
                return false;
            }

            $this->cacheService->deleteByTag($id);

            return true;
        } catch (\Exception $exception) {
            throw new DataObjectManagerException(
                $exception->getMessage(),
                500,
                $exception
            );
        }
    }

    /**
     * Returns one to one relation.
     *
     * @param BaseRepository $repository
     * @param int|null $id
     * @param string $column
     * @return DataObject|null
     * @throws NoSuchEntityException
     */
    public function getOneToOne(BaseRepository $repository, ?int $id, string $column): ?DataObject
    {
        return $repository->get($id, $column, true);
    }

    /**
     * Returns one to many relation.
     *
     * @param BaseRepository $repository
     * @param int            $id
     * @param string         $column
     * @return Collection
     */
    public function getOneToMany(BaseRepository $repository, int $id, string $column): Collection
    {
        $dataObject = $repository->getDataObjectManager()->where($column, $id);

        return $repository->getList($dataObject);
    }

    /**
     * Returns many to many relation.
     *
     * @param BaseRepository $repository
     * @param int            $id
     * @param string         $pivotTable
     * @param string         $primaryPivotColumn
     * @param string         $foreignPivotColumn
     * @return Collection
     */
    public function getManyToMany(
        BaseRepository $repository,
        int $id,
        string $pivotTable,
        string $primaryPivotColumn,
        string $foreignPivotColumn
    ): Collection {
        $primaryTable = $this->entity::TABLE;
        $primaryTableColumn = $this->entity::PRIMARY_KEY;
        $foreignTable = $repository->getEntity()::TABLE;
        $foreignTableColumn = $repository->getEntity()::PRIMARY_KEY;

        $dataObject = $repository->getDataObjectManager()
            ->select([$foreignTable . '.*'])
            ->join(
                $pivotTable,
                $foreignTable . '.' . $foreignTableColumn,
                '=',
                $pivotTable . '.' . $foreignPivotColumn
            )
            ->join(
                $primaryTable,
                $primaryTable . '.' . $primaryTableColumn,
                '=',
                $pivotTable . '.' . $primaryPivotColumn
            )
            ->where($primaryTable . '.' . $primaryTableColumn, $id);

        return $repository->getList($dataObject);
    }

    /**
     * Generates cache key.
     *
     * @param DataObjectManager $dataObjectManager
     * @param string            ...$postfix
     * @return string
     */
    protected function getCacheKey(DataObjectManager $dataObjectManager, string ...$postfix): string
    {
        $sql = $dataObjectManager->toSql();
        $bindings = $dataObjectManager->getBindings();

        $cacheKey = vsprintf(str_replace("?", "%s", $sql), $bindings) . implode($postfix);

        return hash('tiger192,3', $cacheKey);
    }

    /**
     * Caches collection and its items.
     *
     * @param string                         $cacheKey
     * @param Collection|PaginatedCollection $collection
     * @return void
     */
    private function cacheCollection(string $cacheKey, Collection|PaginatedCollection $collection): void
    {
        $cacheTags = [];

        /** @var DataObject $item */
        foreach ($collection as &$item) {
            $cacheItem = clone $item;

            $cacheTags[] = (string)$item->getData($item::PRIMARY_KEY);
            $this->cacheService->set(
                $this->cachePrefix . $item->getData($item::PRIMARY_KEY),
                serialize($cacheItem->clearRelations())
            );
        }

        $this->cacheService->setWithTags(
            $this->cacheCollectionPrefix . $cacheKey,
            serialize($collection),
            $cacheTags,
            $this->cacheCollectionPrefix
        );
    }

    /**
     * Returns data object manager.
     * @return DataObjectManager
     */
    public function getDataObjectManager(): DataObjectManager
    {
        return $this->dataObjectManagerFactory->create($this->entity);
    }

    /**
     * Returns entity of repository.
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}
