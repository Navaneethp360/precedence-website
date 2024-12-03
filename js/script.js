
// Service Boxes Section: Handles the service box functionality
const serviceBoxes = document.querySelectorAll('.service-box')

serviceBoxes.forEach(box => {
    box.addEventListener('click', function() {
        serviceBoxes.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const serviceName = this.querySelector('h3').textContent;
        const modal = document.createElement('div');
        modal.classList.add('service-modal');
        modal.innerHTML = `
            <div class="modal-content">
                <h2>${serviceName} Details</h2>
                <p>Detailed description of ${serviceName} service.</p>
                <button class="close-modal">Close</button>
            </div>
        `;
        document.body.appendChild(modal);
        const closeButton = modal.querySelector('.close-modal');
        closeButton.addEventListener('click', () => {
            document.body.removeChild(modal);
        });
    });
});

// Package Items Section: Handles the package item functionality
const packageItems = document.querySelectorAll('.package-item')

packageItems.forEach(item => {
    item.addEventListener('click', () => {
        packageItems.forEach(p => p.classList.remove('selected'));
        item.classList.add('selected');
        const packageName = item.querySelector('h3').textContent;
        const packagePrice = item.querySelector('.package-price').textContent;
        console.log(`Selected Package: ${packageName} - ${packagePrice}`);
    });
});

// Intersection Observer Section: Handles the intersection observer functionality
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

serviceBoxes.forEach(box => {
    observer.observe(box);
});

packageItems.forEach(item => {
    observer.observe(item);
});

document.addEventListener('DOMContentLoaded', function() {
    // Function to switch language
    function switchLanguage(language) {
        var elements = document.querySelectorAll('[data-en], [data-ar], [data-en-placeholder], [data-ar-placeholder]');
        
        elements.forEach(function(element) {
            // Switch text content based on the selected language
            if (element.hasAttribute('data-en')) {
                element.textContent = language === 'en' ? element.getAttribute('data-en') : element.getAttribute('data-ar');
            }

            // Switch placeholder text based on the selected language
            if (element.hasAttribute('data-en-placeholder')) {
                element.setAttribute('placeholder', language === 'en' ? element.getAttribute('data-en-placeholder') : element.getAttribute('data-ar-placeholder'));
            }
        });

        // Switch text direction for Arabic (Right to Left)
        if (language === 'ar') {
            document.body.classList.add('rtl');
        } else {
            document.body.classList.remove('rtl');
        }
    }

    // Button click event to toggle languages
    document.getElementById('swap-btn').addEventListener('click', function() {
        var currentLang = this.getAttribute('data-lang');
        var newLang = currentLang === 'en' ? 'ar' : 'en';
        switchLanguage(newLang);

        // Update button text and language attribute
        this.setAttribute('data-lang', newLang);
        this.textContent = newLang === 'en' ? 'Ar' : 'En';
    });

    // Initialize with English language
    switchLanguage('en');
});

// Mail sending script
 
function sendEmail(event) {
    event.preventDefault(); // Prevent form submission to the server

    // Get form values
    const firstName = document.querySelector('[name="first_name"]').value;
    const lastName = document.querySelector('[name="last_name"]').value;
    const company = document.querySelector('[name="company"]').value;
    const userEmail = document.querySelector('[name="email"]').value;
    const message = document.querySelector('[name="message"]').value;
    const phone = document.querySelector('[name="phone"]').value;
    const dateTime = document.querySelector('[name="date_time"]').value;

    // Construct the mailto link
    const subject = `Message from ${firstName} ${lastName}`;
    const body = `
        Name: ${firstName} ${lastName}
        Company: ${company}
        Email: ${userEmail}
        Phone: ${phone}

        Meeting: Scheduled on ${dateTime.split('T')[0]} at ${dateTime.split('T')[1].split('.')[0]}
        
        Message:
        ${message}
    `;
    
    const mailtoLink = `mailto:contact@precedencekw.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

    // Open the default email client with the pre-filled content
    window.location.href = mailtoLink;
}

document.head.appendChild(style);