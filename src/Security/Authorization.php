<?php

declare(strict_types=1);

namespace App\Security;

enum Authorization
{
    public const EVENT_CREATE = 'event/create';

    public const ORGANIZATION_CREATE = 'organization/create';

    public const PROJECT_CREATE = 'project/create';
}
