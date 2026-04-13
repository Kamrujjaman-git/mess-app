/** Session success flash: fade out and remove after `data-dismiss-ms` (default 4500). */
function initAutoDismissFlash() {
    const el = document.getElementById('app-flash-success');
    if (!el) {
        return;
    }

    const ms = Number.parseInt(el.dataset.dismissMs ?? '4500', 10);
    const delay = Number.isFinite(ms) && ms > 0 ? ms : 4500;

    const dismiss = () => {
        el.classList.add('opacity-0', 'translate-y-0.5', 'pointer-events-none');
        window.setTimeout(() => {
            el.remove();
        }, 320);
    };

    window.setTimeout(dismiss, delay);
}

document.addEventListener('DOMContentLoaded', initAutoDismissFlash);
