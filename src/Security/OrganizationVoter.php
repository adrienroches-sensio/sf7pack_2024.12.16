<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class OrganizationVoter implements VoterInterface
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $attribute = $attributes[0];

        if (Authorization::ORGANIZATION_CREATE !== $attribute) {
            return self::ACCESS_ABSTAIN;
        }

        if ($this->authorizationChecker->isGranted('ROLE_ORGANIZER') === true) {
            return self::ACCESS_GRANTED;
        }

        if ($this->authorizationChecker->isGranted('ROLE_WEBSITE') === true) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_DENIED;
    }
}
