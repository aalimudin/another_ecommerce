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
use AppBundle\Entity\Address;

class AddressController extends Controller{
    /**
     * @Route("/address", name="address_list")
     * @Method({"GET"})
     */
    public function getUserAddress(){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId(); 

        $userAddress = $this->getDoctrine()->getRepository(Address::class)->findByUserId($userId);
        // dump($userAddress);
        // die;
        return $this->render('ecommerce/user_address.html.twig', array('address' => $userAddress));
    }

    /**
     * @Route("/address/new", name="add_address")
     * Method({"GET", "POST"})
     */
    public function saveUserAddress(Request $request){
        $address = new Address();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $form = $this->createFormBuilder($address)
                        ->add('address', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
                        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUserId($user);
            $address = $form->getData($form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute("address_list");
        }
        return $this->render('ecommerce/new_address.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/address/edit/{address_id}", name="edit_address")
     * Method({"GET", "POST"})
     */
    public function editUserAddress(Request $request, $address_id){
        $address = new Address();

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $address = $this->getDoctrine()->getRepository(Address::class)->findOneById($address_id);

        $form = $this->createFormBuilder($address)
                        ->add('address', TextareaType::class, array('attr' => array('class' => 'form-control')))
                        ->add('save', SubmitType::class, array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary mt-3')))
                        ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUserId($user);
            $address = $form->getData($form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute("address_list");
        }
        return $this->render('ecommerce/edit_address.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/address/delete/{address_id}", name="delete_address")
     * Method({"DELETE"})
     */
    public function deleteAddress(Request $request, $address_id){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();

        $address = $this->getDoctrine()->getRepository(Address::class)->findOneById($address_id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($address);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute("address_list");
    }
}