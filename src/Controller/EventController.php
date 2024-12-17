<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Search\DatabaseEventSearch;
use App\Search\EventSearchInterface;
use App\Security\Authorization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EventController extends AbstractController
{
    #[Route('/events', name: 'app_event_list', methods: ['GET'])]
    public function listEvents(Request $request, DatabaseEventSearch $eventSearch): Response
    {
        $events = $eventSearch->searchByName($request->query->get('name', null));

        return $this->render('event/list_events.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/search', name: 'app_event_search', methods: ['GET'])]
    #[Template('event/list_events.html.twig')]
    public function searchEvent(Request $request, EventSearchInterface $eventSearch): array
    {
        return [
            'events' => $eventSearch->searchByName($request->query->get('name', null))
        ];
    }

    #[Route('/event/{id}', name: 'app_event_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showEvent(Event $event): Response
    {
        return $this->render('event/show_event.html.twig', [
            'event' => $event,
        ]);
    }

    #[IsGranted(Authorization::EVENT_CREATE)]
    #[Route('/event/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function newEvent(Request $request, EntityManagerInterface $manager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/new_event.html.twig', [
            'form' => $form,
        ]);
    }
}
