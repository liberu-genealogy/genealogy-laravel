document.addEventListener('DOMContentLoaded', function() {
    // Example of an animation
    const fadeInElements = document.querySelectorAll('.fade-in');
    fadeInElements.forEach(element => {
        if (element.getBoundingClientRect().top < window.innerHeight) {
            element.classList.add('visible');
        }
    });

    // Example of complex form validation
    const emailInput = document.querySelector('#email');
    emailInput.addEventListener('input', function() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value)) {
            emailInput.classList.add('invalid');
        } else {
            emailInput.classList.remove('invalid');
        }
    });

    // Initialization of a third-party library could go here
    // Example: $('.datepicker').datepicker();
});
