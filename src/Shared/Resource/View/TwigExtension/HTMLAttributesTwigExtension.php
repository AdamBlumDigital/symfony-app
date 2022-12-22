<?php

declare(strict_types=1);

namespace App\Shared\Resource\View\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HTMLAttributesTwigExtension extends AbstractExtension
{
	public function getFilters()
	{
		return [
			new TwigFilter('attributes', [$this, 'attributes'])
		];
	}

	/**
	 *	@param array<mixed> $array
	 */
	public function attributes(array $array): string
	{
		$callback = fn(string $k, string $v): string => "$k=$v";

		$result = implode(' ', array_map($callback, array_keys($array), array_values($array)));

		return $result;
	}
}
