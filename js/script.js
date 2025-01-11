// Language Switcher functionality
document.getElementById('englishBtn').addEventListener('click', function () {
  setLanguage('en');
});

document.getElementById('arabicBtn').addEventListener('click', function () {
  setLanguage('ar');
});

// Function to update language and text content
function setLanguage(language) {
  // Store the selected language in localStorage
  localStorage.setItem('language', language);

  // Toggle button active class
  document.getElementById('englishBtn').classList.toggle('active', language === 'en');
  document.getElementById('arabicBtn').classList.toggle('active', language === 'ar');

  // Update text based on selected language
  const elements = document.querySelectorAll('[data-en], [data-ar]');
  elements.forEach(element => {
    // Check if the element has the corresponding language data
    const text = element.getAttribute(`data-${language}`);
    if (text) {
      element.textContent = text; // Update text content to the selected language
    }
  });

  // Adjust text direction for Arabic (Right-to-Left) and for English (Left-to-Right)
  document.body.style.direction = language === 'ar' ? 'rtl' : 'ltr';

  // Update aria-pressed attribute for accessibility
  document.getElementById('englishBtn').setAttribute('aria-pressed', language === 'en');
  document.getElementById('arabicBtn').setAttribute('aria-pressed', language === 'ar');
}

// On page load, retrieve the stored language preference and apply it
window.onload = function() {
  const storedLanguage = localStorage.getItem('language') || 'en'; // Default to 'en' if no language is stored
  setLanguage(storedLanguage); // Apply the saved language
};
