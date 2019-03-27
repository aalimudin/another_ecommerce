<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\UserOrder;
use AppBundle\Enttiy\OrderAddress;
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
     * @Route("/cart" name="cart_item_list")
     * Method({"GET"})
     */
    public function getUserCart(){
        $user= $this->container->get('security.context')->getToken()->getUser();
        $userId=$user->getId();

        $userCart = $this->getDoctrine()->getRepository(Cart::class)->findOneByUserId($userId)->getId();
        $cartItems = $this->getDoctrine()->getRepository(CartItem::class)->findByCartId($userCart);
        dump($cartItems);
        die;
    }

    /**
     * @Route("/cart/delete/{id}" name="delete_cart_item")
     * Method({"DELETE"})
     */
    public function deleteCartItem(Request $request, $id){
        $cartItem = $this->getDoctrine()->getRepository(CartItem::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cartItem);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/cart/delete/all" name="delete_all_cart_item")
     * Method({"DELETE"})
     */
    public function deleteAllCartItem(Request $request){
        $user= $this->container->get('security.context')->getToken()->getUser();
        $userId=$user->getId();

        $userCart = $this->getDoctrine()->getRepository(Cart::class)->findOneByUserId($userId)->getId();
        $cartItems = $this->getDoctrine()->getRepository(CartItem::class)->findByCartId($userCart);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($cartItems);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/cart/checkout" name="cart_checkout")
     * Method({"POST"})
     */
    public function cartCheckOut(Request $request, $address_id){
        
    }
}