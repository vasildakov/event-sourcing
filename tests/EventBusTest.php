<?php

declare(strict_types=1);

namespace VasilDakov\EventSourcingTest;

use PHPUnit\Framework\TestCase;
use VasilDakov\EventSourcing\EventBus;

/**
 * Class EventBusTest
 *
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class EventBusTest extends TestCase
{
    public function testItCanDispatch()
    {
        $bus = new EventBus();

        $subscriber = new TestSubscriber();

        $bus->register($subscriber);

        $bus->dispatch(new EventA());
        $bus->dispatch(new EventB());

        $this->assertEquals(
            [EventA::class, EventB::class],
            $subscriber->log
        );
    }
}

class EventA {}

class EventB {}

class TestSubscriber {

    public array $log = [];

    public function handleA(EventA $event): void
    {
        $this->log[] = $event::class;
    }
    public function handleB(EventB $event): void
    {
        $this->log[] = $event::class;
    }
}
