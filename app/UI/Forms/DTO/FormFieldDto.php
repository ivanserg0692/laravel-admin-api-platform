<?php

namespace App\UI\Forms\DTO;

/**
 * Typed field contract for schema-driven UI forms.
 */
readonly class FormFieldDto
{
    public function __construct(
        public string  $name,
        public string  $label,
        public string  $type = 'text',
        public ?string $placeholder = null,
        public bool    $required = false,
        public int     $rows = 4,
        public bool    $fullWidth = false,
        public array   $options = [],
        public ?string $id = null,
        public mixed   $value = null,
        public ?string $htmlName = null,
        public ?string $oldKey = null,
    )
    {
    }
}
