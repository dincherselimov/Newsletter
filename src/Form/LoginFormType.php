<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class LoginFormType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('username', TextType::class, [
            'label' => 'Username',
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 3, 'max' => 255]),
            ],
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Password',
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 6]),
                new Regex([
                    'pattern' => '/^[^\'@+=]+$/',
                    'message' => 'Password cannot contain characters like \' or @ or + =',
                ]),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Login'
        ]);    
    }
}
