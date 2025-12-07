<?php
require_once 'backend/includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: backend/admin/dashboard.php");
    exit();
} elseif (isset($_SESSION['doctor_id'])) {
    header("Location: backend/doctor/dashboard.php");
    exit();
} elseif (isset($_SESSION['patient_id'])) {
    header("Location: backend/patient/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareLink Hospital Management System - Excellence in Healthcare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }
        
        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            padding: 15px 50px;
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.15);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .logo i {
            font-size: 32px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-links a:hover {
            color: #667eea;
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .login-btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white !important;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .login-btn::after {
            display: none;
        }
        
        /* Hero Section */
        .hero {
            margin-top: 80px;
            height: 90vh;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9)),
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect fill="%23667eea" width="1200" height="800"/><g fill-opacity="0.1"><path fill="%23ffffff" d="M200 400c50-30 100-50 150-40s90 50 140 80 100 50 150 40 90-50 140-80 100-50 150-40 90 50 140 80"/><path fill="%23ffffff" d="M0 500c50-30 100-50 150-40s90 50 140 80 100 50 150 40 90-50 140-80 100-50 150-40 90 50 140 80 100 50 150 40"/></g></svg>');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="3" fill="rgba(255,255,255,0.15)"/></svg>');
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .hero-content {
            max-width: 800px;
            padding: 40px;
            animation: fadeInUp 1s ease;
            position: relative;
            z-index: 1;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero h1 {
            font-size: 56px;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .hero p {
            font-size: 22px;
            margin-bottom: 40px;
            opacity: 0.95;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary, .btn-secondary {
            padding: 18px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(255, 255, 255, 0.4);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            backdrop-filter: blur(10px);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }
        
        /* Services Section */
        .services {
            padding: 100px 50px;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 42px;
            color: #333;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .section-title p {
            color: #666;
            font-size: 18px;
            max-width: 600px;
            margin: 20px auto 0;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .service-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .service-card:hover::before {
            transform: scaleX(1);
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .service-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .service-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .service-card p {
            color: #666;
            line-height: 1.8;
        }
        
        /* Team Slider */
        .team {
            padding: 100px 50px;
            background: white;
        }
        
        .team-slider {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        .team-track {
            display: flex;
            gap: 30px;
            animation: slide 20s linear infinite;
        }
        
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .team-card {
            min-width: 300px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .team-image {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: white;
        }
        
        .team-info {
            padding: 25px;
            text-align: center;
        }
        
        .team-info h3 {
            font-size: 22px;
            color: #333;
            margin-bottom: 8px;
        }
        
        .team-info .role {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .team-info p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* About Section */
        .about {
            padding: 100px 50px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        }
        
        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        
        .about-text h2 {
            font-size: 42px;
            color: #333;
            margin-bottom: 25px;
        }
        
        .about-text p {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .about-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 40px;
        }
        
        .stat-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        
        .stat-box .number {
            font-size: 36px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        
        .stat-box .label {
            color: #666;
            font-size: 14px;
        }
        
        .about-image {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            color: white;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        }
        
        /* Footer */
        .footer {
            background: #1a1a2e;
            color: white;
            padding: 60px 50px 30px;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-section h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .nav-links {
                display: none;
            }
            
            .hero {
                margin-top: 60px;
                height: auto;
                padding: 80px 20px;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 18px;
            }
            
            .services, .team, .about {
                padding: 60px 20px;
            }
            
            .about-content {
                grid-template-columns: 1fr;
            }
            
            .section-title h2 {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-heartbeat"></i>
            <span>CareLink HMS</span>
        </div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#services">Services</a>
            <a href="#team">Our Team</a>
            <a href="#about">About Us</a>
            <a href="login.php" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Excellence in Healthcare Management</h1>
            <p>Transforming healthcare delivery with innovative technology and compassionate care for better patient outcomes</p>
            <div class="hero-buttons">
                <a href="login.php" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login to Portal
                </a>
                <a href="#services" class="btn-secondary">
                    <i class="fas fa-info-circle"></i> Learn More
                </a>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section class="services" id="services">
        <div class="section-title">
            <h2>Our Services</h2>
            <p>Comprehensive healthcare solutions for patients, doctors, and administrative staff</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-user-injured"></i>
                </div>
                <h3>Patient Management</h3>
                <p>Complete patient registration, records management, and appointment scheduling system</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Laboratory Services</h3>
                <p>Advanced diagnostic testing with quick results and comprehensive reporting</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <h3>Pharmacy Management</h3>
                <p>Integrated pharmaceutical services with inventory tracking and prescription management</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3>Vital Monitoring</h3>
                <p>Real-time patient vital signs tracking and health status monitoring</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-syringe"></i>
                </div>
                <h3>Surgery Management</h3>
                <p>Complete surgical procedures tracking and operation theater management</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-file-medical"></i>
                </div>
                <h3>Medical Records</h3>
                <p>Secure electronic health records with easy access and comprehensive history</p>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="team" id="team">
        <div class="section-title">
            <h2>Meet Our Expert Team</h2>
            <p>Dedicated healthcare professionals committed to your wellbeing</p>
        </div>
        <div class="team-slider">
            <div class="team-track">
                <div class="team-card">
                    <div class="team-image">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="team-info">
                        <h3>Dr. Tanvir Rahman</h3>
                        <p class="role">Cardiology Specialist</p>
                        <p>10+ years of experience in cardiac care and interventional cardiology</p>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-image" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="team-info">
                        <h3>Dr. Nusrat Jahan</h3>
                        <p class="role">Pediatrics Specialist</p>
                        <p>Expert in child healthcare with focus on preventive medicine</p>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-image" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="team-info">
                        <h3>Dr. Mahmud Hasan</h3>
                        <p class="role">Orthopedic Surgeon</p>
                        <p>Specialized in joint replacement and sports medicine</p>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-image" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="team-info">
                        <h3>Dr. Farhana Sultana</h3>
                        <p class="role">Gynecology Specialist</p>
                        <p>Expert in women's health and prenatal care</p>
                    </div>
                </div>
                <!-- Duplicate for seamless loop -->
                <div class="team-card">
                    <div class="team-image">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="team-info">
                        <h3>Dr. Tanvir Rahman</h3>
                        <p class="role">Cardiology Specialist</p>
                        <p>10+ years of experience in cardiac care and interventional cardiology</p>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-image" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="team-info">
                        <h3>Dr. Nusrat Jahan</h3>
                        <p class="role">Pediatrics Specialist</p>
                        <p>Expert in child healthcare with focus on preventive medicine</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>About CareLink HMS</h2>
                <p>CareLink Hospital Management System is a comprehensive healthcare solution designed to streamline hospital operations and improve patient care delivery. Our platform integrates all aspects of hospital management into one seamless system.</p>
                <p>With state-of-the-art technology and user-friendly interfaces, we empower healthcare professionals to focus on what matters most - providing exceptional patient care.</p>
                <div class="about-stats">
                    <div class="stat-box">
                        <div class="number">500+</div>
                        <div class="label">Patients Served</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">50+</div>
                        <div class="label">Medical Staff</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">15+</div>
                        <div class="label">Departments</div>
                    </div>
                    <div class="stat-box">
                        <div class="number">24/7</div>
                        <div class="label">Support Available</div>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <i class="fas fa-hospital"></i>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3><i class="fas fa-heartbeat"></i> CareLink HMS</h3>
                <p>Excellence in Healthcare Management</p>
                <p>Dhaka, Bangladesh</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#team">Our Team</a>
                <a href="#about">About Us</a>
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <a href="#">Patient Care</a>
                <a href="#">Laboratory</a>
                <a href="#">Pharmacy</a>
                <a href="#">Surgery</a>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p><i class="fas fa-phone"></i> +880 1234-567890</p>
                <p><i class="fas fa-envelope"></i> info@carelink.com.bd</p>
                <p><i class="fas fa-clock"></i> 24/7 Emergency Service</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 CareLink HMS. All rights reserved. Developed by Samin</p>
        </div>
    </footer>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>