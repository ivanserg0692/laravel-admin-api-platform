import './bootstrap';

import {initFlowbite} from 'flowbite';



const reinitUi = (root?: HTMLElement): void => {
    initFlowbite();

    const alpine = (window as any).Alpine;
    if (root instanceof HTMLElement && alpine?.initTree) {
        alpine.initTree(root);
    }
};

document.addEventListener('livewire:init', () => {
    const livewire = (window as any).Livewire;

    if (!livewire?.hook) {
        return;
    }

    livewire.hook('morph.updated', ({el}: { el?: Element }) => {
        reinitUi(el instanceof HTMLElement ? el : undefined);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('[data-theme-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });

    reinitUi();
});
