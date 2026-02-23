<?php

namespace App\UI\Lists\DTO;

readonly class ListColumnDto
{
    public function __construct(
        public string $key,
        public string $label,
        public bool $sortable = false,
        public ?string $sortKey = null,
        public ?string $headerClass = null,
        public ?string $cellClass = null,
    ) {
    }
}
