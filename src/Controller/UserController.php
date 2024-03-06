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
        public function register(Request $request, UserRepository $userRepository): Response
        {
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
                    return $this->redirectToRoute('app_register'); // Redirect back to registration page
                }

                // Create the user if no existing user found
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
        public function login(AuthenticationUtils $authenticationUtils): Response
        {
            // Check if the user is already authenticated
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
            // This method is intentionally left blank.
            // Symfony security system will intercept and handle the logout request.
        }

}











    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
// public function login(Request $request, UserRepository $userRepository): Response
// {
//     $error = '';

//     if ($request->isMethod('POST')) {
//         $username = $request->request->get('username');
//         $password = $request->request->get('password');

//         // Attempt to find the user by username and password
//         $user = $userRepository->findByUsernameAndPassword($username, $password);

//         if ($user) {
//             // Redirect authenticated user to a different route
//             return $this->redirectToRoute('app_home');
//         } else {
//             $error = 'Invalid username or password.';
//         }
//     }

//     return $this->render('user/login.html.twig', [
//         'error' => $error,
//     ]);
// }
    
    // /**
    //  * @Route("/register", name="register", methods={"POST"})
    //  */
    // public function register(Request $request, UserRepository $userRepository): Response
    // {
    //     if ($request->isMethod('POST')) {
    //         $username = $request->request->get('username');
    //         $email = $request->request->get('email');
    //         $password = $request->request->get('password');

    //         try {
    //             $user = $userRepository->registerUser($username,$email, $password);
    //             return $this->redirectToRoute('app_login');
    //         } catch (UsernameAlreadyExistsException $e) {
    //             $error = 'Username already exists';
    //         }
    //     }

    //     return $this->render('user/register.html.twig', [
    //         'error' => $error ?? null,
    //     ]);
    // }