<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/product")
 * @IsGranted("ROLE_ADMIN")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('file')->getData();
            $imageFile->move('images/',$imageFile->getClientOriginalName());
            $product->setImage($imageFile->getClientOriginalName());

            $productRepository->add($product);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product,Request $request, ProductRepository $productRepository): Response
    {
//        $product = $productRepository->findBy(['id'=>$id]);
//        $product = $product[0];
        return $this->render('product/show.html.twig', [
//            'product' => isset($id) ? $productRepository->findBy(['id'=>$id]),
            'product' => $product
        ]);
    }

    /**
     * @Route("/editproduct/chooseCategory", name="editproduct1")
     */
    public function editProduct1(CategoryRepository $CategoryRepository): Response
    {
        return $this->render('product/editProduct.html.twig', [
            'allcategories' =>  $CategoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/editproduct2/{categoryId}", name="editproduct2")
     */
    public function editProduct2($categoryId = null, CategoryRepository $CategoryRepository, ProductRepository $productRepository): Response
    {
        return $this->render('product/editProduct.html.twig', [
            'allproducts' => $productRepository->findBy(['category'=>$categoryId])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Product $product,Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('file')->getData();
            $imageFile->move('images/',$imageFile->getClientOriginalName());
            $product->setImage($imageFile->getClientOriginalName());

            $productRepository->add($product);
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Product $product, Request $request,  ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

 }
