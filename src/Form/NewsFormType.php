<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsFormType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Technologies' => 'Technologies',
                    'Politics' => 'Politics',
                    'Interesting' => 'Interesting',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    /**
     * Undocumented function
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
