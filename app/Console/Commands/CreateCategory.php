<?php

namespace App\Console\Commands;

use App\Services\Product\CategoryService;
use Exception;
use Illuminate\Console\Command;

class CreateCategory extends Command
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

    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * CreateCategory constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $name = $this->ask('Name ?');
            $parent = $this->ask('Parent category "ID" ?');
            $category = $this->categoryService->create([
                'name' => $name,
                'parent' => $parent,
            ]);
            $message = trans("categories.created", ['id' => $category->id]);
            info($message);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
