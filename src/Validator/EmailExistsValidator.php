<?php
namespace App\Validator;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * email exists validator
 */
class EmailExistsValidator extends ConstraintValidator
{
    /**
     * @var ManagerRegistry
     */
    private $emNutzer;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     * @param TranslatorInterface $translator
     */
    public function __construct(ManagerRegistry $registry, TranslatorInterface $translator)
    {
        $this->emNutzer = $registry->getManager('nutzer');
        $this->translator = $translator;
    }


    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if(!$constraint instanceof EmailExists) {
            throw new UnexpectedTypeException($constraint, EmailExists::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        // get user
        $nutzer = $this->emNutzer
            ->getRepository(Nutzer::class)
            ->findOneByEmail($value);

        // the argument must be a string or an object implementing __toString()
        if (empty($nutzer)) {
            $this->context->addViolation($this->translator->trans($constraint->message));
        }
    }
}