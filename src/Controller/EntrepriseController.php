<?php

namespace App\Controller;

//appelle toutes les classes dont il a besoin
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    //faire passer directement en argument le repository pour appeler des méthodes plus rapidement
    public function index(EntrepriseRepository $entrepriseRepository): Response
    // public function index(EntityManagerInterface $entityManager): Response
    {
        //SELECT * FROM entreprise WHERE ville=strasbourg ORDER BY raisonSociale ASC
        $entreprises = $entrepriseRepository->findBy([], ["raisonSociale" => "ASC"]);
        // $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    //formulaire ajout ou modification d'une entreprise
    #[Route('/entreprise/new', name: 'new_entreprise')]
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')]
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si l'entreprise n'a pas été trouvé, on en crée une nouvelle, sinon ça veut dire qu'on est sur un formulaire de modification
        if(!$entreprise){
            $entreprise = new Entreprise();

        }

        
        //crée le formulaire
        $form = $this->createForm(EntrepriseType::class, $entreprise);

        //prend en charge le formulaire
        $form->handleRequest($request);

        //si le form a été soumis et qu'il est valide
        if($form->isSubmitted() && $form->isValid() ){
            $entreprise = $form->getData();

            $entityManager->persist($entreprise); //prepare PDO
            $entityManager->flush(); //execute PDO

            //retourne à la liste des entreprises
            return $this->redirectToRoute('app_entreprise');
        }

        //renvoie la vue
        return $this->render('entreprise/new.html.twig', [
            'formAddEntreprise' => $form,
            //s'il reçoit l'id, il le renvoie et donc on est sur la modification, sinon il renvoie false et on est sur l'ajout
            'edit' => $entreprise->getId()
        ]);
    }
    
    //supprimer entreprise: tous ses employés sont supprimés également 'en cascade' (choix au début dans sa construction)
    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')]
    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($entreprise); //prepare ce qu'il faut pour la requete
        $entityManager->flush(); //execute

        // redirection
        return $this->redirectToRoute('app_entreprise');
    }

    //pour le détail d'une entreprise
    #[Route('/entreprise/{id}', name: 'show_entreprise')]
    public function show(Entreprise $entreprise) : Response 
    //en mettant l'objet en paramètre, il retrouve l'objet (l'entreprise) dont l'id a été passé en parametre
    {

        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }





}
