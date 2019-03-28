<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \DateTime;
use Psr\Log\LoggerInterface;

class UserProductController extends Controller{
     /**
     * @Route("/product", name="product_list")
     * Method({"GET"})
     */
    public function getProduct(){

        // $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $product = $this->getDoctrine()->getRepository(Product::class)->getProductWithCategory();
        return $this->render('ecommerce/user_product.html.twig', array('product' => $product));
    }

    /**
     * @Route("/product/{id}", name="product_detail")
     * Method({"GET"})}
     */
    public function getProductDetail($id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        return $this->render('ecommerce/product_detail.html.twig', array('product' => $product));
    }

    /**
     * @Route("/product/addtocart/{id}", name="add_to_cart")
     * Method({"GET", "POST"})
     */
    public function addProductToCart(Request $request, $id, LoggerInterface $logger = null){
        
        $cart = new Cart();
        $product = new Product();
        $cartItem = new CartItem();
        // $datetime = new DateTime();

        $userId= $this->container->get('security.context')->getToken()->getUser();
        // $userId = $user->getId();

        $form = $this->createFormBuilder($cartItem)
                        ->add('quantity', IntegerType::class, array('attr' => array('class' => 'form-contol')))
                        ->add('add_to_cart', SubmitType::class, array('label' => 'Add to Cart', 'attr' 
                            => array('class' => 'btn tbn-primary mt-5')))
                        ->getForm();

        $form->handleRequest($request);

        // if($form->isSubmitted() && $form->isValid()){ 
            $stock = $this->getDoctrine()->getRepository(Product::class)->findOneById($id)->getAvailableQty();

            $quantity = 3;
            $price = $this->getDoctrine()->getRepository(Product::class)->findOneById($id)->getPrice();
            $qtyHold = $this->getDoctrine()->getRepository(Product::class)->findOneById($id)->getQtyHold();
            $availableQty = $this->getDoctrine()->getRepository(Product::class)->findOneById($id)->getAvailableQty();
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            $userCartCount = $this->getDoctrine()->getRepository(Cart::class)->getUserCartCount($userId);
            $entityManager = $this->getDoctrine()->getManager();

            if($availableQty - $quantity <= 0){
                $this->get('session')->getFlashBag()->add('error', 'Stock not enough');
                return $this->redirectToRoute('product_list'); 
            } else {
                $qtyHold = $qtyHold + $quantity;
            
                // dump($userCartCount);
                // die;

                if($userCartCount == 0){
                    $cart->setUserId($userId);
        
                    $entityManager->persist($cart);
                    
                    $entityManager->flush();
                }

                $cartId = $this->getDoctrine()->getRepository(Cart::class)->findOneByUserId($userId);

                // dump($cartId);
                // die();
                $cartItem->setCartId($cartId);
                $cartItem->setProductId($product);   
                $cartItem->setQuantity($quantity);
                $cartItem->setTotalPrice($price * $quantity);

                $totalPrice = $this->getDoctrine()->getRepository(CartItem::class)->getCartTotalPrice($cartId);
                
                $cartId->setTotalPrice($totalPrice);

                $product->setAvailableQty($stock - $quantity);
                $product->setQtyHold($qtyHold);
                // dump($product);
                // die();

                // $entityManager->merge($cart);
                $entityManager->persist($cartItem);
                $entityManager->persist($product);
                $entityManager->flush();
                $entityManager->flush();

                return $this->redirectToRoute('product_list');                
            }

        // }   
        return $this->render('ecommerce/added_to_cart.html.twig', array('form' => $form->createView()));
    }   
}