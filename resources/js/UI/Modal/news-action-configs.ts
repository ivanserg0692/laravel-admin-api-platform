export interface NewsModalActionConfig {
    urlDataKey?: keyof DOMStringMap;
    modalDataKey: keyof DOMStringMap;
    eventName?: string;
    requestErrorMessage?: string;
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
            title: isString(typed.data.title) ? typed.data.title : '',
            deleteUrl: isString(typed.data.delete_url) ? typed.data.delete_url : '',
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

export const newsDeleteActionConfig: NewsModalActionConfig = {
    urlDataKey: 'deleteInitUrl',
    modalDataKey: 'deleteModal',
    eventName: 'news-delete-values-loaded',
    requestErrorMessage: 'delete-init request failed',
    openModalMode: 'when_detail',
    buildDetail(payload, modalName) {
        const typed = payload as DeleteInitResponse;
        if (!typed.ok || !typed.data || !isString(typed.data.title) || !isString(typed.data.delete_url)) {
            return null;
        }

        return {
            modal: modalName,
            id: typed.data.id,
            title: typed.data.title,
            deleteUrl: typed.data.delete_url,
        };
    },
};

interface EditInitResponse {
    ok: boolean;
    data?: {
        id?: number | string;
        title?: string;
        delete_url?: string;
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

interface DeleteInitResponse {
    ok: boolean;
    data?: {
        id?: number | string;
        title?: string;
        delete_url?: string;
    };
}

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null;
}

function isString(value: unknown): value is string {
    return typeof value === 'string';
}
