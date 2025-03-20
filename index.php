<?php
require_once 'includes/functions.php';

// Check if user is already logged in
if (isUserLoggedIn()) {
    header('Location: member/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Music Streaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-black text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-music text-2xl text-pink-600"></i>
                    <span class="ml-2 text-xl font-semibold"><?php echo SITE_NAME; ?></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="login.php" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Login
                    </a>
                    <a href="register.php" class="bg-pink-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-pink-700 transition duration-200">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Your Music, <span class="text-pink-600">Your Way</span>
                    </h1>
                    <p class="text-gray-400 text-lg sm:text-xl mb-8">
                        Stream your favorite music, create custom playlists, and discover new artists. All in one place.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="register.php" class="bg-pink-600 text-white px-8 py-3 rounded-full text-lg font-medium hover:bg-pink-700 transition duration-200 transform hover:scale-105">
                            Get Started Free
                        </a>
                        <a href="#features" class="border border-gray-600 text-gray-300 px-8 py-3 rounded-full text-lg font-medium hover:border-pink-600 hover:text-white transition duration-200">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="w-full h-96 bg-gradient-to-br from-pink-600/20 to-purple-600/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-music text-8xl text-pink-600 animate-bounce"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gradient-to-b from-black to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Why Choose Us?</h2>
                <p class="text-gray-400 text-lg">Experience music like never before with our premium features</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-effect rounded-xl p-6 feature-card transition duration-300">
                    <div class="w-12 h-12 bg-pink-600/20 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-list text-pink-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Custom Playlists</h3>
                    <p class="text-gray-400">Create and manage your personal playlists. Add your favorite tracks and organize them your way.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-effect rounded-xl p-6 feature-card transition duration-300">
                    <div class="w-12 h-12 bg-pink-600/20 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-pink-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Mobile Ready</h3>
                    <p class="text-gray-400">Take your music anywhere. Our platform is fully responsive and works seamlessly on all devices.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-effect rounded-xl p-6 feature-card transition duration-300">
                    <div class="w-12 h-12 bg-pink-600/20 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-heart text-pink-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Personalized Experience</h3>
                    <p class="text-gray-400">Get recommendations based on your listening habits and discover new music you'll love.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-t from-black to-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-8">Ready to Start Your Musical Journey?</h2>
            <p class="text-gray-400 text-lg mb-8">Join thousands of music lovers who have already discovered their perfect soundtrack.</p>
            <a href="register.php" class="inline-block bg-pink-600 text-white px-8 py-3 rounded-full text-lg font-medium hover:bg-pink-700 transition duration-200 transform hover:scale-105">
                Create Your Account Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-music text-2xl text-pink-600"></i>
                        <span class="ml-2 text-xl font-semibold"><?php echo SITE_NAME; ?></span>
                    </div>
                    <p class="text-gray-400">Your ultimate music streaming platform.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Features</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add animation to feature cards on scroll
        const cards = document.querySelectorAll('.feature-card');
        const observer = new IntersectionObserver(
            entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        entry.target.classList.remove('opacity-0', 'translate-y-4');
                    }
                });
            },
            {
                threshold: 0.1
            }
        );

        cards.forEach(card => {
            card.classList.add('opacity-0', 'translate-y-4', 'transition', 'duration-1000');
            observer.observe(card);
        });
    </script>
</body>
</html>