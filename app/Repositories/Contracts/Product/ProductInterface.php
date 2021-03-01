<?php

namespace App\Repositories\Contracts\Product;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Product\Product;
use Illuminate\Database\QueryException;

interface ProductInterface
{
    /**
     * @param int|null $category_id
     * @param string $sortColumn
     * @param string $sortDirection
     * @return LengthAwarePaginator
     */
    public function all(int $category_id = null, string $sortColumn = '', $sortDirection = 'asc'): LengthAwarePaginator;

    /**
     * @param array $attributes
     * @return Product
     * @throws QueryException
     */
    public function create(array $attributes): Product;

    /**
     * @param $id
     * @return Product|null
     */
    public function find($id): ?Product;

    /**
     * @param $id
     * @return boolean
     */
    public function delete($id): bool;
}
