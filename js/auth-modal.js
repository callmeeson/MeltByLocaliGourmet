/**
 * Authentication Modal JavaScript
 * Handles login and registration functionality with validation
 */

let isSignUpMode = false;

// Password strength requirements
const passwordRequirements = {
    minLength: 8,
    hasUpperCase: false,
    hasLowerCase: false,
    hasNumber: false,
    hasSpecial: false
};

// Open auth modal
window.openAuthModal = function (mode = 'login') {
    const modal = document.getElementById('authModal');
    if (!modal) return;

    // Set mode
    if (mode === 'register' || mode === 'signup') {
        isSignUpMode = true;
    } else {
        isSignUpMode = false;
    }

    // Update UI
    updateAuthModalUI();

    // Show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Close auth modal
window.closeAuthModal = function () {
    const modal = document.getElementById('authModal');
    if (!modal) return;

    modal.style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset form
    document.getElementById('authForm').reset();
    clearAuthErrors();
    hidePasswordStrength();
}

// Toggle between login and signup
window.toggleAuthMode = function () {
    isSignUpMode = !isSignUpMode;
    updateAuthModalUI();
    clearAuthErrors();
    hidePasswordStrength();
}

// Update modal UI based on mode
window.updateAuthModalUI = function () {
    const title = document.getElementById('authModalTitle');
    const subtitle = document.getElementById('authModalSubtitle');
    const submitBtn = document.getElementById('authSubmitBtn');
    const toggleText = document.getElementById('toggleText');
    const toggleBtn = document.getElementById('toggleModeBtn');
    const nameField = document.getElementById('nameField');
    const phoneField = document.getElementById('phoneField');

    if (isSignUpMode) {
        // Sign Up Mode
        title.textContent = 'Create Account';
        subtitle.textContent = 'Sign up to discover artisan excellence';
        submitBtn.textContent = 'Create Account';
        toggleText.textContent = 'Already have an account?';
        toggleBtn.textContent = 'Sign In';
        nameField.style.display = 'block';
        phoneField.style.display = 'block';

        // Make fields required
        document.getElementById('authName').required = true;
        document.getElementById('authPhone').required = true;
    } else {
        // Login Mode
        title.textContent = 'Welcome Back';
        subtitle.textContent = 'Sign in to continue your order';
        submitBtn.textContent = 'Sign In';
        toggleText.textContent = "Don't have an account?";
        toggleBtn.textContent = 'Sign Up';
        nameField.style.display = 'none';
        phoneField.style.display = 'none';

        // Remove required
        document.getElementById('authName').required = false;
        document.getElementById('authPhone').required = false;
    }
}

// Toggle password visibility
window.toggleAuthPasswordVisibility = function () {
    const passwordInput = document.getElementById('authPassword');
    const toggleBtn = document.getElementById('togglePasswordBtn');
    if (!passwordInput || !toggleBtn) return;

    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    toggleBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    const eyeIcon = toggleBtn.querySelector('[data-icon="eye"]');
    const eyeOffIcon = toggleBtn.querySelector('[data-icon="eye-off"]');
    if (eyeIcon && eyeOffIcon) {
        eyeIcon.style.display = isHidden ? 'none' : 'inline';
        eyeOffIcon.style.display = isHidden ? 'inline' : 'none';
    }
};

// Clear all errors
function clearAuthErrors() {
    const errors = document.querySelectorAll('.auth-error');
    errors.forEach(error => {
        error.style.display = 'none';
        error.textContent = '';
    });

    const formError = document.getElementById('authFormError');
    if (formError) {
        formError.style.display = 'none';
        formError.querySelector('p').textContent = '';
    }

    // Clear field error states
    const inputs = document.querySelectorAll('#authForm input');
    inputs.forEach(input => {
        input.style.borderColor = 'rgba(184, 134, 11, 0.3)';
    });
}

// Show error
function showAuthError(field, message) {
    const errorElement = document.getElementById(field + 'Error');
    const inputElement = document.getElementById('auth' + field.charAt(0).toUpperCase() + field.slice(1));

    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    if (inputElement) {
        inputElement.style.borderColor = 'var(--destructive)';
    }
}

// Show success
function showFieldSuccess(field) {
    const inputElement = document.getElementById('auth' + field.charAt(0).toUpperCase() + field.slice(1));
    if (inputElement) {
        inputElement.style.borderColor = '#22c55e'; // Green
    }
}

// Show form error
function showFormError(message) {
    const formError = document.getElementById('authFormError');
    if (formError) {
        formError.querySelector('p').textContent = message;
        formError.style.display = 'block';
    }
}

// Validate email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate phone
function validatePhone(phone) {
    const cleaned = phone.replace(/[\s\-\(\)]/g, '');
    const re = /^[+]?[0-9]{10,15}$/;
    return re.test(cleaned);
}

// Validate name
function validateName(name) {
    // At least 2 characters, only letters and spaces
    const re = /^[a-zA-Z\s]{2,}$/;
    return re.test(name.trim());
}

// Calculate password strength
function calculatePasswordStrength(password) {
    let strength = 0;
    const checks = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    if (checks.length) strength += 20;
    if (checks.uppercase) strength += 20;
    if (checks.lowercase) strength += 20;
    if (checks.number) strength += 20;
    if (checks.special) strength += 20;

    return {
        score: strength,
        checks: checks
    };
}

// Update password strength meter
function updatePasswordStrength() {
    const password = document.getElementById('authPassword').value;
    const strengthMeter = document.getElementById('passwordStrengthMeter');

    if (!password || !isSignUpMode) {
        hidePasswordStrength();
        return;
    }

    const result = calculatePasswordStrength(password);
    strengthMeter.style.display = 'block';

    const bar = strengthMeter.querySelector('.strength-bar-fill');
    const text = strengthMeter.querySelector('.strength-text');
    const requirements = strengthMeter.querySelector('.password-requirements');

    // Update bar
    bar.style.width = result.score + '%';

    // Update color and text
    let strengthText = '';
    let color = '';

    if (result.score < 40) {
        strengthText = 'Weak';
        color = '#ef4444';
    } else if (result.score < 60) {
        strengthText = 'Fair';
        color = '#f97316';
    } else if (result.score < 80) {
        strengthText = 'Good';
        color = '#eab308';
    } else {
        strengthText = 'Strong';
        color = '#22c55e';
    }

    bar.style.backgroundColor = color;
    text.textContent = strengthText;
    text.style.color = color;

    // Update requirements checklist
    const checks = result.checks;
    requirements.innerHTML = `
		<div style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.5rem;">
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.25rem;">
				<div style="display: flex; align-items: center; gap: 0.25rem;">
					<span style="color: ${checks.length ? '#22c55e' : '#ef4444'};">${checks.length ? '✓' : '✗'}</span>
					<span>8+ characters</span>
				</div>
				<div style="display: flex; align-items: center; gap: 0.25rem;">
					<span style="color: ${checks.uppercase ? '#22c55e' : '#ef4444'};">${checks.uppercase ? '✓' : '✗'}</span>
					<span>Uppercase letter</span>
				</div>
				<div style="display: flex; align-items: center; gap: 0.25rem;">
					<span style="color: ${checks.lowercase ? '#22c55e' : '#ef4444'};">${checks.lowercase ? '✓' : '✗'}</span>
					<span>Lowercase letter</span>
				</div>
				<div style="display: flex; align-items: center; gap: 0.25rem;">
					<span style="color: ${checks.number ? '#22c55e' : '#ef4444'};">${checks.number ? '✓' : '✗'}</span>
					<span>Number</span>
				</div>
			</div>
		</div>
	`;
}

// Hide password strength meter
function hidePasswordStrength() {
    const strengthMeter = document.getElementById('passwordStrengthMeter');
    if (strengthMeter) {
        strengthMeter.style.display = 'none';
    }
}

// Real-time field validation
function validateField(fieldName, value) {
    let isValid = true;
    let errorMessage = '';

    clearFieldError(fieldName);

    switch (fieldName) {
        case 'name':
            if (!value.trim()) {
                errorMessage = 'Name is required';
                isValid = false;
            } else if (!validateName(value)) {
                errorMessage = 'Please enter a valid name (letters only, min 2 characters)';
                isValid = false;
            }
            break;

        case 'email':
            if (!value.trim()) {
                errorMessage = 'Email is required';
                isValid = false;
            } else if (!validateEmail(value)) {
                errorMessage = 'Please enter a valid email address';
                isValid = false;
            }
            break;

        case 'phone':
            if (!value.trim()) {
                errorMessage = 'Phone number is required';
                isValid = false;
            } else if (!validatePhone(value)) {
                errorMessage = 'Please enter a valid phone number (10-15 digits)';
                isValid = false;
            }
            break;

        case 'password':
            if (!value) {
                errorMessage = 'Password is required';
                isValid = false;
            } else if (isSignUpMode && value.length < 8) {
                errorMessage = 'Password must be at least 8 characters';
                isValid = false;
            }
            break;
    }

    if (!isValid) {
        showAuthError(fieldName, errorMessage);
    } else if (value.trim()) {
        showFieldSuccess(fieldName);
    }

    return isValid;
}

// Clear field error
function clearFieldError(fieldName) {
    const errorElement = document.getElementById(fieldName + 'Error');
    const inputElement = document.getElementById('auth' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1));

    if (errorElement) {
        errorElement.style.display = 'none';
        errorElement.textContent = '';
    }

    if (inputElement) {
        inputElement.style.borderColor = 'rgba(184, 134, 11, 0.3)';
    }
}

