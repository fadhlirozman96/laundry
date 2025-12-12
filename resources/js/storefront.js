// Storefront JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Search Toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.getElementById('searchBar');
    
    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchBar.classList.toggle('active');
            if (searchBar.classList.contains('active')) {
                searchBar.querySelector('input').focus();
            }
        });
    }

    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            if (navMenu) {
                navMenu.classList.toggle('active');
            }
        });
    }

    // Add to Cart Functionality
    const addToCartButtons = document.querySelectorAll('.btn-add-cart, .btn-add-cart-large');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = document.getElementById('productQuantity') 
                ? parseInt(document.getElementById('productQuantity').value) 
                : 1;
            
            // TODO: Implement actual cart functionality
            // For now, just show a message
            alert('Product added to cart! (Cart functionality to be implemented)');
            
            // Update cart count (placeholder)
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                const currentCount = parseInt(cartCount.textContent) || 0;
                cartCount.textContent = currentCount + quantity;
            }
        });
    });

    // Image Zoom on Product Page
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.addEventListener('click', function() {
            // TODO: Implement lightbox/modal for image zoom
        });
    }

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Newsletter Form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            // TODO: Implement newsletter subscription
            alert('Thank you for subscribing!');
            this.reset();
        });
    }

    // Lazy Loading Images (if needed)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

