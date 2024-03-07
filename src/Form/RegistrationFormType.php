<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;;


class RegistrationFormType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();
                
                if ($user['password']) {
                    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
                    
                    $user['password'] = $hashedPassword;
                    $event->setData($user);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
