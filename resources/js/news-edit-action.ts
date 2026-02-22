export class NewsEditAction {
    private readonly link: HTMLElement | null;
    private readonly event: Event;
    private readonly url: string;
    private readonly modal: string;
    private readonly csrfToken: string;
    private readonly previousPointerEvents: string;

    constructor(link: HTMLElement | null, event: Event) {
        this.link = link;
        this.event = event;
        this.url = link?.dataset.editInitUrl ?? '';
        this.modal = link?.dataset.editModal ?? '';
        this.csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        this.previousPointerEvents = link?.style.pointerEvents ?? '';
    }

    static async handle(event: Event, link: HTMLElement | null): Promise<void> {
        const action = new NewsEditAction(link, event);
        await action.run();
    }

    private get isLoading(): boolean {
        return this.link?.dataset.loading === '1';
    }

    private openModal(): void {
        if (!this.modal) {
            return;
        }

        window.dispatchEvent(new CustomEvent('open-modal', { detail: this.modal }));
    }

    private lock(): void {
        if (!this.link) {
            return;
        }

        this.link.dataset.loading = '1';
        this.link.style.pointerEvents = 'none';
    }

    private unlock(): void {
        if (!this.link) {
            return;
        }

        this.link.dataset.loading = '0';
        this.link.style.pointerEvents = this.previousPointerEvents;
    }

    private async requestInit(): Promise<void> {
        const response = await fetch(this.url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
            },
        });

        if (!response.ok) {
            throw new Error('edit-init request failed');
        }

        await response.json();
    }

    private async run(): Promise<void> {
        if (!this.link || !this.url) {
            return;
        }

        this.event.preventDefault();
        this.openModal();

        if (this.isLoading) {
            return;
        }

        this.lock();

        try {
            await this.requestInit();
        } catch (_) {
            // Keep modal open; request can be retried without navigation.
        } finally {
            this.unlock();
        }
    }
}
