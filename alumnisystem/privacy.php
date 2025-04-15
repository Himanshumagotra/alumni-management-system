<?php 
session_start(); 
if (!isset($_SESSION['alumni_id'])) { 
    header("Location: login.php"); 
    exit(); 
} 

include "includes/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Privacy Policy - Alumni System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1, #8b5cf6);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #f8fafc;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-gradient);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .privacy-section {
            transition: all 0.3s ease;
        }

        .privacy-section:hover {
            transform: translateX(10px);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/20 to-purple-900/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNGM0LjQxOCAwIDgtMy41ODIgOC04cy0zLjU4Mi04LTgtOC04IDMuNTgyLTggOCAzLjU4MiA4IDggOHoiIHN0cm9rZT0iIzRjMWQ3NSIgc3Ryb2tlLW9wYWNpdHk9Ii4xIi8+PC9nPjwvc3ZnPg==')] opacity-20"></div>
    </div>

    <!-- Navigation -->
    <nav class="glass-card fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="welcome.php" class="nav-link text-white hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Home</a>
                    <a href="index.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">View All Alumni</a>
                    <a href="faq.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">FAQ</a>
                    <a href="privacy.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Privacy Policy</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="signup.php" class="nav-link text-white/80 hover:text-indigo-300 font-semibold px-3 py-2 rounded-md">Add New Alumni</a>
                    <a href="logout.php" class="action-button text-white font-semibold px-4 py-2 rounded-md">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-24">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold gradient-text">Privacy Policy</h1>
            <p class="mt-4 text-white/60">Last updated: <?php echo date('F d, Y'); ?></p>
        </div>

        <div class="space-y-8">
            <!-- Introduction -->
            <div class="privacy-section glass-card rounded-xl p-6" data-aos="fade-up">
                <h2 class="text-2xl font-semibold text-white mb-4">Introduction</h2>
                <p class="text-white/60">
                    Welcome to the Alumni System. We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you use our platform.
                </p>
            </div>

            <!-- Information Collection -->
            <div class="privacy-section glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-2xl font-semibold text-white mb-4">Information We Collect</h2>
                <p class="text-white/60 mb-4">We collect the following types of information:</p>
                <ul class="list-disc list-inside text-white/60 space-y-2">
                    <li>Personal Information (name, email, phone number)</li>
                    <li>Academic Information (degree, graduation year)</li>
                    <li>Professional Information (occupation, company)</li>
                    <li>Account Credentials (password, securely hashed)</li>
                </ul>
            </div>

            <!-- Data Usage -->
            <div class="privacy-section glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-2xl font-semibold text-white mb-4">How We Use Your Information</h2>
                <p class="text-white/60 mb-4">We use your information for the following purposes:</p>
                <ul class="list-disc list-inside text-white/60 space-y-2">
                    <li>To create and manage your account</li>
                    <li>To facilitate communication between alumni</li>
                    <li>To provide personalized services and content</li>
                    <li>To improve our platform and user experience</li>
                    <li>To send important updates and notifications</li>
                </ul>
            </div>

            <!-- Data Protection -->
            <div class="privacy-section glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="300">
                <h2 class="text-2xl font-semibold text-white mb-4">Data Protection</h2>
                <p class="text-white/60">
                    We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. This includes:
                </p>
                <ul class="list-disc list-inside text-white/60 space-y-2 mt-4">
                    <li>Secure data encryption</li>
                    <li>Regular security audits</li>
                    <li>Access controls and authentication</li>
                    <li>Secure data storage and transmission</li>
                </ul>
            </div>

            <!-- User Rights -->
            <div class="privacy-section glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="400">
                <h2 class="text-2xl font-semibold text-white mb-4">Your Rights</h2>
                <p class="text-white/60 mb-4">As a user, you have the right to:</p>
                <ul class="list-disc list-inside text-white/60 space-y-2">
                    <li>Access your personal information</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Export your data in a portable format</li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="privacy-section glass-card rounded-xl p-6" data-aos="fade-up" data-aos-delay="500">
                <h2 class="text-2xl font-semibold text-white mb-4">Contact Us</h2>
                <p class="text-white/60">
                    If you have any questions or concerns about our Privacy Policy, please contact us at:
                </p>
                <div class="mt-4 space-y-2">
                    <p class="text-white/60">Email: himanshumagotra2006@gmail.com</p>
                    <p class="text-white/60">Phone: +91 6006109112</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900/80 backdrop-blur-md text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About Us -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">
                        The Alumni System is a platform designed to connect graduates, foster professional relationships, and create opportunities for networking and collaboration within our alumni community.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="welcome.php" class="text-gray-400 hover:text-white transition-colors">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="index.php" class="text-gray-400 hover:text-white transition-colors">
                                View All Alumni
                            </a>
                        </li>
                        <li>
                            <a href="faq.php" class="text-gray-400 hover:text-white transition-colors">
                                FAQ
                            </a>
                        </li>
                        <li>
                            <a href="privacy.php" class="text-gray-400 hover:text-white transition-colors">
                                Privacy Policy
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                           himanshumagotra2006@gmail.com
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                           +91 6006109112
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lovey Proffessional University
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Alumni System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html> 