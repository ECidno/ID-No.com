<?php
namespace App\Form\Extension;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * form type extension
 */
class FormTypeExtension extends AbstractTypeExtension
{
    /**
    * @var array settings
    */
   protected $settings = [];

    /**
     * constructor
     *
     * @param LoggerInterface $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ContainerBagInterface $params
    ) {
        $this->settings = $params->get('settings');
    }


    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $rowClass = null;
        $formType = $this->settings['form']['type'] ?? 'default';

        // row class
        if($formType === 'floating') {
            $rowClass = 'form-floating mb-3';
        }

        // set default row class if given
        if($rowClass) {
            $resolver->setDefaults([
                'row_attr' => ['class' => $rowClass]
            ]);
        }
    }


    /**
     * getExtendedTypes
     *
     * @return array
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}