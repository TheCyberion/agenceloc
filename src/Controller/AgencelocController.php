<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeFormType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgencelocController extends AbstractController
{
    #[Route('/agenceloc', name: 'app_agenceloc')]
    public function index(): Response
    {

        return $this->render('agenceloc/index.html.twig') ;
       }

       #[Route('/agenceloc/vehicules', name: 'app_vehicules')]
       #[Route('/agenceloc/vehicule/new', name: 'app_new_vehicule')]
       #[Route('/agenceloc/vehicule/edit/{id}', name: 'app_edit_vehicule')]
       public function adminFormVehicule(VehiculeRepository $repo, Request $globals, EntityManagerInterface $manager, Vehicule $vehicule = null)
       {
           $vehicules=$repo->findAll();
           if($vehicule == null) {
               $vehicule= new Vehicule;
               $vehicule->setDateEnregistrement(new \DateTime);
           }
       
           $form=$this->createForm(VehiculeFormType::class, $vehicule);
       
           $form->handleRequest($globals);
       
           if($form->isSubmitted() && $form->isValid()) {
               $manager->persist($vehicule);
               $manager->flush();
               $this->addFlash('success', "Le véhicule a bien été édité / enregistré !");
       
               return $this->redirectToRoute('app_vehicules');
           }
           return $this->renderForm('agenceloc/vehicule.html.twig', [
               'form'=> $form,
               'vehicules_all' => $vehicules,
               'editMode'=> $vehicule->getId() !== null
           ]);
          
           return ;
       }


}