// Validate form
function validateAuthForm() {
    clearAuthErrors();
    let isValid = true;

    const name = document.getElementById('authName').value.trim();
    const email = document.getElementById('authEmail').value.trim();
    const phone = document.getElementById('authPhone').value.trim();
    const password = document.getElementById('authPassword').value;

    // Validate name (sign up only)
    if (isSignUpMode && !validateField('name', name)) {
        isValid = false;
    }

    // Validate email
    if (!validateField('email', email)) {
        isValid = false;
    }

    // Validate phone (sign up only)
    if (isSignUpMode && !validateField('phone', phone)) {
        isValid = false;
    }

    // Validate password
    if (!validateField('password', password)) {
        isValid = false;
    }

    // Check password strength for signup
    if (isSignUpMode && password) {
        const strength = calculatePasswordStrength(password);
        if (strength.score < 60) {
            showAuthError('password', 'Please choose a stronger password');
            isValid = false;
        }
    }

    return isValid;
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function () {
    const authForm = document.getElementById('authForm');

    if (authForm) {
        authForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate
            if (!validateAuthForm()) {
                return;
            }

            // Get form data
            const formData = new FormData(authForm);
            formData.append('action', isSignUpMode ? 'melt_register' : 'melt_login');
            // Add correct nonce for auth actions
            formData.append('nonce', meltData.nonce);

            // Show loading state
            const submitBtn = document.getElementById('authSubmitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Please wait...';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';

            // Send AJAX request
            fetch(meltData.ajaxurl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success - show toast and reload
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
                        // Show error toast
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
        });
    }

    // Real-time validation on input
    const nameInput = document.getElementById('authName');
    const emailInput = document.getElementById('authEmail');
    const phoneInput = document.getElementById('authPhone');
    const passwordInput = document.getElementById('authPassword');

    if (nameInput) {
        nameInput.addEventListener('blur', function () {
            if (isSignUpMode) validateField('name', this.value);
        });
        nameInput.addEventListener('input', function () {
            if (this.value.trim()) clearFieldError('name');
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            validateField('email', this.value);
        });
        emailInput.addEventListener('input', function () {
            if (this.value.trim()) clearFieldError('email');
        });
    }

    if (phoneInput) {
        phoneInput.addEventListener('blur', function () {
            if (isSignUpMode) validateField('phone', this.value);
        });
        phoneInput.addEventListener('input', function () {
            if (this.value.trim()) clearFieldError('phone');
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            if (isSignUpMode) {
                updatePasswordStrength();
            }
            if (this.value) clearFieldError('password');
        });
        passwordInput.addEventListener('blur', function () {
            validateField('password', this.value);
        });
    }
});

// Close modal when clicking outside
window.addEventListener('click', function (event) {
    const modal = document.getElementById('authModal');
    if (event.target === modal) {
        closeAuthModal();
    }
});
