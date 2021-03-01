<?php

namespace App\Repositories\Eloquent\Product;

use App;
use App\Repositories\Contracts\Product\ProductInterface;
use App\Models\Product\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;

class ProductRepository implements ProductInterface
{

    /**
     * @param $id
     * @return Product|null
     */
    public function find($id): ?Product
    {
        return Product::find($id);
    }

    /**
     * attributes['categories'] must contain categories or fail.
     * if categories is not set the validation must not pass.
     * since categories are always required i ignored the test of their existence here.
     * i dont know if this is ok or must pass it as separate param if the method should be used otherwise !!
     * i also added the attachment of categories in the repository create and not in the service since the service is not allowed to handle database queries.
     * there is also no need to check the $product var since create will return a product or fail
     *
     *
     * @param array $attributes (See above)
     * @return Product
     * @throws QueryException
     */
    public function create(array $attributes): Product
    {
        $product = Product::create($attributes);
        $product->categories()->attach($attributes['categories']);
        return $product;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return Product::destroy($id);
    }

    /**
     * List of products
     *
     * @param int|null $category_id
     * @param string $sortColumn
     * @param string $sortDirection
     * @return LengthAwarePaginator
     */
    public function all(int $category_id = null, string $sortColumn = '', $sortDirection = 'asc'): LengthAwarePaginator
    {
        $query = Product::query()->with('categories');
        if ($category_id) {
            $query = $query->whereHas('categories', function ($query) use ($category_id) {
                $query->where('categories.id', $category_id);
            });
        }
        if ($sortColumn) {
            $query = $query->orderBy($sortColumn, $sortDirection);
        }
        return $query->paginate();
    }

}
