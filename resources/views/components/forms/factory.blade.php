@props([
    'idPrefix' => 'crud-form',
    'fields' => [],
    'values' => [],
    'gridClass' => 'grid gap-4 mb-4 sm:grid-cols-2',
])

<div class="{{ $gridClass }}">
    @foreach($fields as $field)
        @php
            $name = data_get($field, 'name');
            $label = data_get($field, 'label', ucfirst(str_replace('_', ' ', (string)$name)));
            $type = data_get($field, 'type', 'text');
            $placeholder = data_get($field, 'placeholder');
            $required = (bool) data_get($field, 'required', false);
            $rows = (int) data_get($field, 'rows', 4);
            $fullWidth = (bool) data_get($field, 'full_width', false);
            $options = data_get($field, 'options', []);
            $id = data_get($field, 'id', $idPrefix . '-' . $name);
            $rawValue = old($name, data_get($values, $name, data_get($field, 'value')));

            $controlClass = '!mt-0 !rounded-lg !border-gray-300 !bg-gray-50 !px-2.5 !py-2.5 !text-sm !text-gray-900 focus:!border-primary-600 focus:!ring-primary-600 dark:!border-gray-600 dark:!bg-gray-700 dark:!text-white dark:!placeholder-gray-400 dark:focus:!border-primary-500 dark:focus:!ring-primary-500';
            $wrapperClass = $fullWidth ? 'sm:col-span-2' : '';
        @endphp

        @if($name)
            <div class="{{ $wrapperClass }}">
                <x-forms.input-label for="{{ $id }}" :value="$label" class="mb-2 !text-sm !font-medium !text-gray-900 dark:!text-white" />

                @if($type === 'textarea')
                    <x-forms.textarea
                        id="{{ $id }}"
                        name="{{ $name }}"
                        rows="{{ $rows }}"
                        placeholder="{{ $placeholder }}"
                        :required="$required"
                        class="{{ $controlClass }}"
                    >{{ $rawValue }}</x-forms.textarea>
                @elseif($type === 'select')
                    <x-forms.select
                        id="{{ $id }}"
                        name="{{ $name }}"
                        :required="$required"
                        class="{{ $controlClass }}"
                    >
                        @foreach($options as $optionValue => $optionLabel)
                            @php
                                $normalizedValue = is_array($optionLabel) ? data_get($optionLabel, 'value') : $optionValue;
                                $normalizedLabel = is_array($optionLabel) ? data_get($optionLabel, 'label', $normalizedValue) : $optionLabel;
                            @endphp
                            <option value="{{ $normalizedValue }}" @selected((string)$rawValue === (string)$normalizedValue)>{{ $normalizedLabel }}</option>
                        @endforeach
                    </x-forms.select>
                @else
                    <x-forms.text-input
                        :type="$type"
                        id="{{ $id }}"
                        name="{{ $name }}"
                        :value="$rawValue"
                        placeholder="{{ $placeholder }}"
                        :required="$required"
                        class="{{ $controlClass }}"
                    />
                @endif

                <x-forms.input-error :messages="$errors->get($name)" class="mt-2" />
            </div>
        @endif
    @endforeach
</div>
