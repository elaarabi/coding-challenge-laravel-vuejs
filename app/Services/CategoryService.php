<?php

namespace App\Services;

use App\Interfaces\CategoryInterface;
use App;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CategoryRepository;

/**
 * Class CategoryService
 * @package App\Services
 */
class CategoryService
{

    protected CategoryRepository $repository;
    /**
     * CategoryService constructor.
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->repository = $categoryRepository;
    }

    /**
     * Create Category
     * @param array $data
     * @param null $id
     * @return array
     */
    public function create(array $data): array
    {
        $validator = $this->validator($data);
        if ($validator->fails()) {
            return [
                'type' => 'error',
                'info' => $validator->errors()->first(),
                'code' => 500
            ];
        }
        try {
            if (!$this->repository->save()) {
                return [
                    'type' => 'error',
                    'info' => trans("categories.cant_save"),
                    'code' => 500
                ];
            }
            return [
                'type' => 'success',
                'info' => trans("categories.created", [
                    'id' => $this->repository->getModel()->id
                ]),
                'data' => $this->repository->getModel(),
                'code' => 200
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'error',
                'info' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    /**
     * Category validator
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $this->repository->fill($data);
        $validator = Validator::make($data, [
            'name' => 'required|max:50',
        ]);
        $model = $this->repository->getModel();
        $validator->after(function ($validator) use ($model) {
            if ($model->parent && !$model->parentCategory) {
                $validator->errors()->add(
                    'parent', 'Parent category does not exist !'
                );
            }
        });
        return $validator;
    }

    /**
     * Search By Name in categories
     *
     * @param string $term
     * @return array
     */
    public function search($term)
    {
        return [
            'type' => 'success',
            'data' => $this->repository->searchByName($term)
        ];
    }

    /**
     * Delete Category
     *
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        try {
            $model = $this->repository->find($id);

            // Check the product
            if (!$model) {
                return [
                    'type' => 'error',
                    'info' => trans("categories.not_found", [
                        'id' => $id
                    ]),
                    'code' => 404
                ];
            }

            // Delete the model
            if (!$this->repository->delete($id)) {
                return [
                    'type' => 'error',
                    'info' => trans("categories.cant_remove"),
                    'code' => 500
                ];
            }
            return [
                'type' => 'success',
                'info' => trans("categories.removed"),
            ];
        } catch (\Exception $e) {
            return [
                'type' => 'error',
                'info' => $e->getMessage(),
                'code' => 500
            ];
        }
    }
}
