# 🎮 Steam Game Price & Review Insight Portal

A comprehensive web application to explore historical price fluctuations and review trends for Steam games. This portal includes a mathematical "Buy Score" algorithm to recommend the best time to purchase based on historical data.

## 📁 File Structure
steam_tracker/
│
├── css/
│   └── style.css           # Custom UI and Buy Score Number Line styling
├── data/
│   └── [60 CSV Files]      # Pattern: Game_Name_ID_price.csv & Game_Name_ID_reviews.csv
├── includes/
│   ├── db.php              # PostgreSQL connection configuration
│   └── logic.php           # Mathematical "Buy Score" and Insight Logic
├── index.php               # Main Dashboard with search and category filters
├── game.php                # Detailed Analysis page with Chart.js visualizations
├── import.php              # Batch importer for all 60 CSV files
└── README.md               # Setup and execution guide

## 🛠️ Requirements
- **XAMPP** (with Apache)
- **PostgreSQL** (Port 5432)
- **Composer/External Libraries**: None (Chart.js is loaded via CDN)

## 🚀 Setup Instructions

### 1. Configure XAMPP for PostgreSQL
PHP in XAMPP is configured for MySQL by default. You must enable the PostgreSQL driver:
1. Open **XAMPP Control Panel**.
2. Click **Config** next to Apache and select `PHP (php.ini)`.
3. Search (Ctrl+F) for `;extension=pgsql` and `;extension=pdo_pgsql`.
4. Remove the semicolon `;` from the start of both lines.
5. Save the file and **Restart Apache**.

### 2. Database Setup
1. Open **pgAdmin 4** (installed with PostgreSQL).
2. Create a new database named `steam_tracker`.
3. Open the **Query Tool** for that database and run the following:

```sql
CREATE TABLE games (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100)
);

CREATE TABLE price_history (
    id SERIAL PRIMARY KEY,
    game_id INT REFERENCES games(id),
    price_date DATE NOT NULL,
    price NUMERIC(10, 2) NOT NULL
);

CREATE TABLE review_history (
    id SERIAL PRIMARY KEY,
    game_id INT REFERENCES games(id),
    review_date DATE NOT NULL,
    pos_reviews INT,
    neg_reviews INT
);