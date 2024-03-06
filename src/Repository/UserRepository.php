<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByUsernameAndPassword(string $username, string $password): ?User{

        $user = $this->findOneBy(['username' => $username]);

        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }
    
    
    public function registerUser($username, $email, $password)
    {
        if ($this->findBy(['username' => $username])) {
            throw new UsernameAlreadyExistsException('Username already exists');
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email); 
       
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function createUser($username, $email, $password)
    {
        $entityManager = $this->getEntityManager();

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function findExistingUser(string $username, string $email): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

}
