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
    $db = Database::getInstance()->getConnection();
    
    // Get playlist details
    $stmt = $db->prepare("SELECT * FROM playlists WHERE id = ? AND user_id = ?");
    $stmt->execute([$playlistId, getCurrentUser()['id']]);
    $playlist = $stmt->fetch();

    if (!$playlist) {
        throw new Exception("Playlist not found");
    }

    // Get playlist tracks
    $tracks = getPlaylistTracks($playlistId);

    // Get all available tracks for adding to playlist
    $stmt = $db->prepare("
        SELECT * FROM music_tracks 
        WHERE id NOT IN (
            SELECT track_id FROM playlist_tracks WHERE playlist_id = ?
        )
        ORDER BY title ASC
    ");
    $stmt->execute([$playlistId]);
    $availableTracks = $stmt->fetchAll();

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_name'])) {
            $newName = sanitizeInput($_POST['playlist_name']);
            $stmt = $db->prepare("UPDATE playlists SET name = ? WHERE id = ?");
            $stmt->execute([$newName, $playlistId]);
            setMessage('success', 'Playlist name updated successfully!');
            header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $playlistId);
            exit;
        }
        
        if (isset($_POST['add_track'])) {
            $trackId = (int)$_POST['track_id'];
            $trackOrder = count($tracks) + 1;
            addTrackToPlaylist($playlistId, $trackId, $trackOrder);
            setMessage('success', 'Track added successfully!');
            header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $playlistId);
            exit;
        }
    }

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
    <title>Edit Playlist - <?php echo SITE_NAME; ?></title>
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
    </style>
</head>
<body class="min-h-screen text-white">
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
    <main class="pt-20 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <?php
            $message = getMessage();
            if ($message) {
                $bgColor = $message['type'] === 'error' ? 'bg-red-500/10' : 'bg-green-500/10';
                $textColor = $message['type'] === 'error' ? 'text-red-500' : 'text-green-500';
                echo "<div class='$bgColor $textColor p-4 rounded-lg mb-6'>{$message['text']}</div>";
            }
            ?>

            <!-- Edit Playlist Name -->
            <div class="glass-effect rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Edit Playlist Details</h2>
                <form method="POST" class="flex gap-4">
                    <input type="text" name="playlist_name" value="<?php echo htmlspecialchars($playlist['name']); ?>" required
                        class="flex-1 px-4 py-2 rounded-lg bg-gray-800/50 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white placeholder-gray-400">
                    <button type="submit" name="update_name"
                        class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-600 focus:ring-opacity-50 transition duration-200">
                        Update Name
                    </button>
                </form>
            </div>

            <!-- Current Tracks -->
            <div class="glass-effect rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Current Tracks</h2>
                <?php if (empty($tracks)): ?>
                    <p class="text-gray-400">No tracks in this playlist yet.</p>
                <?php else: ?>
                    <div class="space-y-2">
                        <?php foreach ($tracks as $index => $track): ?>
                            <div class="track-item flex items-center justify-between p-4 rounded-lg">
                                <div class="flex items-center flex-1">
                                    <span class="w-8 text-gray-400"><?php echo $index + 1; ?></span>
                                    <div class="w-12 h-12 bg-gray-800 rounded flex items-center justify-center">
                                        <i class="fas fa-music text-pink-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-medium"><?php echo htmlspecialchars($track['title']); ?></h3>
                                        <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($track['artist']); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <?php if ($index > 0): ?>
                                        <button onclick="moveTrack(<?php echo $track['id']; ?>, 'up')"
                                            class="text-gray-400 hover:text-white transition duration-200">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($index < count($tracks) - 1): ?>
                                        <button onclick="moveTrack(<?php echo $track['id']; ?>, 'down')"
                                            class="text-gray-400 hover:text-white transition duration-200">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button onclick="removeTrack(<?php echo $track['id']; ?>)"
                                        class="text-red-500 hover:text-red-400 transition duration-200">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add Tracks -->
            <div class="glass-effect rounded-xl p-6">
                <h2 class="text-2xl font-semibold mb-4">Add Tracks</h2>
                <?php if (empty($availableTracks)): ?>
                    <p class="text-gray-400">No more tracks available to add.</p>
                <?php else: ?>
                    <form method="POST" class="space-y-4">
                        <select name="track_id" class="w-full px-4 py-2 rounded-lg bg-gray-800/50 border border-gray-700 focus:border-pink-600 focus:ring-1 focus:ring-pink-600 text-white">
                            <?php foreach ($availableTracks as $track): ?>
                                <option value="<?php echo $track['id']; ?>">
                                    <?php echo htmlspecialchars($track['title'] . ' - ' . $track['artist']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="add_track"
                            class="w-full bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-600 focus:ring-opacity-50 transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Add Selected Track
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        function removeTrack(trackId) {
            if (confirm('Are you sure you want to remove this track from the playlist?')) {
                window.location.href = `remove_track.php?playlist_id=<?php echo $playlistId; ?>&track_id=${trackId}`;
            }
        }

        function moveTrack(trackId, direction) {
            window.location.href = `move_track.php?playlist_id=<?php echo $playlistId; ?>&track_id=${trackId}&direction=${direction}`;
        }
    </script>
</body>
</html>