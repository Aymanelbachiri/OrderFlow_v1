import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Counter animation
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);

    function updateCounter() {
        start += increment;
        if (start < target) {
            element.textContent = Math.floor(start).toLocaleString();
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target.toLocaleString();
        }
    }

    updateCounter();
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Add animation class
            entry.target.classList.add('animate-fade-in-up');

            // Handle counter animations
            if (entry.target.classList.contains('counter')) {
                const target = parseInt(entry.target.dataset.target);
                animateCounter(entry.target, target);
            }

            // Unobserve after animation
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Observe all elements with animation classes
    const animatedElements = document.querySelectorAll('.animate-fade-in-up, .counter');
    animatedElements.forEach(el => observer.observe(el));

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add parallax effect to hero section
    const hero = document.querySelector('.hero-parallax');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        });
    }

    // Add hover effects to cards
    const cards = document.querySelectorAll('.hover-lift');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Note: Mobile sidebar functionality is handled in admin layout

    // Mobile table responsiveness
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        const wrapper = table.closest('.overflow-x-auto');
        if (wrapper) {
            wrapper.classList.add('mobile-table');
        }
    });

    // Touch-friendly interactions - UNIVERSAL MOBILE BUTTON FIX
    if ('ontouchstart' in window) {
        // Add touch classes to interactive elements
        const interactiveElements = document.querySelectorAll('button, a, input, select, textarea');
        interactiveElements.forEach(el => {
            el.classList.add('touch-target');
            // Use CSS touch-action to prevent double-tap zoom without blocking clicks
            if (el.tagName === 'BUTTON' || el.tagName === 'A') {
                el.style.touchAction = 'manipulation';
                // Ensure tap highlight is visible on iOS
                el.style.webkitTapHighlightColor = 'rgba(0, 0, 0, 0.1)';
            }
        });

        // Add visual touch feedback to all buttons (passive handlers only)
        const buttons = document.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"]');
        buttons.forEach(btn => {
            // Skip if button already has custom touch handlers
            if (btn.dataset.touchHandled) return;
            btn.dataset.touchHandled = 'true';

            // Store original styles
            const originalTransform = btn.style.transform || '';
            const originalOpacity = btn.style.opacity || '1';

            btn.addEventListener('touchstart', function() {
                this.style.opacity = '0.9';
                this.style.transform = originalTransform ? originalTransform + ' scale(0.98)' : 'scale(0.98)';
            }, { passive: true });

            btn.addEventListener('touchend', function() {
                this.style.opacity = originalOpacity;
                this.style.transform = originalTransform;
                // Don't trigger actions here - let the native click event handle it
            }, { passive: true });

            btn.addEventListener('touchcancel', function() {
                this.style.opacity = originalOpacity;
                this.style.transform = originalTransform;
            }, { passive: true });
        });
    }

    // Responsive image handling
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        // Add loading state
        if (!img.complete) {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
        }
    });

    // Mobile form enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.classList.add('mobile-form');
        
        // Prevent zoom on input focus (iOS)
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.type !== 'range' && input.type !== 'checkbox' && input.type !== 'radio') {
                input.addEventListener('focus', function() {
                    if (window.innerWidth < 768) {
                        this.style.fontSize = '16px';
                    }
                });
            }
        });
    });

    // Auto-hide flash messages on mobile
    const flashMessages = document.querySelectorAll('[role="alert"]');
    flashMessages.forEach(message => {
        if (window.innerWidth < 768) {
            setTimeout(() => {
                message.style.transition = 'opacity 0.3s ease';
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 300);
            }, 5000);
        }
    });
});
