<!DOCTYPE html>
<html lang="en">
<head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Chino Park - Secure and Affordable Parking in Dar es Salaam</title>
    <meta name="description" content="Find secure and affordable car parking at Chino Car Parking System in Dar es Salaam. Conveniently located at Kimara, Golani Kijiweni. Book your parking spot today!" />
<meta name="keywords" content="Chino Park, car parking, parking in Dar es Salaam, secure parking, affordable parking, Kimara parking, Golani Kijiweni parking" />
    <link rel="stylesheet" href="custom.css" />
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 960px;
            margin: 2rem auto;
            background: white;
            border-radius: 0.5rem;
            padding: 2rem 3rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1000;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.75rem;
            color: #2563eb;
            text-decoration: none;
        }
        nav {
            display: flex;
            align-items: center;
        }
        nav a {
            color: #495057;
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        nav a:hover {
            color: #2563eb;
        }
        /* Hamburger menu styles */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            width: 25px;
            height: 20px;
            justify-content: space-between;
        }
        .hamburger div {
            height: 3px;
            background-color: #2563eb;
            border-radius: 2px;
        }
        @media (max-width: 768px) {
            nav {
                display: none;
                flex-direction: column;
                width: 100%;
                background: white;
                position: absolute;
                top: 60px;
                left: 0;
                border-top: 1px solid #dee2e6;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            nav.active {
                display: flex;
            }
            nav a {
                margin: 1rem;
                font-size: 1.2rem;
            }
            .hamburger {
                display: flex;
            }
            .container {
                position: relative;
            }
        }
        .hero {
            text-align: center;
            padding: 4rem 2rem 3rem 2rem;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #2563eb;
        }
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            color: #495057;
        }
        .btn-primary {
            background-color: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1.25rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
        }
        .btn-primary:hover {
            background-color: #1e40af;
        }
        .map-container {
            text-align: center;
            margin: 2rem 0;
        }
        iframe {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .features, .testimonials, .hours-pricing, .faq, .social {
            max-width: 960px;
            margin: 3rem auto;
            background: white;
            border-radius: 0.5rem;
            padding: 2rem 3rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            color: #212529;
        }
        .features h3, .testimonials h3, .hours-pricing h3, .faq h3, .social h3 {
            margin-bottom: 1rem;
        }
        .features ul {
            list-style-type: disc;
            padding-left: 1.5rem;
        }
        .testimonials blockquote {
            font-style: italic;
            margin: 1rem 0;
            border-left: 4px solid #2563eb;
            padding-left: 1rem;
            color: #495057;
        }
        .faq dt {
            font-weight: 600;
            margin-top: 1rem;
        }
        .faq dd {
            margin-left: 1rem;
            margin-bottom: 1rem;
        }
        .social a {
            color: #2563eb;
            margin-right: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .social a:hover {
            color: #1e40af;
        }
        .contact {
            background: white;
            color: #2563eb;
            padding: 2rem 3rem;
            border-radius: 0.5rem;
            max-width: 400px;
            margin: 3rem auto 2rem auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-size: 1.25rem;
            font-weight: 600;
            text-align: center;
        }
        footer {
            text-align: center;
            padding: 1rem;
            color: #6c757d;
            font-size: 0.9rem;
            border-top: 1px solid #dee2e6;
            margin-top: 2rem;
        }
        @media (max-width: 900px) {
            .container {
                padding: 1.5rem 1.5rem;
                margin: 1rem;
            }
            .features, .testimonials, .hours-pricing, .faq, .social {
                padding: 1.5rem 1.5rem;
                margin: 2rem 1rem;
            }
            .contact {
                margin: 2rem 1rem;
                padding: 1.5rem 1.5rem;
                max-width: 100%;
            }
            nav a {
                margin-left: 1rem;
            }
        }
        @media (max-width: 600px) {
            .hero h1 {
                font-size: 2.25rem;
            }
            .hero p {
                font-size: 1rem;
            }
            .btn-primary {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
        }
</style>
</head>
<body>
     <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>
    <div class="container">
        <header>
            <a href="#" class="navbar-brand">Chino Park</a>
            <div class="hamburger" id="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <nav id="nav-menu">
                <a href="#features">Features</a>
                <a href="#testimonials">Testimonials</a>
                <a href="#hours-pricing">Hours & Pricing</a>
                <a href="#faq">FAQ</a>
                <a href="#contact">Contact</a>
                <a href="login.php" style="font-weight: 700; color: #2563eb; margin-left: 1.5rem;">Login</a>
            </nav>
        </header>
        <section class="hero">
<h1>Welcome to Chino Park</h1>
            <p>Your trusted parking solution in Dar es Salaam. We provide secure and affordable parking area at Chino Park located at Kimara, Golani Kijiweni in Dar es Salaam.</p>
            <a href="tel:+255716959578" class="btn-primary">Call to Book Now</a>
        </section>
        <section class="map-container">
            <h3>Our Location</h3>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15869.123456789!2d39.152002!3d-6.813609!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4c1234567890%3A0xabcdef1234567890!2sChino%20Park!5e0!3m2!1sen!2stz!4v1680000000000!5m2!1sen!2stz"
                width="100%" height="300" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
        <section id="features" class="features">
<h3>Key Features of Chino Park</h3>
            <ul>
                <li>Secure and monitored parking area for your peace of mind</li>
                <li>Convenient location at Kimara, Golani Kijiweni in Dar es Salaam</li>
                <li>Affordable pricing and flexible parking options to suit your needs</li>
                <li>24/7 customer support and assistance for any inquiries</li>
                <li>Easy booking and quick entry/exit process for a hassle-free experience</li>
            </ul>
        </section>
        <section id="testimonials" class="testimonials">
<h3>What Our Customers Say About Chino Park</h3>
            <blockquote>"Safe and reliable parking service. Highly recommend Chino Car Parking System!" - John M.</blockquote>
            <blockquote>"Convenient location and friendly staff. Will definitely use Chino Car Parking System again." - Asha K.</blockquote>
            <blockquote>"Affordable and secure parking solution. Great experience overall with Chino Car Parking System." - Michael T.</blockquote>
        </section>
        <section id="hours-pricing" class="hours-pricing">
<h3>Chino Park - Operating Hours & Pricing</h3>
            <p><strong>Operating Hours:</strong> 6:00 AM - 10:00 PM (Everyday)</p>
            <p><strong>Pricing:</strong> TZS 1,000 per hour, discounts for monthly subscriptions</p>
        </section>
        <section id="faq" class="faq">
<h3>Frequently Asked Questions About Chino Park</h3>
            <dl>
                <dt>Is the parking area at Chino Car Parking System secure?</dt>
                <dd>Yes, we have 24/7 surveillance and security personnel on site ensuring the safety of your vehicle.</dd>
                <dt>Can I reserve a parking spot in advance at Chino Car Parking System?</dt>
                <dd>Yes, you can call us to book your spot ahead of time for a guaranteed parking space.</dd>
                <dt>What payment methods do you accept at Chino Car Parking System?</dt>
                <dd>We accept cash, mobile money, and credit/debit cards for your convenience.</dd>
            </dl>
        </section>
        <section class="social">
            <h3>Connect with Chino Car Parking System</h3>
            <p>Follow us on social media for updates, promotions, and parking tips:</p>
            <p>
                <a href="#" aria-label="Facebook" title="Facebook">Facebook</a>
                <a href="#" aria-label="Twitter" title="Twitter">Twitter</a>
                <a href="#" aria-label="Instagram" title="Instagram">Instagram</a>
            </p>
        </section>
        <section id="contact" class="contact">
Contact Chino Park at: <a href="tel:+255716959578">0716 959 578</a>
        </section>
        <footer>
&copy; 2024 Chino Park Solution. All rights reserved.
        </footer>
    </div>
    <script>
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('nav-menu');

        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    </script>
    <script>
         window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            loader.style.display = 'none';
        });
    </script>
</body>
</html>
