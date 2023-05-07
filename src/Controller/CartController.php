<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\AdressRepository;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
        ]);
    }

    /**
     * @Route("/cart/{product}/add", name="cart_add")
     */
    public function add(Product $product, CartService $cartService): Response
    {
        $cartService->add($product);
        return $this->redirectToRoute('shop_product',['id'=>$product->getId()]);
    }

    /**
     * @Route("/cart/{product}/update", name="cart_update")
     */
    public function update(Product $product, Request $request, CartService $cartService): Response
    {
//        dd($product,$request);
        $cartService->update($product, $request->request->get('quantity'));
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/{product}/delete", name="cart_delete")
     */
    public function delete(Product $product, CartService $cartService): Response
    {
        $cartService->delete($product);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete", name="cart_empty")
     */
    public function empty(CartService $cartService): Response
    {
        $cartService->empty();
        return $this->redirectToRoute('cart');
    }



    /**
     * @Route("/cart/checkout", name="cart_checkout")
     */
    public function checkout(CartService $cartService): Response
    {
//        dd($cartService->security->getUser()->getAdresses());
        if ($cartService->security->getUser()->getAdresses() ){
            $adresses = $cartService->security->getUser()->getAdresses();
        } else {
            $adresses = null;
        }

        return $this->render('cart/checkout.html.twig',[
            'adresses' => $adresses
        ]);
    }

    /**
     * @Route("/cart/checkout2", name="cart_delivery")
     */
    public function delivery(CartService $cartService, Request $request, AdressRepository $adressRepository): Response
    {
        $data = $request->request->all();
//        dd($data);
        if ($data['delivery'] == 'courier'){
            $deliveryCost = 15;
        } else {
            $deliveryCost = 0;
        }
        if ($data['payment'] == 'cash'){
            $paymentCost = 5;
        } else {
            $paymentCost = 0;
        }
        $totalDeliveryCost = $deliveryCost + $paymentCost;

        if(isset($data['adressType']) && $data['adressType'] == 'old'){
//            $invoiceAdress = new Adress($data['currentAddress']);
            $invoiceAdress = $adressRepository->find($data['currentAddress']);
        } elseif (isset($data['adressType']) && $data['adressType'] == 'new'){
            $invoiceAdress = new Adress();
            $invoiceAdress->setFirstName($data['firstName']);
            $invoiceAdress->setLastName($data['lastName']);
            $invoiceAdress->setUser($adresses = $cartService->security->getUser());
            $invoiceAdress->setAdress($data['adress']);
            $invoiceAdress->setCity($data['city']);
            $invoiceAdress->setState($data['state']);
            $invoiceAdress->setPhone($data['phone']);
            $adressRepository->add($invoiceAdress);
        }

        if (!isset($data['adressType'])){
            $invoiceAdress = 'There is no address. Please add!!';
        }

        $adresses = $cartService->security->getUser()->getAdresses();

        return $this->render('cart/checkout.html.twig',[
            'totalDeliveryCost' => $totalDeliveryCost,
            'delivery' => $data['delivery'],
            'payment' => $data['payment'],
            'adresses' => $adresses,
            'adressType'=> $data['adressType'],
            'invoiceAdresses' => $invoiceAdress->getState().', '.$invoiceAdress->getCity().', str. '.$invoiceAdress->getAdress(),
            ]);
    }

    /**
     * @Route("/cart/finalizeOrder", name="finalizeOrder")
     */
    public function finalizeOrder(CartService $cartService, Request $request, OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, AdressRepository $adressRepository, ProductRepository $ProductRepository, CategoryRepository $CategoryRepository): Response
    {
        $data = $request->request->all();
        $currentAdress = ($adressRepository->findBy(['id'=>$data['currentAddress']]))[0];
        $newOrder = new Order();
        $newOrder->setUser($cartService->security->getUser());
        $newOrder->setDelivery($data['delivery']);
        $newOrder->setPayment($data['payment']);
        $newOrder->setCart($cartService->getCart());
        $newOrder->setAdress($currentAdress);
        $orderRepository->add($newOrder);


        foreach ($cartService->getCart()->getCartItems() as $cartItem){
            $newOrderItem = new OrderItem();
            $newOrderItem->setProduct($cartItem->getProduct());
            $newOrderItem->setQuantity($cartItem->getQuantity());
            $newOrderItem->setAssignedOrder($newOrder);
            $newOrderItem->setPrice($cartItem->getProduct()->getPrice());
            $orderItemRepository->add($newOrderItem);
        }

        $cartService->empty();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/cart/update/{cartItemId}/{quantity}", name="cart_update_product_quantity")
     */
    public function updateCartItemQuantity($cartItemId = null, $quantity = null, CartService $cartService, CartItemRepository $cartItemRepository)
    {
        $product = $cartItemRepository->find($cartItemId)->getProduct();
        $cartService->update($product, $quantity);
        return new JsonResponse(['ok']);
    }

    /**
     * @Route("/cart/delete/{cartItemId}", name="cart_delete_product")
     */
    public function deleteCartItem($cartItemId = null, CartService $cartService, CartItemRepository $cartItemRepository)
    {
        $product = $cartItemRepository->find($cartItemId)->getProduct();
        $cartService->delete($product);
        return new JsonResponse(['ok']);
    }

    /**
     * @Route("/cart/{productId}", name="cart_add_ajax")
     */
    public function addToCartAjax($productId = null,ProductRepository $productRepository, CartService $cartService)
    {
        $product = $productRepository->find($productId);
        $cartService->add($product);
        return new JsonResponse(['ok']);
    }
}
