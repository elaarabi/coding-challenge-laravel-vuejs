<?php

namespace App\Console\Commands;

use App\Services\ProductService;

class CreateProduct extends BaseCmd
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
    protected $service;

    /**
     * CreateProduct constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->formatResult($this->service->createOrUpdate([
            'name' => $this->ask('Name ?'),
            'description' => $this->ask('Description ?'),
            'price' => (float)$this->ask('Price ?'),
            'categories' => $this->ask('Categories IDS : separate with "," ?'),
        ]));

        return 0;
    }
}
