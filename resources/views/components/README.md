# Документация Blade-компонентов

Папка: `resources/views/components`

## Содержание

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

## Общие правила использования
- Компоненты подключаются как `<x-имя-компонента>`.
- Все компоненты поддерживают передачу HTML-атрибутов через `$attributes`.
- Для логики отображения/состояний используются `@props`, Alpine.js и стандартные Blade-слоты.

## Список компонентов

### `application-logo`
- Файл: `application-logo.blade.php`
- Назначение: SVG-логотип приложения.
- Props: нет.
- Слот: нет.
- Пример:

```blade
<x-application-logo class="h-10 w-10 text-gray-500" />
```

### `auth-session-status`
- Файл: `auth-session-status.blade.php`
- Назначение: выводит статус сессии (например, сообщение после восстановления пароля).
- Props:
  - `status`.
- Рендерится только если `status` не пустой.
- Пример:

```blade
<x-auth-session-status :status="session('status')" class="mb-4" />
```

### `cloudflare-captcha`
- Файл: `cloudflare-captcha.blade.php`
- Назначение: виджет Cloudflare Turnstile + отображение ошибки валидации.
- Props: нет.
- Особенности:
  - Добавляет скрипт через `@push('scripts')`.
  - Использует `config('services.cloudflare.site_key')`.
  - Выводит ошибки `cf-turnstile-response` через `x-input-error`.
- Пример:

```blade
<x-cloudflare-captcha class="mt-4" />
```

### `danger-button`
- Файл: `danger-button.blade.php`
- Назначение: красная кнопка для опасных действий.
- Props: нет (по умолчанию `type="submit"`).
- Слот: текст/контент кнопки.
- Пример:

```blade
<x-danger-button>
    Delete
</x-danger-button>
```

### `dropdown-link`
- Файл: `dropdown-link.blade.php`
- Назначение: элемент ссылки внутри dropdown-меню.
- Props: нет.
- Слот: содержимое ссылки.
- Пример:

```blade
<x-dropdown-link :href="route('profile.edit')">
    Profile
</x-dropdown-link>
```

### `dropdown`
- Файл: `dropdown.blade.php`
- Назначение: выпадающее меню на Alpine.js.
- Props:
  - `align` (`right` по умолчанию; поддерживаются `right`, `left`, `top`).
  - `width` (`48` по умолчанию, преобразуется в `w-48`).
  - `contentClasses` (`py-1 bg-white` по умолчанию).
- Слоты:
  - `trigger` - элемент, по клику на который открывается меню.
  - `content` - содержимое выпадающего блока.
- Пример:

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
- Файл: `input-error.blade.php`
- Назначение: список сообщений об ошибках валидации.
- Props:
  - `messages` (массив или строка).
- Рендерится только если `messages` не пустой.
- Пример:

```blade
<x-input-error :messages="$errors->get('email')" class="mt-2" />
```

### `input-label`
- Файл: `input-label.blade.php`
- Назначение: label для поля ввода.
- Props:
  - `value` (опционально; если нет, берётся `$slot`).
- Пример:

```blade
<x-input-label for="email" :value="__('Email')" />
```

### `modal`
- Файл: `modal.blade.php`
- Назначение: модальное окно на Alpine.js с ловушкой фокуса.
- Props:
  - `name` (имя модалки для событий `open-modal`/`close-modal`).
  - `show` (`false` по умолчанию).
  - `maxWidth` (`2xl` по умолчанию; поддерживаются `sm`, `md`, `lg`, `xl`, `2xl`).
- Слот: содержимое модального окна.
- Особенности:
  - Блокирует прокрутку `body` при открытии.
  - Поддерживает опциональный атрибут `focusable` для автофокуса.
- Пример:

```blade
<x-modal name="confirm-user-deletion" :show="false" maxWidth="md">
    <div class="p-6">...</div>
</x-modal>
```

### `nav-link`
- Файл: `nav-link.blade.php`
- Назначение: ссылка верхней навигации с активным/неактивным стилем.
- Props:
  - `active` (`bool`).
- Пример:

```blade
<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    Dashboard
</x-nav-link>
```

### `primary-button`
- Файл: `primary-button.blade.php`
- Назначение: основная тёмная кнопка.
- Props: нет (по умолчанию `type="submit"`).
- Слот: текст/контент кнопки.
- Пример:

```blade
<x-primary-button>
    Save
</x-primary-button>
```

### `responsive-nav-link`
- Файл: `responsive-nav-link.blade.php`
- Назначение: ссылка для мобильной/адаптивной навигации.
- Props:
  - `active` (`bool`).
- Пример:

```blade
<x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
    Profile
</x-responsive-nav-link>
```

### `secondary-button`
- Файл: `secondary-button.blade.php`
- Назначение: вторичная светлая кнопка.
- Props: нет (по умолчанию `type="button"`).
- Слот: текст/контент кнопки.
- Пример:

```blade
<x-secondary-button>
    Cancel
</x-secondary-button>
```

### `text-input`
- Файл: `text-input.blade.php`
- Назначение: универсальный input с базовыми стилями.
- Props:
  - `disabled` (`false` по умолчанию).
- Пример:

```blade
<x-text-input id="email" name="email" type="email" :value="old('email')" required autofocus />
```
