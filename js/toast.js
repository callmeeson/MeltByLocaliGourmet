/**
 * Toast Notification System - Enhanced
 * Beautiful animated toast messages with smooth animations
 */

// Toast queue
let toastQueue = [];
let isShowingToast = false;

/**
 * Show toast notification
 * @param {string} message - The message to display
 * @param {string} type - success, error, warning, info
 * @param {number} duration - How long to show (ms), default 4000
 */
function showToast(message, type = 'info', duration = 4000) {
    toastQueue.push({ message, type, duration });

    if (!isShowingToast) {
        displayNextToast();
    }
}

/**
 * Display the next toast in queue
 */
function displayNextToast() {
    if (toastQueue.length === 0) {
        isShowingToast = false;
        return;
    }

    isShowingToast = true;
    const { message, type, duration } = toastQueue.shift();

    // Create toast element
    const toast = createToastElement(message, type);

    // Add to container
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = createToastContainer();
    }

    container.appendChild(toast);

    // Animate in with smooth transition
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });
    });

    // Auto dismiss
    setTimeout(() => {
        dismissToast(toast);
    }, duration);
}

/**
 * Create toast container
 */
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.style.cssText = `
		position: fixed;
		top: 20px;
		right: 20px;
		z-index: 99999;
		display: flex;
		flex-direction: column;
		gap: 12px;
		pointer-events: none;
		max-width: 400px;
	`;
    document.body.appendChild(container);
    return container;
}

/**
 * Create toast element
 */
function createToastElement(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;

    // Type configurations
    const config = {
        success: {
            icon: '✓',
            bgColor: 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
            iconBg: 'rgba(255, 255, 255, 0.2)'
        },
        error: {
            icon: '✕',
            bgColor: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            iconBg: 'rgba(255, 255, 255, 0.2)'
        },
        warning: {
            icon: '⚠',
            bgColor: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            iconBg: 'rgba(255, 255, 255, 0.2)'
        },
        info: {
            icon: 'ℹ',
            bgColor: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
            iconBg: 'rgba(255, 255, 255, 0.2)'
        }
    };

    const { icon, bgColor, iconBg } = config[type] || config.info;

    toast.style.cssText = `
		min-width: 300px;
		max-width: 400px;
		background: ${bgColor};
		color: white;
		padding: 16px 20px;
		border-radius: 12px;
		box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2), 0 6px 10px rgba(0, 0, 0, 0.15);
		display: flex;
		align-items: center;
		gap: 12px;
		font-family: var(--font-body), -apple-system, BlinkMacSystemFont, sans-serif;
		font-size: 14px;
		transform: translateX(400px);
		opacity: 0;
		transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
		pointer-events: all;
		position: relative;
	`;

    // Icon
    const iconEl = document.createElement('div');
    iconEl.style.cssText = `
		width: 32px;
		height: 32px;
		background: ${iconBg};
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		font-weight: bold;
		flex-shrink: 0;
	`;
    iconEl.textContent = icon;

    // Message
    const messageEl = document.createElement('div');
    messageEl.style.cssText = `
		flex: 1;
		line-height: 1.5;
		word-break: break-word;
	`;
    messageEl.textContent = message;

    // Close button (single, clean design)
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '×';
    closeBtn.setAttribute('aria-label', 'Close notification');
    closeBtn.style.cssText = `
		background: transparent;
		border: none;
		color: white;
		width: 28px;
		height: 28px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		font-size: 24px;
		font-weight: 300;
		line-height: 1;
		flex-shrink: 0;
		transition: all 0.2s ease;
		padding: 0;
		margin: 0;
		opacity: 0.8;
	`;

    closeBtn.onmouseover = () => {
        closeBtn.style.background = 'rgba(255, 255, 255, 0.2)';
        closeBtn.style.opacity = '1';
        closeBtn.style.transform = 'scale(1.1)';
    };

    closeBtn.onmouseout = () => {
        closeBtn.style.background = 'transparent';
        closeBtn.style.opacity = '0.8';
        closeBtn.style.transform = 'scale(1)';
    };

    closeBtn.onclick = (e) => {
        e.stopPropagation();
        dismissToast(toast);
    };

    // Assemble
    toast.appendChild(iconEl);
    toast.appendChild(messageEl);
    toast.appendChild(closeBtn);

    return toast;
}

/**
 * Dismiss toast with smooth animation
 */
function dismissToast(toast) {
    if (!toast || !toast.parentNode) return;

    // Prevent double dismissal
    if (toast.dataset.dismissing === 'true') return;
    toast.dataset.dismissing = 'true';

    // Smooth slide out
    toast.style.transform = 'translateX(400px)';
    toast.style.opacity = '0';

    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }

        // Show next toast after a brief delay
        setTimeout(() => {
            displayNextToast();
        }, 100);
    }, 400);
}

/**
 * Convenience functions
 */
function showSuccessToast(message, duration) {
    showToast(message, 'success', duration);
}

function showErrorToast(message, duration) {
    showToast(message, 'error', duration);
}

function showWarningToast(message, duration) {
    showToast(message, 'warning', duration);
}

function showInfoToast(message, duration) {
    showToast(message, 'info', duration);
}

// Make functions available globally
window.showToast = showToast;
window.showSuccessToast = showSuccessToast;
window.showErrorToast = showErrorToast;
window.showWarningToast = showWarningToast;
window.showInfoToast = showInfoToast;
