import type {NewsModalActionConfig} from './news-action-configs';

export class NewsModalAction {
    private readonly event: Event;
    private readonly link: HTMLElement | null;
    private readonly config: NewsModalActionConfig;
    private readonly csrfToken: string;

    constructor(event: Event, link: HTMLElement | null, config: NewsModalActionConfig) {
        this.event = event;
        this.link = link;
        this.config = config;
        this.csrfToken =
            document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') ?? '';
    }

    async run(): Promise<void> {
        if (!this.link) {
            return;
        }

        const modalName = this.link.dataset[this.config.modalDataKey];
        if (!modalName) {
            return;
        }

        this.event.preventDefault();
        if (this.link.dataset.loading === '1') {
            return;
        }

        const previousPointerEvents = this.lock();

        try {
            const detail = await this.buildDetail(modalName);

            if (detail && this.config.eventName) {
                this.dispatchEvent(this.config.eventName, detail);
            }

            if (this.config.openModalMode === 'always' || (this.config.openModalMode === 'when_detail' && detail)) {
                this.openModal(modalName);
            }
        } catch (_) {
            // Keep modal state unchanged; action can be retried without navigation.
        } finally {
            this.unlock(previousPointerEvents);
        }
    }

    private async buildDetail(modalName: string): Promise<Record<string, unknown> | null> {
        if (this.config.urlDataKey) {
            const requestUrl = this.link?.dataset[this.config.urlDataKey];
            if (!requestUrl) {
                return null;
            }

            const payload = await this.requestJson(requestUrl);
            return this.config.buildDetail(payload, modalName);
        }

        return this.config.buildDetail(null, modalName);
    }

    private lock(): string {
        if (!this.link) {
            return '';
        }

        this.link.dataset.loading = '1';
        const previousPointerEvents = this.link.style.pointerEvents;
        this.link.style.pointerEvents = 'none';

        return previousPointerEvents;
    }

    private unlock(previousPointerEvents: string): void {
        if (!this.link) {
            return;
        }

        this.link.dataset.loading = '0';
        this.link.style.pointerEvents = previousPointerEvents;
    }

    private async requestJson(url: string): Promise<unknown> {
        const response = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
            },
        });

        if (!response.ok) {
            throw new Error(this.config.requestErrorMessage ?? 'request failed');
        }

        return response.json();
    }

    private dispatchEvent(eventName: string, detail: Record<string, unknown>): void {
        window.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    private openModal(modalName: string): void {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: modalName }));
    }
}
