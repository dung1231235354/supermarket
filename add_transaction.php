<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$customer_id = empty($_POST['customer_id']) ? "NULL" : (int)$_POST['customer_id'];
$method = $conn->real_escape_string($_POST['payment_method']);
$tax = (float)$_POST['tax'];
$discount = (float)$_POST['discount'];
$note = $conn->real_escape_string($_POST['item_purchased']);
$date = date('Y-m-d H:i:s'); 
$sql_master = "INSERT INTO transaction (date, customer_id, total_amount, payment_method, tax, discount, item_purchased, status) 
               VALUES ('$date', $customer_id, 0.00, '$method', $tax, $discount, '$note', 'completed')";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if ($conn->query($sql_master) === TRUE) {
    $new_id = $conn->insert_id;
    $subtotal = 0.00;

    for ($i = 1; $i <= 4; $i++) {
        $pid = $_POST["product_id_$i"];
        $qty = $_POST["quantity_$i"];

        if (!empty($pid) && !empty($qty)) {
            $pid = (int)$pid;
            $qty = (int)$qty;

            $price_check = $conn->query("SELECT price FROM product WHERE product_id = $pid");
            if ($price_check && $price_check->num_rows > 0) {
                $row = $price_check->fetch_assoc();
                $unit_price = (float)$row['price'];
                $subtotal += ($unit_price * $qty);

                $conn->query("INSERT INTO involve (transaction_id, product_id, quantity, unit_price) 
                              VALUES ($new_id, $pid, $qty, $unit_price)");
                $conn->query("UPDATE product SET stock = stock - $qty WHERE product_id = $pid");
            }
        }
    }
    $grand_total = $subtotal + $tax - $discount;
    $conn->query("UPDATE transaction SET total_amount = $grand_total WHERE transaction_id = $new_id");

    echo "<h1>Success! Transaction #$new_id logged.</h1>";
    echo "<p>Calculated Grand Total: <strong>$" . number_format($grand_total, 2) . "</strong></p>";

} else {
    echo "<h1 style='color:red;'>Error</h1><p>" . $conn->error . "</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>