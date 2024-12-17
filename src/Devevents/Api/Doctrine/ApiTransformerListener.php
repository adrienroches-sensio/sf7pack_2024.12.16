<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::VIEW)]
final class ApiTransformerListener
{
    public function __construct(
        private readonly ApiParser $apiParser,
    ) {
    }

    public function __invoke(ViewEvent $event): void
    {
        $request = $event->getRequest();
        if ('app_event_search' !== $request->attributes->get('_route')) {
            return;
        }

        $result = $event->getControllerResult();

        $result['events'] = $this->apiParser->parse($result['events']);

        $event->setControllerResult($result);
    }
}
