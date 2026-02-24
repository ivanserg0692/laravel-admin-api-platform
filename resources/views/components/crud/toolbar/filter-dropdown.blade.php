<x-dropdown.toggle-panel
    button-id="filterDropdownButton"
    dropdown-id="filterDropdown"
    panel-class="z-10 hidden w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700"
>
    <x-slot:trigger>
        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
             class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                  clip-rule="evenodd"/>
        </svg>
        Filter
        <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path clip-rule="evenodd" fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
        </svg>
    </x-slot:trigger>

    <x-slot:content>
        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Category</h6>
        <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
            <li class="flex items-center">
                <input id="apple" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="apple"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Apple
                    (56)</label>
            </li>
            <li class="flex items-center">
                <input id="fitbit" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="fitbit"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Fitbit
                    (56)</label>
            </li>
            <li class="flex items-center">
                <input id="dell" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="dell" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Dell
                    (56)</label>
            </li>
            <li class="flex items-center">
                <input id="asus" type="checkbox" value="" checked=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="asus" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Asus
                    (97)</label>
            </li>
            <li class="flex items-center">
                <input id="logitech" type="checkbox" value="" checked=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="logitech"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Logitech
                    (97)</label>
            </li>
            <li class="flex items-center">
                <input id="msi" type="checkbox" value="" checked=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="msi" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">MSI
                    (97)</label>
            </li>
            <li class="flex items-center">
                <input id="bosch" type="checkbox" value="" checked=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="bosch"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Bosch
                    (176)</label>
            </li>
            <li class="flex items-center">
                <input id="sony" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="sony" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Sony
                    (234)</label>
            </li>
            <li class="flex items-center">
                <input id="samsung" type="checkbox" value="" checked=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="samsung"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Samsung
                    (76)</label>
            </li>
            <li class="flex items-center">
                <input id="canon" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="canon"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Canon
                    (49)</label>
            </li>
            <li class="flex items-center">
                <input id="microsoft" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="microsoft"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Microsoft
                    (45)</label>
            </li>
            <li class="flex items-center">
                <input id="razor" type="checkbox" value=""
                       class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                <label for="razor"
                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Razor
                    (49)</label>
            </li>
        </ul>
    </x-slot:content>
</x-dropdown.toggle-panel>
