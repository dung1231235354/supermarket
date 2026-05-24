<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$isPost = $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['type']);
$type = $_POST["type"] ?? '';
$search_term = $_POST["search_term"] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Results &mdash; Supermarket</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">
<style>
    body { 
        margin: 0; 
        padding: 0; 
        font-family: 'DM Sans', sans-serif; 
        background-color: #fdf8e7; 
        color: #1a1a1a; 
        display: flex; 
        flex-direction: column; 
        min-height: 100vh; 
    }
    .navbar { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 30px 50px; 
        text-transform: uppercase; 
        font-size: 13px; 
        letter-spacing: 1.5px; 
        font-weight: 700; 
    }
    .logo { 
        font-family: 'Fraunces', serif; 
        font-size: 28px; 
        font-weight: 900; 
        text-decoration: none; 
        color: #1a1a1a; 
        letter-spacing: -0.5px; 
    }
    .results-wrapper { 
        padding: 0 50px 50px 50px; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
    }
    .results-card { 
        background: #fff; 
        padding: 40px; 
        border: 2px solid #000; 
        box-shadow: 8px 8px 0px #000; 
        width: 100%; 
        max-width: 1200px; 
        overflow-x: auto; 
    }
    .results-card h1 { 
        font-family: 'Fraunces', serif; 
        margin-top: 0; 
        margin-bottom: 20px; 
    }
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 20px; 
        table-layout: auto; 
    }
    th, td { 
        border: 1px solid #000; 
        padding: 12px 15px; 
        text-align: left; 
        font-size: 14px; 
    }
    th { 
        background-color: #f4c242; 
        text-transform: uppercase; 
        font-size: 12px; 
        letter-spacing: 1px; 
        white-space: nowrap; 
    }
    tr:nth-child(even) { 
        background-color: #fafafa; 
    }
    .cta-btn { 
        background-color: #f4c242; 
        color: #000; 
        border: 2px solid #000; 
        padding: 12px 24px; 
        font-family: 'DM Sans', sans-serif; 
        font-size: 14px; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        cursor: pointer; 
        transition: all 0.2s ease; 
        text-decoration: none; 
        display: inline-block; 
    }
    .cta-btn:hover { 
        background-color: #000; 
        color: #f4c242; 
    }
    .error-msg { 
        color: #d9534f; 
        font-weight: bold; 
        font-size: 18px; 
    }
</style>
</head>
<body>

    <header class="navbar">
        <a href="index.php" class="logo">Supermarket</a>
        <div style="font-size: 14px;">Inventory System</div>
    </header>

    <div class="results-wrapper">
        <div class="results-card">
            <?php
            if (!$isPost) {
                echo "<h1>Oops! No search data received.</h1>";
                echo "<p>Please use the search form to look up items.</p>";
                echo "<br><a href='search.html' class='cta-btn'>Go to Search Form</a>";
            } else {
                $conn = new mysqli("localhost", "root", "", "inventory_db");
                if ($conn->connect_error) {
                    die("<p class='error-msg'>Connection failed: " . $conn->connect_error . "</p>");
                }
                $conn->set_charset("utf8mb4");

                $safe_search = $conn->real_escape_string($search_term);

                if ($type === 'customer') {
                    
                    $sql = "
                        SELECT 
                            c.*, 
                            IFNULL(t_summary.transaction_items, '—') AS `transaction_items`,
                            IFNULL(t_summary.total_amount_spent, 0.00) AS `total_amount`
                        FROM customer c
                        LEFT JOIN (
                            SELECT 
                                customer_id, 
                                GROUP_CONCAT(DISTINCT note SEPARATOR ' | ') AS `transaction_items`,
                                SUM(total_amount) AS `total_amount_spent`
                            FROM `transaction`
                            GROUP BY customer_id
                        ) t_summary ON c.customer_id = t_summary.customer_id
                        WHERE c.name LIKE '%$safe_search%'
                    ";
                } else {
                    if ($type === 'transaction') {
                        $column = 'transaction_id';
                    } elseif ($type === 'product' || $type === 'supplier') {
                        $column = 'name';
                    } else {
                        $column = 'product_id';
                    }
                    $sql = "SELECT * FROM `$type` WHERE `$column` LIKE '%$safe_search%'";
                }

                $result = $conn->query($sql);

                echo "<h1>Search Results for '" . htmlspecialchars($search_term) . "' (" . ucfirst($type) . ")</h1>";

                if (!$result) {
                    echo "<p class='error-msg'>Database Error: " . $conn->error . "</p>";
                } elseif ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<thead><tr>";
                    while ($fieldinfo = $result->fetch_field()) {
                        echo "<th>" . ucwords(str_replace('_', ' ', $fieldinfo->name)) . "</th>";
                    }
                    echo "</tr></thead><tbody>";
                    
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $key => $data) {
                            
                            if ($key === 'total_amount') {
                                echo "<td><strong>$" . number_format((float)$data, 2) . "</strong></td>";
                            } else {
                                echo "<td>" . htmlspecialchars($data ?? '—') . "</td>";
                            }
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p class='error-msg'>0 results found. Looks like we don't have that in our records!</p>";
                }
                $conn->close();
                echo "<br><a href='search.html' class='cta-btn'>&larr; Search Again</a>";
            }
            ?>
        </div>
    </div>
</body>
</html>