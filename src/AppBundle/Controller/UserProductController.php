<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UserProductController extends Controller{
     /**
     * @Route("/product", name="product_list")
     * Method({"GET"})
     */
    public function getProduct(){

        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
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
    public function addProductToCart(Request $request, $id){
        
        $cart = new Cart();
        $product = new Product();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $form = $this->createFormBuilder($cart)
                        ->add('amount', IntegerType::class, array('attr' => array('class' => 'form-contol')))
                        ->add('add_to_cart', SubmitType::class, array('label' => 'Add to Cart', 'attr' 
                            => array('class' => 'btn tbn-primary mt-5')))
                        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stock = $this->getDoctrine()->getRepository(Product::class)->getProductStock($id);
            $amount = $form["amount"]->getData();
            $price = $this->getDoctrine()->getRepository(Product::class)->getProductPrice($id);

            $cart->setUserId($userId);
            $cart->setProductId($id);
            $cart->setPrice($price);
            $cart->setAmount($amount);
            $cart->setTotalPrice($price * $amount);

            $product->setStock($stock - $amount);

            $entityManagerCart = $this->getDoctrine()->getManager('cart');
            $entityManager = $this->getDoctrine()->getManager();

            $entityManagerCart->persist($cart);
            $entityManager->persist($product);
            $entityManagerCart->flush();
            $entityManager->flush();
        }   
    }   
}