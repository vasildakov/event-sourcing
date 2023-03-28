<?php

declare(strict_types=1);


namespace VasilDakov\EventSourcing;

/**
 * Class EventBus
 *
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class EventBus
{
    /** @var SubscriberReflection[] */
    private array $subscribers = [];

    public function register(object $subscriber): self
    {
        $this->subscribers[$subscriber::class] = new SubscriberReflection($subscriber);

        return $this;
    }

    public function dispatch(object $event): void
    {
        foreach ($this->subscribers as $reflection)
        {
            $handlerMethod = $reflection->handlerFor($event);

            if (! $handlerMethod) {
                continue;
            }

            $reflection->subscriber->{$handlerMethod}($event);
        }
    }
}