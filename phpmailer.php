<?php
// This PHP code will process the form submission and send the email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $company = htmlspecialchars($_POST['company']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);
    $date_time = htmlspecialchars($_POST['date_time']);

    // Recipient email address
    $to = "contact@precedencekw.com";
    $subject = "Message from $first_name $last_name";

    // Create the email body
    $body = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
        <h2 style='color: #4CAF50;'>New Message from Contact Form</h2>
        
        <p><b>Name:</b> $first_name $last_name</p>
        <p><b>Company:</b> $company</p>
        <p><b>Email:</b> $email</p>
        <p><b>Phone:</b> $phone</p>
        
        <p><b>Message:</b></p>
        <p style='border: 1px solid #ddd; padding: 10px; background-color: #f9f9f9; border-radius: 4px;'>$message</p>
        
        <p><b>Scheduled Meeting:</b> $date_time</p>
    </body>
    </html>
    ";

    // Set email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $email" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        $message_sent = true;  // Message sent successfully
    } else {
        $message_sent = false;  // Failed to send message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="https://www.precedencekw.com/">
                <img src="icons and logos/logo1.png" alt="Company Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="#Home">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="#clients">Clients</a></li>
                <li><a href="#packages">Packages</a></li>
                <li><a href="#contact-us">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contact Form Section -->
    <section id="contact-us" class="contact-us">
        <div class="contact-us-container">
            <div class="contact-us-row">
                <div class="contact-us-left">
                    <h2>Contact Us</h2>
                    <h3>Do you have any questions?</h3>
                    <div class="new-form">
                        <div class="contact-form">
                            <?php if (isset($message_sent)): ?>
                                <div class="thank-you-message">
                                    <?php if ($message_sent): ?>
                                        <h3>Thank you for your message. We will get back to you soon!</h3>
                                    <?php else: ?>
                                        <h3>There was an error sending your message. Please try again later.</h3>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <form action="index.php" method="POST" id="contactForm">
                                    <input type="text" name="first_name" placeholder="First Name" required>
                                    <input type="text" name="last_name" placeholder="Last Name" required>
                                    <input type="text" name="company" placeholder="Company">
                                    <input type="email" name="email" placeholder="Your Email" required>
                                    <textarea name="message" placeholder="Your Message" required></textarea>
                                    <input type="tel" name="phone" placeholder="Phone Number">
                                    <input type="datetime-local" name="date_time" required>
                                    <button type="submit">Send</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-wrap">
            <div class="footer">
                <div class="footer-left">
                    <div class="social-media">
                        <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="footer-right">
                    <div class="logo-section">
                        <a href="#"><img src="https://precedencekw.com/icons%20and%20logos/logo1.png" alt="Logo"></a>
                    </div>
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        <span>Precedence, Shuwaikh industrial area, Street 26, Kuwait City 32000</span>
                    </div>
                    <div class="contact-info">
                        <p><i class="fa fa-envelope"></i> contact@precedencekw.com</p>
                        <p><i class="fa fa-phone"></i> +965 5551 5511</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/scripts.js"></script>
</body>
</html>
