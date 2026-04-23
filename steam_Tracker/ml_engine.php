<?php
include 'includes/db.php';
set_time_limit(0);

echo "<h2>Running Unsupervised Learning Engine...</h2>";

// 1. Fetch all games with their latest price and sentiment
$sql = "SELECT g.id, g.name, 
        (SELECT price FROM price_history WHERE game_id = g.id ORDER BY price_date DESC LIMIT 1) as price,
        (SELECT pos_reviews FROM review_history WHERE game_id = g.id ORDER BY review_date DESC LIMIT 1) as pos,
        (SELECT neg_reviews FROM review_history WHERE game_id = g.id ORDER BY review_date DESC LIMIT 1) as neg
        FROM games g";
$result = mysqli_query($conn, $sql);

$games = [];
$max_price = 1; // Prevent division by zero

while($row = mysqli_fetch_assoc($result)) {
    $price = $row['price'] ?? 0;
    $pos = $row['pos'] ?? 0;
    $neg = $row['neg'] ?? 0;
    $total = $pos + $neg;
    $sentiment = ($total > 0) ? ($pos / $total) * 100 : 0;
    
    if ($price > $max_price) $max_price = $price;

    $games[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $price,
        'sentiment' => $sentiment,
        // Normalize values between 0 and 1 for the math
        'norm_price' => $price / $max_price, 
        'norm_sentiment' => $sentiment / 100 
    ];
}

// 2. K-Means Clustering (K=3) - Simplified for PHP
// We start by guessing 3 random "centers" for our clusters
$centroids = [
    ['p' => 0.1, 's' => 0.9, 'label' => 'Budget Hits'],
    ['p' => 0.5, 's' => 0.5, 'label' => 'Standard Tier'],
    ['p' => 0.9, 's' => 0.8, 'label' => 'Premium Titles']
];

// Assign games to the closest centroid based on mathematical distance
foreach ($games as &$game) {
    $min_distance = 999;
    $assigned_label = 'Uncategorized';
    
    foreach ($centroids as $c) {
        // Pythagorean theorem to find distance between game and centroid
        $distance = sqrt(pow($game['norm_price'] - $c['p'], 2) + pow($game['norm_sentiment'] - $c['s'], 2));
        if ($distance < $min_distance) {
            $min_distance = $distance;
            $assigned_label = $c['label'];
        }
    }
    $game['cluster'] = $assigned_label;
    
    // 3. Anomaly Detection (Hidden Gem Logic)
    // If it's incredibly cheap (under ₹400) but has > 85% sentiment, it's an anomaly!
    $is_anomaly = ($game['price'] > 0 && $game['price'] < 400 && $game['sentiment'] > 85) ? 1 : 0;
    $game['is_anomaly'] = $is_anomaly;

    // 4. Update the Database
    $cid = $game['id'];
    $clabel = mysqli_real_escape_string($conn, $game['cluster']);
    mysqli_query($conn, "UPDATE games SET cluster_label = '$clabel', is_anomaly = $is_anomaly WHERE id = $cid");
}

echo "<p>Success! Grouped " . count($games) . " games into clusters and flagged anomalies.</p>";
echo "<a href='index.php'>Return to Home</a>";
?>