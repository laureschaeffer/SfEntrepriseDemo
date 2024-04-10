<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
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
