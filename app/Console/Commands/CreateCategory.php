<?php

namespace App\Console\Commands;

use App\Services\CategoryService;

class CreateCategory extends BaseCmd
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Category';

    protected $service;

    /**
     * CreateCategory constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
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
            'parent' => $this->ask('Parent category "ID" ?'),
        ]));
        return 0;
    }
}
