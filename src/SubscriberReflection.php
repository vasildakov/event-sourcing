<?php

declare(strict_types=1);

namespace VasilDakov\EventSourcing;

use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * Class SubscriberReflection
 *
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class SubscriberReflection
{
    private ReflectionClass $reflection;

    public object $subscriber;

    public function __construct(object $subscriber)
    {
        $this->reflection = new ReflectionClass($subscriber);

        $this->subscriber = $subscriber;
    }

    public function handlerFor(object $event): ?string
    {
        $collection = new ArrayCollection(
            $this->reflection->getMethods(ReflectionMethod::IS_PUBLIC)
        );

        $handlerMethod = $collection
            ->filter(function (ReflectionMethod $method) use ($event) {
                $firstParameter = $method->getParameters()[0] ?? null;

                if (!$firstParameter) {
                    return false;
                }

                $type = $firstParameter->getType();

                if (! $type instanceof ReflectionNamedType) {
                    return false;
                }

                return $type->getName() === $event::class;
            })->first();

        return $handlerMethod?->getName();
    }
}