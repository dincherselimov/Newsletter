<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface; 

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $newsRepository = $entityManager->getRepository(News::class);
        $allNews = $newsRepository->findBy([], ['published_at' => 'DESC']);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'news' => $allNews,
        ]);
    }
}