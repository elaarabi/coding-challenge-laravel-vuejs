<?php

namespace App\Console\Commands;

use App\Services\Product\CategoryService;
use Illuminate\Console\Command;
use Exception;

class DeleteCategory extends Command
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
    protected CategoryService $categoryService;

    /**
     * DeleteCategory constructor.
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
            $id = $this->argument('id');
            $this->categoryService->delete($id);
            $message = trans("categories.removed");
            info($message);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
