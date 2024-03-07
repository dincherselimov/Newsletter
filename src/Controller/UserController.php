<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\LoginFormType;


class UserController extends AbstractController{
    
        /**
         * @Route("/register", name="register")
         */
        public function register(Request $request, UserRepository $userRepository): Response {

            $form = $this->createForm(RegistrationFormType::class);
            $form->handleRequest($request);
            
            if ($this->getUser()) {
                return $this->redirectToRoute('app_home');
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $existingUsers = $userRepository->findExistingUser(
                    $data->getUsername(),
                    $data->getEmail()
                );

                if (count($existingUsers) > 0) {
                    $this->addFlash('error', 'Username or email already exists.');
                    return $this->redirectToRoute('app_register'); 
                }

                $userRepository->createUser(
                    $data->getUsername(),
                    $data->getEmail(),
                    $data->getPassword()
                );

                return $this->redirectToRoute('app_login');
            }

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }

        /**
         * @Route("/login", name="app_login")
         */
        public function login(AuthenticationUtils $authenticationUtils): Response {
            
            if ($this->getUser()) {
                return $this->redirectToRoute('app_home');
            }

            $form = $this->createForm(LoginFormType::class);

            $lastUsername = $authenticationUtils->getLastUsername();
            $error = $authenticationUtils->getLastAuthenticationError();
            
            return $this->render('login/login.html.twig', [
                'loginForm' => $form->createView(),
                'last_username' => $lastUsername,
                'error'=> $error,
            ]);
        }


        /**
         * @Route("/logout", name="app_logout")
         */
        public function logout(): void{
            // Symfony security system will intercept and handle the logout request.
        }

}
