// Gym Listing Page Enhancements
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling for anchor links
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

    // Auto-hide filters on mobile when scrolling
    let lastScrollTop = 0;
    const filtersSection = document.querySelector('[data-filters-section]');
    
    if (filtersSection) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down - hide filters
                filtersSection.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up - show filters
                filtersSection.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });
    }

    // Enhanced search input with suggestions
    const searchInput = document.querySelector('input[wire\\:model*="search"]');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // This would typically make an AJAX call to get search suggestions
                console.log('Searching for:', this.value);
            }, 300);
        });
    }

    // Location autocomplete enhancement
    const locationInput = document.querySelector('input[wire\\:model*="location"]');
    if (locationInput) {
        // Auto-detect location on focus if empty
        locationInput.addEventListener('focus', function() {
            if (!this.value) {
                detectUserLocation();
            }
        });
    }

    // Gym card hover effects
    document.querySelectorAll('[data-gym-card]').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
        });
    });

    // Price range slider enhancement
    const priceSlider = document.querySelector('input[wire\\:model*="priceRange"]');
    if (priceSlider) {
        priceSlider.addEventListener('input', function() {
            // Update the display values in real-time
            const value = this.value;
            const display = this.parentElement.querySelector('.price-display');
            if (display) {
                display.textContent = `$${value}`;
            }
        });
    }

    // Distance slider enhancement
    const distanceSlider = document.querySelector('input[wire\\:model*="distance"]');
    if (distanceSlider) {
        distanceSlider.addEventListener('input', function() {
            const value = this.value;
            const label = this.parentElement.querySelector('label');
            if (label) {
                label.textContent = `Distance (${value}km)`;
            }
        });
    }

    // Compare modal enhancements
    const compareModal = document.querySelector('[data-compare-modal]');
    if (compareModal) {
        // Close modal when clicking outside
        compareModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && compareModal.style.display === 'flex') {
                compareModal.style.display = 'none';
            }
        });
    }

    // Newsletter signup enhancement
    const newsletterForm = document.querySelector('[data-newsletter-form]');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Show success message
            showNotification('Successfully subscribed to newsletter!', 'success');
        });
    }

    // Loading states for buttons
    document.querySelectorAll('[data-loading]').forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.textContent;
            this.textContent = 'Loading...';
            this.disabled = true;
            
            // Re-enable after a delay (in real app, this would be after the action completes)
            setTimeout(() => {
                this.textContent = originalText;
                this.disabled = false;
            }, 2000);
        });
    });

    // Tooltip functionality for gym facilities
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.left = this.offsetLeft + 'px';
            tooltip.style.top = (this.offsetTop - 30) + 'px';
            
            document.body.appendChild(tooltip);
            this.tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this.tooltip) {
                this.tooltip.remove();
                this.tooltip = null;
            }
        });
    });
});

// Utility functions
function detectUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const { latitude, longitude } = position.coords;
                // In a real app, you'd reverse geocode these coordinates
                console.log('Location detected:', latitude, longitude);
            },
            function(error) {
                console.log('Location detection failed:', error.message);
            }
        );
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Intersection Observer for lazy loading images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Debounce utility function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export for use in other modules
window.GymListing = {
    showNotification,
    detectUserLocation,
    debounce
}; 