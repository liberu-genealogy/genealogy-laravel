// CSRF token refresh mechanism
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            // Get the current CSRF token from meta tag
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Update all CSRF token fields in the form
            const csrfFields = form.querySelectorAll('input[name="_token"]');
            csrfFields.forEach(field => {
                field.value = token;
            });
            
            // If no CSRF field exists, create one
            if (csrfFields.length === 0) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = token;
                form.appendChild(tokenInput);
            }
        });
    });
});