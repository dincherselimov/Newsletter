<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface; 

class NewsRepository extends ServiceEntityRepository {
    
    private $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager) {
        parent::__construct($registry, News::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param [type] $title
     * @param [type] $description
     * @param [type] $category
     * @return News
     */
    public function publishNews( $title, $description, $category): News {
        $news = new News();
        $news->setTitle($title);
        $news->setDescription($description);
        $news->setCategory($category);
        $news->setPublishedAt(new \DateTime());

        $this->entityManager->persist($news);
        $this->entityManager->flush();

        return $news;
    }

    //Here can be added update and delete instead of doing it directly in the controller

}
