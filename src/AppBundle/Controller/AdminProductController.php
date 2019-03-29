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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AdminProductController extends Controller{
    /**
     * @Route("/admin/product", name="admin_product_list")
     * Method({"GET"})
     */
    public function getProduct(){
        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('ecommerce/admin_product.html.twig', array('product' => $product));
    }

    /**
     * @Route("/admin/product/update/{id}", name="product_update")
     * Method({"POST"})
     */
    public function editProduct(Request $request, $id){
        $product = new Product();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $form = $this->createFormBuilder($product)
                        ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('category_id', TextType::class, array('attr' => array('class' =>'form-control')))
                        ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('price', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('stock', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary')))
                        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('product_list');
        }

    }

    /**
     * @Route{"/admin/product/delete/{id}", name="delete_product"}
     * @Method({"DELETE"})
     */
    public function deleteProduct(Request $request, $id){
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

}