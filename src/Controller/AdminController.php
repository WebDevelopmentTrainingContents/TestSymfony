<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }



    #[Route('/gestion_contact/afficher', name: 'contact_afficher')]
    public function afficher_contacts(ContactRepository $cR): Response
    {
        $contacts = $cR->findAll();


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'ContactController',
            "contacts" => $contacts

        ]);
    }


    #[Route('/gestion_contact/afficher/{contact_id}', name: 'contact_afficher_id')]
    #[ParamConverter('contact', class:"App\Entity\Contact", options: ['mapping' => ['id' => 'contact_id']])]
    public function afficher_contacts_id($id, ContactRepository $repoContact): Response
    {
        $contact = $repoContact->find($id);

        dump($contact);
        return $this->render('admin/indexId.html.twig', [
            'controller_name' => 'ContactController',
            "contact" => $contact

        ]);
    }

    #[Route('/gestion_contact/ajouter', name: 'contact_ajouter')]
    public function ajouter_contacts(Request $request, EntityManagerInterface $manager): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
            /* dd($request); */
            $manager->persist($contact);
            $manager->flush();
            $this->addFlash('success', "Bonjour " . $contact->getPrenom() . ", vous avez bien été ajouté en BDD !" );
            return $this->redirectToRoute('contact_afficher');
        }
      
        return $this->render('admin/ajout.html.twig', [
            'controller_name' => 'ContactController',
            "formContact" => $form->createView(),
            "contact" => $contact

        ]);
    }
   
    #[Route('/gestion_contact/editer/{id}', name: 'contact_editer')]
    public function editer_contacts(Contact $contact, Request $request, EntityManagerInterface $manager): Response
    {

       

/*         dd($contact); */

        $form = $this->createForm(ContactType::class, $contact);

        
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
           
            $manager->persist($contact);
            $manager->flush();
            
            $this->addFlash("success", "Contact numéro " . $contact->getId() . " modifié.");
            
            return $this->redirectToRoute('contact_afficher');
        }

        
        return $this->render('admin/edit.html.twig', [
            'formContact' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    #[Route('/gestion_contact/supprimer/{id}', name: 'contact_supprimer')]
    public function supprimer_contacts(Contact $contact, EntityManagerInterface $manager): Response
    {

        $idContact = $contact->getId();

        $manager->remove($contact);
        $manager->flush();

        $this->addFlash("success", "Contact numéro " . $idContact . " suprimé.");

        return $this->redirectToRoute('contact_afficher');
 
}
}