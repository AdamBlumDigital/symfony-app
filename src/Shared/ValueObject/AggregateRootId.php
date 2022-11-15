<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

use Symfony\Component\Uid\Ulid;
use InvalidArgumentException;

abstract class AggregateRootId
{
	protected string $ulid;

	public function __construct(string $ulid)
	{
		if (!Ulid::isValid($ulid)) {
			throw new InvalidArgumentException('Not valid ULID');
		}

		$this->ulid = $ulid;
	}

	public function getValue(): string 
	{
		return $this->ulid;
	}

	public function __toString(): string
	{
		return $this->ulid;
	}
}
