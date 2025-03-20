<?php
require_once '../includes/functions.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

try {
    $playlistId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$playlistId) {
        throw new Exception("Invalid playlist ID");
    }

    $db = Database::getInstance()->getConnection();

    // Verify the playlist belongs to the current user
    $stmt = $db->prepare("SELECT user_id FROM playlists WHERE id = ?");
    $stmt->execute([$playlistId]);
    $playlist = $stmt->fetch();

    if (!$playlist || $playlist['user_id'] != getCurrentUser()['id']) {
        throw new Exception("Playlist not found or access denied");
    }

    // Begin transaction
    $db->beginTransaction();

    // Delete playlist tracks first (this should cascade, but let's be explicit)
    $stmt = $db->prepare("DELETE FROM playlist_tracks WHERE playlist_id = ?");
    $stmt->execute([$playlistId]);

    // Delete the playlist
    $stmt = $db->prepare("DELETE FROM playlists WHERE id = ?");
    $stmt->execute([$playlistId]);

    // Commit transaction
    $db->commit();

    setMessage('success', 'Playlist deleted successfully');
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    setMessage('error', $e->getMessage());
}

// Redirect back to dashboard
header('Location: dashboard.php');
exit;