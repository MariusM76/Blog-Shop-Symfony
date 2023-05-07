<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin")
     */
    public function index($categoryId = null, CategoryRepository $CategoryRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'allcategories' =>  $CategoryRepository->findAll()
        ]);
    }

}
