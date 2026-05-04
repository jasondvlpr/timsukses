/* BossGroupHub - Global Toast Notification System */

/**
 * Menampilkan pesan Toast premium
 * @param {string} message - Pesan yang ingin ditampilkan
 * @param {string} type - 'success' atau 'error'
 */
function showToast(message, type = 'success') {
    // Pastikan container ada di DOM
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    
    // Warna default jika Tailwind tidak terdeteksi
    const bgColor = type === 'success' ? '#10b981' : '#f43f5e';
    
    const icon = type === 'success' 
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

    toast.className = `toast-item`;
    toast.style.setProperty('--toast-bg', bgColor);
    toast.innerHTML = `
        <div style="flex-shrink: 0; background: rgba(255,255,255,0.2); padding: 6px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            ${icon}
        </div>
        <div style="font-weight: 700; font-size: 0.875rem; line-height: 1.25rem;">
            ${message}
        </div>
    `;

    container.appendChild(toast);

    // Otomatis hapus setelah 4.5 detik
    const timer = setTimeout(() => {
        removeToast(toast);
    }, 4500);

    // Hapus manual saat diklik
    toast.onclick = () => {
        clearTimeout(timer);
        removeToast(toast);
    };
}

/**
 * Menghapus elemen toast dengan animasi fadeOut
 * @param {HTMLElement} toast 
 */
function removeToast(toast) {
    if (!toast) return;
    toast.style.animation = 'toastFadeOut 0.4s ease-in forwards';
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 400);
}
