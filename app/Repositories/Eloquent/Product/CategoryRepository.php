<?php

namespace App\Repositories\Eloquent\Product;

use App\Repositories\Contracts\Product\CategoryInterface;
use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

class CategoryRepository implements CategoryInterface
{

    /**
     * @param array $attributes
     * @return Category
     * @throws QueryException
     */
    public function create(array $attributes): Category
    {
        return Category::create($attributes);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return Category::destroy($id);
    }

    /**
     * @param $name
     * @return Collection
     */
    public function searchByName($name): Collection
    {
        $query = Category::query()->select('id', 'name as text');
        if ($name) {
            $query = $query->where('name', 'like', "%$name%");
        }
        return $query->take(25)->get();
    }
}
