// Modern Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeButton = document.querySelector('.mobile-menu-close');
    const overlay = document.querySelector('.mobile-menu-overlay');

    // Open menu function
    function openMobileMenu() {
        toggle?.classList.add('active');
        mobileMenu?.classList.add('active');
        overlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close menu function (global)
    window.closeMobileMenu = function () {
        toggle?.classList.remove('active');
        mobileMenu?.classList.remove('active');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Toggle menu on button click
    if (toggle && mobileMenu) {
        toggle.addEventListener('click', function () {
            if (mobileMenu.classList.contains('active')) {
                window.closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        // Close button
        if (closeButton) {
            closeButton.addEventListener('click', window.closeMobileMenu);
        }

        // Close on overlay click
        if (overlay) {
            overlay.addEventListener('click', window.closeMobileMenu);
        }

        // Close on ESC key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mobileMenu.classList.contains('active')) {
                window.closeMobileMenu();
            }
        });
    }

    // Initialize Lucide icons when menu opens
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === 'class') {
                if (mobileMenu.classList.contains('active')) {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            }
        });
    });

    if (mobileMenu) {
        observer.observe(mobileMenu, { attributes: true });
    }
});
