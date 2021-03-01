<?php

namespace App\Services\Product;

use app\models\Product\Category;
use App\Repositories\Contracts\Product\CategoryInterface;
use App;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoryService
 * @package App\Services\Product
 */
class CategoryService
{

    protected CategoryInterface $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array $attributes
     * @return Category
     * @throws ValidationException|QueryException
     */
    public function create(array $attributes): Category
    {
        $this->validate($attributes);
        return $this->categoryRepository->create($attributes);
    }

    /**
     * @param array $data
     * @return Validator
     * @throws ValidationException
     */
    public function validate(array $data): Validator
    {
        $rules = [
            'name' => 'required|max:50',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Search Category By Name
     *
     * @param $name
     * @return Collection
     */
    public function search($name): Collection
    {
        return $this->categoryRepository->searchByName($name);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
