<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\UserOrder;
use AppBundle\Entity\Address;
use AppBundle\Entity\OrderAddress;
use AppBundle\Entity\OrderItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \DateTime;

class CartController extends Controller{
    /**
     * @Route("/cart", name="cart_item_list")
     * Method({"GET"})
     */
    public function getUserCart(){
        $user= $this->container->get('security.context')->getToken()->getUser();
        $userId=$user->getId();

        $userCart = $this->getDoctrine()->getRepository(Cart::class)->findOneByUserId($userId)->getId();
        $cartItems = $this->getDoctrine()->getRepository(CartItem::class)->getUserCart($userCart);

        // dump($cartItem);
        // die;
        return $this->render('ecommerce/cart.html.twig', array('cartItems' => $cartItems));
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_cart_item")
     * Method({"DELETE"})
     */
    public function deleteCartItem(Request $request, $id){
        $cartItem = $this->getDoctrine()->getRepository(CartItem::class)->findOneById($id);
        $productId = $cartItem->getProductId();
        $quantity = $cartItem->getQuantity();
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneById($productId);
        $qtyHold =  $product->getQtyHold();
        $availableQty = $product->getAvailableQty();

        $qtyHold = $qtyHold - $quantity;
        $availableQty = $availableQty + $quantity;

        $product->setQtyHold($qtyHold);
        $product->setAvailableQty($availableQty);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->remove($cartItem);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('cart_item_list');
    }

    /**
     * @Route("/cart/delete/all", name="delete_all_cart_item")
     * Method({"DELETE"})
     */
    public function deleteAllCartItem(Request $request){
        $user= $this->container->get('security.context')->getToken()->getUser();
        $userId=$user->getId();

        $userCart = $this->getDoctrine()->getRepository(Cart::class)->findOneByUserId($userId)->getId();
        $cartItems = $this->getDoctrine()->getRepository(CartItem::class)->findByCartId($userCart);

        dump($cartItems);
        die;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cartItems);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/cart/checkout", name="cart_checkout")
     * Method({"POST"})
     */
    public function cartCheckOut(){
        $id = 179;
        $userOrder = new UserOrder();
        $orderAddress = new OrderAddress();
        $orderItem = new OrderItem();

        $user= $this->container->get('security.context')->getToken()->getUser();
        $userId=$user->getId();

        $userCart = $this->getDoctrine()->getRepository(Cart::class)->findOneByUserId($userId);
        $userCartId = $userCart->getId();
        $cartItems = $this->getDoctrine()->getRepository(CartItem::class)->findByCartId($userCartId);
        $cartTotalPrice = $this->getDoctrine()->getRepository(CartItem::class)->getCartTotalPrice($userCartId);

        $address = $this->getDoctrine()->getRepository(Address::class)->findOneByUserId($userId)->getAddress();
        // dump($address);
        // die;
        // $orderAddress->setAddress($address->getAddress());

        $userOrder->setUserId($user);
        $userOrder->setOrderAddress($address);
        $userOrder->setTotalPrice($cartTotalPrice);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userOrder);
        $entityManager->flush();

        $orderId = $this->getDoctrine()->getRepository(UserOrder::class)->getLatestUserOrder($userId);
        // dump($cartItems);
        // die;

        $idCartItems= $this->getDoctrine()->getRepository(CartItem::class)->getItemIdArray($userCart);
        // dump($idCartItems);
        // die;

        foreach($idCartItems as $idCartItem){
            // dump($idCartItem['id']);
            
            $cartProductId = $this->getDoctrine()->getRepository(CartItem::class)->find($idCartItem['id'])->getProductId();
            $cartProduct = $this->getDoctrine()->getRepository(Product::class)->findOneById($cartProductId);

            $cartQuantity = $this->getDoctrine()->getRepository(CartItem::class)->find($idCartItem['id'])->getQuantity();
            $cartTotalPrice = $this->getDoctrine()->getRepository(CartItem::class)->find($idCartItem['id'])->getTotalPrice();
            $orderItem = new OrderItem();
            
            $orderItem->setProductId($cartProduct);
            $orderItem->setQuantity($cartQuantity);
            $orderItem->setTotalPrice($cartTotalPrice);
            $orderItem->setOrderId($orderId);

            $entityManager->persist($orderItem);
        }
        // die;
        $entityManager->flush();
        return $this->render('ecommerce/added_to_cart.html.twig');
    }
}