<?php

namespace CelebrityAgent\Form\Type;

use CelebrityAgent\Entity\User;
use CelebrityAgent\Form\DTO\UserDTO;
use CelebrityAgent\Form\Extension\Type\DeleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for working with a User.
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userDto = $builder->getData();

        if (true === $options['public'] || true === $options['profile']) {

            $builder
                ->add(
                    'email',
                    RepeatedType::class,
                    [
                        'first_options' => [
                            'attr' => [
                                'autocomplete' => (true === $options['profile'] ? 'new-password' : 'on')
                            ],
                            'label' => (true === $options['profile'] ? 'Replace Email' : 'Email Address')
                        ],
                        'second_options' => [
                            'attr' => [
                                'autocomplete' => (true === $options['profile'] ? 'new-password' : 'on')
                            ],
                            'label' => 'Repeat Email'
                        ],
                        'type' => TextType::class,
                        'required' => (true === $options['public'])
                    ]
                )
            ;

        } else {

            $builder
                ->add(
                    'email',
                    TextType::class,
                    [
                        'label' => 'Email Address'
                    ]
                )
            ;

        }

        if (true === $options['include_details']) {

            $builder
                ->add(
                    'firstName',
                    TextType::class,
                    [
                        'label' => 'First Name'
                    ]
                )
                ->add(
                    'lastName',
                    TextType::class,
                    [
                        'label' => 'Last Name'
                    ]
                )
            ;

        }

        if ($options['include_password'] === true) {

            if ($options['repeat_password'] === true) {

                $builder
                    ->add(
                        'password',
                        RepeatedType::class,
                        [
                            'first_options' => [
                                'attr' => [
                                    'autocomplete' => (true === $options['profile'] ? 'new-password' : 'on')
                                ],
                                'label' => (true === $options['profile'] ? 'Replace Password' : 'Password')
                            ],
                            'second_options' => [
                                'attr' => [
                                    'autocomplete' => (true === $options['profile'] ? 'new-password' : 'on')
                                ],
                                'label' => 'Repeat Password'
                            ],
                            'type' => PasswordType::class,
                            'required' => (true === $options['public'])
                        ]
                    )
                ;

            } else {

                $builder
                    ->add(
                        'password',
                        PasswordType::class,
                        [
                            'label' => 'Password',
                            'required' => false
                        ]
                    )
                ;

            }

        }

        // for new users, only one access level option is presented
        if ($options['include_role'] === true) {
            $builder
                ->add(
                    'role',
                    ChoiceType::class,
                    [
                        'choices' => User::getValidRoles(),
                        'label' => 'Access Level'
                    ]
                )
            ;
        }

        $builder
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => $userDto->isPersisted() ? 'Save Changes' : ($options['public'] ? 'Sign Up' : 'Add User')
                ]
            )
        ;

        // only allow deletion if the user is functionally empty
        if ($userDto->isPersisted() && $userDto->isEmpty() && true === $options['include_delete']) {

            $builder->add(
                'delete',
                DeleteType::class,
                [
                    'confirmation' => true,
                    'confirmation_message' => 'Are you sure you want to delete this user?',
                    'label' => 'Delete User'
                ]
            );

        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'include_delete' => false,
            'include_details' => false,
            'include_password' => true,
            'include_role' => false,
            'profile' => false,
            'public' => false,
            'repeat_password' => true
        ]);
    }
}
