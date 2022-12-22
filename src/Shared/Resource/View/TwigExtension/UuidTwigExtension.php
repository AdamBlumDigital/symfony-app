<?php

declare(strict_types=1);

namespace App\Shared\Resource\View\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\TimeBasedUidInterface;

class UuidTwigExtension extends AbstractExtension
{
	public function getFilters()
	{
		return [
			new TwigFilter('uuid_date', [$this, 'getDate'])
		];
	}

	public function getDate(string $string): \DateTimeImmutable
	{
		/**
		 *	Not all UUIDs are time-based, so the UUID must
		 *	be checked to see if it implements the
		 *	TimeBasedUidInterface
		 */
		$mustImplementInterface = TimeBasedUidInterface::class;
		$uuid = Uuid::fromString($string);
		$uuidClass = get_class($uuid); // the base Uuid class
		$uuidClassImplements = class_implements($uuidClass);
		if ($uuidClassImplements == false) {
			throw new \DomainException(sprintf('Object of type <%s> does not implement any of the necessary classes.', $uuidClass));
		}
		$canGetTime = in_array($mustImplementInterface, $uuidClassImplements, true);
		if (!$canGetTime) {
			throw new \DomainException(sprintf('Object of type <%s> does not implement <%s>.', $uuidClass, $mustImplementInterface));
		}

		/**
		 *	PHPStan thinks this is only ever a Uuid type, and not
		 *	a more specific type that implements the getDateTime()
		 *	method. This can be safely ignored due to the above
		 *	error handling.
		 */
		/** @phpstan-ignore-next-line */
		return $uuid->getDateTime();
	}
}
