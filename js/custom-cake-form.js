document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('custom-cake-form');
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    const nextBtns = document.querySelectorAll('.btn-next');
    const prevBtns = document.querySelectorAll('.btn-prev');
    const reviewContainer = document.getElementById('review-container');
    
    let currentStep = 0;

    // Next Button Click
    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    updateFormSteps();
                    updateProgressBar();
                    
                    // If moving to the review step (last step)
                    if (currentStep === steps.length - 1) {
                        populateReview();
                    }
                }
            }
        });
    });

    // Previous Button Click
    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                updateFormSteps();
                updateProgressBar();
            }
        });
    });

    function updateFormSteps() {
        steps.forEach((step, index) => {
            if (index === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        
        // Scroll to top of form
        const formContainer = document.querySelector('.custom-cake-container');
        if(formContainer) {
            formContainer.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function updateProgressBar() {
        progressSteps.forEach((step, index) => {
            if (index < currentStep + 1) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
            
            if (index < currentStep) {
                step.classList.add('completed');
            } else {
                step.classList.remove('completed');
            }
        });
    }

    function validateStep(stepIndex) {
        const currentStepEl = steps[stepIndex];
        const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = 'red';
                
                // Reset border color on input
                input.addEventListener('input', function() {
                    this.style.borderColor = '#ddd';
                }, { once: true });
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields.');
        }

        return isValid;
    }

    function populateReview() {
        reviewContainer.innerHTML = '';
        
        const formData = new FormData(form);
        const reviewData = {};
        
        // Map labels to inputs for display
        const labels = {
            'fullname': 'Full Name',
            'contact-number': 'Contact Number',
            'email': 'Email Address',
            'order-type': 'Order Type',
            'delivery-address': 'Delivery Address',
            'preferred-date': 'Preferred Date',
            'preferred-time': 'Preferred Time',
            'cake-flavor': 'Cake Flavor',
            'servings': 'Servings',
            'instructions': 'Other Instructions'
        };

        // Iterate through inputs to get values
        for (const [key, value] of formData.entries()) {
            if (value && labels[key]) {
                 // Handle file upload name
                if (value instanceof File && value.name) {
                     reviewData[labels[key]] = value.name;
                } else if (typeof value === 'string') {
                    reviewData[labels[key]] = value;
                }
            }
        }
        
        // Render Review Items
        for (const [label, value] of Object.entries(reviewData)) {
            const item = document.createElement('div');
            item.className = 'review-item';
            item.innerHTML = `
                <span class="review-label">${label}</span>
                <span class="review-value">${value}</span>
            `;
            reviewContainer.appendChild(item);
        }
    }
    
    // Toggle Delivery Address visibility based on Order Type
    const orderTypeSelect = document.querySelector('select[name="order-type"]');
    const deliveryAddressGroup = document.getElementById('delivery-address-group');
    const deliveryAddressInput = document.querySelector('input[name="delivery-address"]');
    
    if(orderTypeSelect) {
        orderTypeSelect.addEventListener('change', function() {
            if(this.value === 'delivery') {
                deliveryAddressGroup.classList.remove('hidden');
                deliveryAddressInput.setAttribute('required', 'required');
            } else {
                deliveryAddressGroup.classList.add('hidden');
                deliveryAddressInput.removeAttribute('required');
                deliveryAddressInput.value = '';
            }
        });
    }
});
