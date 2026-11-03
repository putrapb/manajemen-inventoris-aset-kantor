import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import * as bootstrap from 'bootstrap';
import { initToasts } from './toast';
import { initAssetDrafts } from './asset-draft';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    initToasts();
    initAssetDrafts();
    initDeleteModals();
});

function initDeleteModals() {
    const modal = document.getElementById('deleteAssetModal');

    if (! modal) {
        return;
    }

    const form = modal.querySelector('form');
    const nameTarget = modal.querySelector('[data-delete-name]');

    document.querySelectorAll('[data-delete-asset]').forEach((button) => {
        button.addEventListener('click', () => {
            form.setAttribute('action', button.dataset.deleteUrl);
            nameTarget.textContent = button.dataset.assetName;
        });
    });
}
