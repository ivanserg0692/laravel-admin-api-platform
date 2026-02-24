<?php

namespace App\View\Components\Forms;

use App\UI\Forms\DTO\FormFieldDto;
use Closure;
use InvalidArgumentException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Factory extends Component
{
    // Optional schema-driven form renderer: currently used by CRUD flows,
    // but intentionally framework-agnostic for any other form scenarios.
    public const DEFAULT_ERROR_BAG = 'default';
    public const MODE_PLAIN = 'plain';
    public const MODE_DOT = 'dot';
    public const MODE_BRACKET = 'bracket';
    private const NAME_MODES = [self::MODE_PLAIN, self::MODE_DOT, self::MODE_BRACKET];

    /** @var FormFieldDto[] */
    public array $normalizedFields;
    public string $controlClass;
    public string $resolvedErrorBag;
    public ?string $resolvedInputNamespace;

    public function __construct(
        public string $idPrefix = 'crud-form',
        public array  $fields = [],
        public array  $values = [],
        public ?string $errorBag = null,
        public ?string $inputNamespace = null,
        public ?string $alpineModelRoot = null,
        public string $nameMode = self::MODE_PLAIN,
        public string $gridClass = 'grid gap-4 mb-4 sm:grid-cols-2',
    )
    {
        $this->nameMode = $this->resolveNameMode($nameMode);
        // Normalizes schema-driven form fields for rendering in the Blade view.
        $this->resolvedErrorBag = $this->resolveErrorBag($errorBag);
        $this->resolvedInputNamespace = $this->resolveInputNamespace($inputNamespace, $errorBag);
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
            ['htmlName' => $htmlName, 'oldKey' => $oldKey] = $this->resolveFieldNames($field->name);
            $resolvedValue = old($oldKey, data_get($this->values, $field->name, $field->value));
            $alpineModel = $this->resolveAlpineModelExpression($field->name);

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
                htmlName: $htmlName,
                oldKey: $oldKey,
                alpineModel: $alpineModel,
            );
        }

        return $normalized;
    }

    /**
     * Resolves the error bag used for validation messages.
     * Priority: explicit errorBag -> idPrefix (camelCase) -> default bag.
     */
    private function resolveErrorBag(?string $errorBag): string
    {
        if (filled($errorBag)) {
            return (string) $errorBag;
        }

        if (!filled($this->idPrefix)) {
            return self::DEFAULT_ERROR_BAG;
        }

        $camel = Str::camel((string) $this->idPrefix);

        return $camel !== '' ? $camel : self::DEFAULT_ERROR_BAG;
    }

    /**
     * Resolves the namespace used for old input isolation.
     * Field binding convention:
     * - plain: name / name
     * - dot: namespace.name / namespace.name
     * - bracket: namespace[name] / namespace.name
     */
    private function resolveInputNamespace(?string $inputNamespace, ?string $errorBag): ?string
    {
        if ($this->nameMode === self::MODE_PLAIN) {
            return null;
        }

        if (filled($inputNamespace)) {
            return (string) $inputNamespace;
        }

        if (filled($errorBag) && $errorBag !== self::DEFAULT_ERROR_BAG) {
            return (string) $errorBag;
        }

        if (!filled($this->idPrefix)) {
            return null;
        }

        $camel = Str::camel((string) $this->idPrefix);

        return $camel !== '' ? $camel : null;
    }

    /** @return array{htmlName: string, oldKey: string} */
    private function resolveFieldNames(string $fieldName): array
    {
        if (!$this->resolvedInputNamespace || $this->nameMode === self::MODE_PLAIN) {
            return ['htmlName' => $fieldName, 'oldKey' => $fieldName];
        }

        return match ($this->nameMode) {
            self::MODE_DOT => [
                'htmlName' => $this->resolvedInputNamespace . '.' . $fieldName,
                'oldKey' => $this->resolvedInputNamespace . '.' . $fieldName,
            ],
            self::MODE_BRACKET => [
                'htmlName' => $this->resolvedInputNamespace . '[' . $fieldName . ']',
                'oldKey' => $this->resolvedInputNamespace . '.' . $fieldName,
            ],
            default => ['htmlName' => $fieldName, 'oldKey' => $fieldName],
        };
    }

    private function resolveNameMode(string $nameMode): string
    {
        if (!in_array($nameMode, self::NAME_MODES, true)) {
            throw new InvalidArgumentException('Unsupported nameMode. Allowed: ' . implode(', ', self::NAME_MODES) . '.');
        }

        return $nameMode;
    }

    private function resolveAlpineModelExpression(string $fieldName): ?string
    {
        if (!filled($this->alpineModelRoot)) {
            return null;
        }

        return $this->alpineModelRoot . '.' . $fieldName;
    }
}
