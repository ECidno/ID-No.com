<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Main\Items;
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
                    'placeholder' => new TranslatableMessage('items.idNo.lbl'),
                    'pattern' => '[a-z,A-Z,0-9]{4}-[a-z,A-Z,0-9]{4}',
                    'maxlength' => 9,
                ],
                'required' => true
            ])
            ->add('anbringung', TextType::class, [
                'label' => new TranslatableMessage('items.anbringung.lbl'),
                'attr' => [
                    'placeholder' => new TranslatableMessage('items.anbringung.lbl'),
                    'maxlength' => 255,
                ],
                'required' => true
            ])
            ->add('nutzerId', HiddenType::class)
            ->add('personId', HiddenType::class);
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