@props([
    'name' => 'update-product',
    'fields' => [],
    'values' => [],
    'action' => '#',
    'title' => __('crud.edit_label'),
    'submitLabel' => 'Update product',
    'deleteModal' => null,
])

<x-modals.panel :name="$name" maxWidth="2xl" :title="$title">
    <div
        x-data="{
            modalName: @js($name),
            deleteModalName: @js($deleteModal),
            form: @js($values),
            currentItemId: null,
            currentItemTitle: '',
            deleteUrl: '',
            setValues(values) {
                if (!values || typeof values !== 'object' || Array.isArray(values)) {
                    return;
                }

                Object.entries(values).forEach(([name, rawValue]) => {
                    if (!Object.prototype.hasOwnProperty.call(this.form, name)) {
                        return;
                    }

                    this.form[name] = rawValue ?? '';
                });
            }
        }"
        x-on:news-edit-values-loaded.window="
            if ($event.detail?.modal !== modalName) return;
            currentItemId = $event.detail?.id ?? null;
            currentItemTitle = typeof $event.detail?.title === 'string' ? $event.detail.title : '';
            deleteUrl = typeof $event.detail?.deleteUrl === 'string' ? $event.detail.deleteUrl : '';
            setValues($event.detail?.values);
        "
    >
        <x-crud.forms.update
            id-prefix="update-product"
            :action="$action"
            :fields="$fields"
            :values="$values"
            alpine-model-root="form"
        >
            <div class="flex items-center space-x-4">
                <x-buttons.primary type="submit">
                    {{ $submitLabel }}
                </x-buttons.primary>
                <x-buttons.danger
                    type="button"
                    x-bind:disabled="!deleteModalName || !deleteUrl"
                    x-on:click="
                        if (!deleteModalName || !deleteUrl) return;
                        window.dispatchEvent(new CustomEvent('news-delete-values-loaded', {
                            detail: {
                                modal: deleteModalName,
                                id: currentItemId,
                                title: currentItemTitle,
                                deleteUrl: deleteUrl,
                            }
                        }));
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: deleteModalName }));
                    "
                >
                    <svg class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('crud.delete_label') }}
                </x-buttons.danger>
            </div>
        </x-crud.forms.update>
    </div>
</x-modals.panel>
