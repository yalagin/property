<?php

namespace CelebrityAgent\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for logging in a user.
 */
class LoginType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                '_username',
                TextType::class,
                [
                    'label' => 'Email Address'
                ]
            )
            ->add(
                '_password',
                PasswordType::class,
                [
                    'label' => 'Password'
                ]
            )
            ->add(
                '_remember_me',
                CheckboxType::class,
                [
                    'data' => true,
                    'label' => 'Remember Me',
                    'label_attr' => ['class' => 'checkbox-custom'],
                    'required' => false
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Sign In'
                ]
            )
        ;

        $builder->setAction($options['action']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'action' => null,
            'csrf_field_name' => '_csrf_token',
            'data_class' => null,
            'intention' => 'authenticate'
        ]);
    }
}
