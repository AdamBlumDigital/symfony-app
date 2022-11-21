<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

use App\Shared\Message\AsyncMessageInterface;

/**
 *	This message is handled via async transport.
 *
 *	config/packages/messenger.yaml
 */
final class CreateArticleCommand implements AsyncMessageInterface
{
	private string $title;

	public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
