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

class UserOrderController extends Controller{
    /**
     * @Route("/order", name="order_list")
     * Method("GET")
     */
    public function getOrderList(){
        $user= $this->container->get('security.context')->getToken()->getUser();
        $userId=$user->getId();

        $userOrder = $this->getDoctrine()->getRepository(UserOrder::class)->findByUserId($userId);
        // $cartItems = $this->getDoctrine()->getRepository(CartItem::class)->getUserCart($userCart);

        // dump($cartItem);
        // die;
        return $this->render('ecommerce/user_order.html.twig', array('order' => $userOrder));
    }
}