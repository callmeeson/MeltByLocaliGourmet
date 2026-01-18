/**
 * Auth Modal Loading Spinner Enhancement
 * Prevents double-clicking with immediate button disable
 */

// Add spinner CSS animation
const spinnerStyle = document.createElement('style');
spinnerStyle.textContent = `
    @keyframes auth-spinner {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .auth-loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: auth-spinner 0.8s linear infinite;
        margin-right: 0.5rem;
        vertical-align: middle;
    }
    
    .auth-btn-loading {
        pointer-events: none !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
    }
`;
document.head.appendChild(spinnerStyle);

// Override the form submission to use spinner
document.addEventListener('DOMContentLoaded', function () {
    const authForm = document.getElementById('authForm');
    if (!authForm) return;

    // Find the existing submit handler and enhance it
    authForm.addEventListener('submit', function (e) {
        const submitBtn = document.getElementById('authSubmitBtn');
        if (!submitBtn) return;

        // IMMEDIATE check if already loading - prevents double-click
        if (submitBtn.classList.contains('auth-btn-loading') || submitBtn.disabled) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        // Store original content
        if (!submitBtn.dataset.originalContent) {
            submitBtn.dataset.originalContent = submitBtn.innerHTML;
        }

        // Add loading state IMMEDIATELY (no setTimeout to prevent race condition)
        submitBtn.innerHTML = '<span class="auth-loading-spinner"></span> Processing...';
        submitBtn.classList.add('auth-btn-loading');
        submitBtn.disabled = true;
    }, true); // Use capture to run before other handlers
});

// Function to reset button state (can be called from other scripts)
function resetAuthButton() {
    const submitBtn = document.getElementById('authSubmitBtn');
    if (submitBtn && submitBtn.dataset.originalContent) {
        submitBtn.innerHTML = submitBtn.dataset.originalContent;
        submitBtn.classList.remove('auth-btn-loading');
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    }
}

// Make it globally available
window.resetAuthButton = resetAuthButton;
