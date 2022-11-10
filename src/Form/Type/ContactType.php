<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Contact;
use App\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * contact form type
 */
class ContactType extends AbstractType
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
            ->add('person', EntityHiddenType::class)
            ->add('contactname', TextType::class, [
                'label' => new TranslatableMessage('contact.contactname.lbl'),
                'attr' => [
                    'maxlength' => 100,
                ],
                'required' => true
            ])
            ->add('contactnameShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'row_attr' => ['class' => 'form-switch'],
                'attr' => ['role' => 'switch'],
                'required' => false
            ])
            ->add('telefon', TextType::class, [
                'label' => new TranslatableMessage('contact.telefon.lbl'),
                'attr' => [
                    'maxlength' => 100,
                ],
                'required' => false
            ])
            ->add('telefonShow', CheckboxType::class, [
                'label' => new TranslatableMessage('sichtbar'),
                'row_attr' => ['class' => 'form-switch'],
                'attr' => ['role' => 'switch'],
                'required' => false
            ])
            ->add('beziehung', TextType::class, [
                'label' => new TranslatableMessage('contact.beziehung.lbl'),
                'attr' => [
                    'maxlength' => 100,
                ],
                'required' => false
            ])
            ->add('beziehungShow', CheckboxType::class, [
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
            'data_class' => Contact::class,
            'csrf_token_id' => Contact::class,
            'csrf_protection' => true,
        ]);
    }
}