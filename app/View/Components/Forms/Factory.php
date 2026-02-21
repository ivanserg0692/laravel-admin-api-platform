<?php

namespace App\View\Components\Forms;

use App\UI\Forms\DTO\FormFieldDto;
use Closure;
use InvalidArgumentException;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Factory extends Component
{
    /** @var FormFieldDto[] */
    public array $normalizedFields;
    public string $controlClass;

    public function __construct(
        public string $idPrefix = 'crud-form',
        public array  $fields = [],
        public array  $values = [],
        public string $gridClass = 'grid gap-4 mb-4 sm:grid-cols-2',
    )
    {
        // Normalizes schema-driven form fields for rendering in the Blade view.
        $this->normalizedFields = $this->normalizeFields();
        $this->controlClass = '!mt-0 !rounded-lg !border-gray-300 !bg-gray-50 !px-2.5 !py-2.5 !text-sm !text-gray-900 focus:!border-primary-600 focus:!ring-primary-600 dark:!border-gray-600 dark:!bg-gray-700 dark:!text-white dark:!placeholder-gray-400 dark:focus:!border-primary-500 dark:focus:!ring-primary-500';
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.factory');
    }

    /** @return FormFieldDto[] */
    private function normalizeFields(): array
    {
        $normalized = [];

        foreach ($this->fields as $field) {
            if (!$field instanceof FormFieldDto) {
                throw new InvalidArgumentException('Forms factory expects fields to be instances of ' . FormFieldDto::class . '.');
            }

            $fieldId = $field->id ?? ($this->idPrefix . '-' . $field->name);
            $resolvedValue = old($field->name, data_get($this->values, $field->name, $field->value));

            $normalized[] = new FormFieldDto(
                name: $field->name,
                label: $field->label,
                type: $field->type,
                placeholder: $field->placeholder,
                required: $field->required,
                rows: $field->rows,
                fullWidth: $field->fullWidth,
                options: $field->options,
                id: $fieldId,
                value: $resolvedValue,
            );
        }

        return $normalized;
    }
}
