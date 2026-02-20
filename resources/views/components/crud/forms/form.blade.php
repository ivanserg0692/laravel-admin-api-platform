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

            $inputClass = 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500';
            $textareaClass = 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500';
            $wrapperClass = $fullWidth ? 'sm:col-span-2' : '';
        @endphp

        @if($name)
            <div class="{{ $wrapperClass }}">
                <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>

                @if($type === 'textarea')
                    <textarea
                        id="{{ $id }}"
                        name="{{ $name }}"
                        rows="{{ $rows }}"
                        placeholder="{{ $placeholder }}"
                        @required($required)
                        class="{{ $textareaClass }}"
                    >{{ $rawValue }}</textarea>
                @elseif($type === 'select')
                    <select
                        id="{{ $id }}"
                        name="{{ $name }}"
                        @required($required)
                        class="{{ $inputClass }}"
                    >
                        @foreach($options as $optionValue => $optionLabel)
                            @php
                                $normalizedValue = is_array($optionLabel) ? data_get($optionLabel, 'value') : $optionValue;
                                $normalizedLabel = is_array($optionLabel) ? data_get($optionLabel, 'label', $normalizedValue) : $optionLabel;
                            @endphp
                            <option value="{{ $normalizedValue }}" @selected((string)$rawValue === (string)$normalizedValue)>{{ $normalizedLabel }}</option>
                        @endforeach
                    </select>
                @else
                    <input
                        type="{{ $type }}"
                        id="{{ $id }}"
                        name="{{ $name }}"
                        value="{{ $rawValue }}"
                        placeholder="{{ $placeholder }}"
                        @required($required)
                        class="{{ $inputClass }}"
                    >
                @endif
            </div>
        @endif
    @endforeach
</div>
