<?php

namespace App\Services\Product;

use App;
use App\Repositories\Contracts\Product\ProductInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use App\Models\Product\Product;

/**
 * Class ProductService
 * @package App\Services\Product
 */
class ProductService
{
    /**
     * @var ProductInterface
     */
    protected ProductInterface $productRepository;

    /**
     * ProductService constructor.
     * @param ProductInterface $productRepository
     */
    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
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
        return $this->productRepository->all($category_id, $sortColumn, $sortDirection);
    }

    /**
     * Create Or Update Model
     * @param array $attributes
     * @return Product
     * @throws ValidationException|QueryException
     */
    public function create(array $attributes): Product
    {
        $attributes = $this->prepareData($attributes);
        $this->validate($attributes);
        return $this->productRepository->create($attributes);
    }

    /**
     * Prepare input attributes
     * Save Uploaded Image
     *
     * @param array $attributes
     * @return array
     */
    public function prepareData(array $attributes): array
    {
        if (isset($attributes['image'])) {
            if (preg_match('/^data:image\/(\w+);base64,/', $attributes['image'])) {
                $image = substr($attributes['image'], strpos($attributes['image'], ',') + 1);
                $image = base64_decode($image);
                $safeName = md5($image) . '.' . 'png';
                Storage::disk('public')->put('products/' . $safeName, $image);
                $attributes['image'] = 'media/products/' . $safeName;
            }
        }
        return $attributes;
    }

    /**
     * @param array $attributes
     * @throws ValidationException
     */
    public function validate(array $attributes)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required|max:65535',
            'price' => "required|regex:/^\d+(\.\d{1,2})?$/",
            'categories' => "required",
        ];
        $validator = Validator::make($attributes, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->productRepository->delete($id);
    }
}
