<?php

namespace App\Controller;

//appelle toutes les classes dont il a besoin
use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    //faire passer directement en argument le repository pour appeler des méthodes plus rapidement
    #[Route('/employe', name: 'app_employe')]
    public function index(EmployeRepository $employeRepository): Response
    {
        //SELECT * FROM employe ORDER BY nom ASC
        $employes = $employeRepository->findBy([], ["nom" => "ASC"]);
        return $this->render('employe/index.html.twig', [
            'employes' => $employes
        ]);
    }

    //formulaire ajout d'un employe
    #[Route('/employe/new', name: 'new_employe')]
    #[Route('/employe/{id}/edit', name: 'edit_employe')]
    public function new_edit(Employe $employe = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si l'employe n'a pas été trouvé, on en crée un nouveau, sinon ça veut dire qu'on est sur un formulaire de modification
        if(!$employe){
            $employe = new Employe();
        }
        
        //crée le formulaire
        $form = $this->createForm(EmployeType::class, $employe);
        //prend en charge le formulaire
        $form->handleRequest($request);

        //si le form a été soumis et qu'il est valide
        if($form->isSubmitted() && $form->isValid() ){
            $employe = $form->getData();

            $entityManager->persist($employe); //prepare PDO
            $entityManager->flush(); //execute PDO

            //retourne à la liste des entreprises
            return $this->redirectToRoute('app_employe');
        }


        //renvoie la vue
        return $this->render('employe/new.html.twig', [
            'formAddEmploye' => $form,
            //s'il reçoit l'id, il le renvoie et donc on est sur la modification, sinon il renvoie false et on est sur l'ajout
            'edit' => $employe->getId()
        ]);
    }

    //detail d'un employe
    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response 
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}
