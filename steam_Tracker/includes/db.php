<?php
/**
 * db.php — Database connection.
 * Also creates the users, wishlist, and cart tables if they don't exist.
 */

$conn = mysqli_connect("localhost", "root", "", "steam_tracker");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ---- Ensure user-related tables exist ----

mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS users (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        username      VARCHAR(50) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS wishlist (
        id       INT AUTO_INCREMENT PRIMARY KEY,
        user_id  INT NOT NULL,
        game_id  INT NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
        UNIQUE KEY uq_wishlist (user_id, game_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS cart (
        id       INT AUTO_INCREMENT PRIMARY KEY,
        user_id  INT NOT NULL,
        game_id  INT NOT NULL,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
        UNIQUE KEY uq_cart (user_id, game_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
