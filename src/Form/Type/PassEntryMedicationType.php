<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2024 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\PassEntryMedication;
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
 * PassEntryMedicationType type
 */
class PassEntryMedicationType extends AbstractType
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
            ->add('ingredient', TextType::class, [
                'label' => new TranslatableMessage('person.medication.ingredient.lbl'),
                'required' => false
            ])
            ->add('tradeName', TextType::class, [
                'label' => new TranslatableMessage('person.medication.tradeName.lbl'),
                'required' => false
            ])
            ->add('dosage', TextType::class, [
                'label' => new TranslatableMessage('person.medication.dosage.lbl'),
                'required' => false
            ])
            ->add('consumption', TextType::class, [
                'label' => new TranslatableMessage('person.medication.consumption.lbl'),
                'required' => false
            ])
            ->add('comment', TextType::class, [
                'label' => new TranslatableMessage('person.medication.comment.lbl'),
                'required' => false
            ])
            ->add('emergencyNotes', TextType::class, [
                'label' => new TranslatableMessage('person.medication.emergencyNotes.lbl'),
                'required' => false
            ])
            ->add('remove', ButtonType::class, [
                'label' => new TranslatableMessage('person.medication.delete.lbl'),
                'attr' => [
                    'class' => 'remove-item-widget',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3 remove-widget-container'
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
            'data_class' => PassEntryMedication::class,
            'csrf_token_id' => PassEntryMedication::class,
            'csrf_protection' => true,
        ]);
    }

}
