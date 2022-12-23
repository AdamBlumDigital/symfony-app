<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Shared\Event\DomainEventInterface;

abstract class AggregateRoot
{
    /**
     * @var array<object>
     *
     * @todo	Once DomainEventInterface is being used,
     * 			update array type to accurately reflect
     * 			the shape of the data.
     */
    protected array $domainEvents;

    public function recordDomainEvent(DomainEventInterface $event): self
    {
        $this->domainEvents[] = $event;

        return $this;
    }

    /**
     * @return array<object>
     *
     * @todo	Once DomainEventInterface is being used,
     * 			update this method return type to accurately
     * 			reflect the shape of the data.
     */
    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }
}
