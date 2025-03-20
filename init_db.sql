-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin', 'member')) DEFAULT 'member',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for users
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);

-- Music tracks table
CREATE TABLE IF NOT EXISTS music_tracks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    artist TEXT NOT NULL,
    album TEXT,
    duration INTEGER,
    file_path TEXT NOT NULL,
    cover_image TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for music_tracks
CREATE INDEX IF NOT EXISTS idx_tracks_title ON music_tracks(title);
CREATE INDEX IF NOT EXISTS idx_tracks_artist ON music_tracks(artist);

-- Albums table
CREATE TABLE IF NOT EXISTS albums (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    artist TEXT NOT NULL,
    cover_image TEXT,
    release_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for albums
CREATE INDEX IF NOT EXISTS idx_albums_title ON albums(title);
CREATE INDEX IF NOT EXISTS idx_albums_artist ON albums(artist);

-- Playlists table
CREATE TABLE IF NOT EXISTS playlists (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create index for playlists
CREATE INDEX IF NOT EXISTS idx_playlists_user_id ON playlists(user_id);

-- Playlist tracks table
CREATE TABLE IF NOT EXISTS playlist_tracks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    playlist_id INTEGER NOT NULL,
    track_id INTEGER NOT NULL,
    track_order INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
    FOREIGN KEY (track_id) REFERENCES music_tracks(id) ON DELETE CASCADE,
    UNIQUE(playlist_id, track_order)
);

-- Insert default admin user (password: admin123)
INSERT OR IGNORE INTO users (username, email, password, role) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample music tracks
INSERT OR IGNORE INTO music_tracks (title, artist, album, duration, file_path, cover_image) VALUES 
('Bohemian Rhapsody', 'Queen', 'A Night at the Opera', 354, '/music/bohemian_rhapsody.mp3', '/covers/bohemian_rhapsody.jpg'),
('Imagine', 'John Lennon', 'Imagine', 183, '/music/imagine.mp3', '/covers/imagine.jpg'),
('Billie Jean', 'Michael Jackson', 'Thriller', 294, '/music/billie_jean.mp3', '/covers/thriller.jpg'),
('Sweet Child O Mine', 'Guns N Roses', 'Appetite for Destruction', 356, '/music/sweet_child.mp3', '/covers/appetite.jpg'),
('Hotel California', 'Eagles', 'Hotel California', 391, '/music/hotel_california.mp3', '/covers/hotel_california.jpg'),
('Stairway to Heaven', 'Led Zeppelin', 'Led Zeppelin IV', 482, '/music/stairway_to_heaven.mp3', '/covers/led_zeppelin_iv.jpg'),
('Like a Rolling Stone', 'Bob Dylan', 'Highway 61 Revisited', 373, '/music/like_a_rolling_stone.mp3', '/covers/highway_61.jpg'),
('Purple Rain', 'Prince', 'Purple Rain', 520, '/music/purple_rain.mp3', '/covers/purple_rain.jpg'),
('Smells Like Teen Spirit', 'Nirvana', 'Nevermind', 301, '/music/smells_like_teen_spirit.mp3', '/covers/nevermind.jpg'),
('Yesterday', 'The Beatles', 'Help!', 125, '/music/yesterday.mp3', '/covers/help.jpg');

-- Insert sample albums
INSERT OR IGNORE INTO albums (title, artist, cover_image, release_date) VALUES
('A Night at the Opera', 'Queen', '/covers/bohemian_rhapsody.jpg', '1975-11-21'),
('Imagine', 'John Lennon', '/covers/imagine.jpg', '1971-09-09'),
('Thriller', 'Michael Jackson', '/covers/thriller.jpg', '1982-11-30'),
('Appetite for Destruction', 'Guns N Roses', '/covers/appetite.jpg', '1987-07-21'),
('Hotel California', 'Eagles', '/covers/hotel_california.jpg', '1977-05-07'),
('Led Zeppelin IV', 'Led Zeppelin', '/covers/led_zeppelin_iv.jpg', '1971-11-08'),
('Highway 61 Revisited', 'Bob Dylan', '/covers/highway_61.jpg', '1965-08-30'),
('Purple Rain', 'Prince', '/covers/purple_rain.jpg', '1984-06-25'),
('Nevermind', 'Nirvana', '/covers/nevermind.jpg', '1991-09-24'),
('Help!', 'The Beatles', '/covers/help.jpg', '1965-08-06');