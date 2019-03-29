<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Phone;

class PhoneController extends Controller{
     /**
     * @Route("/phone", name="phone_list")
     * @Method({"GET"})
     */
    public function getUserPhone(){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId(); 

        $userPhone = $this->getDoctrine()->getRepository(Phone::class)->findByUserId($userId);
        // dump($userPhone);
        // die;
        return $this->render('ecommerce/phone_list.html.twig', array('phone' => $userPhone));
    }

    /**
     * @Route("/phone/new", name="add_phone")
     * Method({"GET", "POST"})
     */
    public function saveUserPhone(Request $request){
        $phone = new Phone();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $form = $this->createFormBuilder($phone)
                        ->add('phone', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
                        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $phone->setUserId($user);
            $phone = $form->getData($form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phone);
            $entityManager->flush();

            return $this->redirectToRoute("phone_list");
        }
        return $this->render('ecommerce/new_phone.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/phone/edit/{phone_id}", name="edit_phone")
     * Method({"GET", "POST"})
     */
    public function editUserPhone(Request $request, $phone_id){
        $phone = new Phone();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $phone = $this->getDoctrine()->getRepository(Phone::class)->findOneById($phone_id);

        $form = $this->createFormBuilder($phone)
                        ->add('phone', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
                        ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $phone->setUserId($user);
            $phone = $form->getData($form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phone);
            $entityManager->flush();

            return $this->redirectToRoute("phone_list");
        }
        return $this->render('ecommerce/edit_phone.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/phone/delete/{phone_id}", name="delete_phone")
     * Method({"DELETE"})
     */
    public function deletePhone(Request $request, $phone_id){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $phone = $this->getDoctrine()->getRepository(Phone::class)->findOneById($phone_id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phone);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute("phone_list");
    }
}