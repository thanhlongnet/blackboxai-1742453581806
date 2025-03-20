<?php
require_once '../includes/functions.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

try {
    $playlistId = isset($_GET['playlist_id']) ? (int)$_GET['playlist_id'] : 0;
    $trackId = isset($_GET['track_id']) ? (int)$_GET['track_id'] : 0;

    if (!$playlistId || !$trackId) {
        throw new Exception("Invalid request");
    }

    // Remove track from playlist
    if (removeTrackFromPlaylist($playlistId, $trackId)) {
        setMessage('success', 'Track removed successfully');
    }
} catch (Exception $e) {
    setMessage('error', $e->getMessage());
}

// Redirect back to edit playlist page
header('Location: edit_playlist.php?id=' . $playlistId);
exit;