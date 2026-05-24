<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $conn->real_escape_string($_POST['name']);
$category = $conn->real_escape_string($_POST['category']);
$price = (float)$_POST['price'];
$stock = (int)$_POST['stock'];
$supplier_id = empty($_POST['supplier_id']) ? "NULL" : (int)$_POST['supplier_id'];
$mfg_date = empty($_POST['manufacture_date']) ? "NULL" : "'" . $conn->real_escape_string($_POST['manufacture_date']) . "'";
$exp_date = empty($_POST['expiry_date']) ? "NULL" : "'" . $conn->real_escape_string($_POST['expiry_date']) . "'";
$status = $conn->real_escape_string($_POST['status']);
$description = $conn->real_escape_string($_POST['description']);

$sql = "INSERT INTO product (name, category, price, stock, supplier_id, manufacture_date, expiry_date, status, description) 
        VALUES ('$name', '$category', $price, $stock, $supplier_id, $mfg_date, $exp_date, '$status', '$description')";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if ($conn->query($sql) === TRUE) {
    echo "<h1>Success! Product Added.</h1>";
} else {
    echo "<h1 style='color:red;'>Error</h1><p>" . $conn->error . "</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>