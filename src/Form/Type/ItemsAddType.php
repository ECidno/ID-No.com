<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Items;
use App\Entity\Person;
use App\Entity\Nutzer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * items add form type
 */
class ItemsAddType extends AbstractType
{
    /**
     * constructor
     *
     * @param ContainerBagInterface TokenStorageInterface $token
     */
    public function __construct(protected TokenStorageInterface $token)
    {}


    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Nutzer
         */
        $nutzer = $this->token->getToken()->getUser();

        // builder
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
                'required' => true,
            ])
            ->add('anbringung', TextType::class, [
                'label' => new TranslatableMessage('items.anbringung.lbl'),
                'attr' => [
                    'autocomplete' => 'off',
                    'maxlength' => 255,
                ],
                'required' => false,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use($nutzer) {
                $form = $event->getForm();

                // show profile select if more than one profiles exists
                if($nutzer->getPersons()->count() === 1) {
                    $form->add('person', EntityHiddenType::class);
                } else {
                    $form->add('person', EntityType::class, [
                        'label' => new TranslatableMessage('items.person.lbl'),
                        'class' => Person::class,
                        'query_builder' => function (EntityRepository $er) use($nutzer) {
                            return $er
                                ->createQueryBuilder('p')
                                ->where('p.nutzer=:nutzer')
                                ->orderBy('p.vorname', 'ASC')
                                ->setParameter('nutzer', $nutzer);
                        },
                        'choice_label' => 'fullName',
                        'placeholder' => 'items.person.not_assigned.lbl',
                        'required' => false,
                    ]);
                }
            })
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
            'data_class' => Items::class,
            'csrf_token_id' => Items::class,
            'csrf_protection' => true,
        ]);
    }
}