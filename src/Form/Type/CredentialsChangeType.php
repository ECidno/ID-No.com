<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * credentials change form type
 */
class CredentialsChangeType extends AbstractType
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
            ->add('email', EmailType::class, [
                'label' => new TranslatableMessage('person.email'),
                'attr' => [
                    'class' => 'ajax-validate',
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                    'data-url' => '/api/profile/validate/email/',
                ],
                'required' => true,
            ])
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                'label' => new TranslatableMessage('credentials.oldPassword'),
                'constraints' => new UserPassword(),
                'required' => true
            ])
            ->add('plainPasswort', RepeatedType::class, [
                'type' => PasswordType::class,
                'attr' => [
                    'maxlength' => 100,
                    'autocomplete' => 'off',
                ],
                'first_options' => [
                    'label' => new TranslatableMessage('credentials.newPassword')
                ],
                'second_options' => [
                    'label' => new TranslatableMessage('credentials.newRepeatPassword')
                ],
                'required' => true
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
            'data_class' => Nutzer::class,
        ]);
    }
}
