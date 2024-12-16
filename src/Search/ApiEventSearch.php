<?php

declare(strict_types=1);

namespace App\Search;

use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\HttpClient;

final class ApiEventSearch implements EventSearchInterface
{
    public function __construct(
        #[Autowire(env: 'DEVEVENTSAPI_KEY')]
        #[SensitiveParameter]
        private readonly string $apiKey,
    ) {
    }

    public function searchByName(string|null $name = null): array
    {
        $client = HttpClient::create();

        return $client->request('GET', 'https://www.devevents-api.fr/events', [
            'query' => ['name' => $name],
            'headers' => [
                'apikey' => $this->apiKey,
                'Accept' => 'application/json',
            ],
        ])->toArray();
    }
}
