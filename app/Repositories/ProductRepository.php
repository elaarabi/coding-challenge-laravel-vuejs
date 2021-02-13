<?php

namespace App\Repositories;

use App;
use App\Interfaces\CategoryInterface;
use App\Interfaces\ProductInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductRepository extends BaseRepository implements ProductInterface
{
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
    public function all(array $attributes)
    {
        $query = $this->model->with('categories');
        $skipe = 0;
        if (isset($attributes['page'])) {
            if ((int)$attributes['page'] > 1) {
                $skipe = ((int)$attributes['page'] - 1) * self::$perPage;
            }
            unset($attributes['page']);
        }
        if (isset($attributes['sort'])) {
            if ($attributes['sort']) {
                if (substr($attributes['sort'], 0, 1) === '-') {
                    $query = $query->orderBy(substr($attributes['sort'], 1), 'desc');
                } else {
                    $query = $query->orderBy($attributes['sort']);
                }
            }
            unset($attributes['sort']);
        }
        if (isset($attributes['category_id'])) {
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
        $count = $query->count();
        return [
            'count' => $count,
            'pages' => (int)ceil($count / self::$perPage),
            'models' => $query->with('categories')->skip($skipe)->take(self::$perPage)->get(),
        ];
    }

    public function validator(array $data)
    {
        if (!empty($data['categories']) && !is_array($data['categories'])) {
            $data['categories'] = explode(',', $data['categories']);
        }
        $this->fill($data);
        $validator = Validator::make($data, [
            'name' => 'required|max:50',
            'description' => 'required|max:65535',
            'price' => "required|regex:/^\d+(\.\d{1,2})?$/",
            'categories' => "required",
        ]);
        $validator->after(function ($validator) use ($data) {
            if (!empty($data['categories'])) {
                foreach ($data['categories'] as $category) {
                    if (!$this->categoryRepository->find(trim($category))) {
                        $validator->errors()->add(
                            'categories', "Category $category does not exist !"
                        );
                    }
                }
            }
        });
        return $validator;
    }

    /**
     * @param $cats
     */
    public function syncCats($cats)
    {
        $this->model->categories()->sync($cats);
    }
}
