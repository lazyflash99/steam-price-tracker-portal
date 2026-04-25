<?php
/**
 * auth.php — Session management helpers.
 * Include at the top of any page that requires authentication awareness.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Returns true if a user is currently logged in.
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Returns the logged-in user's ID, or null.
 */
function currentUserId(): ?int {
    return isLoggedIn() ? (int)$_SESSION['user_id'] : null;
}

/**
 * Returns the logged-in user's username, or null.
 */
function currentUsername(): ?string {
    return $_SESSION['username'] ?? null;
}

/**
 * Redirects to login page if the user is not authenticated.
 * @param string $redirect  URL to send back to after login.
 */
function requireLogin(string $redirect = ''): void {
    if (!isLoggedIn()) {
        $url = 'login.php';
        if ($redirect) $url .= '?redirect=' . urlencode($redirect);
        header("Location: $url");
        exit;
    }
}

/**
 * Count wishlist items for the current user.
 */
function wishlistCount($conn): int {
    $uid = currentUserId();
    if (!$uid) return 0;
    $res = mysqli_query($conn, "SELECT COUNT(*) as c FROM wishlist WHERE user_id=$uid");
    return (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}

/**
 * Count cart items for the current user.
 */
function cartCount($conn): int {
    $uid = currentUserId();
    if (!$uid) return 0;
    $res = mysqli_query($conn, "SELECT COUNT(*) as c FROM cart WHERE user_id=$uid");
    return (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
}
