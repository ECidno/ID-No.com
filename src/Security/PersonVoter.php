<?php
namespace App\Security;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer;
use App\Entity\Person;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * person voter
 */
class PersonVoter extends Voter
{
    // operations
    const NEW = 'new';
    const CREATE = 'create';
    const READ = 'read';
    const UPDATE = 'update';
    const EDIT = 'edit';
    const DELETE = 'delete';


    /**
     * supports
     *
     * @param string $attribute
     * @param object $subject
     *
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (
            !in_array(
                $attribute,
                [
                    self::NEW,
                    self::CREATE,
                    self::READ,
                    self::UPDATE,
                    self::EDIT,
                    self::DELETE,
                ]
            )
        ) {
            return false;
        }

        // subject match
        return $subject instanceof Person;
    }


    /**
     * voteOnAttribute
     *
     * @param string $attribute
     * @param object $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // deny if not logged on
        if (!$user instanceof Nutzer) {
            return false;
        }

        /** @var Person $person */
        $person = $subject;

        // switch case operation
        switch ($attribute) {
            case self::READ:
            case self::EDIT:
            case self::UPDATE:
            case self::DELETE:
                return $this->canRud($person, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }


    /**
     * can read, update, delete
     *
     * @param Person $person
     * @param Nutzer $user
     *
     * @return bool
     */
    private function canRud(Person $person, Nutzer $user): bool
    {
				return $user->getPersons()->contains($person);
    }
}