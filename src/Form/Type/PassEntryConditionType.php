<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2024 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\PassEntryCondition;
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
 * PassEntryConditionType type
 */
class PassEntryConditionType extends AbstractType
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
                'label' => new TranslatableMessage('person.condition.category.lbl'),
                'choices' => [
                    'Herz-Kreislauf-Erkrankungen' => 'Herz-Kreislauf-Erkrankungen',
                    'Neurologische/ Psychische Erkrankungen' => 'Neurologische/ Psychische Erkrankungen',
                    'Krebs' => 'Krebs',
                    'Atemwegserkrankungen' => 'Atemwegserkrankungen',
                    'Stoffwechselerkrankungen' => 'Stoffwechselerkrankungen',
                    'Muskel-/ Skelettsystem' => 'Muskel-/ Skelettsystem',
                    'Autoimmunerkrankungen' => 'Autoimmunerkrankungen',
                    'Nierenerkrankungen' => 'Nierenerkrankungen',
                ],
                'choice_attr' => [
                    'Herz-Kreislauf-Erkrankungen' => ['data-category' => 'herzkreislauf'],
                    'Neurologische/ Psychische Erkrankungen' => ['data-category' => 'neurologisch'],
                    'Krebs' => ['data-category' => 'krebs'],
                    'Atemwegserkrankungen' => ['data-category' => 'atemweg'],
                    'Stoffwechselerkrankungen' => ['data-category' => 'stoffwechsel'],
                    'Muskel-/ Skelettsystem' => ['data-category' => 'muskel'],
                    'Autoimmunerkrankungen' => ['data-category' => 'autoimmun'],
                    'Nierenerkrankungen' => ['data-category' => 'niere'],
                ],
                'attr' => [
                    'class' => 'condition-category-select',
                    'data-width' => '100%',
                ]
            ])
            ->add('title', ChoiceType::class, [
                'label' => new TranslatableMessage('person.condition.title.lbl'),
                'choices' => [
                    'Herzinfarkt' => 'Herzinfarkt',
                    'Bluthochdruck' => 'Bluthochdruck',
                    'Angina Pectoris' => 'Angina Pectoris',
                    'Herzinsuffizienz und andere kardiovaskuläre Probleme' => 'Herzinsuffizienz und andere kardiovaskuläre Probleme',
                    'Herzrhythmusstörung' => 'Herzrhythmusstörung',
                    'Herzmuskelentzündung' => 'Herzmuskelentzündung',
                    'KHK' => 'KHK',
                    
                    'Schlaganfall' => 'Schlaganfall',
                    'Demenz' => 'Demenz',
                    'Alzheimer' => 'Alzheimer',
                    'Depressionen' => 'Depressionen',
                    'Angststörungen' => 'Angststörungen',

                    'Lungenkrebs' => 'Lungenkrebs',
                    'Brustkrebs' => 'Brustkrebs',
                    'Darmkrebs' => 'Darmkrebs',
                    'Gebärmutterhalskrebs' => 'Gebärmutterhalskrebs',
                    'Prostatakrebs' => 'Prostatakrebs',
                    'Blasenkrebs' => 'Blasenkrebs',
                    'Hautkrebs' => 'Hautkrebs',
                    'Nierenzellkarzinom' => 'Nierenzellkarzinom',
                    'Schilddrüsenkrebs' => 'Schilddrüsenkrebs',

                    'Lungenerkrankung (COPD)' => 'Lungenerkrankung (COPD)',
                    'Asthma' => 'Asthma',
                    'chronische Bronchitis' => 'chronische Bronchitis',

                    'Struma' => 'Struma',
                    'Morbus Basedow' => 'Morbus Basedow',
                    'Hashimoto-Thyreoditis' => 'Hashimoto-Thyreoditis',
                    'Überfunktion' => 'Überfunktion',
                    'Unterfunktion' => 'Unterfunktion',
                    'Diabetes Typ 1' => 'Diabetes Typ 1',
                    'Diabetes Typ 2' => 'Diabetes Typ 2',

                    'Arthritis' => 'Arthritis',
                    'Arthrose' => 'Arthrose',
                    'Osteoporose' => 'Osteoporose',

                    'Rheuma' => 'Rheuma',
                    'Lupus' => 'Lupus',
                    'Morbus Crohn' => 'Morbus Crohn',
                    'Zöliakie' => 'Zöliakie',
                    'Colitis ulcerosa' => 'Colitis ulcerosa',

                    'Akute Niereninsuffizienz' => 'Akute Niereninsuffizienz',
                    'Chronische Niereninsuffizienz' => 'Chronische Niereninsuffizienz',
                    'Diabetische Nephropathie' => 'Diabetische Nephropathie',
                    'Morbus Addison' => 'Morbus Addison',
                    'Nierenbeckenentzündung (Pyelonephritis)' => 'Nierenbeckenentzündung (Pyelonephritis)',
                    'Nierensteine' => 'Nierensteine',
                    'Nierenszysten' => 'Nierenszysten',
                    'Dialysepflicht' => 'Dialysepflicht',

                    'Andere' => 'Andere',
                ],
                'choice_attr' => [
                    'Herzinfarkt' => ['class' => 'herzkreislauf'],
                    'Bluthochdruck' => ['class' => 'herzkreislauf'],
                    'Angina Pectoris' => ['class' => 'herzkreislauf'],
                    'Herzinsuffizienz und andere kardiovaskuläre Probleme' => ['class' => 'herzkreislauf'],
                    'Herzrhythmusstörung' => ['class' => 'herzkreislauf'],
                    'Herzmuskelentzündung' => ['class' => 'herzkreislauf'],
                    'KHK' => ['class' => 'herzkreislauf'],
                    
                    'Schlaganfall' => ['class' => 'neurologisch'],
                    'Demenz' => ['class' => 'neurologisch'],
                    'Alzheimer' => ['class' => 'neurologisch'],
                    'Depressionen' => ['class' => 'neurologisch'],
                    'Angststörungen' => ['class' => 'neurologisch'],
                    
                    'Lungenkrebs' => ['class' => 'krebs'],
                    'Brustkrebs' => ['class' => 'krebs'],
                    'Darmkrebs' => ['class' => 'krebs'],
                    'Gebärmutterhalskrebs' => ['class' => 'krebs'],
                    'Prostatakrebs' => ['class' => 'krebs'],
                    'Blasenkrebs' => ['class' => 'krebs'],
                    'Hautkrebs' => ['class' => 'krebs'],
                    'Nierenzellkarzinom' => ['class' => 'krebs'],
                    'Schilddrüsenkrebs' => ['class' => 'krebs'],

                    'Lungenerkrankung (COPD)' => ['class' => 'atemweg'],
                    'Asthma' => ['class' => 'atemweg'],
                    'chronische Bronchitis' => ['class' => 'atemweg'],

                    'Struma' => ['class' => 'stoffwechsel'],
                    'Morbus Basedow' => ['class' => 'stoffwechsel'],
                    'Hashimoto-Thyreoditis' => ['class' => 'stoffwechsel'],
                    'Überfunktion' => ['class' => 'stoffwechsel'],
                    'Unterfunktion' => ['class' => 'stoffwechsel'],
                    'Diabetes Typ 1' => ['class' => 'stoffwechsel'],
                    'Diabetes Typ 2' => ['class' => 'stoffwechsel'],

                    'Arthritis' => ['class' => 'muskel'],
                    'Arthrose' => ['class' => 'muskel'],
                    'Osteoporose' => ['class' => 'muskel'],

                    'Rheuma' => ['class' => 'autoimmun'],
                    'Lupus' => ['class' => 'autoimmun'],
                    'Morbus Crohn' => ['class' => 'autoimmun'],
                    'Zöliakie' => ['class' => 'autoimmun'],
                    'Colitis ulcerosa' => ['class' => 'autoimmun'],

                    'Akute Niereninsuffizienz' => ['class' => 'niere'],
                    'Chronische Niereninsuffizienz' => ['class' => 'niere'],
                    'Diabetische Nephropathie' => ['class' => 'niere'],
                    'Morbus Addison' => ['class' => 'niere'],
                    'Nierenbeckenentzündung (Pyelonephritis)' => ['class' => 'niere'],
                    'Nierensteine' => ['class' => 'niere'],
                    'Nierenszysten' => ['class' => 'niere'],
                    'Dialysepflicht' => ['class' => 'niere'],

                    'Andere' => ['class' => 'all'],

                ],
                'attr' => [
                    'class' => 'condition-title-select',
                    'data-width' => '100%',
                ]
            ])
            ->add('comment', TextType::class, [
                'label' => new TranslatableMessage('person.condition.comment.lbl'),
                'required' => false
            ])
            ->add('remove', ButtonType::class, [
                'label' => 'delete',
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
            'data_class' => PassEntryCondition::class,
            'csrf_token_id' => PassEntryCondition::class,
            'csrf_protection' => true,
        ]);
    }

}
