@props([
    'name' => 'read-product',
    'title' => null,
])

<x-modals.panel :name="$name" maxWidth="xl" :title="$title ?? __('crud.preview_label')">
    <div
        x-data="{
            modalName: @js($name),
            preview: {
                title: '',
                status: '',
                published_at: '',
                preview: '',
                content: '',
                cover_image: ''
            },
            setPreview(payload) {
                if (!payload || typeof payload !== 'object' || Array.isArray(payload)) {
                    return;
                }

                Object.entries(payload).forEach(([name, value]) => {
                    if (!Object.prototype.hasOwnProperty.call(this.preview, name)) {
                        return;
                    }

                    this.preview[name] = value ?? '';
                });
            }
        }"
        x-on:news-preview-loaded.window="
            if ($event.detail?.modal !== modalName) return;
            setPreview($event.detail?.preview);
        "
    >
        <div class="space-y-4">
            <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                <h3 class="font-semibold" x-text="preview.title || '-'"></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="preview.status || '-'"></p>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="preview.published_at || '-'"></p>
            </div>

            <template x-if="preview.cover_image">
                <img :src="preview.cover_image" alt="" class="w-full max-h-64 object-cover rounded-md">
            </template>

            <dl class="space-y-4">
                <div>
                    <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Preview</dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400 whitespace-pre-line" x-text="preview.preview || '-'"></dd>
                </div>
                <div>
                    <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Content</dt>
                    <dd class="font-light text-gray-500 dark:text-gray-400 whitespace-pre-line" x-text="preview.content || '-'"></dd>
                </div>
            </dl>
        </div>
    </div>
</x-modals.panel>
