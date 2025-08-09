document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Add error message if not exists
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                        const errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'This field is required';
                        errorMsg.style.color = 'var(--danger-color)';
                        errorMsg.style.fontSize = '0.8rem';
                        errorMsg.style.marginTop = '0.25rem';
                        field.parentNode.insertBefore(errorMsg, field.nextSibling);
                    }
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.nextElementSibling;
                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                        errorMsg.remove();
                    }
                }
            });
            
            // Password confirmation validation
            const password = form.querySelector('#password');
            const confirmPassword = form.querySelector('#confirm_password');
            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    confirmPassword.classList.add('error');
                    
                    if (!confirmPassword.nextElementSibling || !confirmPassword.nextElementSibling.classList.contains('error-message')) {
                        const errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'Passwords do not match';
                        errorMsg.style.color = 'var(--danger-color)';
                        errorMsg.style.fontSize = '0.8rem';
                        errorMsg.style.marginTop = '0.25rem';
                        confirmPassword.parentNode.insertBefore(errorMsg, confirmPassword.nextSibling);
                    }
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });
    
    // Date picker initialization
    if (document.getElementById('start_date')) {
        flatpickr('#start_date', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            minDate: 'today'
        });
    }
    
    if (document.getElementById('end_date')) {
        flatpickr('#end_date', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            minDate: document.getElementById('start_date') ? document.getElementById('start_date').value : 'today'
        });
        
        // Update end date min date when start date changes
        if (document.getElementById('start_date')) {
            document.getElementById('start_date').addEventListener('change', function() {
                flatpickr('#end_date', {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    minDate: this.value
                });
            });
        }
    }
    
    // Initialize any charts
    if (document.getElementById('eventsChart')) {
        initEventsChart();
    }
    
    // Initialize any other page-specific JS
    if (document.querySelector('.calendar-container')) {
        initCalendar();
    }
});

function initEventsChart() {
    // This would be replaced with actual data fetching in a real application
    console.log('Initializing events chart');
    
    const ctx = document.getElementById('eventsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Events Created',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(0, 51, 102, 0.7)',
                borderColor: 'rgba(0, 51, 102, 1)',
                borderWidth: 1
            }, {
                label: 'Participants',
                data: [120, 190, 30, 50, 20, 30],
                backgroundColor: 'rgba(230, 166, 18, 0.7)',
                borderColor: 'rgba(230, 166, 18, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initCalendar() {
    // This would be replaced with FullCalendar initialization in a real application
    console.log('Initializing calendar');
}