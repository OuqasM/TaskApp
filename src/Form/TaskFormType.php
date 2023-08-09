<?php

namespace App\Form;

use App\Entity\Task;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Titre', TypeTextType::class, [
            'label' => 'Titre', // Label for the field
            'attr' => [
                'class' => 'form-control', // CSS class for styling
                // Add more attributes as needed, e.g. 'placeholder', 'maxlength', etc.
            ],
        ])
        ->add('Description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'class' => 'form-control', // CSS class for styling
                'rows' => 4, // Set the number of rows for the textarea
                // Add more attributes as needed
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
