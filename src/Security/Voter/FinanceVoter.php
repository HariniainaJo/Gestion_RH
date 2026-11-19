<?php

namespace App\Security\Voter;

use App\Entity\Employe;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\User\UserInterface;

class FinanceVoter extends Voter
{
    public const VIEW_SALARY = 'FINANCE_VIEW_SALARY';
    public const MANAGE_FINANCE = 'FINANCE_MANAGE';
    public const MANAGE_LOGISTIQUE = 'LOGISTIQUE_MANAGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW_SALARY, self::MANAGE_FINANCE, self::MANAGE_LOGISTIQUE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles) || in_array('ROLE_HR', $roles)) {
            return true;
        }

        return false;
    }
}

