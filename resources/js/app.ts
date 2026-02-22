import './bootstrap';
import { NewsEditAction } from './news-edit-action';

import Alpine from 'alpinejs';
import 'flowbite';

window.Alpine = Alpine;

Alpine.start();

window.App = window.App || {};
window.App.UI = window.App.UI || {};
window.App.UI.NewsActions = window.App.UI.NewsActions || {};

window.App.UI.NewsActions.editInit = (event, link) => NewsEditAction.handle(event, link);
window.App.UI.NewsActions.previewInit = (event, link) => NewsEditAction.handlePreview(event, link);
window.newsEditInit = window.App.UI.NewsActions.editInit;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('[data-theme-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
});
