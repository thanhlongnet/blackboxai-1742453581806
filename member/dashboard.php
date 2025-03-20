<?php
require_once '../includes/functions.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$user = getCurrentUser();
$playlists = getUserPlaylists($user['id']);

// Handle new playlist creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_playlist'])) {
    try {
        $playlistName = sanitizeInput($_POST['playlist_name']);
        createPlaylist($user['id'], $playlistName);
        setMessage('success', 'Playlist created successfully!');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
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
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
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
        .playlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="min-h-screen bg-black text-white">
    <!-- Navigation -->
    <nav class="glass-effect fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-music text-2xl text-pink-600"></i>
                    <span class="ml-2 text-xl font-semibold"><?php echo SITE_NAME; ?></span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">
                        <i class="fas fa-user mr-2"></i>
                        <?php echo htmlspecialchars($user['username']); ?>
                    </span>
                    <a href="../logout.php" class="text-gray-300 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="ml-1">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Welcome Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4">Welcome to Your Music Hub</h1>
                <p class="text-gray-400">Manage your playlists and discover new music</p>
            </div>

            <!-- Messages -->
            <?php
            $message = getMessage();
            if ($message) {
                $bgColor = $message['type'] === 'error' ? 'bg-red-500/10' : 'bg-green-500/10';
                $textColor = $message['type'] === 'error' ? 'text-red-500' : 'text-green-500';
                echo "<div class='$bgColor $textColor p-4 rounded-lg mb-6 text-center'>{$message['text']}</div>";
            }
            ?>

            <!-- Create Playlist Section -->
            <div class="glass-effect rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Create New Playlist</h2>
                <form method="POST" class="flex gap-4">
                    <input type="text" name="playlist_name" required
                        class="flex-1 px-4 py-2 rounded-lg bg-gray-800/50 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400"
                        placeholder="Enter playlist name">
                    <button type="submit" name="create_playlist"
                        class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-600 focus:ring-opacity-50 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Create Playlist
                    </button>
                </form>
            </div>

            <!-- Playlists Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($playlists as $playlist): ?>
                <div class="glass-effect rounded-xl p-6 playlist-card transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($playlist['name']); ?></h3>
                        <div class="flex space-x-2">
                            <button class="text-gray-400 hover:text-white transition duration-200"
                                onclick="window.location.href='edit_playlist.php?id=<?php echo $playlist['id']; ?>'">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-400 hover:text-red-500 transition duration-200"
                                onclick="deletePlaylist(<?php echo $playlist['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-gray-400 text-sm">
                        <p>Created: <?php echo date('M j, Y', strtotime($playlist['created_at'])); ?></p>
                        <?php
                        try {
                            $tracks = getPlaylistTracks($playlist['id']);
                            $trackCount = count($tracks);
                            echo "<p class='mt-1'>$trackCount " . ($trackCount === 1 ? "track" : "tracks") . "</p>";
                        } catch (Exception $e) {
                            echo "<p class='mt-1 text-red-500'>Error loading tracks</p>";
                        }
                        ?>
                    </div>
                    <button class="mt-4 w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-200"
                        onclick="window.location.href='view_playlist.php?id=<?php echo $playlist['id']; ?>'">
                        <i class="fas fa-play mr-2"></i>Play
                    </button>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($playlists)): ?>
            <div class="text-center text-gray-400 mt-8">
                <i class="fas fa-music text-4xl mb-4"></i>
                <p>You haven't created any playlists yet.</p>
                <p class="mt-2">Start by creating your first playlist above!</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function deletePlaylist(playlistId) {
            if (confirm('Are you sure you want to delete this playlist?')) {
                window.location.href = `delete_playlist.php?id=${playlistId}`;
            }
        }

        // Add hover effect to playlist cards
        document.querySelectorAll('.playlist-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('transform', 'scale-105');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('transform', 'scale-105');
            });
        });
    </script>
</body>
</html>