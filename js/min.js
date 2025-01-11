

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
