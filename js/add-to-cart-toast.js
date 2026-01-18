/**
 * Add to Cart Handler with Toast Notification
 * Shows success toast with "View Cart" button after adding product to cart
 */

(function () {
    'use strict';

    // Listen for WooCommerce add to cart events
    jQuery(document.body).on('added_to_cart', function (event, fragments, cart_hash, button) {
        // Get cart URL from WooCommerce params or use default
        const cartUrl = wc_add_to_cart_params.cart_url || window.location.origin + '/cart/';

        // Show toast with View Cart button
        showAddToCartToast(cartUrl);
    });

    /**
     * Show custom toast notification with View Cart button
     */
    function showAddToCartToast(cartUrl) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'toast toast-success';

        toast.style.cssText = `
            min-width: 320px;
            max-width: 400px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
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
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            flex-shrink: 0;
        `;
        iconEl.textContent = '✓';

        // Message container
        const contentEl = document.createElement('div');
        contentEl.style.cssText = `
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        `;

        // Message text
        const messageEl = document.createElement('div');
        messageEl.style.cssText = `
            line-height: 1.5;
            font-weight: 500;
        `;
        messageEl.textContent = 'Added to cart successfully!';

        // View Cart button
        const viewCartBtn = document.createElement('a');
        viewCartBtn.href = cartUrl;
        viewCartBtn.textContent = 'View Cart';
        viewCartBtn.style.cssText = `
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            align-self: flex-start;
            border: 1px solid rgba(255, 255, 255, 0.3);
        `;

        viewCartBtn.onmouseover = () => {
            viewCartBtn.style.background = 'rgba(255, 255, 255, 0.3)';
            viewCartBtn.style.transform = 'translateY(-1px)';
        };

        viewCartBtn.onmouseout = () => {
            viewCartBtn.style.background = 'rgba(255, 255, 255, 0.2)';
            viewCartBtn.style.transform = 'translateY(0)';
        };

        // Close button
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
        contentEl.appendChild(messageEl);
        contentEl.appendChild(viewCartBtn);

        toast.appendChild(iconEl);
        toast.appendChild(contentEl);
        toast.appendChild(closeBtn);

        // Add to container
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = createToastContainer();
        }

        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            });
        });

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            dismissToast(toast);
        }, 5000);
    }

    /**
     * Create toast container if it doesn't exist
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
     * Dismiss toast with animation
     */
    function dismissToast(toast) {
        if (!toast || !toast.parentNode) return;
        if (toast.dataset.dismissing === 'true') return;

        toast.dataset.dismissing = 'true';
        toast.style.transform = 'translateX(400px)';
        toast.style.opacity = '0';

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    }

})();
