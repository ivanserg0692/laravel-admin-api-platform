<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">
            <div class="mb-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create Product</h1>
            </div>

            <x-crud.forms.create id-prefix="page-create-product">
                <div class="flex items-center gap-3">
                    <x-buttons.primary type="submit" class="px-5 py-2.5">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add new product
                    </x-buttons.primary>
                    <x-buttons.secondary :href="route('news.index')">
                        Cancel
                    </x-buttons.secondary>
                </div>
            </x-crud.forms.create>
        </div>
    </div>
</section>
