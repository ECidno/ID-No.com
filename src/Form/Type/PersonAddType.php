<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2023 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Person;
use App\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * person add form type
 */
class PersonAddType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nutzer', EntityHiddenType::class)
            ->add('anrede', ChoiceType::class, [
                'label' => new TranslatableMessage('person.geschlecht'),
                'choices' => [
                    'm' => 'm',
                    'w' => 'w',
                    'x' => 'x',
                ],
                'choice_label' => function($choice, $key, $value) {
                    return new TranslatableMessage('person.geschlecht.'.$key);
                },
                'required' => true
            ])
            ->add('vorname', TextType::class, [
                'label' => new TranslatableMessage('person.vorname.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 100,
                ],
                'required' => true
            ])
            ->add('nachname', TextType::class, [
                'label' => new TranslatableMessage('person.nachname.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 100,
                ],
                'required' => true
            ])
            ->add('status', HiddenType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'required' => true
            ])
            ->add('terms', CheckboxType::class, [
                'label' => new TranslatableMessage('person.terms.lbl'),
                'mapped' => false,
                'row_attr' => ['class' => 'form-switch pt-3'],
                'attr' => [
                    'autocomplete' => 'off',
                    'role' => 'switch',
                ],
                'required' => true
            ])
            ;
    }


    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'csrf_token_id' => Person::class,
            'csrf_protection' => true,
        ]);
    }
}