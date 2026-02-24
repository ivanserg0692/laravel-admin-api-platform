@push('scripts')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush

<div class="cf-turnstile"
     data-sitekey="{{ config('services.cloudflare.site_key') }}">
</div>

<x-forms.input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2" />
