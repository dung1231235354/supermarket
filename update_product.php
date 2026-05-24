<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = (int)$_POST['product_id'];
$updates = [];

if (!empty($_POST['name'])) $updates[] = "name = '" . $conn->real_escape_string($_POST['name']) . "'";
if (!empty($_POST['category'])) $updates[] = "category = '" . $conn->real_escape_string($_POST['category']) . "'";
if ($_POST['price'] !== "") $updates[] = "price = " . (float)$_POST['price'];
if ($_POST['stock'] !== "") $updates[] = "stock = " . (int)$_POST['stock'];
if (!empty($_POST['supplier_id'])) $updates[] = "supplier_id = " . (int)$_POST['supplier_id'];
if (!empty($_POST['manufacture_date'])) $updates[] = "manufacture_date = '" . $conn->real_escape_string($_POST['manufacture_date']) . "'";
if (!empty($_POST['expiry_date'])) $updates[] = "expiry_date = '" . $conn->real_escape_string($_POST['expiry_date']) . "'";
if (!empty($_POST['status'])) $updates[] = "status = '" . $conn->real_escape_string($_POST['status']) . "'";
if (!empty($_POST['description'])) $updates[] = "description = '" . $conn->real_escape_string($_POST['description']) . "'";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if (count($updates) > 0) {
    $sql = "UPDATE product SET " . implode(", ", $updates) . " WHERE product_id = $id";
    if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
        echo "<h1>Success! Product #$id updated.</h1>";
    } else {
        echo "<h1>No Changes Made.</h1><p>Ensure the ID exists.</p>";
    }
} else {
    echo "<h1>No Changes Made.</h1><p>You didn't fill out any new information.</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>