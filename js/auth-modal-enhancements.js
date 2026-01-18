/**
 * Password Reset and Remaining Attempts Enhancement
 * Extends auth-modal.js with password reset functionality and attempt warnings
 */

// Add password reset mode
let isPasswordResetMode = false;

// Show password reset form
function showPasswordResetForm() {
    isPasswordResetMode = true;
    isSignUpMode = false;

    const title = document.getElementById('authModalTitle');
    const subtitle = document.getElementById('authModalSubtitle');
    const submitBtn = document.getElementById('authSubmitBtn');
    const toggleSection = document.querySelector('.auth-toggle');
    const nameField = document.getElementById('nameField');
    const phoneField = document.getElementById('phoneField');
    const passwordField = document.getElementById('authPassword').parentElement;
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');

    // Update UI
    title.textContent = 'Reset Password';
    subtitle.textContent = 'Enter your email to receive reset instructions';
    submitBtn.textContent = 'Send Reset Link';

    // Hide unnecessary fields
    nameField.style.display = 'none';
    phoneField.style.display = 'none';
    passwordField.style.display = 'none';
    if (forgotPasswordLink) forgotPasswordLink.style.display = 'none';
    if (toggleSection) toggleSection.style.display = 'none';

    // Add back to login link
    if (!document.getElementById('backToLoginLink')) {
        const backLink = document.createElement('div');
        backLink.id = 'backToLoginLink';
        backLink.style.cssText = 'text-align: center; margin-top: 1rem; color: var(--muted-foreground); font-size: 0.875rem;';
        backLink.innerHTML = '<a href="#" onclick="backToLogin(); return false;" style="color: var(--primary); text-decoration: none; font-weight: 500;">← Back to Login</a>';
        document.getElementById('authForm').appendChild(backLink);
    }

    clearAuthErrors();
}

// Back to login
function backToLogin() {
    isPasswordResetMode = false;
    isSignUpMode = false;

    const passwordField = document.getElementById('authPassword').parentElement;
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const toggleSection = document.querySelector('.auth-toggle');
    const backLink = document.getElementById('backToLoginLink');

    // Show password field
    passwordField.style.display = 'block';
    if (forgotPasswordLink) forgotPasswordLink.style.display = 'block';
    if (toggleSection) toggleSection.style.display = 'block';
    if (backLink) backLink.remove();

    updateAuthModalUI();
    clearAuthErrors();
}

// Handle password reset submission
function handlePasswordResetSubmit(email) {
    const formData = new FormData();
    formData.append('action', 'melt_request_password_reset');
    formData.append('email', email);
    formData.append('nonce', meltAjax.nonce);

    return fetch(meltAjax.ajaxurl, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json());
}

// Show remaining attempts warning
function showRemainingAttemptsWarning(remaining) {
    // Remove existing warning
    const existingWarning = document.getElementById('attemptsWarning');
    if (existingWarning) {
        existingWarning.remove();
    }

    if (remaining === undefined || remaining > 2) {
        return; // Don't show warning if more than 2 attempts remaining
    }

    const warning = document.createElement('div');
    warning.id = 'attemptsWarning';
    warning.style.cssText = `
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        border-left: 4px solid #ef4444;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideIn 0.3s ease;
    `;

    let message = '';
    let icon = '⚠️';

    if (remaining === 0) {
        message = 'Account locked. Please try again in 15 minutes.';
        icon = '🔒';
    } else if (remaining === 1) {
        message = '<strong>Warning:</strong> Last attempt remaining before account lockout!';
        icon = '⚠️';
    } else if (remaining === 2) {
        message = `<strong>Caution:</strong> ${remaining} attempts remaining`;
        icon = '⚠️';
    }

    warning.innerHTML = `
        <span style="font-size: 1.25rem;">${icon}</span>
        <span style="color: #dc2626; font-size: 0.875rem; flex: 1;">${message}</span>
    `;

    // Insert before form
    const form = document.getElementById('authForm');
    form.insertBefore(warning, form.firstChild);
}

// Extend the original form submission handler
document.addEventListener('DOMContentLoaded', function () {
    const authForm = document.getElementById('authForm');

    if (authForm) {
        // Store original submit handler
        const originalSubmit = authForm.onsubmit;

        authForm.addEventListener('submit', function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();

            // Handle password reset mode
            if (isPasswordResetMode) {
                const email = document.getElementById('authEmail').value.trim();

                if (!email || !validateEmail(email)) {
                    showAuthError('email', 'Please enter a valid email address');
                    return;
                }

                const submitBtn = document.getElementById('authSubmitBtn');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;

                handlePasswordResetSubmit(email)
                    .then(data => {
                        if (data.success) {
                            if (typeof showSuccessToast === 'function') {
                                showSuccessToast(data.data.message);
                            }
                            setTimeout(() => {
                                backToLogin();
                            }, 2000);
                        } else {
                            if (typeof showErrorToast === 'function') {
                                showErrorToast(data.data.message);
                            }
                        }
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof showErrorToast === 'function') {
                            showErrorToast('An error occurred. Please try again.');
                        }
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });

                return false;
            }

            // Continue with normal login/signup
            if (!validateAuthForm()) {
                return;
            }

            const formData = new FormData(authForm);
            formData.append('action', isSignUpMode ? 'melt_register' : 'melt_login');
            formData.append('nonce', meltAjax.nonce);

            const submitBtn = document.getElementById('authSubmitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Please wait...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';

            fetch(meltAjax.ajaxurl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showSuccessToast === 'function') {
                            showSuccessToast(data.data.message || 'Success!');
                        }
                        setTimeout(() => {
                            if (data.data.redirect) {
                                window.location.href = data.data.redirect;
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                    } else {
                        // Show remaining attempts warning
                        if (data.data.remaining_attempts !== undefined) {
                            showRemainingAttemptsWarning(data.data.remaining_attempts);
                        }

                        if (typeof showErrorToast === 'function') {
                            showErrorToast(data.data.message || 'An error occurred. Please try again.');
                        } else {
                            showFormError(data.data.message || 'An error occurred. Please try again.');
                        }
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof showErrorToast === 'function') {
                        showErrorToast('An error occurred. Please try again.');
                    } else {
                        showFormError('An error occurred. Please try again.');
                    }
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                });
        }, true); // Use capture to override existing handler
    }
});

// Add CSS animation
const authEnhancementStyle = document.createElement('style');
authEnhancementStyle.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(authEnhancementStyle);

