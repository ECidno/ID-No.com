<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2024 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\PassEntryAllergy;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * PassEntryAllergyType type
 */
class PassEntryAllergyType extends AbstractType
{
    /**
     * @var EntityManagerInterface emDefault
     */
    protected $emDefault;


    /**
     * __construct
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->emDefault = $registry->getManager('default');
    }


    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'label' => new TranslatableMessage('person.kategorie.lbl'),
                'choices' => [
                    'Herz-Kreislauf-Erkrankungen' => 'Herz-Kreislauf-Erkrankungen',
                    'Neurologische/ Psychische Erkrankungen' => 'Neurologische/ Psychische Erkrankungen',
                    'Krebs' => 'Krebs',
                    'Atemwegserkrankungen' => 'Atemwegserkrankungen',
                ],
                // 'row_attr' => [
                //     'class' => 'col-8'
                // ]
            ])
            ->add('comment', TextType::class, [
                'label' => new TranslatableMessage('person.erkrankungen.anmerkungen.lbl'),
                'required' => false
            ])
            ->add('remove', ButtonType::class, [
                'label' => 'delete',
                'attr' => [
                    'class' => 'remove-item-widget',
                ],
                'row_attr' => [
                    'class' => 'col-3'
                ]
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
            'data_class' => PassEntryAllergy::class,
            'csrf_token_id' => PassEntryAllergy::class,
            'csrf_protection' => true,
        ]);
    }

}
