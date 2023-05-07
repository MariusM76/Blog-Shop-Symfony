<?php

namespace App\Controller;


use App\Repository\CartItemRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class UpdateQtyCartAjax extends AbstractController
{
///**
// * @param SessionInterface $session
// * @param EntityManagerInterface $entityManager
// */
//
//    public function __construct(EntityManagerInterface $entityManager)
//    {
//    $this->entityManager = $entityManager;
//    }
//
    /**
     * @Route("/cart/update", name="cartUpdate")
     */
    public function updateCartItemQty(CartItemRepository $cartItemRepository, CartService $cartService)
    {
        if (isset($_POST['quantity']) && isset($_POST['cartItemId']) ) {
            $quantity = $_POST['quantity'];
            $cartItemID = $_POST['cartItemId'];
//            $cartCount = $_POST['totalCartQty'];
            $itemToUpdate = ($cartItemRepository->findBy(['id' => $_POST['cartItemId']]))[0];
//            dd($cartItemRepository);
            $product = $itemToUpdate->getProduct();
            $cartService->update($product,$quantity);
            $cartService::countCartProducts($cartService->getCart());
            $cartService->refreshCart($cartService->getCart());
//            return $this->redirectToRoute('cart');
        }

    }

}
