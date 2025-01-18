

// Mobile Menu Toggle functionality (Hamburger Menu)
$(document).ready(function() {
    // When the hamburger link is clicked
    $(".humbergur-link").click(function () {
        // Toggle the 'new-class' on the body
        $("body").toggleClass("new-class"); 

        // Toggle the 'active' class on the navigation
        $(".navigation").toggleClass("active");
    });
    
    
    
});


document.addEventListener("DOMContentLoaded", function () {
        const whatsappIcon = document.getElementById("whatsapp-icon");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 200) { // Show after 200px of scrolling
                whatsappIcon.classList.add("show");
                whatsappIcon.classList.remove("hide");
            } else {
                whatsappIcon.classList.add("hide");
                whatsappIcon.classList.remove("show");
            }
        });
    });
