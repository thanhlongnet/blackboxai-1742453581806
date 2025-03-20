<?php
require_once 'includes/config.php';

try {
    // Create SQLite database
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Enable foreign key support
    $pdo->exec('PRAGMA foreign_keys = ON;');

    // Read and execute the SQL file
    $sql = file_get_contents('init_db.sql');
    $pdo->exec($sql);

    echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Complete - " . SITE_NAME . "</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
    </style>
</head>
<body class='min-h-screen flex items-center justify-center text-white p-4'>
    <div class='text-center max-w-md'>
        <div class='text-green-500 text-6xl mb-6'>
            <i class='fas fa-check-circle'></i>
        </div>
        <h1 class='text-3xl font-bold mb-4'>Setup Complete!</h1>
        <p class='text-gray-400 mb-8'>
            The database has been initialized successfully. You can now start using the music streaming platform.
        </p>
        <div class='space-y-4'>
            <p class='text-gray-300 text-sm'>
                Default admin credentials:<br>
                Username: <span class='font-mono bg-gray-800 px-2 py-1 rounded'>admin</span><br>
                Password: <span class='font-mono bg-gray-800 px-2 py-1 rounded'>admin123</span>
            </p>
            <div class='pt-4'>
                <a href='/' class='inline-block px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition duration-200'>
                    <i class='fas fa-home mr-2'></i>Go to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
    ";
} catch (PDOException $e) {
    echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Error - " . SITE_NAME . "</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
    </style>
</head>
<body class='min-h-screen flex items-center justify-center text-white p-4'>
    <div class='text-center max-w-md'>
        <div class='text-red-500 text-6xl mb-6'>
            <i class='fas fa-exclamation-circle'></i>
        </div>
        <h1 class='text-3xl font-bold mb-4'>Setup Failed</h1>
        <p class='text-gray-400 mb-8'>
            An error occurred while setting up the database:
        </p>
        <div class='bg-red-500/10 text-red-500 p-4 rounded-lg mb-8 text-sm'>
            " . htmlspecialchars($e->getMessage()) . "
        </div>
        <div>
            <a href='javascript:history.back()' class='inline-block px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition duration-200'>
                <i class='fas fa-arrow-left mr-2'></i>Go Back
            </a>
        </div>
    </div>
</body>
</html>
    ";
}
?>