<?php
require_once '../includes/functions.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// Get playlist ID from URL
$playlistId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Get playlist details and tracks
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
    $stmt->execute([$playlistId, getCurrentUser()['id']]);
    $playlist = $stmt->fetch();

    if (!$playlist) {
        throw new Exception("Playlist not found");
    }

    $tracks = getPlaylistTracks($playlistId);
} catch (Exception $e) {
    setMessage('error', $e->getMessage());
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($playlist['name']); ?> - <?php echo SITE_NAME; ?></title>
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
        .track-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .progress-bar {
            background: rgba(255, 255, 255, 0.1);
            cursor: pointer;
            border-radius: 9999px;
        }
        .progress {
            background: #ec4899;
            border-radius: 9999px;
            transition: width 0.1s linear;
        }
    </style>
</head>
<body class="min-h-screen bg-black text-white">
    <!-- Navigation -->
    <nav class="glass-effect fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-gray-300 hover:text-white">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">
                        <i class="fas fa-user mr-2"></i>
                        <?php echo htmlspecialchars(getCurrentUser()['username']); ?>
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 pb-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Playlist Header -->
            <div class="glass-effect rounded-xl p-8 mb-8">
                <div class="flex items-center space-x-6">
                    <div class="w-48 h-48 bg-gray-800 rounded-lg flex items-center justify-center">
                        <i class="fas fa-music text-6xl text-pink-600"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($playlist['name']); ?></h1>
                        <p class="text-gray-400">
                            <?php echo count($tracks); ?> tracks • 
                            Created <?php echo date('M j, Y', strtotime($playlist['created_at'])); ?>
                        </p>
                        <button class="mt-4 bg-pink-600 text-white px-6 py-2 rounded-full hover:bg-pink-700 transition duration-200">
                            <i class="fas fa-play mr-2"></i>Play All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tracks List -->
            <div class="glass-effect rounded-xl p-6">
                <?php if (empty($tracks)): ?>
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-music text-4xl mb-4"></i>
                        <p>This playlist is empty.</p>
                        <p class="mt-2">Add some tracks to get started!</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-2">
                        <?php foreach ($tracks as $index => $track): ?>
                            <div class="track-item flex items-center p-4 rounded-lg transition duration-200" 
                                 data-track-id="<?php echo $track['id']; ?>"
                                 data-track-url="<?php echo htmlspecialchars($track['file_path']); ?>">
                                <div class="w-8 text-gray-400"><?php echo $index + 1; ?></div>
                                <div class="w-12 h-12 bg-gray-800 rounded flex items-center justify-center">
                                    <i class="fas fa-music text-pink-600"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-medium"><?php echo htmlspecialchars($track['title']); ?></h3>
                                    <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($track['artist']); ?></p>
                                </div>
                                <div class="text-gray-400 text-sm">
                                    <?php echo gmdate("i:s", $track['duration']); ?>
                                </div>
                                <button class="ml-4 text-gray-400 hover:text-white transition duration-200">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Music Player -->
    <div class="fixed bottom-0 left-0 right-0 glass-effect border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Track Info -->
                <div class="flex items-center space-x-4 w-1/4">
                    <div class="w-12 h-12 bg-gray-800 rounded flex items-center justify-center">
                        <i class="fas fa-music text-pink-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-sm" id="current-track-title">No track selected</h4>
                        <p class="text-gray-400 text-xs" id="current-track-artist"></p>
                    </div>
                </div>

                <!-- Player Controls -->
                <div class="flex flex-col items-center w-2/4">
                    <div class="flex items-center space-x-6 mb-4">
                        <button class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fas fa-random"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fas fa-step-backward"></i>
                        </button>
                        <button class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center hover:bg-pink-700 transition duration-200" id="play-pause-btn">
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fas fa-step-forward"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white transition duration-200">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                    <div class="w-full flex items-center space-x-4">
                        <span class="text-xs text-gray-400" id="current-time">0:00</span>
                        <div class="flex-1 h-1 progress-bar">
                            <div class="h-full progress" style="width: 0%" id="progress-bar"></div>
                        </div>
                        <span class="text-xs text-gray-400" id="duration">0:00</span>
                    </div>
                </div>

                <!-- Volume Control -->
                <div class="flex items-center space-x-4 w-1/4 justify-end">
                    <button class="text-gray-400 hover:text-white transition duration-200">
                        <i class="fas fa-volume-up"></i>
                    </button>
                    <div class="w-32 h-1 progress-bar">
                        <div class="h-full progress" style="width: 100%" id="volume-bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Music player functionality would go here
        // This would include track selection, play/pause, progress bar updates, etc.
        document.addEventListener('DOMContentLoaded', function() {
            const tracks = document.querySelectorAll('.track-item');
            const playPauseBtn = document.getElementById('play-pause-btn');
            const progressBar = document.getElementById('progress-bar');
            const currentTimeDisplay = document.getElementById('current-time');
            const durationDisplay = document.getElementById('duration');
            const currentTrackTitle = document.getElementById('current-track-title');
            const currentTrackArtist = document.getElementById('current-track-artist');

            let currentTrack = null;
            let isPlaying = false;

            tracks.forEach(track => {
                track.addEventListener('click', () => {
                    const title = track.querySelector('h3').textContent;
                    const artist = track.querySelector('p').textContent;
                    
                    currentTrackTitle.textContent = title;
                    currentTrackArtist.textContent = artist;
                    
                    // Here you would also handle the actual audio playback
                    // For now, we'll just toggle the play button
                    isPlaying = true;
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                });
            });

            playPauseBtn.addEventListener('click', () => {
                isPlaying = !isPlaying;
                playPauseBtn.innerHTML = isPlaying ? 
                    '<i class="fas fa-pause"></i>' : 
                    '<i class="fas fa-play"></i>';
            });
        });
    </script>
</body>
</html>