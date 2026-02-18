# Navigation Responsive Guide

This note explains how to tell which parts of `navigation.blade.php` are desktop-only or mobile-only.

## Tailwind Rule: Mobile-First

Tailwind classes without a breakpoint prefix apply to all screen sizes by default.

Breakpoint-prefixed classes (like `sm:*`) apply only from that breakpoint and up.

- `sm` means `min-width: 640px`
- So `sm:*` affects screens `>= 640px`

## Why `hidden sm:flex` Is Desktop-Only

`hidden sm:flex` combines two rules:

1. `hidden` -> hide element by default (`display: none`)
2. `sm:flex` -> from `640px` and up, show it as `display: flex`

Result:

- `< 640px` (mobile): hidden
- `>= 640px` (sm and larger): visible

That is why this is treated as desktop/tablet-only in practice.

## Why `sm:hidden` Is Mobile-Only

`sm:hidden` means:

- from `640px` and up, hide the element
- below `640px`, this rule does not apply

If the element is visible by default, then:

- `< 640px` (mobile): visible
- `>= 640px` (sm and larger): hidden

That is why this pattern is treated as mobile-only.

## Block Map in `navigation.blade.php`

Use this as a direct map of what belongs to desktop vs mobile.

1. `div.hidden ... sm:flex` (navigation links container, around line 7)  
   Type: Desktop (`sm+`)  
   Why: hidden by default, visible from `sm`.

2. `div.hidden sm:flex ...` (settings dropdown container, around line 15)  
   Type: Desktop (`sm+`)  
   Why: hidden on mobile, shown from `sm`.

3. `div... sm:hidden` (hamburger container, around line 49)  
   Type: Mobile (`<640px`)  
   Why: visible by default, hidden from `sm`.

4. `div ... class="hidden sm:hidden"` with `:class="{'block': open, 'hidden': ! open}"` (responsive menu, around line 61)  
   Type: Mobile (`<640px`)  
   Why: always hidden on `sm+`, and on mobile it is toggled by `open`.

5. `x-nav-link` inside the desktop container (around line 8)  
   Type: Desktop  
   Why: it lives inside a desktop-only wrapper.

6. `x-responsive-nav-link` items (around lines 63, 76, 84)  
   Type: Mobile  
   Why: they live inside the responsive mobile menu block.

## Quick Reading Pattern

- `hidden sm:flex` -> hidden on mobile, visible on `sm+`
- `sm:hidden` -> visible on mobile, hidden on `sm+`
