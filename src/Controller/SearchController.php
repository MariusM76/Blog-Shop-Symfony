<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("search/", name="app_search", methods={"GET", "POST"})
     */
    public function index(Request $request, ProductRepository $productRepository, ArticleRepository $articleRepository): Response
    {
        $requestData = $request->query->get('query');
        $em = $this->getDoctrine()->getManager();
        $qb1 = $em->createQueryBuilder();

        $qb1->select('p')
            ->from(Product::class, 'p')
            ->where('p.name LIKE :query')
            ->orderBy('p.name', 'ASC')
            ->setParameter('query', '%'.$requestData.'%');

        $productResult = $qb1->getQuery()->getResult();

        $qb2 = $em->createQueryBuilder();

        $qb2->select('a')
            ->from(Article::class, 'a')
            ->where('a.title LIKE :query')
            ->orderBy('a.title', 'ASC')
            ->setParameter('query', '%'.$requestData.'%');

        $articleResult = $qb2->getQuery()->getResult();

        return $this->render('search/index.html.twig',[
            'articleResult' => $articleResult,
            'results' => $productResult,
            'requestData' => $requestData
        ]);
    }

}
