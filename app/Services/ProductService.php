<?php

namespace App\Services;

use App\Interfaces\ProductInterface;
use App;
use Illuminate\Support\Facades\Storage;

class ProductService extends BaseService
{
    /**
     * ProductService constructor.
     * @param ProductInterface $productRepository
     */
    public function __construct(ProductInterface $productRepository)
    {
        $this->trans = 'products';
        $this->repository = $productRepository;
    }

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
        return parent::prepareData($data);
    }

    /**
     * @param array $data
     */
    public function afterSave(array $data)
    {
        $this->repository->syncCats($data['categories']);
    }
}
