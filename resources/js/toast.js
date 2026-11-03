import { Toast } from 'bootstrap';

const variantTitles = {
    success: 'Berhasil',
    danger: 'Terjadi Kesalahan',
    warning: 'Perhatian',
    info: 'Informasi',
};

export function showToast(message, variant = 'success') {
    const container = document.getElementById('toast-container');

    if (! container) {
        return;
    }

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${variant} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.dataset.bsDelay = '6000';

    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex';

    const body = document.createElement('div');
    body.className = 'toast-body';

    const title = document.createElement('strong');
    title.className = 'd-block mb-1';
    title.textContent = variantTitles[variant] ?? variantTitles.info;

    const text = document.createElement('span');
    text.textContent = message;

    body.append(title, text);

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'btn-close btn-close-white me-2 m-auto';
    close.dataset.bsDismiss = 'toast';
    close.setAttribute('aria-label', 'Tutup');

    wrapper.append(body, close);
    toast.append(wrapper);
    container.append(toast);

    toast.addEventListener('hidden.bs.toast', () => toast.remove());

    Toast.getOrCreateInstance(toast).show();
}

export function initToasts() {
    document.querySelectorAll('#toast-container .toast').forEach((element) => {
        Toast.getOrCreateInstance(element).show();
    });
}
