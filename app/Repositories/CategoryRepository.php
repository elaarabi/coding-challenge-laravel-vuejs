<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function __construct()
    {
        $this->model = new Category();
    }

    /**
     * @param $term
     * @return mixed
     */
    public function searchByName($term)
    {
        $query = $this->model->newQuery()->select('id', 'name as text');
        if ($term) {
            $query = $query->where('name', 'like', "%$term%");
        }
        return $query->take(self::$perPage)->get();
    }
}
