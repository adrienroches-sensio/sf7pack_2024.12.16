<?php

declare(strict_types=1);

namespace App\Search;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsAlias]
final class ApiEventSearch implements EventSearchInterface
{
    public function __construct(
        #[Autowire(service: 'events.client')]
        private readonly HttpClientInterface $client,
    ) {
    }

    public function searchByName(string|null $name = null): array
    {
        return $this->client->request('GET', '/events', [
            'query' => ['name' => $name],
        ])->toArray();
    }
}
