/**
 * Main JavaScript for Melt Custom Theme
 */

(function () {
    'use strict';

    // Hero Slideshow
    let currentSlide = 0;
    let slideInterval;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicator');
    const heroTitle = document.getElementById('heroTitle');
    const heroSubtitle = document.getElementById('heroSubtitle');

    // Slide data from PHP
    const slideData = [
        { title: 'Artisan Patisserie', subtitle: 'Handcrafted confections made with premium ingredients and timeless techniques' },
        { title: 'Elegant Creations', subtitle: 'Exquisite cakes designed to elevate your special moments' },
        { title: 'Fresh & Delicious', subtitle: 'Premium ingredients sourced daily for the finest flavors' },
        { title: 'Layered Perfection', subtitle: 'Each layer crafted with precision and passion' }
    ];

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (indicators[i]) {
                indicators[i].classList.remove('active');
            }
        });

        if (slides[index]) {
            slides[index].classList.add('active');
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }

            // Update text content
            if (heroTitle && slideData[index]) {
                heroTitle.textContent = slideData[index].title;
            }
            if (heroSubtitle && slideData[index]) {
                heroSubtitle.textContent = slideData[index].subtitle;
            }
        }

        currentSlide = index;
    }

    window.nextSlide = function () {
        let next = (currentSlide + 1) % slides.length;
        showSlide(next);
        resetInterval();
    };

    window.prevSlide = function () {
        let prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
        resetInterval();
    };

    window.goToSlide = function (index) {
        showSlide(index);
        resetInterval();
    };

    function resetInterval() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
        slideInterval = setInterval(() => {
            window.nextSlide();
        }, 5000);
    }

    // Start slideshow
    if (slides.length > 1) {
        resetInterval();
    }

    // Header scroll behavior
    const header = document.querySelector('.site-header');
    const heroSection = document.querySelector('.hero-section');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        // Get hero section height, or default to 85vh if hero section doesn't exist
        let heroHeight = 0;
        if (heroSection) {
            heroHeight = heroSection.offsetHeight;
        } else {
            // Default to 85vh for pages without hero
            heroHeight = window.innerHeight * 0.85;
        }

        // Check if scrolled past hero section
        if (currentScroll > heroHeight - 100) { // Subtract 100px for smooth transition before end of hero
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });

    // Mobile menu toggle
    window.toggleMobileMenu = function () {
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.querySelector('.mobile-menu-icon');

        if (mobileMenu) {
            if (mobileMenu.style.display === 'none' || !mobileMenu.style.display) {
                mobileMenu.style.display = 'block';
                if (menuIcon) {
                    menuIcon.setAttribute('data-lucide', 'x');
                    lucide.createIcons();
                }
            } else {
                mobileMenu.style.display = 'none';
                if (menuIcon) {
                    menuIcon.setAttribute('data-lucide', 'menu');
                    lucide.createIcons();
                }
            }
        }
    };

    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in-section, .fade-in-item').forEach(element => {
        observer.observe(element);
    });

    // Add to cart functionality (for WooCommerce integration)
    window.addToCart = function (productId) {
        if (typeof meltAjax === 'undefined') {
            console.error('AJAX not configured');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'melt_update_cart');
        formData.append('nonce', meltAjax.nonce);
        formData.append('product_id', productId);
        formData.append('quantity', 1);

        fetch(meltAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.data.cart_count;
                        cartCount.style.display = data.data.cart_count > 0 ? 'flex' : 'none';
                    }
                    alert('Product added to cart!');
                } else {
                    alert('Error adding product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    };

    // Search modal placeholder
    window.openSearch = function () {
        alert('Search functionality - Connect to your search implementation');
    };

    // Locations modal placeholder
    window.openLocations = function () {
        alert('Locations - Connect to your locations page or modal');
    };

    // Auth modal placeholder
    window.openAuthModal = function () {
        // Redirect to WooCommerce my account page
        if (typeof wc_add_to_cart_params !== 'undefined') {
            window.location.href = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'myaccount');
        } else {
            window.location.href = '/my-account';
        }
    };

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();

        // Reinitialize icons when DOM changes (for dynamic content)
        const iconObserver = new MutationObserver(() => {
            lucide.createIcons();
        });

        iconObserver.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Play button hover effect
    document.querySelectorAll('.play-button').forEach(button => {
        button.addEventListener('mouseover', function () {
            const playIcon = this.querySelector('[data-lucide="play"]');
            if (playIcon) {
                playIcon.style.color = 'white';
            }
        });

        button.addEventListener('mouseout', function () {
            const playIcon = this.querySelector('[data-lucide="play"]');
            if (playIcon) {
                playIcon.style.color = 'var(--primary)';
            }
        });
    });

})();
