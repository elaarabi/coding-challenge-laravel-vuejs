<?php

namespace App\Repositories;

use App;
use App\Interfaces\CategoryInterface;
use App\Interfaces\ProductInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductRepository extends BaseRepository implements ProductInterface
{
    /**
     * The category repository interface
     *
     * @var CategoryInterface
     */
    protected $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->model = new Product();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function all(array $attributes, $page = 1, $sortColumn = null, $sortDirection = 'asc')
    {
        $query = $this->model->newQuery()->with('categories');
        $skipe = 0;
        if ((int)$page > 1) {
            $skipe = ((int)$page - 1) * self::$perPage;
        }
        if (isset($attributes['category_id'])) {

            //Include in the search the children of category

            $category = $this->categoryRepository->find($attributes['category_id']);
            $categoryIds = $category->children()->pluck('id')->toArray();
            $categoryIds[] = $category->id;
            unset($attributes['category_id']);
            $query = $query->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            });
        }
        foreach ($attributes as $key => $val) {
            $query = $query->where($key, $val);
        }
        if ($sortColumn) {
            $query = $query->orderBy($sortColumn, $sortDirection);
        }
        $count = $query->count();
        return [
            'count' => $count,
            'pages' => (int)ceil($count / self::$perPage),
            'models' => $query->skip($skipe)->take(self::$perPage)->get(),
        ];
    }

    /**
     * Synchronize Many To Many relation with categories
     * @param $cats
     */
    public function syncCats($cats)
    {
        $this->model->categories()->sync($cats);
    }
}
