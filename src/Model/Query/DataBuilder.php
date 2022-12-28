<?php
namespace Orion\Framework\Model\Query;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;

/**
 * Class Builder
 *
 * @package Orion\Framework\Model\Query
 */
class DataBuilder extends Builder
{
    use BuildsQueries;

    /**
     * Create a new length-aware paginator instance.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int                            $total
     * @param int                            $perPage
     * @param int                            $currentPage
     * @param array                          $options
     *
     * @return PaginatedCollection
     * @throws BindingResolutionException
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options): PaginatedCollection
    {
        return Container::getInstance()->makeWith(PaginatedCollection::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return LengthAwarePaginator|PaginatedCollection
     * @throws BindingResolutionException
     */
    public function paginate(
        $perPage = 15,
        $columns = ['*'],
        $pageName = 'page',
        $page = null
    ): LengthAwarePaginator|PaginatedCollection {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $total = $this->getCountForPagination();

        $results = $total ? $this->forPage($page, $perPage)->get($columns) : collect();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}
