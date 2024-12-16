<?php

declare(strict_types=1);

namespace App\Search;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsDecorator(EventSearchInterface::class)]
final class CachedEventSearch implements EventSearchInterface
{
    private const DEFAULT_NAME = '_default';

    public function __construct(
        private readonly EventSearchInterface $inner,
        private readonly CacheInterface $cache,
    ) {
    }

    public function searchByName(?string $name = null): array
    {
        return $this->cache->get(md5($name ?? self::DEFAULT_NAME), function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600); // Cache for 1 hour

            return $this->inner->searchByName($name); // Call the decorated service method
        });
    }
}
