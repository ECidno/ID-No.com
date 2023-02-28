<?php
namespace App\Security;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer;
use App\Entity\Contact;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * contacts voter
 */
class ContactsVoter extends Voter
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
        return $subject instanceof Contact;
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

        /** @var Contact $post */
        $contact = $subject;

        // switch case operation
        switch ($attribute) {

            case self::CREATE:
                return $this->canC($contact, $user);

            case self::READ:
            case self::EDIT:
            case self::UPDATE:
            case self::DELETE:
                return $this->canRud($contact, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }


    /**
     * can create
     *
     * @param Contact $contact
     * @param Nutzer $user
     *
     * @return bool
     */
    private function canC(Contact $contact, Nutzer $user): bool
    {
        return $user->getPersons()->contains($contact->getPerson());
    }


    /**
     * can read, update, delete
     *
     * @param Contact $contact
     * @param Nutzer $user
     *
     * @return bool
     */
    private function canRud(Contact $contact, Nutzer $user): bool
    {
        // iterate user's persons
        foreach ($user->getPersons() as $person) {
            if(
                $person->getContacts()->contains($contact) &&
                $user->getPersons()->contains($contact->getPerson())
            ) {
                return true;
            }
        }

        // return
        return false;
    }
}