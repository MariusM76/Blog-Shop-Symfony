<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $CategoryRepository, ProductRepository $ProductRepository): Response
    {
        return $this->render('default/index.html.twig',[
            'categories'    =>  $CategoryRepository->findAll(),
            'allproducts'   =>  $ProductRepository->findAll()
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(CategoryRepository $CategoryRepository, ProductRepository $ProductRepository): Response
    {
        return $this->render('about.html.twig');
    }

}
