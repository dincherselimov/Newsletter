<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsFormType;
use App\Form\UpdateNewsFormType;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class NewsController extends AbstractController {

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/publish-news", name="publish_news")
     */
    public function publishNews(Request $request, NewsRepository $newsRepository): Response {
        $form = $this->createForm(NewsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
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

    /**
     * @Route("/news/delete/{id}", name="app_delete_news", methods={"DELETE"})
     */
    public function delete(Request $request, News $news): Response {
        $this->entityManager->remove($news);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }


    /**
     * @Route("/news/update/{id}", name="app_update_news", methods={"GET", "POST"})
     */
    public function update(Request $request, News $news): Response {
        $form = $this->createForm(UpdateNewsFormType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($news);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('news/update.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }
}
