<?php

declare(strict_types=1);

namespace App\Search;

final class ApiEventSearch implements EventSearchInterface
{
    public function searchByName(?string $name = null): array
    {
        return [];
    }
}
