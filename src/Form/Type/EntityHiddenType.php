<?php
namespace App\Form\Type;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the custom form field type used to add a hidden entity
 *
 * See https://symfony.com/doc/current/form/create_custom_field_type.html
 */
class EntityHiddenType extends HiddenType implements DataTransformerInterface
{
    /**
     * @var ManagerRegistry registry
     */
    protected $registry;

    /**
     * @var EntitiyManager em
     */
    protected $em;

    /**
     * @var string entityClassName
     */
    protected $entityClassName = null;


    /**
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Set class, eg: App\Entity\RuleSet
        $this->entityClassName = sprintf(
            'App\Entity\%s',
            ucfirst($builder->getName())
        );

        $this->em = $this->registry->getManager('default');
        $builder->addModelTransformer($this);
    }


    /**
     * transform
     *
     * @param object $object
     * @return string
     */
    public function transform($object): string
    {
        // Modified from comments to use instanceof so that base classes or interfaces can be specified
        if (null === $object || !$object instanceof $this->entityClassName) {
            return '';
        }

        // return id
        return $object->getId();
    }


    /**
     * reverse transform
     *
     * @param string $data
     * @return ?object
     */
    public function reverseTransform($data): ?object
    {
        $object = null;

        if (!$data) {
            return null;
        }

        try {
            $object = $this->em
                ->getRepository($this->entityClassName)
                ->findOneBy([
                    'id' => $data,
                ]);
        }
        catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage());
        }

        if ($object === null) {
            throw new TransformationFailedException(
                sprintf(
                    'A %s with id "%s" does not exist!',
                    $this->entityClassName,
                    $data
                )
            );
        }

        // return
        return $object;
    }
}