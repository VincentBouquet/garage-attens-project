<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ManageEmployeeVoter extends Voter
{
    private $security;

    /**
     * ManageEmployeeVoter constructor .
     */
    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        /*return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
            && $subject instanceof \App\Entity\ManageEmployee;*/
        return $attribute === "admin";
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if($this->security->isGranted("ROLE_ADMIN") || $user === $subject->getId() ) {
            return true;
        } else {
            return false ;
        }
    }
}
