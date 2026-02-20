<x-modals.panel name="delete-product" maxWidth="md" panelClass="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
    <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
    <p class="mb-4 text-gray-500 dark:text-gray-300">Are you sure you want to delete this item?</p>
    <div class="flex justify-center items-center space-x-4">
        <x-buttons.secondary type="button" class="py-2 px-3" x-on:click="$dispatch('close-modal', 'delete-product')">
            No, cancel
        </x-buttons.secondary>
        <x-buttons.danger type="submit" class="py-2 px-3">
            Yes, I'm sure
        </x-buttons.danger>
    </div>
</x-modals.panel>
