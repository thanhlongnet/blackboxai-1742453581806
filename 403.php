<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .shake {
            animation: shake 0.8s cubic-bezier(.36,.07,.19,.97) both;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-white p-4">
    <div class="text-center">
        <div class="relative mb-8">
            <div class="text-pink-600 text-9xl font-bold shake">403</div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <i class="fas fa-lock text-6xl text-gray-800"></i>
            </div>
        </div>
        <h1 class="text-4xl font-bold mb-4">Access Forbidden</h1>
        <p class="text-gray-400 text-lg mb-8">Sorry, you don't have permission to access this page.</p>
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
        <div class="mt-12">
            <i class="fas fa-shield-alt text-6xl text-pink-600/50"></i>
        </div>
    </div>

    <script>
        // Add shake animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const errorCode = document.querySelector('.shake');
            errorCode.style.opacity = '0';
            setTimeout(() => {
                errorCode.style.transition = 'opacity 0.5s ease-out';
                errorCode.style.opacity = '1';
            }, 100);

            // Add shake animation on hover
            errorCode.addEventListener('mouseenter', function() {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = 'shake 0.8s cubic-bezier(.36,.07,.19,.97) both';
                }, 10);
            });
        });
    </script>
</body>
</html>