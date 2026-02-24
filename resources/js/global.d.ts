export {};

declare global {
    interface Window {
        App: {
            UI: {
                NewsActions: {
                    editInit: (event: Event, link: HTMLElement | null) => Promise<void>;
                    previewInit: (event: Event, link: HTMLElement | null) => Promise<void>;
                    deleteInit: (event: Event, link: HTMLElement | null) => Promise<void>;
                };
            };
        };
        newsEditInit: (event: Event, link: HTMLElement | null) => Promise<void>;
    }
}
