<?php

namespace App\UI\Forms\DTO;

/**
 * Typed field contract for schema-driven UI forms.
 */
readonly class FormFieldDto
{
    public function __construct(
        public string  $name, // Logical field key (domain-level), e.g. "title".
        public string  $label, // UI label text.
        public string  $type = 'text', // Control type: text, select, textarea, number, etc.
        public ?string $placeholder = null, // Optional placeholder for input/textarea.
        public bool    $required = false, // HTML required flag.
        public int     $rows = 4, // Textarea row count.
        public bool    $fullWidth = false, // Whether field spans full grid width.
        public array   $options = [], // Select options (value => label or structured option arrays).
        public ?string $id = null, // Explicit HTML id; if null, computed by the renderer.
        public mixed   $value = null, // Default/current field value.
        public ?string $alpineModel = null, // Optional Alpine model expression, e.g. "form.title".
    )
    {
    }
}
