<?php

namespace App\Services;

use App\Interfaces\CategoryInterface;
use App;

class CategoryService extends BaseService
{
    protected App\Repositories\BaseRepository $repository;

    /**
     * CategoryService constructor.
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->trans = 'categories';
        $this->repository = $categoryRepository;
    }

    /**
     * @param string $term
     * @return array
     */
    public function search($term)
    {
        return [
            'type' => 'success',
            'data' => $this->repository->searchByName($term)
        ];
    }
}
