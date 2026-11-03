import { showToast } from './toast';

const MAX_AGE_MS = 7 * 24 * 60 * 60 * 1000;

export function initAssetDrafts() {
    clearSubmittedDraft();

    const form = document.querySelector('[data-draft-form]');

    if (! form) {
        return;
    }

    const key = form.dataset.draftKey;
    const statusTarget = form.querySelector('[data-draft-status]');
    const clearButton = form.querySelector('[data-draft-clear-button]');

    if (! key || ! window.localStorage) {
        return;
    }

    const fields = Array.from(form.elements).filter((field) => {
        if (! field.name || field.dataset.noDraft !== undefined) {
            return false;
        }

        return ! ['_token', '_method', 'file'].includes(field.name) && field.type !== 'file';
    });

    const readDraft = () => {
        try {
            return JSON.parse(window.localStorage.getItem(key) ?? 'null');
        } catch {
            return null;
        }
    };

    const saveDraft = () => {
        const values = {};

        fields.forEach((field) => {
            values[field.name] = field.value;
        });

        window.localStorage.setItem(key, JSON.stringify({
            values,
            savedAt: Date.now(),
        }));

        if (statusTarget) {
            statusTarget.textContent = 'Draft tersimpan otomatis.';
        }
    };

    const restoreDraft = () => {
        const draft = readDraft();

        if (! draft || ! draft.values) {
            return;
        }

        if (! draft.savedAt || Date.now() - draft.savedAt > MAX_AGE_MS) {
            window.localStorage.removeItem(key);
            return;
        }

        fields.forEach((field) => {
            if (Object.prototype.hasOwnProperty.call(draft.values, field.name)) {
                field.value = draft.values[field.name];
            }
        });

        if (clearButton) {
            clearButton.classList.remove('d-none');
        }

        if (statusTarget) {
            statusTarget.textContent = 'Draft dipulihkan dari penyimpanan lokal.';
        }

        showToast('Draft form berhasil dipulihkan. Silakan periksa kembali data Anda.', 'info');
    };

    let timer = null;
    const scheduleSave = () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(saveDraft, 500);
    };

    form.addEventListener('input', scheduleSave);
    form.addEventListener('change', scheduleSave);

    if (clearButton) {
        clearButton.addEventListener('click', () => {
            window.localStorage.removeItem(key);
            clearButton.classList.add('d-none');

            if (statusTarget) {
                statusTarget.textContent = 'Draft dihapus.';
            }

            showToast('Draft form berhasil dihapus.', 'info');
        });
    }

    restoreDraft();
}

function clearSubmittedDraft() {
    const key = document.body.dataset.clearDraft;

    if (key && window.localStorage) {
        window.localStorage.removeItem(key);
    }
}
