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
use App\Entity\Address;

class AddressController extends Controller{
    /**
     * @Route("/user/{id}/address", name="address_list")
     * @Method({"GET"})
     */
    public function getUserAddress(){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId(); 

        $userAddress = $this->getDoctrine()->getRepository(Address::class)->getUserAddress($userId);
        
    }

    /**
     * @Route("/user/{id}/address/new", name="add_address")
     * Method({"GET", "POST"})
     */
    public function saveUserAddress(Request $request){
        $address = new Address();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $form = $this->createFormBuilder($address)
                        ->add('address', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('city', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('province', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('country', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
                        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUserId($userId);
            $address = $form->getData($form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute("address_list");
        }
    }

    /**
     * @Route("/user/{id}/address/edit/{address_id}", name="edit_address")
     * Method({"GET", "POST"})
     */
    public function editUserAddress(Request $response, $address_id){
        $address = new Address();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $form = $this->createFormBuilder($address)
                        ->add('address', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('city', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('province', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('country', TextType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
                        ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUserId($userId);
            $address = $form->getData($form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute("address_list");
        }
    }

    /**
     * @Route("/user/{id}/address/delete/{address_id}", name="delete_address")
     * Method({"DELETE"})
     */
    public function deleteAddress(Request $request, $address_id){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $address = $this->getDoctrine()->getRepository()->deleteAddress($address_id, $userId);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($address);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}