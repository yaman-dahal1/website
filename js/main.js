// js/main.js
// Mobile menu toggle + active nav highlighting + smooth validation

document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Toggle mobile menu
    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navbar.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }
    
    // Close mobile menu on click link
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if (navbar.classList.contains('active')) {
                navbar.classList.remove('active');
                hamburger.classList.remove('active');
            }
            // Active link highlight
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });
    
    // Highlight active section on scroll
    const sections = document.querySelectorAll('section');
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            if (pageYOffset >= sectionTop) {
                current = section.getAttribute('id');
            }
        });
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
    
    // Client-side form validation before submit
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            const name = document.getElementById('name')?.value.trim();
            const email = document.getElementById('email')?.value.trim();
            const message = document.getElementById('message')?.value.trim();
            
            let errors = [];
            if (!name) errors.push('Name is required');
            if (!email || !/^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/.test(email)) errors.push('Valid email is required');
            if (!message) errors.push('Message cannot be empty');
            
            if (errors.length > 0) {
                e.preventDefault();
                let alertDiv = document.querySelector('.alert');
                if (!alertDiv) {
                    alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-error';
                    contactForm.prepend(alertDiv);
                }
                alertDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errors.join(', ')}`;
                alertDiv.style.display = 'block';
                setTimeout(() => alertDiv.style.display = 'none', 4000);
            }
        });
    }
    
    // Smooth scrolling for all internal anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== "#" && targetId !== "#!") {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});