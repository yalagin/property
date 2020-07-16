<?php


namespace CelebrityAgent\Form\Type;

use CelebrityAgent\Form\DTO\NoteActivityDTO;
use CelebrityAgent\Form\Extension\Type\DeleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteActivityType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
            ->add(
                'text',
                TextareaType::class,
                ['label' => 'Your Note']
            )
            ->add(
                'submit',
                SubmitType::class,
                ['label' => 'Submit']
            );
        if ($data->isPersisted() && $data->isEmpty() && $options['include_delete']) {
            $builder->add(
                'delete',
                DeleteType::class,
                [   'confirmation' => true,
                    'confirmation_message' => 'Are you sure you want to delete this activity?',
                    'label' => 'Delete Activity'    ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NoteActivityDTO::class,
            'include_delete' => false,
            'validation_groups' =>  ['Default']
        ]);
    }
}