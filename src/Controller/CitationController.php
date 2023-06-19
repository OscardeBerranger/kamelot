<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Repository\CitationRepository;
use App\Services\TheBestOne;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CitationController extends AbstractController
{


    #[Route('/profile/saveCitation', name: 'app_home_saveCitation')]
    public function saveCitation(Request $request, EntityManagerInterface $manager, CitationRepository $repository): Response
    {
        if ($repository->findOneBy(['content'=>$request->get('content')])){
            $citation = $repository->findOneByContent($request->get('content'));
            $citation->addUtilisateur($this->getUser());
            $manager->persist($citation);
            $manager->flush();
        }else {
            $citation = new Citation();
            $citation->setAuteur($request->get('auteur'));
            $citation->setContent($request->get('content'));
            $citation->addUtilisateur($this->getUser());
            $manager->persist($citation);
            $manager->flush();
        }

        return $this->render('user/index.html.twig', [
            'citation'=>$citation
        ]);
    }


    #[Route('/profile/citation/removeUser/{id}', name: 'app_citation_removeuser')]
    public function removeUser(Citation $citation, EntityManagerInterface $manager){
        if (!$citation){
            return $this->redirect('app_home');
        }
        $citation->removeUtilisateur($this->getUser());
        $manager->flush();
        return $this->redirectToRoute('app_user');
    }

    #[Route('/admin/citation/removeCitation/{id}', name: 'app_citation_removecitation')]
    public function removeCitation(Citation $citation, EntityManagerInterface $manager, CitationRepository $repository){
        if (!$citation){
            return $this->redirect('app_home');
        }
        $repository->remove($citation);

        $manager->flush();
        return $this->redirectToRoute('app_user');
    }

    #[Route('/citation', name: 'app_citation')]
    public function index(CitationRepository $repository){
        return $this->render('citation/index.html.twig', [
            'citations'=>$repository->findAll()
        ]);
    }

    #[Route('/citation/alloffame', name: 'app_alloffame')]
    public function alloffame(CitationRepository $repository, TheBestOne $service){
        $citations = $service->allOfFame();
        return $this->render('citation/best.html.twig', [
            'citations'=>$citations
        ]);
    }
    #[Route('/api/citation/getUser', name: 'app_connectWithApi_getUser', methods: ['GET'])]
    public function connectedWithApi(CitationRepository $citationRepository, TheBestOne $service){
        $user = $this->getUser();
        $userName = $user->getEmail();
        return $this->json($userName, 200);
    }

    #[Route('/api/citation/getCitation', name: 'app_connectWithApi_getQuote', methods: ['GET'])]
    public function getBestWithApi(TheBestOne $service){
        $citations = $service->allOfFame();
        return $this->json($citations, 200, [], ['groups'=>'citation:read']);
    }

}
