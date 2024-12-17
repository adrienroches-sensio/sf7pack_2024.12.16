<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use function str_starts_with;

final class EventVoter implements VoterInterface, CacheableVoterInterface
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $attribute = $attributes[0];

        if (Authorization::EVENT_CREATE !== $attribute) {
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

    public function supportsAttribute(string $attribute): bool
    {
        return str_starts_with($attribute, 'event/');
    }

    public function supportsType(string $subjectType): bool
    {
        return true;
    }
}
