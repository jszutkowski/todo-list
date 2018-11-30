<?php
/**
 * User: jszutkowski
 */

namespace App\Security\Voter;


use App\Entity\TodoList;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TodoVoter extends Voter
{
    const VOTER_SHOW_LIST = 'VOTER_SHOW_LIST';
    const VOTER_EDIT_LIST = 'VOTER_EDIT_LIST';
    const VOTER_EDIT_LIST_ITEM = 'VOTER_EDIT_ITEM';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            static::VOTER_SHOW_LIST,
            static::VOTER_EDIT_LIST,
            static::VOTER_EDIT_LIST_ITEM,
        ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        /** @var $user User*/
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        if (!$subject instanceof TodoList) {
            return false;
        }

        return $user->getId() === $subject->getUser()->getId();
    }

}
