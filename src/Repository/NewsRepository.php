<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface; // Make sure to add this import

class NewsRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, News::class);
        $this->entityManager = $entityManager;
    }

    public function publishNews( $title, $description, $category): News
    {
        $news = new News();
        $news->setTitle($title);
        $news->setDescription($description);
        $news->setCategory($category);
        $news->setPublishedAt(new \DateTime());

        $this->entityManager->persist($news);
        $this->entityManager->flush();

        return $news;
    }
}
