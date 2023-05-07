<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /** @var Cart */
    private $cart;

    /** @var SessionInterface */
    private $session;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param SessionInterface $session
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->getCurrentCart();
    }

    public static function countCartProducts($cart)
    {
        $total = 0;
        foreach ($cart->getCartItems() as $cartItem){
            $total+= $cartItem->getQuantity();
        }
        return $total;
    }

    public function getCartCount()
    {
        return self::countCartProducts($this->cart);
    }

    public function getCartTotal()
    {
        $total = 0;
        foreach ($this->getCart()->getCartItems() as $cartItem){
            $total+= $this->getPromoCartItemTotal($cartItem);
        }
        return $total;
    }

    public function getPromoCartTotal()
    {
        return $this->getCartTotal();
    }

    public function getCartItemTotal(CartItem $cartItem)
    {
        return $cartItem->getQuantity() * $cartItem->getProduct()->getPrice();
    }

    public function getPromoCartItemTotal(CartItem $cartItem)
    {
        if ($cartItem->getQuantity() >= 10){
            return $this->getCartItemTotal($cartItem)*0.8;
        } else {
            return $this->getCartItemTotal($cartItem);
        }
    }

    public function add(Product $product, $quantity=1)
    {
        $cartItem = $this->getCartItemForProduct($product);
        if ($cartItem){
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);
        $cartItem->setCart($this->cart);
        }
        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();
    }

    public function update(Product $product, $quantity=1)
    {
        $cartItem = $this->getCartItemForProduct($product);
        if ($cartItem){
            $cartItem->setQuantity($quantity);
            $this->entityManager->persist($cartItem);
            $this->entityManager->flush();
        }
    }

    public function delete(Product $product)
    {
        $cartItem = $this->getCartItemForProduct($product);
        $this->entityManager->remove($cartItem);
        $this->entityManager->flush();
    }

    public function empty()
    {
        foreach ($this->cart->getCartItems() as $cartItem) {
            $this->entityManager->remove($cartItem);
        }
        $this->entityManager->flush();
    }

    private function getCartItemForProduct(Product $product): ?CartItem
    {
        foreach ($this->cart->getCartItems() as $cartItem){
             If ($cartItem->getProduct()->getId() == $product->getId()){
                 return $cartItem;
             }
        }
        return null;
    }

    private function getCurrentCart()
    {
        if ($this->session->has('cart_id')){
            $this->cart = $this->entityManager->getRepository(Cart::class)->find($this->session->get('cart_id'));
        } else {
            $this->cart = new Cart();
            $this->entityManager->persist($this->cart);
            $this->entityManager->flush();
            $this->session->set('cart_id',$this->cart->getId());
        }

        if ($this->security->getUser() != null){
            $user = $this->security->getUser();
            $userId = $user->getId();
            $this->cart->setUser($user);

            $lastUserCart = $this->entityManager->getRepository(Cart::class)->findBy(['user'=>$userId],['id'=>'DESC'],1);

            if ($lastUserCart){
                $this->cart = $lastUserCart[0];
            }
            $this->entityManager->persist($this->cart);
            $this->entityManager->flush();
        }

    }

    public function deleteCart($cart)
    {
        $this->entityManager->remove($cart);
        $this->entityManager->flush();
    }

    public function refreshCart($cart)
    {
        $this->entityManager->persist($this->getCart());
        $this->entityManager->flush();
    }

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

}