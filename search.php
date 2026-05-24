<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Results</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">
<style>
    body { margin: 0; font-family: 'DM Sans', sans-serif; background-color: #fdf8e7; color: #1a1a1a; padding: 40px; }
    .header { display: flex; justify-content: space-between; text-transform: uppercase; font-size: 13px; font-weight: 700; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 40px; }
    .logo-text { font-family: 'Fraunces', serif; font-size: 24px; font-weight: 900; }
    .cta-btn { background-color: #fff; color: #000; border: 2px solid #000; padding: 10px 20px; font-weight: 700; text-transform: uppercase; cursor: pointer; text-decoration: none; display: inline-block; box-shadow: 2px 2px 0px #000;}
    .cta-btn:hover { background-color: #000; color: #f4c242; box-shadow: 0px 0px 0px #000; transform: translate(2px, 2px); }
    h1 { font-family: 'Fraunces', serif; font-size: 42px; margin-bottom: 10px; }
    
    .table-section { margin-bottom: 40px; background: #fff; border: 2px solid #000; padding: 20px; box-shadow: 8px 8px 0px #000; overflow-x: auto; }
    .table-section h3 { font-family: 'Fraunces', serif; font-size: 24px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-top: 0; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; white-space: nowrap; }
    .data-table th { font-weight: 700; text-transform: uppercase; font-size: 11px; padding: 12px; background-color: #000; color: #f4c242; border: 2px solid #000; }
    .data-table td { padding: 12px; border: 2px solid #000; background-color: #fff; }
    .status-badge { padding: 6px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #000; border: 1px solid #000; display: inline-block; }
</style>
</head>
<body>

    <div class="header">
        <div class="logo-text">SUPERMARKET</div>
        <div>SEARCH RESULTS</div>
    </div>

    <a href="indexx.php" class="cta-btn" style="margin-bottom: 30px;">← Back to Dashboard</a>

    <?php
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $type = isset($_GET['type']) ? $_GET['type'] : 'all';
    
    if ($query === '') {
        echo "<h1>Please enter a search term.</h1>";
    } else {
        $display_type = ($type === 'all') ? "All Data" : ucfirst($type) . "s";
        echo "<h1>Searching $display_type for: \"<em>" . htmlspecialchars($query) . "</em>\"</h1>";

        $conn = new mysqli("localhost", "root", "", "inventory_db");
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $safe_query = $conn->real_escape_string($query);
        $search_term = "%" . $safe_query . "%";
        $found_something = false;

        
        if ($type === 'all' || $type === 'product') {
            $sql = "SELECT * FROM product WHERE name LIKE '$search_term' OR category LIKE '$search_term' OR product_id LIKE '$search_term' OR description LIKE '$search_term'";
            $res = $conn->query($sql);
            if ($res && $res->num_rows > 0) {
                $found_something = true;
                echo "<div class='table-section'><h3>Products Found</h3><table class='data-table'>
                      <thead><tr>
                        <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Supp ID</th><th>Mfg Date</th><th>Exp Date</th><th>Description</th><th>Status</th>
                      </tr></thead><tbody>";
                while($row = $res->fetch_assoc()) {
                    $badge_color = ($row['status'] === 'available') ? "#a2e8a2" : "#ff9e9e";
                    echo "<tr>
                            <td>#" . $row["product_id"] . "</td>
                            <td><strong>" . $row["name"] . "</strong></td>
                            <td>" . ($row["category"] ?: "N/A") . "</td>
                            <td>$" . number_format($row["price"], 2) . "</td>
                            <td>" . $row["stock"] . "</td>
                            <td>" . ($row["supplier_id"] ?: "N/A") . "</td>
                            <td>" . ($row["manufacture_date"] ?: "N/A") . "</td>
                            <td>" . ($row["expiry_date"] ?: "N/A") . "</td>
                            <td>" . ($row["description"] ?: "N/A") . "</td>
                            <td><span class='status-badge' style='background-color: $badge_color;'>" . str_replace('_', ' ', $row['status']) . "</span></td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            }
        }

        
        if ($type === 'all' || $type === 'customer') {
            $sql = "SELECT * FROM customer WHERE name LIKE '$search_term' OR email LIKE '$search_term' OR phone LIKE '$search_term' OR customer_id LIKE '$search_term'";
            $res = $conn->query($sql);
            if ($res && $res->num_rows > 0) {
                $found_something = true;
                echo "<div class='table-section'><h3>Customers Found</h3><table class='data-table'>
                      <thead><tr>
                        <th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>DOB</th><th>Gender</th><th>Reg Date</th><th>Points</th><th>Status</th>
                      </tr></thead><tbody>";
                while($row = $res->fetch_assoc()) {
                    $badge_color = ($row['status'] === 'active') ? "#a2e8a2" : "#e0e0e0";
                    echo "<tr>
                            <td>#" . $row["customer_id"] . "</td>
                            <td><strong>" . $row["name"] . "</strong></td>
                            <td>" . ($row["phone"] ?: "N/A") . "</td>
                            <td>" . ($row["email"] ?: "N/A") . "</td>
                            <td>" . ($row["address"] ?: "N/A") . "</td>
                            <td>" . ($row["date_of_birth"] ?: "N/A") . "</td>
                            <td>" . ($row["gender"] ?: "N/A") . "</td>
                            <td>" . ($row["registration_date"] ?: "N/A") . "</td>
                            <td>" . $row["points"] . "</td>
                            <td><span class='status-badge' style='background-color: $badge_color;'>" . ucfirst($row['status']) . "</span></td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            }
        }

        
        if ($type === 'all' || $type === 'supplier') {
            $sql = "SELECT * FROM supplier WHERE name LIKE '$search_term' OR contact LIKE '$search_term' OR supplier_id LIKE '$search_term' OR type LIKE '$search_term'";
            $res = $conn->query($sql);
            if ($res && $res->num_rows > 0) {
                $found_something = true;
                echo "<div class='table-section'><h3>Suppliers Found</h3><table class='data-table'>
                      <thead><tr>
                        <th>ID</th><th>Company Name</th><th>Type</th><th>Contact</th><th>Email</th><th>Address</th><th>Country</th><th>Website</th><th>Description</th><th>Status</th>
                      </tr></thead><tbody>";
                while($row = $res->fetch_assoc()) {
                    $badge_color = ($row['status'] === 'active') ? "#a2e8a2" : "#ff9e9e";
                    echo "<tr>
                            <td>#" . $row["supplier_id"] . "</td>
                            <td><strong>" . $row["name"] . "</strong></td>
                            <td>" . ($row["type"] ?: "N/A") . "</td>
                            <td>" . ($row["contact"] ?: "N/A") . "</td>
                            <td>" . ($row["email"] ?: "N/A") . "</td>
                            <td>" . ($row["address"] ?: "N/A") . "</td>
                            <td>" . ($row["country"] ?: "N/A") . "</td>
                            <td>" . ($row["website"] ?: "N/A") . "</td>
                            <td>" . ($row["description"] ?: "N/A") . "</td>
                            <td><span class='status-badge' style='background-color: $badge_color;'>" . ucfirst($row['status']) . "</span></td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            }
        }

     
        if ($type === 'all' || $type === 'transaction') {
            $sql = "SELECT * FROM transaction WHERE transaction_id LIKE '$search_term' OR payment_method LIKE '$search_term' OR status LIKE '$search_term' OR item_purchased LIKE '$search_term' ORDER BY date DESC";
            $res = $conn->query($sql);
            
            if ($res && $res->num_rows > 0) {
                $found_something = true;
                echo "<div class='table-section'><h3>Transactions Found</h3><table class='data-table'>
                      <thead><tr>
                        <th>Trans ID</th><th>Cust ID</th><th>Date / Time</th><th>Items Purchased</th><th>Tax</th><th>Discount</th><th>Total Amount</th><th>Method</th><th>Created At</th><th>Status</th>
                      </tr></thead><tbody>";
                while($row = $res->fetch_assoc()) {
                    $badge_color = ($row['status'] === 'completed') ? "#f4c242" : "#e0e0e0";
                    echo "<tr>
                            <td>#" . $row["transaction_id"] . "</td>
                            <td>" . ($row["customer_id"] ?? "Guest") . "</td>
                            <td>" . $row["date"] . "</td>
                            <td>" . ($row["item_purchased"] ?: "N/A") . "</td>
                            <td>$" . number_format($row["tax"], 2) . "</td>
                            <td>$" . number_format($row["discount"], 2) . "</td>
                            <td><strong>$" . number_format($row["total_amount"], 2) . "</strong></td>
                            <td>" . ucfirst($row["payment_method"]) . "</td>
                            <td>" . $row["created_at"] . "</td>
                            <td><span class='status-badge' style='background-color: $badge_color;'>" . ucfirst($row['status']) . "</span></td>
                          </tr>";
                }
                echo "</tbody></table></div>";
            }
        }

        if (!$found_something) {
            echo "<div class='table-section'><p>No results found for that search query.</p></div>";
        }
        $conn->close();
    }
    ?>

</body>
</html>