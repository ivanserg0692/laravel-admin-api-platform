import './bootstrap';
import { NewsModalAction, type NewsModalActionConfig } from './news-edit-action';

import Alpine from 'alpinejs';
import 'flowbite';

window.Alpine = Alpine;

Alpine.start();

window.App = window.App || {};
window.App.UI = window.App.UI || {};
window.App.UI.NewsActions = window.App.UI.NewsActions || {};

const newsEditActionConfig: NewsModalActionConfig = {
    urlDataKey: 'editInitUrl',
    modalDataKey: 'editModal',
    eventName: 'news-edit-values-loaded',
    requestErrorMessage: 'edit-init request failed',
    openModalMode: 'always',
    buildDetail(payload, modalName) {
        const typed = payload as EditInitResponse;
        if (!typed.ok || !typed.data || !isRecord(typed.data.values)) {
            return null;
        }

        return {
            modal: modalName,
            id: typed.data.id,
            values: typed.data.values,
        };
    },
};

const newsPreviewActionConfig: NewsModalActionConfig = {
    urlDataKey: 'previewInitUrl',
    modalDataKey: 'previewModal',
    eventName: 'news-preview-loaded',
    requestErrorMessage: 'preview-init request failed',
    openModalMode: 'when_detail',
    buildDetail(payload, modalName) {
        const typed = payload as PreviewInitResponse;
        if (!typed.ok || !typed.data || !isRecord(typed.data.preview)) {
            return null;
        }

        return {
            modal: modalName,
            id: typed.data.id,
            preview: typed.data.preview,
        };
    },
};

window.App.UI.NewsActions.editInit = (event, link) =>
    new NewsModalAction(event, link, newsEditActionConfig).run();
window.App.UI.NewsActions.previewInit = (event, link) =>
    new NewsModalAction(event, link, newsPreviewActionConfig).run();
window.newsEditInit = window.App.UI.NewsActions.editInit;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('[data-theme-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
});

interface EditInitResponse {
    ok: boolean;
    data?: {
        id?: number | string;
        values?: Record<string, unknown>;
    };
}

interface PreviewInitResponse {
    ok: boolean;
    data?: {
        id?: number | string;
        preview?: Record<string, unknown>;
    };
}

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null;
}
