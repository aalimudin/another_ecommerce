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
use Psr\Log\LoggerInterface;

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

        dump($cartItem);
        die;
        return $this->render('ecommerce/cart.html.twig', array('cartItems' => $cartItems));
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_cart_item")
     * Method({"DELETE"})
     */
    public function deleteCartItem(Request $request, $id){
        $product = new Product();

        $cartItem = $this->getDoctrine()->getRepository(CartItem::class)->findOneById($id);
        $productId = $this->getDoctrine()->getRepository(CartItem::class)->findOneById($id)->getProductId();
        $quantity = $this->getDoctrine()->getRepository(CartItem::class)->findOneById($id)->getQuantity();
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneById($productId);
        $qtyHold =  $this->getDoctrine()->getRepository(Product::class)->findOneById($productId)->getQtyHold();
        $availableQty = $this->getDoctrine()->getRepository(Product::class)->findOneById($productId)->getAvailableQty();

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

        $address = $this->getDoctrine()->getRepository(Address::class)->findOneByUserId($userId)->getAddress();
        // dump($address);
        // die;
        // $orderAddress->setAddress($address->getAddress());

        $userOrder->setUserId($user);
        $userOrder->setOrderAddress($address);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userOrder);
        $entityManager->flush();

        $orderId = $this->getDoctrine()->getRepository(UserOrder::class)->getLatestUserOrder($userCartId);
        dump($orderId);
        die;

        // $cartItem = $this->getDoctrine()->getRepository(CartItem::class)->getItemIdArray($userCart);
        // foreach($cartItems as $cartItem){
        //     $orderItem = $this->getDoctrine()->getRepository(CartItem::class)->find($cartItem['id']);
        //     $orderItem->setOrderId($orderId);
            
        // }
    }
}