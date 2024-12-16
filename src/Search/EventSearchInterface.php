<?php

declare(strict_types=1);

namespace App\Search;

interface EventSearchInterface
{
    public function searchByName(string|null $name = null): array;
}
