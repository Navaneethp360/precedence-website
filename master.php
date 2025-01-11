<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Precedence'; ?></title>
    
    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="css/styles2.css">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Slick Slider CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    
    <!-- Additional Font Awesome Version -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Your other custom styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include Header -->
    <header class="header">
        <div class="logo">
            <a href="#">
                <img src="icons and logos/logo1.png" alt="Company Logo">
            </a>
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="#Home" data-en="Home" data-ar="الرئيسية">Home</a></li>
                <li>
                    <a href="#services" data-en="Services" data-ar="خدمات">Services</a>
                    <div class="mega-menu">
                        <ul>
                            <li><a href="#" data-en="Marketing Strategy" data-ar="استراتيجية التسويق">Marketing Strategy</a></li>
                            <li><a href="#" data-en="Branding and Rebranding" data-ar="التسمية والتسمية التجارية">Branding and Rebranding</a></li>
                            <li><a href="#" data-en="Digital Solutions" data-ar="الحلول الرقمية">Digital Solutions</a></li>
                            <li><a href="#" data-en="Social Media Marketing" data-ar="تسويق وسائل التواصل الاجتماعي">Social Media Marketing</a></li>
                            <li><a href="#" data-en="Media Production" data-ar="الإنتاج الإعلامي">Media Production</a></li>
                            <li><a href="#" data-en="3D Production" data-ar="الإنتاج ثلاثي الأبعاد">3D Production</a></li>
                            <li><a href="#" data-en="Ads" data-ar="الإعلانات">Ads</a></li>
                            <li><a href="#" data-en="Marketing" data-ar="التسويق">Marketing</a></li>
                        </ul>
                    </div>
                </li>
                <li><a href="#about" data-en="About Us" data-ar="معلومات عنا">About Us</a></li>
                <li><a href="#clients" data-en="Clients" data-ar="عملائنا">Clients</a></li>
                <li><a href="#contact" data-en="Contact" data-ar="اتصل بنا">Contact</a></li>
            </ul>
        </nav>
        <div class="humbergur-link">
            <a href="#"><i class="fa fa-bars"></i></a>
        </div>
    </header>

    <!-- Main Content Section -->
    <main>
        <?php include($content); ?> <!-- Dynamic content inclusion -->
    </main>

    <!-- Include Footer -->
    <footer class="footer-wrap">
        <div class="footer">
            <div class="footer-left">
                <div class="large-text">Let's Talk</div>
                <div class="small-text">contact@precedencekw.com</div>
                <div class="small-text">Precedence, Shuwaikh industrial area, Street 26, Kuwait City 32000</div>
                <div class="row-small-texts">
                    <span>Privacy</span>
                    <span>Terms</span>
                    <span>SiteMap</span>
                </div>
            </div>
            <div class="footer-right">
                <div class="footer-column">
                    <p>Services</p>
                    <p>Clients</p>
                    <p>About</p>
                </div>
                <div class="social-media">
                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    <a href="#"><i class="fa fa-whatsapp"></i></a>
                    <a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                </div>
                <div class="small-text">
                    <p>© 2025 Precedence. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
