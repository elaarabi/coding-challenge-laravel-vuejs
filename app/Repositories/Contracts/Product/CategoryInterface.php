<?php

namespace App\Repositories\Contracts\Product;


use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

interface CategoryInterface
{

    /**
     * @param $name
     * @return Collection
     */
    public function searchByName($name): Collection;

    /**
     * @param array $attributes
     * @return Category
     * @throws QueryException
     */
    public function create(array $attributes): Category;

    /**
     * @param $id
     * @return boolean
     */
    public function delete($id): bool;
}
