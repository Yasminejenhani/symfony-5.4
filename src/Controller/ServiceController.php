<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_service')]
    public function showService($name): Response
    {
        return $this->render('Author/show.html.twig', [
           // 'controller_name' => 'ServiceController',
           'name' => $name,
        ]);
    }
    #[Route('/go-to-index', name: 'go_to_index')]
    public function goToIndex(): Response
    {
        // Redirect to the 'index' action of the 'HomeController'
        // return $this->redirectToRoute('homePage', ['name' => 'yasmine']); 
        return $this->render("service/list.html.twig");
    }
}