<?php

namespace App\Form\Type;

use App\Entity\Nutzer\Nutzer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idno', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'idNo',
                    'placeholder' => new TranslatableMessage('items.idNo.lbl'),
                    'pattern' => '[a-z,A-Z,0-9]{4}-[a-z,A-Z,0-9]{4}',
                    'maxlength' => 9,
                ],
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
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
            ->add('plainPasswort', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => new TranslatableMessage('registration.passwort')
                ],
                'second_options' => [
                    'label' => new TranslatableMessage('registration.repeatPasswort')
                ]
            ])
            ->add('agb', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'constraints' => new IsTrue(),
            ])
            ->add('sendInformation', CheckboxType::class, [
                'label' => false,
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nutzer::class,
        ]);
    }
}
