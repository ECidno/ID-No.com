<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

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

/**
 * person form type
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idno', TextType::class, [
                'label' => new TranslatableMessage('items.idNo.lbl'),
                'mapped' => false,
                'attr' => [
                    'class' => 'idNo',
                    'placeholder' => new TranslatableMessage('items.idNo.lbl'),
                    'pattern' => '[a-z,A-Z,0-9]{4}-[a-z,A-Z,0-9]{4}',
                    'maxlength' => 9,
                ],
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => new TranslatableMessage('person.email'),
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
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
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
                'required' => true
            ])
            ->add('nachname', TextType::class, [
                'label' => new TranslatableMessage('person.nachname.lbl'),
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
                'required' => true
            ])
            ->add('plainPasswort', RepeatedType::class, [
                'type' => PasswordType::class,
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
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
                    'role' => 'switch',
                    'autocomplete' => 'off',
                ],
                'constraints' => new IsTrue(),
            ])
            ->add('sendInformation', CheckboxType::class, [
                'label' => false,
                'row_attr' => ['class' => 'form-switch'],
                'attr' => [
                    'role' => 'switch',
                    'autocomplete' => 'off',
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
