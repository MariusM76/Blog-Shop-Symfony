<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class TwigGlobalService
{
    /** @var CategoryRepository */
    private $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getFilterCategories()
    {
        return $this->categoryRepository->findAll();
    }


}