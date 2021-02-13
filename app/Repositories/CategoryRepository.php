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
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $this->fill($data);
        $validator = Validator::make($data, [
            'name' => 'required|max:50',
        ]);
        $validator->after(function ($validator) {
            if ($this->model->parent && !$this->model->parentCategory) {
                $validator->errors()->add(
                    'parent', 'Parent category does not exist !'
                );
            }
        });
        return $validator;
    }

    /**
     * @param $term
     * @return mixed
     */
    public function searchByName($term)
    {
        $query = $this->model->select('id', 'name as text');
        if ($term) {
            $query = $query->where('name', 'like', "%$term%");
        }
        return $query->take(self::$perPage)->get();
    }
}
