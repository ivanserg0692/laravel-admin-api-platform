export class NewsEditAction {
    private readonly link: HTMLElement | null;
    private readonly event: Event;
    private readonly url: string;
    private readonly modal: string;
    private readonly csrfToken: string;
    private readonly previousPointerEvents: string;
    private static readonly VALUES_LOADED_EVENT = 'news-edit-values-loaded';
    private static readonly PREVIEW_LOADED_EVENT = 'news-preview-loaded';

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

    static async handlePreview(event: Event, link: HTMLElement | null): Promise<void> {
        if (!link) {
            return;
        }

        const previewInitUrl = link.dataset.previewInitUrl;
        const previewModal = link.dataset.previewModal;

        if (!previewInitUrl || !previewModal) {
            return;
        }

        event.preventDefault();

        if (link.dataset.loading === '1') {
            return;
        }

        link.dataset.loading = '1';
        const previousPointerEvents = link.style.pointerEvents;
        link.style.pointerEvents = 'none';

        const csrfToken =
            document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content') ?? '';

        try {
            const response = await fetch(previewInitUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) {
                throw new Error('preview-init request failed');
            }

            const payload = (await response.json()) as PreviewInitResponse;
            if (payload.ok && payload.data && isRecord(payload.data.preview)) {
                window.dispatchEvent(
                    new CustomEvent(NewsEditAction.PREVIEW_LOADED_EVENT, {
                        detail: {
                            modal: previewModal,
                            id: payload.data.id,
                            preview: payload.data.preview,
                        },
                    }),
                );
                window.dispatchEvent(new CustomEvent('open-modal', { detail: previewModal }));
            }
        } catch (_) {
            // Keep current state; preview can be retried without navigation.
        } finally {
            link.dataset.loading = '0';
            link.style.pointerEvents = previousPointerEvents;
        }
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

    private async requestInit(): Promise<EditInitResponse> {
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

        return (await response.json()) as EditInitResponse;
    }

    private dispatchLoadedValues(payload: EditInitResponse): void {
        if (!payload.ok || !payload.data || !isRecord(payload.data.values)) {
            return;
        }

        window.dispatchEvent(
            new CustomEvent(NewsEditAction.VALUES_LOADED_EVENT, {
                detail: {
                    modal: this.modal,
                    id: payload.data.id,
                    values: payload.data.values,
                },
            }),
        );
    }

    private async run(): Promise<void> {
        if (!this.link || !this.url) {
            return;
        }

        this.event.preventDefault();

        if (this.isLoading) {
            return;
        }

        this.lock();

        try {
            const payload = await this.requestInit();
            this.dispatchLoadedValues(payload);
            this.openModal();
        } catch (_) {
            // Keep modal open; request can be retried without navigation.
        } finally {
            this.unlock();
        }
    }
}

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
