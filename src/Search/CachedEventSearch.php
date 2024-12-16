<?php

declare(strict_types=1);

namespace App\Search;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CachedEventSearch implements EventSearchInterface
{
    public function __construct(
        private readonly EventSearchInterface $inner,
        private readonly CacheInterface $cache,
    ) {
    }

    public function searchByName(?string $name = null): array
    {
        return $this->cache->get(md5($name), function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600); // Cache for 1 hour

            return $this->inner->searchByName($name); // Call the decorated service method
        });
    }
}
