<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
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

    //detail d'un employe
    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response 
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}
