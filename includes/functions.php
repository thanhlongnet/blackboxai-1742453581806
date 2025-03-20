<?php
require_once 'database.php';

function secureSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function registerUser($username, $email, $password) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Check if username or email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Username or email already exists");
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);
        
        return true;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function loginUser($username, $password) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Get user by username or email
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid credentials");
        }
        
        // Start secure session and store user data
        secureSessionStart();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        return true;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function isUserLoggedIn() {
    secureSessionStart();
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isUserLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role']
    ];
}

function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

function logout() {
    secureSessionStart();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, '/');
}

// Playlist Management Functions
function createPlaylist($userId, $name) {
    try {
        $db = Database::getInstance()->getConnection();
        
        if (empty($name)) {
            throw new Exception("Playlist name is required");
        }
        
        $stmt = $db->prepare("INSERT INTO playlists (user_id, name) VALUES (?, ?)");
        $stmt->execute([$userId, $name]);
        
        return $db->lastInsertId();
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function getUserPlaylists($userId) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM playlists WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll();
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function addTrackToPlaylist($playlistId, $trackId, $trackOrder) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Verify playlist exists and user owns it
        $stmt = $db->prepare("SELECT user_id FROM playlists WHERE id = ?");
        $stmt->execute([$playlistId]);
        $playlist = $stmt->fetch();
        
        if (!$playlist || $playlist['user_id'] != getCurrentUser()['id']) {
            throw new Exception("Invalid playlist");
        }
        
        // Add track to playlist
        $stmt = $db->prepare("INSERT INTO playlist_tracks (playlist_id, track_id, track_order) VALUES (?, ?, ?)");
        $stmt->execute([$playlistId, $trackId, $trackOrder]);
        
        return true;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function getPlaylistTracks($playlistId) {
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT t.*, pt.track_order 
            FROM playlist_tracks pt 
            JOIN music_tracks t ON pt.track_id = t.id 
            WHERE pt.playlist_id = ? 
            ORDER BY pt.track_order ASC
        ");
        $stmt->execute([$playlistId]);
        
        return $stmt->fetchAll();
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function removeTrackFromPlaylist($playlistId, $trackId) {
    try {
        $db = Database::getInstance()->getConnection();
        
        // Verify playlist exists and user owns it
        $stmt = $db->prepare("SELECT user_id FROM playlists WHERE id = ?");
        $stmt->execute([$playlistId]);
        $playlist = $stmt->fetch();
        
        if (!$playlist || $playlist['user_id'] != getCurrentUser()['id']) {
            throw new Exception("Invalid playlist");
        }
        
        // Remove track from playlist
        $stmt = $db->prepare("DELETE FROM playlist_tracks WHERE playlist_id = ? AND track_id = ?");
        $stmt->execute([$playlistId, $trackId]);
        
        return true;
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

// File Upload Helpers
function uploadFile($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'mp3']) {
    try {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception("No file uploaded");
        }

        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("File type not allowed");
        }

        $targetFile = $targetDir . '/' . uniqid() . '.' . $fileType;
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception("Failed to upload file");
        }

        return basename($targetFile);
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

// Sanitization and Validation
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Error and Success Messages
function setMessage($type, $message) {
    secureSessionStart();
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

function getMessage() {
    secureSessionStart();
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}