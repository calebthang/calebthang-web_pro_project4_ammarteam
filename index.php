<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Connect - Your Real Estate Marketplace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Navigation */
        nav {
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 1rem 5%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-outline {
            border: 2px solid #2563eb;
            color: #2563eb;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url(background.avif);
            background-size: cover;
            background-position: center;
            height: 600px;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            margin-top: 60px;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        /* Features Section */
        .features {
            padding: 5rem 1rem;
            background: #f8fafc;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2rem;
            color: #1e293b;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .feature-card i {
            font-size: 2.5rem;
            color: #2563eb;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            color: #1e293b;
        }

        /* Why Choose Us Section */
        .why-choose-us {
            padding: 5rem 1rem;
            background: white;
        }

        .why-choose-us-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .why-choose-us h2 {
            margin-bottom: 3rem;
            font-size: 2rem;
            color: #1e293b;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            text-align: left;
        }

        .benefit-item {
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 8px;
        }

        .benefit-item h3 {
            color: #2563eb;
            margin-bottom: 1rem;
        }

        /* Footer */
        footer {
            background: #1e293b;
            color: white;
            padding: 3rem 1rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: #cbd5e1;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .feature-grid, .benefits-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="logo">Property Connect</a>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#why-choose-us">Why Choose Us</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $_SESSION['user_type']; ?>_dashboard.php" class="btn btn-outline">Dashboard</a>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="register.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Find Your Perfect Property</h1>
            <p>Connect with trusted buyers and sellers in your area. Your dream property is just a click away.</p>
            <a href="register.php" class="btn btn-primary">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <h2>Our Features</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <i class="fas fa-search"></i>
                    <h3>Smart Search</h3>
                    <p>Find properties that match your exact criteria with our advanced search filters.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Verified Listings</h3>
                    <p>All our listings are verified to ensure you get authentic property information.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-handshake"></i>
                    <h3>Direct Connect</h3>
                    <p>Connect directly with property owners and buyers without intermediaries.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us" id="why-choose-us">
        <div class="why-choose-us-container">
            <h2>Why Choose Property Connect?</h2>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <h3>Trusted Platform</h3>
                    <p>Join thousands of satisfied users who have found their perfect property match through our platform.</p>
                </div>
                <div class="benefit-item">
                    <h3>No Hidden Fees</h3>
                    <p>We believe in complete transparency. No hidden charges or surprise fees.</p>
                </div>
                <div class="benefit-item">
                    <h3>Expert Support</h3>
                    <p>Our dedicated support team is always ready to help you with any questions or concerns.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <ul>
                    <li><a href="#">Our Story</a></li>
                    <li><a href="#">Team</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Resources</h3>
                <ul>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Market Updates</a></li>
                    <li><a href="#">Help Center</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li><a href="mailto:contact@propertyconnect.com">contact@propertyconnect.com</a></li>
                    <li><a href="tel:+1234567890">+1 (234) 567-890</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>