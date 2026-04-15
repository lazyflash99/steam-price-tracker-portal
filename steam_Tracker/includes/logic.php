<?php
include 'db.php';

function getBuyScore($conn, $game_id) {
    // 1. Get Current Price
    $res = mysqli_query($conn, "SELECT price FROM price_history WHERE game_id = $game_id ORDER BY price_date DESC LIMIT 1");
    $current = mysqli_fetch_assoc($res)['price'] ?? 0;
    
    // 2. Get Historical Stats
    $res_stats = mysqli_query($conn, "SELECT MIN(price) as min_p, AVG(price) as avg_p FROM price_history WHERE game_id = $game_id");
    $stats = mysqli_fetch_assoc($res_stats);
    $min = $stats['min_p'];
    $avg = $stats['avg_p'];

    if ($current <= 0) return 0;

    $score = 0;

    // --- Price Component (75 Points) ---
    if ($current <= $min) {
        // All-time low gets full points
        $score = 75;
    } elseif ($current > $avg) {
        // If price is above average, it's a "bad" deal
        $score = 15;
    } else {
        // Between Min and Average: Scale logic
        $range = $avg - $min;
        $savings = $avg - $current;
        $score = 15 + (($savings / $range) * 60);
    }

    // --- Review Component (25 Points) ---
    $res_rev = mysqli_query($conn, "SELECT pos_reviews, neg_reviews FROM review_history WHERE game_id = $game_id ORDER BY review_date DESC LIMIT 1");
    $rev = mysqli_fetch_assoc($res_rev);
    if ($rev && ($rev['pos_reviews'] + $rev['neg_reviews']) > 0) {
        $sentiment = $rev['pos_reviews'] / ($rev['pos_reviews'] + $rev['neg_reviews']);
        $score += ($sentiment * 25);
    }

    return round($score);
}
?>