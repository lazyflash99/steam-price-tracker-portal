<?php 
include 'includes/db.php'; 
include 'includes/logic.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$game_res = mysqli_query($conn, "SELECT * FROM games WHERE id = $id");
$game = mysqli_fetch_assoc($game_res);
if (!$game) { die("Game not found!"); }

$buy_score = getBuyScore($conn, $id);

// Data for Price Chart
$p_res = mysqli_query($conn, "SELECT price_date, price FROM price_history WHERE game_id = $id ORDER BY price_date ASC");
$p_dates = []; $p_values = [];
while($r = mysqli_fetch_assoc($p_res)) { $p_dates[] = $r['price_date']; $p_values[] = $r['price']; }

// Data for Review Chart
$r_res = mysqli_query($conn, "SELECT review_date, pos_reviews, neg_reviews FROM review_history WHERE game_id = $id ORDER BY review_date ASC");
$r_dates = []; $r_pos = []; $r_neg = [];
while($r = mysqli_fetch_assoc($r_res)) { $r_dates[] = $r['review_date']; $r_pos[] = $r['pos_reviews']; $r_neg[] = $r['neg_reviews']; }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $game['name']; ?> Analysis</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <a href="index.php" style="color: #66c0f4; text-decoration: none;">← Back to Portal</a>
    
    <h1 style="font-size: 3em; margin: 10px 0; color: #fff;"><?php echo strtoupper($game['name']); ?></h1>
    <p style="color: #889297;">Category: <?php echo $game['category']; ?></p>

    <div class="card">
        <h2>Buy Recommendation Score</h2>
        <div class="score-container">
            <div class="pointer" style="left: <?php echo $buy_score; ?>%;">▼<br><strong><?php echo $buy_score; ?></strong></div>
            <div class="number-line"></div>
            <div class="labels">
                <span>Wait for Sale</span>
                <span>Fair Price</span>
                <span>Great Deal!</span>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>Price History (INR)</h3>
        <canvas id="priceChart"></canvas>
    </div>

    <div class="card">
        <h3>Review Trend (Positive vs. Negative)</h3>
        <canvas id="reviewChart"></canvas>
    </div>
</div>

<script>
const commonOptions = {
    responsive: true,
    scales: {
        x: { 
            grid: { display: false },
            ticks: { maxTicksLimit: 8, color: '#889297' } 
        },
        y: { 
            ticks: { color: '#889297' } 
        }
    },
    plugins: { legend: { labels: { color: '#fff' } } }
};

// Price Chart (Stepped Line)
new Chart(document.getElementById('priceChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($p_dates); ?>,
        datasets: [{
            label: 'Price (₹)',
            data: <?php echo json_encode($p_values); ?>,
            borderColor: '#66c0f4',
            backgroundColor: 'rgba(102, 192, 244, 0.1)',
            fill: true,
            stepped: true,
            pointRadius: 0
        }]
    },
    options: commonOptions
});

// Review Chart (Dual Line Trend)
new Chart(document.getElementById('reviewChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($r_dates); ?>,
        datasets: [
            {
                label: 'Positive Reviews',
                data: <?php echo json_encode($r_pos); ?>,
                borderColor: '#2ecc71',
                tension: 0.3,
                pointRadius: 2
            },
            {
                label: 'Negative Reviews',
                data: <?php echo json_encode($r_neg); ?>,
                borderColor: '#e74c3c',
                tension: 0.3,
                pointRadius: 2
            }
        ]
    },
    options: commonOptions
});
</script>
</body>
</html>