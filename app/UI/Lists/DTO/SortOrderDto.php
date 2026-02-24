<?php

namespace App\UI\Lists\DTO;

/**
 * Sort entry contract for list ordering.
 * Query format example: sort[]=title:asc&sort[]=published_at:desc
 */
readonly class SortOrderDto
{
    public function __construct(
        public string $key,
        public string $direction,
    ) {
    }

    public function toQueryValue(): string
    {
        return $this->key . ':' . $this->direction;
    }
}
