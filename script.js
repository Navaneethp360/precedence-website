// Carousel Section: Handles the carousel functionality
const carousel = document.querySelector('.carousel-container');
const slides = document.querySelectorAll('.carousel-slide');
const indicators = document.querySelectorAll('.indicator');
let currentSlide = 0;

function changeSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
    indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentSlide);
    });
}

setInterval(changeSlide, 3000);

indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        currentSlide = index;
        carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        indicators.forEach(ind => ind.classList.remove('active'));
        indicator.classList.add('active');
    });
});

// Service Boxes Section: Handles the service box functionality
const serviceBoxes = document.querySelectorAll('.service-box');

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
const packageItems = document.querySelectorAll('.package-item');

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

let currentCreativeSlide = 0;
const creativeSlides = document.querySelectorAll('#CreativeWorks .creative-carousel-slide');
const creativeIndicators = document.querySelectorAll('#CreativeWorks .creative-indicator');

function showCreativeSlide(index) {
    creativeSlides.forEach((slide, i) => {
        slide.classList.remove('active');
        creativeIndicators[i].classList.remove('active');
        if (i === index) {
            slide.classList.add('active');
            creativeIndicators[i].classList.add('active');
        }
    });
}

function nextCreativeSlide() {
    currentCreativeSlide = (currentCreativeSlide + 1) % creativeSlides.length;
    showCreativeSlide(currentCreativeSlide);
}

// Auto slide every 3 seconds
setInterval(nextCreativeSlide, 3000);

// Add click event to indicators
creativeIndicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        currentCreativeSlide = index;
        showCreativeSlide(currentCreativeSlide);
    });
});

// Initialize the first slide
showCreativeSlide(currentCreativeSlide);


document.head.appendChild(style);