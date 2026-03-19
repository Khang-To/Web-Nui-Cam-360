document.addEventListener("DOMContentLoaded", function () {
    const toasts = document.querySelectorAll(".toast-item");

    toasts.forEach((toast) => {

        setTimeout(() => {
            toast.classList.add("show");
        }, 100);

        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    });
});


/**
 * Hiển thị toast dùng cho AJAX (dùng chung toàn hệ thống admin)
 */
window.showToast = function (message, type = 'success') {

    const container = document.querySelector(".toast-container");

    if (!container) return;

    let icon = '';
    let className = '';

    switch (type) {
        case 'success':
            icon = 'bi-check-circle-fill';
            className = 'toast-success';
            break;
        case 'error':
            icon = 'bi-x-circle-fill';
            className = 'toast-error';
            break;
        case 'warning':
            icon = 'bi-exclamation-triangle-fill';
            className = 'toast-warning';
            break;
    }

    const toast = document.createElement("div");
    toast.className = `toast-item ${className}`;

    toast.innerHTML = `
        <i class="bi ${icon}"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    // trigger animation giống toast cũ
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 400);
    }, 3000);
};
