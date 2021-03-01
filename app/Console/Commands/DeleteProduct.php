<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Product\ProductService;
use Exception;

class DeleteProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Product';

    /**
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * DeleteProduct constructor.
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
            $id = $this->argument('id');
            $this->productService->delete($id);
            $message = trans("products.removed");
            info($message);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
