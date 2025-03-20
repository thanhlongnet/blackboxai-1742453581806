<?php
require_once 'includes/functions.php';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match");
        }

        if (registerUser($username, $email, $password)) {
            setMessage('success', 'Registration successful! Please login.');
            header('Location: login.php');
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
    <title>Register - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <i class="fas fa-music text-5xl text-pink-600 mb-4"></i>
            <h1 class="text-3xl font-bold">Create Account</h1>
            <p class="text-gray-400 mt-2">Join our music streaming platform</p>
        </div>

        <!-- Registration Form -->
        <form method="POST" class="bg-gray-900 rounded-xl p-8 shadow-2xl space-y-6">
            <?php
            $message = getMessage();
            if ($message) {
                $bgColor = $message['type'] === 'error' ? 'bg-red-500/10' : 'bg-green-500/10';
                $textColor = $message['type'] === 'error' ? 'text-red-500' : 'text-green-500';
                echo "<div class='$bgColor $textColor p-4 rounded-lg mb-6'>{$message['text']}</div>";
            }
            ?>

            <!-- Username Field -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-user text-gray-400"></i>
                    </span>
                    <input type="text" id="username" name="username" required
                        class="block w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="Choose a username">
                </div>
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </span>
                    <input type="email" id="email" name="email" required
                        class="block w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="your@email.com">
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
                        class="block w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-lock text-gray-400"></i>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_password" required
                        class="block w-full pl-10 pr-3 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-600 focus:ring-opacity-50 transition duration-200">
                Create Account
            </button>

            <!-- Login Link -->
            <p class="text-center text-gray-400 text-sm">
                Already have an account?
                <a href="login.php" class="text-pink-600 hover:text-pink-500">Login here</a>
            </p>
        </form>
    </div>

    <script>
        // Client-side password validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>