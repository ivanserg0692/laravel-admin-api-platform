import './bootstrap';
import {NewsModalAction} from './UI/Modal/news-edit-action';
import {
    newsDeleteActionConfig,
    newsEditActionConfig,
    newsPreviewActionConfig,
} from './UI/Modal/news-action-configs';

import Alpine from 'alpinejs';
import 'flowbite';

window.Alpine = Alpine;

Alpine.start();

window.App = window.App || {};
window.App.UI = window.App.UI || {};
window.App.UI.NewsActions = window.App.UI.NewsActions || {};

window.App.UI.NewsActions.editInit = (event, link) =>
    new NewsModalAction(event, link, newsEditActionConfig).run();
window.App.UI.NewsActions.previewInit = (event, link) =>
    new NewsModalAction(event, link, newsPreviewActionConfig).run();
window.App.UI.NewsActions.deleteInit = (event, link) =>
    new NewsModalAction(event, link, newsDeleteActionConfig).run();
window.newsEditInit = window.App.UI.NewsActions.editInit;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('[data-theme-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
});
