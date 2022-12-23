<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

/**
 *	This message is handled via sync transport.
 */
final class FindSomeArticlesQuery
{
    private int $pageNumber;
    private int $pageSize;

    public function __construct(int $pageNumber, int $pageSize)
    {
        $this->pageNumber = $pageNumber;
        $this->pageSize = $pageSize;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }
}
