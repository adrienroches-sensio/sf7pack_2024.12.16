<?php

declare(strict_types=1);

namespace App\Search;

use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class ApiEventSearch implements EventSearchInterface
{
    public function __construct(
        #[Autowire(env: 'DEVEVENTSAPI_KEY')]
        #[SensitiveParameter]
        private readonly string $apiKey,
    ) {
    }

    public function searchByName(?string $name = null): array
    {
        return [];
    }
}
