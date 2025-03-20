<?php
require_once 'includes/functions.php';

// Redirect if already logged in
if (isUserLoggedIn()) {
    header('Location: member/dashboard.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];

        if (loginUser($username, $password)) {
            header('Location: member/dashboard.php');
            exit;
        }
    } catch (Exception $e) {
        setMessage('error', $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #000000, #1a1a1a);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <i class="fas fa-music text-5xl text-pink-600 mb-4 animate-bounce"></i>
            <h1 class="text-3xl font-bold text-white">Welcome Back</h1>
            <p class="text-gray-400 mt-2">Login to your account</p>
        </div>

        <!-- Login Form -->
        <form method="POST" class="glass-effect rounded-xl p-8 shadow-2xl space-y-6">
            <?php
            $message = getMessage();
            if ($message) {
                $bgColor = $message['type'] === 'error' ? 'bg-red-500/10' : 'bg-green-500/10';
                $textColor = $message['type'] === 'error' ? 'text-red-500' : 'text-green-500';
                echo "<div class='$bgColor $textColor p-4 rounded-lg mb-6'>{$message['text']}</div>";
            }
            ?>

            <!-- Username/Email Field -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username or Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-user text-gray-400"></i>
                    </span>
                    <input type="text" id="username" name="username" required
                        class="block w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800/50 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="Enter your username or email">
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-lock text-gray-400"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="block w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800/50 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me Checkbox -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 rounded border-gray-700 text-pink-600 focus:ring-pink-600 bg-gray-800">
                    <label for="remember" class="ml-2 text-sm text-gray-400">Remember me</label>
                </div>
                <a href="#" class="text-sm text-pink-600 hover:text-pink-500">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-600 focus:ring-opacity-50 transition duration-200 transform hover:scale-105">
                Sign In
            </button>

            <!-- Register Link -->
            <p class="text-center text-gray-400 text-sm">
                Don't have an account?
                <a href="register.php" class="text-pink-600 hover:text-pink-500">Register here</a>
            </p>
        </form>

        <!-- Social Login Options -->
        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm mb-4">Or continue with</p>
            <div class="flex justify-center space-x-4">
                <button class="p-2 rounded-full bg-gray-800 hover:bg-gray-700 transition duration-200">
                    <i class="fab fa-google text-white"></i>
                </button>
                <button class="p-2 rounded-full bg-gray-800 hover:bg-gray-700 transition duration-200">
                    <i class="fab fa-facebook text-white"></i>
                </button>
                <button class="p-2 rounded-full bg-gray-800 hover:bg-gray-700 transition duration-200">
                    <i class="fab fa-apple text-white"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Add smooth hover effect to form fields
        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('transform', 'scale-105');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('transform', 'scale-105');
            });
        });
    </script>
</body>
</html>