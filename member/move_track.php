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
    $direction = isset($_GET['direction']) ? $_GET['direction'] : '';

    if (!$playlistId || !$trackId || !in_array($direction, ['up', 'down'])) {
        throw new Exception("Invalid request");
    }

    $db = Database::getInstance()->getConnection();

    // Get current track order
    $stmt = $db->prepare("SELECT track_order FROM playlist_tracks WHERE playlist_id = ? AND track_id = ?");
    $stmt->execute([$playlistId, $trackId]);
    $currentTrack = $stmt->fetch();

    if (!$currentTrack) {
        throw new Exception("Track not found in playlist");
    }

    $currentOrder = $currentTrack['track_order'];
    $newOrder = $direction === 'up' ? $currentOrder - 1 : $currentOrder + 1;

    // Begin transaction
    $db->beginTransaction();

    // Update the other track that's being swapped
    $stmt = $db->prepare("
        UPDATE playlist_tracks 
        SET track_order = ? 
        WHERE playlist_id = ? AND track_order = ?
    ");
    $stmt->execute([$currentOrder, $playlistId, $newOrder]);

    // Update the current track
    $stmt = $db->prepare("
        UPDATE playlist_tracks 
        SET track_order = ? 
        WHERE playlist_id = ? AND track_id = ?
    ");
    $stmt->execute([$newOrder, $playlistId, $trackId]);

    // Commit transaction
    $db->commit();

    setMessage('success', 'Track order updated successfully');
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    setMessage('error', $e->getMessage());
}

// Redirect back to edit playlist page
header('Location: edit_playlist.php?id=' . $playlistId);
exit;