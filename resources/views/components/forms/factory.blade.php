<div class="{{ $gridClass }}">
    @foreach($normalizedFields as $field)
        <div class="{{ $field->fullWidth ? 'sm:col-span-2' : '' }}">
            <x-forms.input-label for="{{ $field->id }}" :value="$field->label" class="mb-2 !text-sm !font-medium !text-gray-900 dark:!text-white" />

            @if($field->type === 'textarea')
                <x-forms.textarea
                    id="{{ $field->id }}"
                    name="{{ $field->name }}"
                    rows="{{ $field->rows }}"
                    placeholder="{{ $field->placeholder }}"
                    :required="$field->required"
                    class="{{ $controlClass }}"
                >{{ $field->value }}</x-forms.textarea>
            @elseif($field->type === 'select')
                <x-forms.select
                    id="{{ $field->id }}"
                    name="{{ $field->name }}"
                    :required="$field->required"
                    class="{{ $controlClass }}"
                >
                    @foreach($field->options as $optionValue => $optionLabel)
                        @php
                            $normalizedValue = is_array($optionLabel) ? data_get($optionLabel, 'value') : $optionValue;
                            $normalizedLabel = is_array($optionLabel) ? data_get($optionLabel, 'label', $normalizedValue) : $optionLabel;
                        @endphp
                        <option value="{{ $normalizedValue }}" @selected((string)$field->value === (string)$normalizedValue)>{{ $normalizedLabel }}</option>
                    @endforeach
                </x-forms.select>
            @else
                <x-forms.text-input
                    :type="$field->type"
                    id="{{ $field->id }}"
                    name="{{ $field->name }}"
                    :value="$field->value"
                    placeholder="{{ $field->placeholder }}"
                    :required="$field->required"
                    class="{{ $controlClass }}"
                />
            @endif

            <x-forms.input-error :messages="$errors->get($field->name)" class="mt-2" />
        </div>
    @endforeach
</div>
