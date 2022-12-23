<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use Symfony\Component\Uid\Uuid;

abstract class AggregateRootId
{
    protected string $uuid;

    public function __construct(string $uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException('Not valid UUID');
        }

        $this->uuid = $uuid;
    }

    public function getValue(): string
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
