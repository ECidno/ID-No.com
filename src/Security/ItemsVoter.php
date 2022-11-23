<?php
namespace App\Security;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Main\Items;
use App\Entity\Nutzer\Nutzer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * items voter
 */
class ItemsVoter extends Voter
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
        return $subject instanceof Items;
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

        /** @var Items $item */
        $item = $subject;

        // switch case operation
        switch ($attribute) {

            case self::CREATE:
            case self::DELETE:
                $this->canC($item, $user);

            case self::READ:
            case self::EDIT:
            case self::UPDATE:
                return $this->canRud($item, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }


    /**
     * can create
     *
     * @param Items $item
     * @param Nutzer $user
     *
     * @return bool
     */
    private function canC(Items $item, Nutzer $user): bool
    {
        return $this->canRud($item, $user);
    }


    /**
     * can read, update, delete
     *
     * @param Items $item
     * @param Nutzer $user
     *
     * @return bool
     */
    private function canRud(Items $item, Nutzer $user): bool
    {
        // iterate user's persons
        foreach ($user->getPersons() as $person) {
            if(
                $person->getId() === $item->getPersonId() &&
                $user->getId() === $item->getNutzerId()
            ) {
                return true;
            }
        }

        // return false
        return false;
    }
}