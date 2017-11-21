<?php

namespace DddStarterPack\Application\Event\Subscriber;

use DddStarterPack\Application\Serializer\Serializer;
use DddStarterPack\Domain\Model\Event\Event;
use DddStarterPack\Domain\Model\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredEventFactory;

class PersistAllEventSubscriber implements EventSubscriber
{
    private $eventStore;
    private $serializer;
    private $storedEventFactory;

    public function __construct(EventStore $anEventStore, Serializer $serializer, StoredEventFactory $storedEventFactory)
    {
        $this->eventStore = $anEventStore;
        $this->serializer = $serializer;
        $this->storedEventFactory = $storedEventFactory;
    }

    public function handle(Event $anEvent)
    {
        $serializedEvents = $this->serializer->serialize($anEvent, 'json');

        $storedEvent = $this->storedEventFactory->build(get_class($anEvent), $anEvent->occurredOn(), $serializedEvents);

        $this->eventStore->append($storedEvent);
    }

    public function isSubscribedTo(Event $aDomainEvent): bool
    {
        return true;
    }
}