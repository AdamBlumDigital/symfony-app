<?php

declare(strict_types=1);

namespace App\Shared\Resource\View\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class IsNumericTwigExtension extends AbstractExtension
{
	public function getTests()
	{
		return [
			new TwigTest('numeric', [$this, 'numeric'])
		];
	}

	public function numeric(mixed $subject): bool
	{
		return \is_numeric($subject);
	}
}
