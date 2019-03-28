<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CategoryController extends Controller{
    /**
     * @Route("/category", name="show_categories")
     * Method({"GET"})
     */
    public function getCategories(){
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

    }

    /**
     * @Route("/category/delete/{id}", name="delete_category")
     * Method({"DELETE"})
     */
    public function deleteCategory(Request $request, $id){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/category/{id}", name="category_product")
     * Method({"GET"})
     */
    public function getCategoryProduct($id){
        $categories = $this->getDoctrine()->getRepository(Product::class)->getCategoryProduct($id);
        dump($categories);
        die;
    }
}