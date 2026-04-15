<?php
include 'includes/db.php';
set_time_limit(0); 

$directory = "data/";
$files = scandir($directory);

function getGameId($conn, $name) {
    $clean_name = mysqli_real_escape_string($conn, str_replace('_', ' ', $name));
    $res = mysqli_query($conn, "SELECT id FROM games WHERE name = '$clean_name'");
    if ($row = mysqli_fetch_assoc($res)) { return $row['id']; }
    mysqli_query($conn, "INSERT INTO games (name, category) VALUES ('$clean_name', 'Steam Games')");
    return mysqli_insert_id($conn);
}

echo "<h2>Importing Data...</h2>";

foreach ($files as $file) {
    // Regex updated to handle 'price' or 'prices'
    if (preg_match('/^(.*)_([0-9]+)_(price|prices|reviews)\.csv$/i', $file, $matches)) {
        $game_id = getGameId($conn, $matches[1]);
        $type = strtolower($matches[3]);
        
        $handle = fopen($directory . $file, "r");
        fgetcsv($handle); // Skip header

        while (($data = fgetcsv($handle)) !== FALSE) {
            // Convert DD-MM-YYYY to YYYY-MM-DD for MySQL
            $raw_date = $data[0];
            $formatted_date = date('Y-m-d', strtotime($raw_date));
            $date = mysqli_real_escape_string($conn, $formatted_date);

            if (strpos($type, 'price') !== false) {
                $price = mysqli_real_escape_string($conn, $data[1]);
                mysqli_query($conn, "INSERT INTO price_history (game_id, price_date, price) VALUES ($game_id, '$date', '$price')");
            } else {
                $pos = (int)$data[1];
                $neg = (int)$data[2];
                mysqli_query($conn, "INSERT INTO review_history (game_id, review_date, pos_reviews, neg_reviews) VALUES ($game_id, '$date', $pos, $neg)");
            }
        }
        fclose($handle);
        echo "Successfully Processed: $file <br>";
    }
}
echo "<h3>Import Complete! <a href='index.php'>Go to Portal</a></h3>";
?>