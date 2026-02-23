export interface NewsModalActionConfig {
    urlDataKey: keyof DOMStringMap;
    modalDataKey: keyof DOMStringMap;
    eventName: string;
    requestErrorMessage: string;
    openModalMode: 'always' | 'when_detail';
    buildDetail: (payload: unknown, modalName: string) => Record<string, unknown> | null;
}

export const newsEditActionConfig: NewsModalActionConfig = {
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

export const newsPreviewActionConfig: NewsModalActionConfig = {
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
