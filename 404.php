<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-white p-4">
    <div class="text-center">
        <div class="text-pink-600 text-9xl font-bold mb-8">404</div>
        <h1 class="text-4xl font-bold mb-4">Page Not Found</h1>
        <p class="text-gray-400 text-lg mb-8">Oops! The page you're looking for doesn't exist.</p>
        <div class="space-x-4">
            <a href="javascript:history.back()" 
               class="inline-block px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Go Back
            </a>
            <a href="/" 
               class="inline-block px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition duration-200">
                <i class="fas fa-home mr-2"></i>Home
            </a>
        </div>
        <div class="mt-12 animate-bounce">
            <i class="fas fa-music text-6xl text-pink-600/50"></i>
        </div>
    </div>

    <script>
        // Optional: Add some animation to the error message
        document.addEventListener('DOMContentLoaded', function() {
            const errorCode = document.querySelector('.text-9xl');
            errorCode.style.transform = 'scale(0)';
            setTimeout(() => {
                errorCode.style.transition = 'transform 0.5s ease-out';
                errorCode.style.transform = 'scale(1)';
            }, 100);
        });
    </script>
</body>
</html>