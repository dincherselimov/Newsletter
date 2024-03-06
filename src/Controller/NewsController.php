<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsFormType;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/publish-news", name="publish_news")
     */
    public function publishNews(Request $request, NewsRepository $newsRepository): Response
    {
        $form = $this->createForm(NewsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            // Check if news with the same title already exists
            if ($newsRepository->findBy(['title' => $data->getTitle()])) {
                throw new \Exception('News with the same title already exists');
            }

            $news = $newsRepository->publishNews(
                $data->getTitle(),
                $data->getDescription(),
                $data->getCategory()
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('news/news.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
