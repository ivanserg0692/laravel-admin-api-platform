<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-forms.input-label for="password" :value="__('Password')" />

            <x-forms.text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-forms.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-end">
            <x-buttons.primary>
                {{ __('Confirm') }}
            </x-buttons.primary>
        </div>
    </form>
</x-guest-layout>
