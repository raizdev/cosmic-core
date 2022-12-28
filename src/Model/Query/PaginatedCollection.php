<?php
namespace Orion\Core\Model\Query;

use Illuminate\Pagination\LengthAwarePaginator as IlluminatePaginator;

/**
 * Class PaginatedCollection
 *
 * @package Orion\Core\Model\Query
 */
class PaginatedCollection extends IlluminatePaginator
{
    /**
     * Accumulate the Items and sets them
     *
     * @param $items
     *
     * @return $this
     */
    public function setItems($items): self
    {
        $this->items = accumulate($items);

        return $this;
    }
}
