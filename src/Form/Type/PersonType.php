<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Person;
use App\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * person form type
 */
class PersonType extends AbstractType
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
                    'placeholder' => new TranslatableMessage('person.vorname.lbl'),
                    'maxlength' => 100,
                ],
                'required' => true
            ])
            ->add('nachname', TextType::class, [
                'label' => new TranslatableMessage('person.nachname.lbl'),
                'attr' => [
                    'placeholder' => new TranslatableMessage('person.nachname.lbl'),
                    'maxlength' => 100,
                ],
                'required' => true
            ])

            ->add('strasse', TextType::class, [
                'label' => new TranslatableMessage('person.strasse.lbl'),
                'attr' => [
                    'placeholder' => new TranslatableMessage('person.strasse.lbl'),
                    'maxlength' => 100,
                ],
                'required' => false
            ])
            ->add('strasseShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'row_attr' => ['class' => 'form-switch'],
                'attr' => ['role' => 'switch'],
                'required' => false
            ])
            ->add('plz', TextType::class, [
                'label' => new TranslatableMessage('person.plz.lbl'),
                'attr' => [
                    'placeholder' => new TranslatableMessage('person.plz.lbl'),
                    'maxlength' => 13,
                ],
                'required' => false
            ])
            ->add('ort', TextType::class, [
                'label' => new TranslatableMessage('person.ort.lbl'),
                'attr' => [
                    'placeholder' => new TranslatableMessage('person.ort.lbl'),
                    'maxlength' => 100,
                ],
                'required' => false
            ])
            ->add('ortShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'row_attr' => ['class' => 'form-switch'],
                'attr' => ['role' => 'switch'],
                'required' => false
            ]);
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