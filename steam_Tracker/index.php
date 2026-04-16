<?php 
include 'includes/db.php'; 
include 'includes/logic.php'; 

// --- 1. SET UP THE SEARCH & FILTER LOGIC ---
$search_query = "";
if (!empty($_GET['search'])) {
    $s = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = " AND (g.name LIKE '%$s%' OR g.category LIKE '%$s%')";
}

// --- 2. DEFINE THE MAIN SQL ---
// This pulls each game and its MOST RECENT price based on the date
$query = "SELECT g.id, g.name, g.category, p.price 
          FROM games g 
          LEFT JOIN price_history p ON g.id = p.game_id 
          WHERE (p.price_date = (SELECT MAX(price_date) FROM price_history WHERE game_id = g.id) 
          OR p.price IS NULL) $search_query";

// Apply "Insight" Filters
if (!empty($_GET['insight'])) {
    switch ($_GET['insight']) {
        case 'cheapest': $query .= " ORDER BY p.price ASC"; break;
        case 'expensive': $query .= " ORDER BY p.price DESC"; break;
        case 'price_drop':
            $query = "SELECT g.id, g.name, g.category, p.price, (MAX(ph.price) - MIN(ph.price)) as drop_val 
                      FROM games g 
                      JOIN price_history p ON g.id = p.game_id 
                      JOIN price_history ph ON g.id = ph.game_id
                      WHERE (p.price_date = (SELECT MAX(price_date) FROM price_history WHERE game_id = g.id)) $search_query
                      GROUP BY g.id ORDER BY drop_val DESC";
            break;
        case 'stable':
            $query = "SELECT g.id, g.name, g.category, p.price, STDDEV(ph.price) as dev 
                      FROM games g 
                      JOIN price_history p ON g.id = p.game_id 
                      JOIN price_history ph ON g.id = ph.game_id
                      WHERE (p.price_date = (SELECT MAX(price_date) FROM price_history WHERE game_id = g.id)) $search_query
                      GROUP BY g.id ORDER BY dev ASC";
            break;
    }
}

$result = mysqli_query($conn, $query);

// --- 3. AUTO-REDIRECT FEATURE ---
// If searching for a specific game results in exactly one match, go straight to that page
if (mysqli_num_rows($result) == 1 && !empty($_GET['search'])) {
    $one_game = mysqli_fetch_assoc($result);
    header("Location: game.php?id=" . $one_game['id']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam Insight Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Steam Insight Portal</h1>
    </header>
    
    <div class="card">
        <form method="GET" class="filter-form" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Search game or category..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="flex: 2; padding: 10px; border-radius: 4px; border: 1px solid #2a475e; background: #fff;">
            
            <select name="insight" style="flex: 1; padding: 10px; border-radius: 4px; border: 1px solid #2a475e;">
                <option value="">-- Select Insight --</option>
                <option value="cheapest" <?php if(($_GET['insight']??'')=='cheapest') echo 'selected'; ?>>Cheapest Games</option>
                <option value="expensive" <?php if(($_GET['insight']??'')=='expensive') echo 'selected'; ?>>Most Expensive</option>
                <option value="price_drop" <?php if(($_GET['insight']??'')=='price_drop') echo 'selected'; ?>>Highest Price Drop Ever</option>
                <option value="stable" <?php if(($_GET['insight']??'')=='stable') echo 'selected'; ?>>Most Stable Price</option>
            </select>
            
            <button type="submit" class="btn-primary">Explore</button>
            <a href="index.php" class="btn-reset">Reset</a>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Game Name</th>
                    <th>Category</th>
                    <th>Current Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    // Reset pointer if we previously fetched for auto-redirect
                    mysqli_data_seek($result, 0); 
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                        echo "<td><span class='badge'>" . htmlspecialchars($row['category']) . "</span></td>";
                        echo "<td>₹" . number_format($row['price'], 2) . "</td>";
                        echo "<td><a href='game.php?id=" . $row['id'] . "' class='analyze-link'>Analyze Details</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; padding: 40px;'>No games found matching your criteria. Make sure you have run import.php.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>