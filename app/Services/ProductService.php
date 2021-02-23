<?php

namespace App\Services;

use App\Interfaces\ProductInterface;
use App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ProductRepository;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    protected ProductRepository $repository;

    /**
     * ProductService constructor.
     * @param ProductInterface $productRepository
     */
    public function __construct(ProductInterface $productRepository)
    {
        $this->repository = $productRepository;

    }

    /**
     * Find Multiple Models
     *
     * @param array $data
     * @return array
     */
    public function getAll(array $data): array
    {
        $sortColumn = null;
        $sortDirection = 'asc';
        $page = null;
        if (isset($data['sort'])) {
            if ($data['sort']) {
                if (substr($data['sort'], 0, 1) === '-') {
                    $sortColumn = substr($data['sort'], 1);
                    $sortDirection = 'desc';
                } else {
                    $sortColumn = $data['sort'];
                }
            }
            unset($data['sort']);
        }
        if (isset($data['page']) && (int)$data['page'] > 1) {
            $page = (int)$data['page'];
            unset($data['page']);
        }
        try {
            return [
                'type' => 'success',
                'data' => $this->repository->all($data, $page, $sortColumn, $sortDirection)
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
     * Create Or Update Model
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        if (!empty($data['categories']) && !is_array($data['categories'])) {
            $data['categories'] = explode(',', $data['categories']);
        }
        $data = $this->prepareData($data);
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
                    'info' => trans("products.cant_save"),
                    'code' => 500
                ];
            }
            $this->repository->syncCats($data['categories']);
            return [
                'type' => 'success',
                'info' => trans("products.created", [
                    'id' => $this->repository->getModel()->id
                ]),
                'data' => $this->repository->getModel()
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
     * Prepare input data data
     * @param array $data
     * @return array
     */
    public function prepareData(array $data): array
    {
        if (isset($data['image'])) {
            if (preg_match('/^data:image\/(\w+);base64,/', $data['image'])) {
                $image = substr($data['image'], strpos($data['image'], ',') + 1);
                $image = base64_decode($image);
                $safeName = md5($image) . '.' . 'png';
                Storage::disk('public')->put('products/' . $safeName, $image);
                $data['image'] = 'media/products/' . $safeName;
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $this->repository->fill($data);
        $validator = Validator::make($data, [
            'name' => 'required|max:50',
            'description' => 'required|max:65535',
            'price' => "required|regex:/^\d+(\.\d{1,2})?$/",
            'categories' => "required",
        ]);
        return $validator;
    }
}
