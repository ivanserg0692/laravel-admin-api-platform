<?php

namespace App\UI\Forms\DTO;

/**
 * Typed field contract for schema-driven UI forms.
 */
readonly class FormFieldDto
{
    public function __construct(
        public string $name,
        public string $label,
        public string $type = 'text',
        public ?string $placeholder = null,
        public bool $required = false,
        public int $rows = 4,
        public bool $fullWidth = false,
        public array $options = [],
        public ?string $id = null,
        public mixed $value = null,
    ) {
    }

    public function toArray(string $idPrefix): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'required' => $this->required,
            'rows' => $this->rows,
            'full_width' => $this->fullWidth,
            'options' => $this->options,
            'id' => $this->id ?? ($idPrefix . '-' . $this->name),
            'value' => $this->value,
        ];
    }
}
