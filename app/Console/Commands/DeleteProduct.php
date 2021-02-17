<?php

namespace App\Console\Commands;

use App\Services\ProductService;

class DeleteProduct extends BaseCmd
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
    protected $service;

    /**
     * DeleteProduct constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->service = $productService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->formatResult($this->service->delete($this->argument('id')));
        return 0;
    }
}
