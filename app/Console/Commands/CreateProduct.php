<?php

namespace App\Console\Commands;

use App\Services\Product\ProductService;
use Illuminate\Console\Command;
use Exception;

class CreateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Product';

    /**
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * CreateProduct constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $name = $this->ask('Name ?');
            $description = $this->ask('Description ?');
            $price = (float)$this->ask('Price ?');
            $categories = $this->ask('Categories IDS : separate with "," ?');
            $product = $this->productService->create([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'categories' => explode(',', $categories),
            ]);
            $message = trans("products.created", ['id' => $product->id]);
            info($message);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
