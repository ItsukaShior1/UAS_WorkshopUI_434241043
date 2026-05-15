// Bookify Laravel JavaScript
// Simplified for Laravel Blade templates

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.getElementById('menuToggle');
        
        if (sidebar && menuToggle && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        }
    });

    // Set active nav based on current route
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        if (item.href && window.location.pathname.startsWith(item.href.replace(window.location.origin, ''))) {
            item.classList.add('active');
        }
    });

    // Format currency helper
    window.formatCurrency = function(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(amount);
    };

    // Format date helper
    window.formatDate = function(date) {
        return new Date(date).toLocaleDateString('id-ID');
    };
});

// Modal functions for future use
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Alert helper
function showAlert(message, type = 'info') {
    alert(message);
}
