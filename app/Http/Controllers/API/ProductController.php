<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseAPI;

    /**
     * @var ProductService
     */
    protected ProductService $service;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }

    /**
     * List of products
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->formatResponse($this->service->getAll($request->all()));
    }

    /**
     * Create Product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->formatResponse($this->service->create($request->all()));
    }
}
