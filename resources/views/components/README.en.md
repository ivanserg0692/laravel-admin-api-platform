# Blade Components Documentation

Folder: `resources/views/components`

## Contents

### Branding
- [`application-logo`](#application-logo)

### Form Controls
- [`input-label`](#input-label)
- [`text-input`](#text-input)
- [`input-error`](#input-error)
- [`cloudflare-captcha`](#cloudflare-captcha)

### Buttons
- [`primary-button`](#primary-button)
- [`secondary-button`](#secondary-button)
- [`danger-button`](#danger-button)

### Navigation & Links
- [`nav-link`](#nav-link)
- [`responsive-nav-link`](#responsive-nav-link)
- [`dropdown-link`](#dropdown-link)

### Overlays & Menus
- [`dropdown`](#dropdown)
- [`modal`](#modal)

## General Usage Rules
- Components are used as `<x-component-name>`.
- All components accept additional HTML attributes via `$attributes`.
- Rendering/state logic is handled with `@props`, Alpine.js, and standard Blade slots.

## Components

### `application-logo`
- File: `application-logo.blade.php`
- Purpose: Application SVG logo.
- Props: none.
- Slot: none.
- Example:

```blade
<x-application-logo class="h-10 w-10 text-gray-500" />
```

### `auth-session-status`
- File: `auth-session-status.blade.php`
- Purpose: Displays session status (for example, after password reset actions).
- Props:
  - `status`.
- Renders only when `status` is not empty.
- Example:

```blade
<x-auth-session-status :status="session('status')" class="mb-4" />
```

### `cloudflare-captcha`
- File: `cloudflare-captcha.blade.php`
- Purpose: Cloudflare Turnstile widget + validation error output.
- Props: none.
- Notes:
  - Pushes script via `@push('scripts')`.
  - Uses `config('services.cloudflare.site_key')`.
  - Renders `cf-turnstile-response` errors via `x-input-error`.
- Example:

```blade
<x-cloudflare-captcha class="mt-4" />
```

### `danger-button`
- File: `danger-button.blade.php`
- Purpose: Red button for destructive actions.
- Props: none (`type="submit"` by default).
- Slot: button text/content.
- Example:

```blade
<x-danger-button>
    Delete
</x-danger-button>
```

### `dropdown-link`
- File: `dropdown-link.blade.php`
- Purpose: Link element inside a dropdown menu.
- Props: none.
- Slot: link content.
- Example:

```blade
<x-dropdown-link :href="route('profile.edit')">
    Profile
</x-dropdown-link>
```

### `dropdown`
- File: `dropdown.blade.php`
- Purpose: Alpine.js dropdown menu.
- Props:
  - `align` (`right` by default; supports `right`, `left`, `top`).
  - `width` (`48` by default, mapped to `w-48`).
  - `contentClasses` (`py-1 bg-white` by default).
- Slots:
  - `trigger` - clickable element that toggles the dropdown.
  - `content` - dropdown body content.
- Example:

```blade
<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button type="button">Menu</button>
    </x-slot>

    <x-slot name="content">
        <x-dropdown-link :href="route('logout')">Logout</x-dropdown-link>
    </x-slot>
</x-dropdown>
```

### `input-error`
- File: `input-error.blade.php`
- Purpose: Validation error messages list.
- Props:
  - `messages` (array or string).
- Renders only when `messages` is not empty.
- Example:

```blade
<x-input-error :messages="$errors->get('email')" class="mt-2" />
```

### `input-label`
- File: `input-label.blade.php`
- Purpose: Label for input fields.
- Props:
  - `value` (optional; falls back to `$slot`).
- Example:

```blade
<x-input-label for="email" :value="__('Email')" />
```

### `modal`
- File: `modal.blade.php`
- Purpose: Alpine.js modal with focus trap behavior.
- Props:
  - `name` (modal name for `open-modal`/`close-modal` events).
  - `show` (`false` by default).
  - `maxWidth` (`2xl` by default; supports `sm`, `md`, `lg`, `xl`, `2xl`).
- Slot: modal content.
- Notes:
  - Locks `body` scrolling while open.
  - Supports optional `focusable` attribute for auto-focus.
- Example:

```blade
<x-modal name="confirm-user-deletion" :show="false" maxWidth="md">
    <div class="p-6">...</div>
</x-modal>
```

### `nav-link`
- File: `nav-link.blade.php`
- Purpose: Top navigation link with active/inactive styles.
- Props:
  - `active` (`bool`).
- Example:

```blade
<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    Dashboard
</x-nav-link>
```

### `primary-button`
- File: `primary-button.blade.php`
- Purpose: Primary dark button.
- Props: none (`type="submit"` by default).
- Slot: button text/content.
- Example:

```blade
<x-primary-button>
    Save
</x-primary-button>
```

### `responsive-nav-link`
- File: `responsive-nav-link.blade.php`
- Purpose: Link for mobile/responsive navigation.
- Props:
  - `active` (`bool`).
- Example:

```blade
<x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
    Profile
</x-responsive-nav-link>
```

### `secondary-button`
- File: `secondary-button.blade.php`
- Purpose: Secondary light button.
- Props: none (`type="button"` by default).
- Slot: button text/content.
- Example:

```blade
<x-secondary-button>
    Cancel
</x-secondary-button>
```

### `text-input`
- File: `text-input.blade.php`
- Purpose: Generic input with base styles.
- Props:
  - `disabled` (`false` by default).
- Example:

```blade
<x-text-input id="email" name="email" type="email" :value="old('email')" required autofocus />
```
