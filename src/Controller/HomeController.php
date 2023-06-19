<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Repository\CitationRepository;
use App\Services\ApiCallerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ApiCallerService $service): Response
    {
        $citations = [];
        for ($i = 0; $i < 1; $i++) {
            array_push($citations, $service->callerCitation());
        }
        return $this->render('home/index.html.twig', [
            'citations' => $citations,
        ]);
    }


}
