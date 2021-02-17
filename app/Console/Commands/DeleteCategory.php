<?php

namespace App\Console\Commands;

use App\Services\CategoryService;

class DeleteCategory extends BaseCmd
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Category';

    /**
     * @var CategoryService
     */
    protected $service;

    /**
     * DeleteCategory constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->service = $categoryService;
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
