<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$tx_id = (int)$_POST['transaction_id'];
$updates = [];
$need_total_recalc = false;


if (!empty($_POST['status'])) $updates[] = "status = '" . $conn->real_escape_string($_POST['status']) . "'";
if (!empty($_POST['payment_method'])) $updates[] = "payment_method = '" . $conn->real_escape_string($_POST['payment_method']) . "'";
if (!empty($_POST['item_purchased'])) $updates[] = "item_purchased = '" . $conn->real_escape_string($_POST['item_purchased']) . "'";

if ($_POST['tax'] !== "") {
    $updates[] = "tax = " . (float)$_POST['tax'];
    $need_total_recalc = true;
}
if ($_POST['discount'] !== "") {
    $updates[] = "discount = " . (float)$_POST['discount'];
    $need_total_recalc = true;
}

if (count($updates) > 0) {
    $conn->query("UPDATE transaction SET " . implode(", ", $updates) . " WHERE transaction_id = $tx_id");
}


$updating_items = false;
for ($i = 1; $i <= 4; $i++) {
    if (!empty($_POST["product_id_$i"]) && !empty($_POST["quantity_$i"])) $updating_items = true;
}

if ($updating_items) {
    $need_total_recalc = true;
    
    
    $old_items = $conn->query("SELECT product_id, quantity FROM involve WHERE transaction_id = $tx_id");
    while($row = $old_items->fetch_assoc()) {
        $pid = $row['product_id']; $qty = $row['quantity'];
        $conn->query("UPDATE product SET stock = stock + $qty WHERE product_id = $pid");
    }
    
    
    $conn->query("DELETE FROM involve WHERE transaction_id = $tx_id");

    
    for ($i = 1; $i <= 4; $i++) {
        $pid = $_POST["product_id_$i"];
        $qty = $_POST["quantity_$i"];
        if (!empty($pid) && !empty($qty)) {
            $pid = (int)$pid; $qty = (int)$qty;
            $price_check = $conn->query("SELECT price FROM product WHERE product_id = $pid");
            if ($price_check && $price_check->num_rows > 0) {
                $unit_price = (float)$price_check->fetch_assoc()['price'];
                $conn->query("INSERT INTO involve (transaction_id, product_id, quantity, unit_price) VALUES ($tx_id, $pid, $qty, $unit_price)");
                $conn->query("UPDATE product SET stock = stock - $qty WHERE product_id = $pid");
            }
        }
    }
}


if ($need_total_recalc) {
    $calc_result = $conn->query("
        SELECT 
            (SELECT IFNULL(SUM(quantity * unit_price), 0) FROM involve WHERE transaction_id = $tx_id) as subtotal,
            tax, 
            discount 
        FROM transaction 
        WHERE transaction_id = $tx_id
    ");
    
    if ($calc_result && $calc_result->num_rows > 0) {
        $data = $calc_result->fetch_assoc();
        $grand_total = (float)$data['subtotal'] + (float)$data['tax'] - (float)$data['discount'];
        $conn->query("UPDATE transaction SET total_amount = $grand_total WHERE transaction_id = $tx_id");
    }
}

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
echo "<h1>Updates Applied Successfully.</h1>";
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>