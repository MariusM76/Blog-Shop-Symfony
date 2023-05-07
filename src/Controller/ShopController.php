<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryFormType;
use App\Form\CategoryType;
use App\Form\FilterType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use function PHPUnit\Framework\isNull;

/**
 * @Route("/shop")
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/{category}", name="shop")
     */
    public function index($category=null, CategoryRepository $CategoryRepository,ProductRepository $productRepository, Request $request): Response
    {

        $filter = $this->createForm(FilterType::class);
        $filter->handleRequest($request);

        if ($filter->isSubmitted() && $filter->isValid()){
            $data = $filter->getData();

            foreach ($data as $key=>$value){
                if(is_null($value)){
                    unset($data[$key]);
                }
            }
            $qb = $productRepository->createQueryBuilder('p');

            if(count($data['price_range'])>0){
                foreach ($data['price_range'] as $key => $range){
                    $values = explode('-', $range);
                    $qb->orWhere("p.price BETWEEN :start$key AND :end$key")
                        ->setParameter("start$key",$values[0])
                        ->setParameter("end$key",$values[1]);
                }
            }

            if($data['category']->count()>0){
                $qb->andWhere('p.category in (:category)')
                    ->setParameter('category',$data['category']);
            }

            $products = $qb->getQuery()->getResult();

        } else {
//            $category = $CategoryRepository->findAll();
            $products = $productRepository->findAll();
        }

            return $this->render('shop/index.html.twig', [
                'category'      =>  isset($categoryId) ? $CategoryRepository->find($categoryId) : null,
                'products' => $products,
//                'allcategories' =>  $CategoryRepository->findAll(),
//                'allproducts'   =>  $productRepository->findAll(),
                'filter' => $filter->createView()
            ]);
    }

    /**
     * @Route("/product/{id}", name="shop_product")
     */
    public function productPage(Product $product, Request $request,CategoryRepository $CategoryRepository, ProductRepository $ProductRepository): Response
    {
        $id = $request->get('id');

        return $this->render('shop/product.html.twig', [
            'product' => $ProductRepository->find($id),
        ]);
    }
}
