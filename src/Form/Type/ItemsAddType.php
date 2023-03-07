<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Items;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * items add form type
 */
class ItemsAddType extends AbstractType
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
            ->add('idNo', TextType::class, [
                'label' => new TranslatableMessage('items.idNo.lbl'),
                'attr' => [
                    'class' => 'idNo ajax-validate',
                    'pattern' => Items::IDNO_PATTERN,
                    'maxlength' => 9,
                    'autocomplete' => 'off',
                    'data-url' => '/api/items/validate/idno/',
                ],
                'required' => true
            ])
            ->add('anbringung', TextType::class, [
                'label' => new TranslatableMessage('items.anbringung.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 255,
                ],
                'required' => false
            ])
            ->add('nutzer', HiddenType::class)
            ->add('person', HiddenType::class);
        }


    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Items::class,
            'csrf_token_id' => Items::class,
            'csrf_protection' => true,
        ]);
    }
}